<?php
require_once __DIR__ . '/../config/database.php';

class TourController
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->connect();
    }

    public function triggerCleanup()
    {
        $this->autoCancelExpiredBookings();
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success']);
        exit;
    }
    public function home()
    {
        $queryDiscount = "
            SELECT t.*, 
                   (SELECT IFNULL(AVG(rating), 0) FROM reviews WHERE tour_id = t.tour_id) AS avg_rating, 
                   (SELECT COUNT(review_id) FROM reviews WHERE tour_id = t.tour_id) AS review_count
            FROM tours t
            WHERE t.status = 'active' AND t.discount_percent > 0
            ORDER BY t.discount_percent DESC, t.tour_id DESC
            LIMIT 8
        ";
        $stmt = $this->db->query($queryDiscount);

        $queryBestSeller = "
    SELECT 
        t.*,
        IFNULL(AVG(r.rating), 0) AS avg_rating,
        COUNT(DISTINCT r.review_id) AS review_count,
        IFNULL(SUM(
            CASE 
                WHEN b.status IN ('confirmed', 'completed', 'checked_in') 
                THEN b.number_of_people 
                ELSE 0 
            END
        ), 0) AS total_booked
    FROM tours t
    LEFT JOIN departures d ON t.tour_id = d.tour_id
    LEFT JOIN bookings b ON d.departure_id = b.departure_id
    LEFT JOIN reviews r ON t.tour_id = r.tour_id
    WHERE t.status = 'active'
    GROUP BY t.tour_id
    ORDER BY total_booked DESC, t.tour_id DESC
    LIMIT 8
";

        $stmtBest = $this->db->query($queryBestSeller);
        $bestSellerTours = $stmtBest->fetchAll(PDO::FETCH_ASSOC);

        $stmtBlogs = $this->db->query("SELECT * FROM blogs ORDER BY created_at DESC LIMIT 4");
        $blogs = $stmtBlogs->fetchAll(PDO::FETCH_ASSOC);

        $stmtCounts = $this->db->query("
            SELECT 
                SUM(CASE WHEN destination LIKE '%Đà Nẵng%' THEN 1 ELSE 0 END) as danang,
                SUM(CASE WHEN destination LIKE '%Phú Quốc%' THEN 1 ELSE 0 END) as phuquoc,
                SUM(CASE WHEN destination LIKE '%Sapa%' THEN 1 ELSE 0 END) as sapa,
                SUM(CASE WHEN destination LIKE '%Đà Lạt%' THEN 1 ELSE 0 END) as dalat
            FROM tours 
            WHERE status = 'active'
        ");
        $destCounts = $stmtCounts->fetch(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/home.php';
    }

    public function detail()
    {
        $slug = $_GET['slug'] ?? '';

        if (empty($slug)) {
            header("Location: index.php?action=tours");
            exit;
        }

        $stmtTour = $this->db->prepare("SELECT t.*, p.partner_name FROM tours t LEFT JOIN partners p ON t.partner_id = p.partner_id WHERE t.slug = ? AND t.status = 'active'");
        $stmtTour->execute([$slug]);
        $detail = $stmtTour->fetch(PDO::FETCH_ASSOC);

        if (!$detail) {
            echo "<script>alert('Tour không tồn tại hoặc đã ngừng hoạt động!'); window.location.href='index.php?action=tours';</script>";
            exit;
        }

        $id = $detail['tour_id'];

        $stmtSchedule = $this->db->prepare("SELECT * FROM tour_schedules WHERE tour_id = ? ORDER BY day_number ASC");
        $stmtSchedule->execute([$id]);
        $schedules = $stmtSchedule->fetchAll(PDO::FETCH_ASSOC);

        $stmtDepartures = $this->db->prepare("SELECT * FROM departures WHERE tour_id = ? AND start_date >= CURDATE() ORDER BY start_date ASC");
        $stmtDepartures->execute([$id]);
        $departures = $stmtDepartures->fetchAll(PDO::FETCH_ASSOC);

        $stmtReviews = $this->db->prepare("
            SELECT r.*, u.full_name
            FROM reviews r 
            LEFT JOIN users u ON r.user_id = u.user_id 
            WHERE r.tour_id = ? 
            ORDER BY r.created_at DESC
        ");
        $stmtReviews->execute([$id]);
        $reviews = $stmtReviews->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/tour_detail.php';
    }

    public function tours()
    {
        $location = isset($_GET['location']) && is_string($_GET['location']) ? $_GET['location'] : '';
        $keyword = isset($_GET['keyword']) && is_string($_GET['keyword']) ? $_GET['keyword'] : '';
        $departure_date = isset($_GET['departure_date']) && is_string($_GET['departure_date']) ? $_GET['departure_date'] : '';
        $max_price = isset($_GET['max_price']) && is_scalar($_GET['max_price']) ? $_GET['max_price'] : '';
        $category = isset($_GET['cat']) && is_string($_GET['cat']) ? $_GET['cat'] : '';

        $search_term = !empty($location) ? $location : $keyword;

        $query = "
            SELECT t.*, 
                   IFNULL(AVG(r.rating), 0) AS avg_rating, 
                   COUNT(DISTINCT r.review_id) AS review_count
            FROM tours t
            LEFT JOIN reviews r ON t.tour_id = r.tour_id
            LEFT JOIN departures d ON t.tour_id = d.tour_id
            WHERE t.status = 'active'
        ";

        $params = [];

        if (!empty($search_term)) {
            $query .= " AND (t.destination LIKE ? OR t.tour_name LIKE ?)";
            $params[] = "%$search_term%";
            $params[] = "%$search_term%";
        }

        if (!empty($max_price)) {
            $query .= " AND t.price <= ?";
            $params[] = $max_price;
        }

        if (!empty($departure_date)) {
            $query .= " AND d.start_date >= ?";
            $params[] = $departure_date;
        }

        if ($category == 'sea') {
            $query .= " AND (t.tour_name LIKE '%biển%' OR t.destination IN ('Đà Nẵng','Nha Trang','Phú Quốc'))";
        } elseif ($category == 'mountain') {
            $query .= " AND (t.tour_name LIKE '%núi%' OR t.destination IN ('Sapa','Đà Lạt'))";
        }

        $query .= " GROUP BY t.tour_id ORDER BY t.tour_id DESC";

        $stmt = $this->db->prepare($query);
        $isSuccess = $stmt->execute($params);

        if (!$isSuccess) {
            $errorInfo = $stmt->errorInfo();
            die("<div style='color:red; padding:20px; font-weight:bold; text-align:center;'>LỖI SQL: " . $errorInfo[2] . "</div>");
        }

        $tours = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$tours) {
            $tours = [];
        }

        require __DIR__ . '/../views/tours.php';
    }

    public function getDepartures()
    {
        if (ob_get_length())
            ob_clean();
        header('Content-Type: application/json; charset=utf-8');

        $tour_id = isset($_GET['tour_id']) ? intval($_GET['tour_id']) : 0;

        try {
            $stmt = $this->db->prepare("SELECT * FROM departures WHERE tour_id = ? AND start_date >= CURDATE() AND status = 'upcoming' ORDER BY start_date ASC");
            $stmt->execute([$tour_id]);
            $departures = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($departures);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database error']);
        }
        exit();
    }

    public function booking()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            echo "<script>
                    alert('Bạn cần đăng nhập để có thể đặt tour!'); 
                    window.location.href='index.php?action=login';
                  </script>";
            exit;
        }
        $tour_id = $_GET['tour_id'] ?? 0;
        $departure_id = $_GET['departure_id'] ?? 0;

        $stmtTour = $this->db->prepare("SELECT * FROM tours WHERE tour_id = ? AND status = 'active'");
        $stmtTour->execute([$tour_id]);
        $detail = $stmtTour->fetch(PDO::FETCH_ASSOC);

        $stmtDep = $this->db->prepare("SELECT * FROM departures WHERE departure_id = ? AND tour_id = ?");
        $stmtDep->execute([$departure_id, $tour_id]);
        $departure = $stmtDep->fetch(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/booking.php';
    }

    // --- HÀM TẠO ĐƠN HÀNG (CÓ CHỐNG LỖI VÀ BÁO CHUÔNG ĐƠN MỚI) ---
    public function confirmBooking()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $tour_id = $_POST['tour_id'] ?? 0;
            $departure_id = $_POST['departure_id'] ?? 0;
            $customer_name = trim($_POST['customer_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $people = (int) ($_POST['people'] ?? 1);
            $note = trim($_POST['note'] ?? '');
            $payment_method = $_POST['payment_method'] ?? 'cod';

            $pickup_type = $_POST['pickup_type'] ?? 'meeting_point';
            $raw_address = trim($_POST['pickup_address'] ?? '');

            // Xử lý điểm đón
            if ($pickup_type === 'hotel') {
                $final_pickup = "Đón Khách sạn: " . $raw_address;
            } elseif ($pickup_type === 'other') {
                $final_pickup = "Khách Tỉnh (SB/BX): " . $raw_address;
            } else {
                $final_pickup = "Tự đến Điểm hẹn tập trung";
            }

            try {
                $this->db->beginTransaction();

                // TRỪ GHẾ NGAY KHI KHÁCH ĐẶT (GIỮ CHỖ)
                $queryUpdateSeats = "
                    UPDATE departures
                    SET available_seats = available_seats - ?,
                        booked_seats = booked_seats + ?
                    WHERE departure_id = ?
                    AND available_seats >= ?
                ";

                $stmtUpdateSeats = $this->db->prepare($queryUpdateSeats);
                $stmtUpdateSeats->execute([$people, $people, $departure_id, $people]);

                if ($stmtUpdateSeats->rowCount() === 0) {
                    throw new Exception("Chuyến đi không còn đủ chỗ!");
                }

                // Lấy giá + phần trăm giảm giá
                $stmt = $this->db->prepare("SELECT price, discount_percent FROM tours WHERE tour_id = ?");
                $stmt->execute([$tour_id]);
                $tour = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$tour) {
                    throw new Exception("Không tìm thấy tour");
                }

                $price = (float) $tour['price'];
                $discount = (float) ($tour['discount_percent'] ?? 0);

                // Tính giá sau giảm
                $discountedPrice = $price;
                if ($discount > 0) {
                    $discountedPrice = $price - ($price * $discount / 100);
                }

                // Tổng tiền
                $total_price = $discountedPrice * $people;
                $user_id = $_SESSION['user']['user_id'] ?? 1;

                // Lưu booking
                $query = "
                    INSERT INTO bookings (
                        user_id, departure_id, customer_name, email, phone, pickup_address, number_of_people, total_price, note, status
                    )
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')
                ";
                $stmtInsert = $this->db->prepare($query);
                $stmtInsert->execute([$user_id, $departure_id, $customer_name, $email, $phone, $final_pickup, $people, $total_price, $note]);

                $booking_id = $this->db->lastInsertId();

                // Tạo payment
                if ($payment_method === 'qr') {
                    $transaction_code = "TXN" . time() . rand(100, 999);
                    $stmtPay = $this->db->prepare("
                        INSERT INTO payments(booking_id, amount, payment_method, payment_status, transaction_code)
                        VALUES(?, ?, 'qr', 'pending', ?)
                    ");
                    $stmtPay->execute([$booking_id, $total_price, $transaction_code]);
                    $payment_id = $this->db->lastInsertId();
                } else {
                    $stmtPay = $this->db->prepare("
                        INSERT INTO payments(booking_id, amount, payment_method, payment_status)
                        VALUES(?, ?, 'cod', 'pending')
                    ");
                    $stmtPay->execute([$booking_id, $total_price]);
                }

                // 🔥 LƯU DATABASE: GỬI CHUÔNG BÁO CHO ADMIN CÓ ĐƠN MỚI 🔥
                $link_admin = "manager.php?action=bookingDetail&id=" . $booking_id;
                $message_admin = "🛒 Có khách hàng vừa đặt đơn mới (#" . str_pad($booking_id, 6, '0', STR_PAD_LEFT) . "). Đang chờ thanh toán.";
                $this->db->prepare("INSERT INTO notifications (user_id, booking_id, type, link, message) VALUES (NULL, ?, 'Đơn Hàng', ?, ?)")
                    ->execute([$booking_id, $link_admin, $message_admin]);
                // 🔥 THÊM ĐOẠN NÀY ĐỂ KÊU TING TING
                $_SESSION['realtime_notify'] = [
                    'target_role' => 'admin_group',
                    'type' => 'Đơn Hàng',
                    'title' => 'Đơn hàng mới',
                    'message' => $message_admin
                ];

                $this->db->commit();

                // Chuyển hướng
                if ($payment_method === 'qr') {
                    header(
                        "Location:index.php?action=payment&payment_id="
                        . encode_id($payment_id)
                        . "&booking_id="
                        . encode_id($booking_id)
                    );
                } else {
                    $_SESSION['success'] = "Đặt tour thành công!";
                    header("Location:index.php?action=myBookings");
                }
                exit;

            } catch (Exception $e) {
                $this->db->rollBack();
                echo "<script>
                    alert('" . $e->getMessage() . "');
                    history.back();
                </script>";
                exit;
            }
        }
    }

    public function myBookings()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $user_id = $_SESSION['user']['user_id'] ?? $_SESSION['user']['id'];
        $user_name = $_SESSION['user']['full_name'] ?? $_SESSION['user']['name'] ?? 'Khách hàng';
        $user_email = $_SESSION['user']['email'] ?? 'Chưa cập nhật email';

        $stmt = $this->db->prepare("
            SELECT b.booking_id, b.customer_name, b.number_of_people, b.total_price, b.status, b.booking_date,
                   d.start_date, d.end_date,
                   t.tour_id, t.tour_name, t.image,
                   p.payment_method, p.payment_status, p.payment_id
            FROM bookings b
            JOIN departures d ON b.departure_id = d.departure_id
            JOIN tours t ON d.tour_id = t.tour_id
            LEFT JOIN payments p ON b.booking_id = p.booking_id
            WHERE b.user_id = ?
            ORDER BY b.booking_id DESC
        ");
        $stmt->execute([$user_id]);
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalBookings = count($bookings);
        require __DIR__ . '/../views/my_booking.php';
    }

    public function bookingDetail()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit;
        }

        $hash_id = $_GET['booking_id'] ?? '';
        $booking_id = decode_id($hash_id);

        if ($booking_id <= 0) {
            die("<div class='text-center mt-5'><h3>Đường dẫn không hợp lệ hoặc mã vé đã bị sai lệch!</h3></div>");
        }

        $stmt = $this->db->prepare("
            SELECT b.*, d.start_date, d.end_date, t.tour_name, t.image, t.destination, t.price as unit_price,
                   p.payment_method, p.payment_status, p.transaction_code, p.payment_date
            FROM bookings b
            JOIN departures d ON b.departure_id = d.departure_id
            JOIN tours t ON d.tour_id = t.tour_id
            LEFT JOIN payments p ON b.booking_id = p.booking_id
            WHERE b.booking_id = ? AND b.user_id = ?
        ");

        $user_id = $_SESSION['user']['user_id'] ?? 1;
        $stmt->execute([$booking_id, $user_id]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$booking) {
            die("<div class='text-center mt-5'><h3>Không tìm thấy thông tin đơn hàng!</h3></div>");
        }

        require __DIR__ . '/../views/booking_detail.php';
    }

    // --- XỬ LÝ KHÁCH HÀNG TỰ BẤM HỦY ĐƠN ---
    public function requestCancel()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?action=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hash_id = $_POST['booking_id'] ?? '';
            $booking_id = decode_id($hash_id);
            $user_id = $_SESSION['user']['user_id'] ?? $_SESSION['user']['id'];

            if ($booking_id <= 0) {
                echo "<script>alert('Dữ liệu không hợp lệ!'); window.location.href='index.php?action=myBookings';</script>";
                exit;
            }

            $stmt = $this->db->prepare("
                SELECT b.status, b.note, b.customer_name, b.number_of_people, b.departure_id, p.payment_status, d.start_date 
                FROM bookings b 
                JOIN departures d ON b.departure_id = d.departure_id 
                LEFT JOIN payments p ON b.booking_id = p.booking_id
                WHERE b.booking_id = ? AND b.user_id = ?
            ");
            $stmt->execute([$booking_id, $user_id]);
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$booking || $booking['status'] === 'cancelled') {
                echo "<script>alert('Không thể thực hiện yêu cầu này!'); window.location.href='index.php?action=myBookings';</script>";
                exit;
            }

            $days_until_start = floor((strtotime($booking['start_date']) - time()) / (60 * 60 * 24));
            if ($days_until_start < 7) {
                echo "<script>
                        alert('Chuyến đi khởi hành trong vòng chưa tới 7 ngày. Theo chính sách, bạn không thể tự hủy lúc này.'); 
                        window.location.href='index.php?action=myBookings';
                      </script>";
                exit;
            }

            $reason = $_POST['cancel_reason'] ?? 'Không rõ lý do';
            $cancel_note = $_POST['cancel_note'] ?? '';

            $cancelInfo = "LÝ DO HỦY: $reason.";
            if (!empty($cancel_note)) {
                $cancelInfo .= " Ghi chú thêm: $cancel_note.";
            }

            if (($booking['payment_status'] ?? '') === 'paid') {
                $bank_name = $_POST['bank_name'] ?? '';
                $bank_account = $_POST['bank_account'] ?? '';
                $account_holder = $_POST['account_holder'] ?? '';
                $cancelInfo .= "\nTHÔNG TIN NHẬN HOÀN TIỀN: Ngân hàng $bank_name, STK: $bank_account, Chủ thẻ: $account_holder.";
            }

            $oldNote = !empty($booking['note']) ? $booking['note'] . "\n\n" : "";
            $finalNote = $oldNote . "--- YÊU CẦU HỦY BỞI KHÁCH HÀNG ---\n" . $cancelInfo;

            $stmtUpdate = $this->db->prepare("UPDATE bookings SET status = 'cancelled', note = ? WHERE booking_id = ?");
            if ($stmtUpdate->execute([$finalNote, $booking_id])) {

                // ĐÃ SỬA CHỖ NÀY: Vừa cộng lại ghế trống, vừa trừ đi ghế đã đặt
                $queryRestoreSeats = "UPDATE departures SET available_seats = available_seats + ?, booked_seats = booked_seats - ? WHERE departure_id = ?";
                $this->db->prepare($queryRestoreSeats)->execute([$booking['number_of_people'], $booking['number_of_people'], $booking['departure_id']]);

                // 🔥 LƯU DATABASE: GỬI CHUÔNG BÁO CHO ADMIN KHÁCH HỦY ĐƠN 🔥
                $link_admin = "manager.php?action=bookingDetail&id=" . $booking_id;
                $message_admin = "🚨 Khách hàng " . $booking['customer_name'] . " vừa gửi yêu cầu tự hủy đơn hàng #" . str_pad($booking_id, 6, '0', STR_PAD_LEFT);
                $this->db->prepare("INSERT INTO notifications (user_id, booking_id, type, link, message) VALUES (NULL, ?, 'Hủy Đơn', ?, ?)")
                    ->execute([$booking_id, $link_admin, $message_admin]);
                // 🔥 THÊM ĐOẠN NÀY ĐỂ KÊU TING TING
                $_SESSION['realtime_notify'] = [
                    'target_role' => 'admin_group',
                    'type' => 'Hủy Đơn',
                    'title' => 'Yêu cầu hủy',
                    'message' => $message_admin
                ];

                if (($booking['payment_status'] ?? '') === 'paid') {
                    echo "<script>alert('Yêu cầu hủy thành công. Hệ thống đã ghi nhận thông tin tài khoản, kế toán sẽ xử lý hoàn tiền trong 3-5 ngày làm việc.'); window.location.href='index.php?action=myBookings';</script>";
                } else {
                    echo "<script>alert('Đã hủy chuyến đi thành công.'); window.location.href='index.php?action=myBookings';</script>";
                }
            } else {
                echo "<script>alert('Có lỗi hệ thống, vui lòng thử lại sau.'); window.location.href='index.php?action=myBookings';</script>";
            }
        }
    }

    // --- HỆ THỐNG TỰ ĐỘNG HỦY ĐƠN TREO ---
    private function autoCancelExpiredBookings()
    {
        $query = "SELECT b.booking_id, b.departure_id, b.number_of_people 
                  FROM bookings b
                  JOIN payments p ON b.booking_id = p.booking_id
                  WHERE b.status = 'pending' 
                  AND p.payment_method = 'qr' 
                  AND p.payment_status = 'pending'
                  AND b.booking_date <= (NOW() - INTERVAL 15 MINUTE)";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $expiredBookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($expiredBookings as $b) {
            // ĐÃ SỬA CHỖ NÀY: Vừa cộng lại ghế trống, vừa trừ đi ghế đã đặt
            $stmtRestore = $this->db->prepare("UPDATE departures SET available_seats = available_seats + ?, booked_seats = booked_seats - ? WHERE departure_id = ?");
            $stmtRestore->execute([$b['number_of_people'], $b['number_of_people'], $b['departure_id']]);

            $stmtCancelB = $this->db->prepare("UPDATE bookings SET status = 'cancelled', note = CONCAT(IFNULL(note,''), 'Đã hủy do hết thời gian thanh toán') WHERE booking_id = ?");
            $stmtCancelB->execute([$b['booking_id']]);

            $stmtCancelP = $this->db->prepare("UPDATE payments SET payment_status = 'failed' WHERE booking_id = ?");
            $stmtCancelP->execute([$b['booking_id']]);

            // 🔥 LƯU DATABASE: GỬI CHUÔNG BÁO CHO ADMIN ĐƠN HẾT HẠN 🔥
            $link_admin = "manager.php?action=bookingDetail&id=" . $b['booking_id'];
            $message_admin = "⚠️ Đơn hàng #" . str_pad($b['booking_id'], 6, '0', STR_PAD_LEFT) . " đã tự động bị hủy do quá hạn 15 phút chưa thanh toán.";
            $this->db->prepare("INSERT INTO notifications (user_id, booking_id, type, link, message) VALUES (NULL, ?, 'Hủy Đơn', ?, ?)")
                ->execute([$b['booking_id'], $link_admin, $message_admin]);
            // 🔥 THÊM ĐOẠN NÀY ĐỂ KÊU TING TING
            $_SESSION['realtime_notify'] = [
                'target_role' => 'admin_group',
                'type' => 'Hủy Đơn',
                'title' => 'Hệ thống tự động hủy',
                'message' => $message_admin
            ];
        }
    }

    public function blogDetail()
    {
        $slug = $_GET['slug'] ?? '';
        if (empty($slug)) {
            header("Location: index.php?action=blogs");
            exit;
        }

        $stmt = $this->db->prepare("SELECT * FROM blogs WHERE slug = ?");
        $stmt->execute([$slug]);
        $blog = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$blog) {
            die("<div style='text-align:center; padding: 100px; font-family: sans-serif;'>
                    <h2>Không tìm thấy bài viết!</h2>
                    <a href='index.php'>Quay lại trang chủ</a>
                 </div>");
        }

        $id = $blog['blog_id'];

        $stmtRelated = $this->db->prepare("SELECT * FROM blogs WHERE blog_id != ? ORDER BY created_at DESC LIMIT 3");
        $stmtRelated->execute([$id]);
        $relatedBlogs = $stmtRelated->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/blog_detail.php';
    }

   public function blogs()
{
    $stmt = $this->db->query("SELECT * FROM blogs ORDER BY created_at DESC");
    $blogsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    require __DIR__ . '/../views/blogs.php';
}

public function about()
{
    require __DIR__ . '/../views/about.php';
}

public function careers()
{
    require __DIR__ . '/../views/careers.php';
}

public function affiliate()
{
    require __DIR__ . '/../views/affiliate.php';
}

public function guide()
{
    require __DIR__ . '/../views/guide.php';
}

public function faq()
{
    require __DIR__ . '/../views/faq.php';
}

public function policy()
{
    require __DIR__ . '/../views/policy.php';
}
}