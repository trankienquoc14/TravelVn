<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../vendor/autoload.php';

class ChatController
{
    private $db;
    private $cloudinary;

    public function __construct()
    {
        $this->db = (new Database())->connect();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // ĐÃ XÓA TOÀN BỘ KHỞI TẠO PUSHER Ở ĐÂY

        // 2. Khởi tạo Cloudinary bảo mật bằng biến môi trường $_ENV
        $this->cloudinary = new \Cloudinary\Cloudinary([
            'cloud' => [
                'cloud_name' => 'dp2tvflu0',
                'api_key' => '829286427571743',
                'api_secret' => '5wNIiZAzncf_hybOvZqN8ZlYOZ8'
            ],
            'url' => ['secure' => true]
        ]);
    }

    // 1. Gửi tin nhắn
    public function sendMessage()
    {
        header('Content-Type: application/json');

        try {
            $message = $_POST['message'] ?? '';
            $senderType = $_SESSION['user']['role'] ?? 'customer';
            $senderName = $_SESSION['user']['full_name'] ?? 'Khách vãng lai';
            $departureId = (isset($_POST['departure_id']) && trim($_POST['departure_id']) !== '') ? (int) $_POST['departure_id'] : null;

            // XÁC ĐỊNH SESSION CHAT
            if ($senderType === 'customer') {
                if (isset($_SESSION['user']['user_id'])) {
                    $sessionId = 'user_' . $_SESSION['user']['user_id'];
                } else {
                    if (!isset($_SESSION['chat_session_id'])) {
                        $_SESSION['chat_session_id'] = uniqid('chat_');
                    }
                    $sessionId = $_SESSION['chat_session_id'];
                }
            } else {
                $sessionId = $_POST['session_id'] ?? '';
            }

            if (!empty($message) && !empty($sessionId)) {

                // Tự động kế thừa departure_id cho Admin/Guide
                if ($senderType !== 'customer' && empty($departureId)) {
                    $stmtGetDep = $this->db->prepare("SELECT departure_id FROM chat_messages WHERE session_id = ? AND departure_id IS NOT NULL AND departure_id > 0 LIMIT 1");
                    $stmtGetDep->execute([$sessionId]);
                    $depRow = $stmtGetDep->fetch(PDO::FETCH_ASSOC);
                    if ($depRow) {
                        $departureId = $depRow['departure_id'];
                    }
                }

                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $currentTime = date('Y-m-d H:i:s');

                // LƯU TIN NHẮN
                $stmt = $this->db->prepare("
                    INSERT INTO chat_messages 
                    (session_id, sender_type, sender_name, message, departure_id, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$sessionId, $senderType, $senderName, $message, $departureId, $currentTime]);

                // Đóng gói dữ liệu tin nhắn người dùng để gửi lại cho Frontend
                $userData = [
                    'session_id' => $sessionId,
                    'sender_type' => $senderType,
                    'sender_name' => $senderName,
                    'departure_id' => $departureId,
                    'message' => htmlspecialchars($message),
                    'time' => date('H:i')
                ];

                $botData = null; // Khởi tạo biến bot rỗng

                // AUTO REPLY BOT
                $isFirst = false;
                if ($senderType === 'customer') {
                    $checkStmt = $this->db->prepare("SELECT COUNT(*) as total FROM chat_messages WHERE session_id = ?");
                    $checkStmt->execute([$sessionId]);
                    $count = $checkStmt->fetch(PDO::FETCH_ASSOC);

                    if ($count['total'] == 1) {
                        $isFirst = true;
                        $autoReply = "🤖 Xin chào! TravelVN đã nhận được tin nhắn của bạn. Chúng tôi sẽ phản hồi sớm nhất ❤️";
                        $botStmt = $this->db->prepare("
                            INSERT INTO chat_messages (session_id, sender_type, sender_name, message, departure_id, created_at) 
                            VALUES (?, 'admin', 'TravelVN Bot', ?, ?, ?)
                        ");
                        $botStmt->execute([$sessionId, $autoReply, $departureId, $currentTime]);

                        // Đóng gói dữ liệu của Bot
                        $botData = [
                            'session_id' => $sessionId,
                            'sender_type' => 'admin',
                            'sender_name' => 'TravelVN Bot',
                            'message' => $autoReply,
                            'time' => date('H:i')
                        ];
                    }
                }

                // Trả về JSON chứa toàn bộ dữ liệu để giao diện bắt và đẩy lên Socket.io
                echo json_encode([
                    'status' => 'success',
                    'session_id' => $sessionId,
                    'is_first' => $isFirst,
                    'user_message' => $userData,
                    'bot_message' => $botData
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Dữ liệu rỗng']);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Lỗi DB: ' . $e->getMessage()]);
        }
        exit;
    }

    // 2. Lấy danh sách phiên chat (Giữ nguyên)
    public function getSessions()
    {
        // ... (Code cũ của bạn giữ nguyên, không thay đổi)
        header('Content-Type: application/json');

        try {
            $role = $_SESSION['user']['role'] ?? '';
            $userId = $_SESSION['user']['user_id'] ?? $_SESSION['user']['id'] ?? 0;

            $primaryKey = 'id';

            if ($role === 'admin' || $role === 'tour_manager') {
                $sql = "SELECT 
                            m1.session_id, 
                            (SELECT sender_name FROM chat_messages WHERE session_id = m1.session_id ORDER BY {$primaryKey} ASC LIMIT 1) AS sender_name, 
                            m1.message, 
                            m1.created_at,
                            (SELECT COUNT(*) FROM chat_messages WHERE session_id = m1.session_id AND is_read = 0 AND sender_type = 'customer') AS unread_count
                        FROM chat_messages m1
                        JOIN (SELECT MAX({$primaryKey}) as last_id FROM chat_messages GROUP BY session_id) m2 
                        ON m1.{$primaryKey} = m2.last_id
                        ORDER BY m1.created_at DESC";
                $stmt = $this->db->query($sql);
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

            } else if ($role === 'guide') {
                $sql = "SELECT 
                            m1.session_id, 
                            (SELECT sender_name FROM chat_messages WHERE session_id = m1.session_id ORDER BY {$primaryKey} ASC LIMIT 1) AS sender_name, 
                            m1.message, 
                            m1.created_at,
                            t.tour_name, 
                            (SELECT COUNT(*) FROM chat_messages WHERE session_id = m1.session_id AND is_read = 0 AND sender_type = 'customer') AS unread_count
                        FROM chat_messages m1
                        JOIN (
                            SELECT session_id, MAX({$primaryKey}) as last_id, MAX(departure_id) as active_dep_id 
                            FROM chat_messages GROUP BY session_id
                        ) m2 ON m1.{$primaryKey} = m2.last_id
                        JOIN departures d ON m2.active_dep_id = d.departure_id
                        JOIN tours t ON d.tour_id = t.tour_id
                        JOIN departure_guides dg ON d.departure_id = dg.departure_id
                        WHERE dg.guide_id = ?
                        ORDER BY m1.created_at DESC";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$userId]);
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            } else {
                echo json_encode([]);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Lỗi DB: ' . $e->getMessage()]);
        }
        exit;
    }

    // Hàm đánh dấu đã đọc (Giữ nguyên)
    public function markAsRead()
    {
        // ... (Code cũ giữ nguyên)
        header('Content-Type: application/json');
        $role = $_SESSION['user']['role'] ?? 'customer';

        if ($role === 'customer') {
            if (isset($_SESSION['user']['user_id'])) {
                $sessionId = 'user_' . $_SESSION['user']['user_id'];
            } else {
                $sessionId = $_SESSION['chat_session_id'] ?? '';
            }

            if (!empty($sessionId)) {
                $stmt = $this->db->prepare("UPDATE chat_messages SET is_read = 1 WHERE session_id = ? AND sender_type != 'customer'");
                $stmt->execute([$sessionId]);
            }
        } else {
            $sessionId = $_GET['session_id'] ?? $_POST['session_id'] ?? '';
            if (!empty($sessionId)) {
                $stmt = $this->db->prepare("UPDATE chat_messages SET is_read = 1 WHERE session_id = ? AND sender_type = 'customer'");
                $stmt->execute([$sessionId]);
            }
        }

        echo json_encode(['status' => 'success']);
        exit;
    }

    // 3. Lấy lịch sử tin nhắn (Giữ nguyên)
    public function getHistory()
    {
        // ... (Code cũ giữ nguyên)
        header('Content-Type: application/json');
        $role = $_SESSION['user']['role'] ?? 'customer';

        if ($role === 'customer') {
            if (isset($_SESSION['user']['user_id'])) {
                $sessionId = 'user_' . $_SESSION['user']['user_id'];
            } else {
                $sessionId = $_SESSION['chat_session_id'] ?? '';
            }
        } else {
            $sessionId = $_GET['session_id'] ?? '';
        }

        if (empty($sessionId)) {
            echo json_encode([]);
            exit;
        }

        $stmt = $this->db->prepare("SELECT * FROM chat_messages WHERE session_id = ? ORDER BY created_at ASC");
        $stmt->execute([$sessionId]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }

    // Xóa toàn bộ lịch sử của một cuộc trò chuyện (Giữ nguyên)
    public function deleteSession()
    {
        header('Content-Type: application/json');
        $sessionId = $_POST['session_id'] ?? '';

        if (!empty($sessionId)) {
            $stmt = $this->db->prepare("DELETE FROM chat_messages WHERE session_id = ?");
            if ($stmt->execute([$sessionId])) {
                echo json_encode(['status' => 'success']);
                exit;
            }
        }
        echo json_encode(['status' => 'error']);
        exit;
    }

    // Đếm tổng tin nhắn chưa đọc (Giữ nguyên)
    public function getTotalUnread()
    {
        header('Content-Type: application/json');

        $stmt = $this->db->query("SELECT COUNT(*) as total FROM chat_messages WHERE is_read = 0 AND sender_type = 'customer'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['total' => $result['total'] ?? 0]);
        exit;
    }

    // Đếm số tin nhắn Admin gửi mà Khách chưa đọc (Giữ nguyên)
    public function getCustomerUnreadCount()
    {
        header('Content-Type: application/json');

        if (isset($_SESSION['user']['user_id'])) {
            $sessionId = 'user_' . $_SESSION['user']['user_id'];
        } else {
            $sessionId = $_SESSION['chat_session_id'] ?? '';
        }

        if (empty($sessionId)) {
            echo json_encode(['total' => 0]);
            exit;
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM chat_messages WHERE session_id = ? AND is_read = 0 AND sender_type != 'customer'");
        $stmt->execute([$sessionId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['total' => $result['total'] ?? 0]);
        exit;
    }

    // =======================================================
    // CÁC HÀM BỔ SUNG CHO CHAT CHUYÊN NGHIỆP (FILE, VOICE, MAP)
    // =======================================================

    // 4. Xử lý Upload Tệp đính kèm và Hình ảnh
    public function uploadFile()
    {
        header('Content-Type: application/json');
        try {
            $senderType = $_SESSION['user']['role'] ?? 'customer';
            $senderName = $_SESSION['user']['full_name'] ?? 'Khách vãng lai';
            $departureId = !empty($_POST['departure_id']) ? (int) $_POST['departure_id'] : null;

            if (empty($_SESSION['chat_session_id'])) {
                $_SESSION['chat_session_id'] = 'guest_' . session_id();
            }
            $sessionId = isset($_SESSION['user']['user_id']) ? 'user_' . $_SESSION['user']['user_id'] : $_SESSION['chat_session_id'];

            if (!isset($_FILES['file']) || $_FILES['file']['error'] != 0) {
                echo json_encode(['error' => 'Không có file hoặc file lỗi']);
                exit;
            }

            $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
            $imageTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $isImage = in_array($ext, $imageTypes);
            $messageType = $isImage ? 'image' : 'file';

            $result = $this->cloudinary->uploadApi()->upload(
                $_FILES['file']['tmp_name'],
                [
                    'folder' => 'travelvn/chat',
                    'resource_type' => 'auto'
                ]
            );

            $dbUrl = $result['secure_url'];

            $stmt = $this->db->prepare("INSERT INTO chat_messages (session_id, sender_type, sender_name, file_url, message_type, departure_id ) VALUES (?,?,?,?,?,?)");
            $stmt->execute([$sessionId, $senderType, $senderName, $dbUrl, $messageType, $departureId]);

            // Trả về JSON kèm dữ liệu message thay vì gọi Pusher
            echo json_encode([
                'success' => true,
                'url' => $dbUrl,
                'type' => $messageType,
                'message_data' => [
                    'session_id' => $sessionId,
                    'sender_type' => $senderType,
                    'sender_name' => $senderName,
                    'file_url' => $dbUrl,
                    'message_type' => $messageType,
                    'departure_id' => $departureId,
                    'time' => date('H:i')
                ]
            ]);

        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    // 5. Xử lý Upload File Ghi âm (Voice)
    public function uploadVoice()
    {
        header('Content-Type: application/json');
        try {
            $senderType = $_SESSION['user']['role'] ?? 'customer';
            $senderName = $_SESSION['user']['full_name'] ?? 'Khách vãng lai';
            $departureId = !empty($_POST['departure_id']) ? (int) $_POST['departure_id'] : null;

            if (empty($_SESSION['chat_session_id'])) {
                $_SESSION['chat_session_id'] = 'guest_' . session_id();
            }
            $sessionId = isset($_SESSION['user']['user_id']) ? 'user_' . $_SESSION['user']['user_id'] : $_SESSION['chat_session_id'];

            if (!isset($_FILES['voice']) || $_FILES['voice']['error'] != 0) {
                echo json_encode(['error' => 'Không nhận được file ghi âm']);
                exit;
            }

            $result = $this->cloudinary->uploadApi()->upload(
                $_FILES['voice']['tmp_name'],
                [
                    'folder' => 'travelvn/chat/audio',
                    'resource_type' => 'video'
                ]
            );

            $dbUrl = $result['secure_url'];

            $stmt = $this->db->prepare("
                INSERT INTO chat_messages (session_id, sender_type, sender_name, file_url, message_type, departure_id)
                VALUES (?,?,?,?,?,?)
            ");
            $stmt->execute([$sessionId, $senderType, $senderName, $dbUrl, 'audio', $departureId]);

            // Trả về JSON kèm dữ liệu message
            echo json_encode([
                'success' => true,
                'url' => $dbUrl,
                'type' => 'audio',
                'message_data' => [
                    'session_id' => $sessionId,
                    'sender_type' => $senderType,
                    'sender_name' => $senderName,
                    'file_url' => $dbUrl,
                    'message_type' => 'audio',
                    'departure_id' => $departureId,
                    'time' => date('H:i')
                ]
            ]);

        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    // 6. Xử lý Chia sẻ Vị trí (Location)
    public function sendLocation()
    {
        header('Content-Type: application/json');

        $senderType = $_SESSION['user']['role'] ?? 'customer';
        $senderName = $_SESSION['user']['full_name'] ?? 'Khách vãng lai';
        $departureId = $_POST['departure_id'] ?? null;
        $lat = $_POST['latitude'] ?? '';
        $lng = $_POST['longitude'] ?? '';

        if ($senderType === 'customer') {
            $sessionId = isset($_SESSION['user']['user_id']) ? 'user_' . $_SESSION['user']['user_id'] : ($_SESSION['chat_session_id'] ?? '');
        } else {
            $sessionId = $_POST['session_id'] ?? '';
        }

        if (!empty($lat) && !empty($lng) && !empty($sessionId)) {
            $mapLink = "https://www.google.com/maps?q={$lat},{$lng}";
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $currentTime = date('Y-m-d H:i:s');

            $stmt = $this->db->prepare("
                INSERT INTO chat_messages 
                (session_id, sender_type, sender_name, message, message_type, departure_id, created_at) 
                VALUES (?, ?, ?, ?, 'location', ?, ?)
            ");
            $stmt->execute([$sessionId, $senderType, $senderName, $mapLink, $departureId, $currentTime]);

            // Trả về JSON chứa cục dữ liệu vị trí
            echo json_encode([
                'status' => 'success',
                'message_data' => [
                    'session_id' => $sessionId,
                    'sender_type' => $senderType,
                    'sender_name' => $senderName,
                    'message' => $mapLink,
                    'message_type' => 'location',
                    'departure_id' => $departureId,
                    'time' => date('H:i')
                ]
            ]);
            exit;
        }
        echo json_encode(['error' => 'Invalid location data']);
        exit;
    }

    // 7. Hàm dọn dẹp bộ nhớ đệm Frontend
    public function triggerCleanup()
    {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'ignored']);
        exit;
    }
}