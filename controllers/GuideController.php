<?php
require_once __DIR__ . '/../config/database.php';

class GuideController
{
    private $db;

    public function __construct()
    {
        require_once __DIR__ . '/../config/middleware.php';
        Middleware::guideOnly();
        $this->db = (new Database())->connect();

        // Kiểm tra session an toàn để tránh lỗi Notice
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'guide') {
            header("Location: ../views/login.php");
            exit();
        }
    }

    // ================= LỊCH CÔNG TÁC =================
    public function schedule() {
    $guide_id = $_SESSION['user']['user_id'];
    
    // 1. Tạo biến mảng để hứng điều kiện
    $where = ["dg.guide_id = ?"];
    $params = [$guide_id];

    // 2. Bắt các tham số từ URL do form GET gửi lên
    if (!empty($_GET['status'])) {
        $where[] = "d.status = ?";
        $params[] = $_GET['status'];
    }
    if (!empty($_GET['start_date'])) {
        $where[] = "d.start_date >= ?";
        $params[] = $_GET['start_date'];
    }
    if (!empty($_GET['end_date'])) {
        $where[] = "d.start_date <= ?";
        $params[] = $_GET['end_date'];
    }

    // 3. Gắn chuỗi WHERE vào SQL
    $whereSql = implode(" AND ", $where);

    $sql = "SELECT d.*, t.tour_name 
            FROM departures d
            JOIN tours t ON d.tour_id = t.tour_id
            JOIN departure_guides dg ON d.departure_id = dg.departure_id
            WHERE $whereSql";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);
    $departures = $stmt->fetchAll(PDO::FETCH_ASSOC);

    require __DIR__ . '/../views/guide/schedule.php';
}
   // ================= XEM CHI TIẾT ĐOÀN =================
    public function viewDeparture()
    {
        $id = $_GET['id'] ?? 0;

        // Lấy thông tin Tour và Lịch trình khởi hành
        $stmt = $this->db->prepare("
            SELECT d.*, t.* FROM departures d
            JOIN tours t ON d.tour_id = t.tour_id
            WHERE d.departure_id = ?
        ");
        $stmt->execute([$id]);
        $departure = $stmt->fetch(PDO::FETCH_ASSOC);

        // ... (phía trên giữ nguyên)
        $stmt = $this->db->prepare("
            SELECT b.*, c.checkin_id, c.checkin_time, c.staff_id
            FROM bookings b
            LEFT JOIN checkins c ON b.booking_id = c.booking_id
            WHERE b.departure_id = ? 
            AND b.status IN ('confirmed', 'completed') -- Đã bỏ chữ 'checked_in' ở đây
            ORDER BY 
                CASE WHEN c.checkin_id IS NOT NULL THEN 1 ELSE 2 END ASC
        ");
        $stmt->execute([$id]);
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require __DIR__ . '/../views/guide/view_departure.php';
    }

    public function checkin()
{
    // 1. Chỉ định rõ đây là API trả về JSON
    header('Content-Type: application/json');

    // 2. Chặn các request không phải là AJAX
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    if (!$isAjax) {
        echo json_encode(['status' => 'error', 'message' => 'Truy cập không hợp lệ']);
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $booking_id = $_POST['booking_id'] ?? 0;
        $departure_id = $_POST['departure_id'] ?? 0;
        $staff_id = $_SESSION['user']['user_id'] ?? 0;

if (!$staff_id) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Phiên đăng nhập hết hạn'
    ]);
    exit;
}

        try {
            // Kiểm tra xem đã check-in chưa
            $checkExist = $this->db->prepare("SELECT checkin_id FROM checkins WHERE booking_id = ?");
            $checkExist->execute([$booking_id]);
            
            if ($checkExist->rowCount() == 0) {
                $stmt = $this->db->prepare("INSERT INTO checkins (booking_id, staff_id, checkin_time) VALUES (?, ?, NOW())");
                $stmt->execute([$booking_id, $staff_id]);
                
                // Gửi Pusher
                require_once __DIR__ . '/../vendor/autoload.php';
                $pusher = new Pusher\Pusher('e5405b1b2139fed6f8bc', '2f482d4b39a5f0acd508', '2149497', ['cluster' => 'ap1', 'useTLS' => true]);
                $pusher->trigger('departure-channel-' . $departure_id, 'new-checkin', [
                    'booking_id' => $booking_id,
                    'pax_added' => $this->getPaxByBooking($booking_id),
                    'time' => date('H:i - d/m')
                ]);

                echo json_encode(['status' => 'success', 'message' => 'Điểm danh thành công!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Khách đã điểm danh rồi!']);
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'Lỗi server: ' . $e->getMessage()]);
        }
    }
    exit; // QUAN TRỌNG: Dừng chương trình để không in thêm header/footer
}

// Helper nhỏ để lấy số pax
private function getPaxByBooking($booking_id) {
    $stmt = $this->db->prepare("SELECT number_of_people FROM bookings WHERE booking_id = ?");
    $stmt->execute([$booking_id]);
    return $stmt->fetchColumn();
}

    // ================= UPDATE TRẠNG THÁI TOUR =================
    public function updateStatus()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: guide.php");
        exit;
    }

    $departure_id = (int)($_POST['departure_id'] ?? 0);
    $newStatus = $_POST['status'] ?? '';

    try {

        // Lấy dữ liệu tour
        $stmt = $this->db->prepare("
            SELECT departure_id,
                   start_date,
                   end_date,
                   status
            FROM departures
            WHERE departure_id=?
        ");

        $stmt->execute([$departure_id]);

        $tour = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$tour) {
            throw new Exception("Không tìm thấy tour");
        }

        $today = date('Y-m-d');

        $start = $tour['start_date'];
        $end   = $tour['end_date'];

        /*
        ======================
        RÀNG BUỘC LOGIC
        ======================
        */

        // Chưa tới ngày khởi hành
        if ($today < $start) {

            if ($newStatus != 'upcoming') {
                throw new Exception(
                    "Tour chưa đến ngày khởi hành → chỉ được ở trạng thái Chờ khởi hành"
                );
            }
        }

        // Đang trong khoảng diễn ra
        elseif ($today >= $start && $today <= $end) {

            if (!in_array($newStatus, ['ongoing'])) {
                throw new Exception(
                    "Tour đang diễn ra → chỉ được chuyển sang Đang diễn ra"
                );
            }
        }

        // Đã qua ngày kết thúc
        elseif ($today > $end) {

            if ($newStatus != 'completed') {
                throw new Exception(
                    "Tour đã kết thúc → chỉ được chuyển sang Hoàn thành"
                );
            }
        }

        // Update
        $stmtUpdate = $this->db->prepare("
            UPDATE departures
            SET status=?
            WHERE departure_id=?
        ");

        $stmtUpdate->execute([
            $newStatus,
            $departure_id
        ]);

        $_SESSION['success'] = "Cập nhật trạng thái thành công";

    } catch (Exception $e) {

        $_SESSION['error'] = $e->getMessage();
    }

    header("Location: guide.php?action=departureDetail&id=".$departure_id);
    exit;
}
    // ================= HỖ TRỢ KHÁCH TRONG ĐOÀN (CHAT) =================
    public function chat()
    {
        // Khai báo biến để UI nhận diện mục đang chọn (nếu cần dùng trong Header)
        $activeMenu = 'chat';

        // Gọi đến file view riêng biệt không có sidebar của HDV
        require __DIR__ . '/../views/guide/chat_guide.php';
    }
}
?>