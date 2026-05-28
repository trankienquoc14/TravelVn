<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helpers.php';

class ManagerController
{
    private $db;

    public function __construct()
    {
        require_once __DIR__ . '/../config/middleware.php';
        Middleware::managerOnly();
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->db = (new Database())->connect();
    }

    public function dashboard()
    {
        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'tour_manager' && $_SESSION['user']['role'] != 'admin')) {
            header("Location: ../views/login.php");
            exit();
        }

        $filterDate = $_GET['filter_date'] ?? date('Y-m-d');
        $startOfMonth = date('Y-m-01', strtotime($filterDate));

        $totalTours = $this->db->query("SELECT COUNT(*) FROM tours WHERE status = 'active'")->fetchColumn();

        $stmtBookings = $this->db->prepare("SELECT COUNT(*) FROM bookings WHERE DATE(booking_date) BETWEEN ? AND ?");
        $stmtBookings->execute([$startOfMonth, $filterDate]);
        $totalBookings = $stmtBookings->fetchColumn();

        $stmtRev = $this->db->prepare("
            SELECT COALESCE(SUM(total_price),0) 
            FROM bookings 
            WHERE status IN ('confirmed','completed','checked_in')
            AND DATE(booking_date) BETWEEN ? AND ?
        ");
        $stmtRev->execute([$startOfMonth, $filterDate]);
        $totalRevenue = $stmtRev->fetchColumn();

        $userName = $_SESSION['user']['full_name'] ?? $_SESSION['user']['name'] ?? 'Quản trị viên';
        require __DIR__ . '/../views/manager/dashboard.php';
    }

    public function tours()
    {
        require_once __DIR__ . '/../models/Tour.php';
        $tourModel = new Tour($this->db);
        $tours = $tourModel->getAllTours();
        require __DIR__ . '/../views/manager/manager_tours.php';
    }

    public function createTour()
    {
        $partners = $this->db->query("SELECT * FROM partners")->fetchAll(PDO::FETCH_ASSOC);
        require __DIR__ . '/../views/manager/create_tour.php';
    }

    public function storeTour()
    {
        $imageName = null;
        if (!empty($_FILES['image']['name'])) {
            $imageName = time() . '_' . $_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], "../public/uploads/" . $imageName);
        }

        $slug = create_slug($_POST['tour_name']);
        $stmt = $this->db->prepare("
            INSERT INTO tours 
            (partner_id, tour_name, slug, destination, description, price, duration, status, created_by, hotel, include_service, exclude_service, itinerary, image)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'active', ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $_POST['partner_id'],
            $_POST['tour_name'],
            $slug,
            $_POST['destination'],
            $_POST['description'],
            $_POST['price'],
            $_POST['duration'],
            $_SESSION['user']['user_id'],
            $_POST['hotel'],
            $_POST['include_service'],
            $_POST['exclude_service'],
            $_POST['itinerary'],
            $imageName
        ]);

        $_SESSION['success'] = "Đã thêm tour mới thành công!";
        header("Location: manager.php?action=tours");
        exit;
    }

    public function editTour()
    {
        require_once __DIR__ . '/../models/Tour.php';
        $id = $_GET['id'];
        $tourModel = new Tour($this->db);
        $tour = $tourModel->getTourById($id);
        $partners = $this->db->query("SELECT * FROM partners")->fetchAll(PDO::FETCH_ASSOC);
        require __DIR__ . '/../views/manager/edit_tour.php';
    }

    public function updateTour()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['tour_id'];
            $stmt = $this->db->prepare("SELECT image FROM tours WHERE tour_id=?");
            $stmt->execute([$id]);
            $old = $stmt->fetch(PDO::FETCH_ASSOC);
            $imageName = $old['image'];

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = __DIR__ . '/../public/uploads/';
                $imageName = time() . '_' . $_FILES['image']['name'];
                if (!empty($old['image']) && file_exists($targetDir . $old['image'])) {
                    unlink($targetDir . $old['image']);
                }
                move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $imageName);
            }

            $slug = create_slug($_POST['tour_name']);
            $stmt = $this->db->prepare("
    UPDATE tours 
    SET partner_id=?, tour_name=?, slug=?, destination=?, description=?, price=?, discount_percent=?, duration=?, hotel=?, include_service=?, exclude_service=?, itinerary=?, image=?
    WHERE tour_id=?
");

$stmt->execute([
    $_POST['partner_id'],
    $_POST['tour_name'],
    $slug,
    $_POST['destination'],
    $_POST['description'],
    $_POST['price'],
    $_POST['discount_percent'], // Thêm biến này
    $_POST['duration'],
    $_POST['hotel'],
    $_POST['include_service'],
    $_POST['exclude_service'],
    $_POST['itinerary'],
    $imageName,
    $id
]);

            $_SESSION['success'] = "Đã cập nhật thông tin tour thành công!";
            header("Location: manager.php?action=tours");
            exit();
        }
    }

    public function deleteTour()
    {
        $id = $_GET['id'];
        $stmt = $this->db->prepare("SELECT image FROM tours WHERE tour_id=?");
        $stmt->execute([$id]);
        $tour = $stmt->fetch(PDO::FETCH_ASSOC);

        $path = __DIR__ . '/../public/uploads/';
        if (!empty($tour['image']) && file_exists($path . $tour['image'])) {
            unlink($path . $tour['image']);
        }

        $stmt = $this->db->prepare("DELETE FROM tours WHERE tour_id=?");
        $stmt->execute([$id]);

        $_SESSION['success'] = "Đã xóa tour thành công!";
        header("Location: manager.php?action=tours");
        exit;
    }

    public function partners()
    {
        $partners = $this->db->query("SELECT * FROM partners")->fetchAll(PDO::FETCH_ASSOC);
        require __DIR__ . '/../views/manager/partners.php';
    }

    public function createPartner() { require __DIR__ . '/../views/manager/create_partner.php'; }

    public function storePartner()
    {
        $stmt = $this->db->prepare("INSERT INTO partners (partner_name, contact_person, phone, email, address, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$_POST['partner_name'], $_POST['contact_person'], $_POST['phone'], $_POST['email'], $_POST['address']]);
        $_SESSION['success'] = "Đã thêm đối tác thành công!";
        header("Location: manager.php?action=partners");
        exit;
    }

    public function editPartner()
    {
        $id = $_GET['id'];
        $stmt = $this->db->prepare("SELECT * FROM partners WHERE partner_id=?");
        $stmt->execute([$id]);
        $partner = $stmt->fetch(PDO::FETCH_ASSOC);
        require __DIR__ . '/../views/manager/edit_partner.php';
    }

    public function updatePartner()
    {
        $stmt = $this->db->prepare("UPDATE partners SET partner_name=?, contact_person=?, phone=?, email=?, address=? WHERE partner_id=?");
        $stmt->execute([$_POST['partner_name'], $_POST['contact_person'], $_POST['phone'], $_POST['email'], $_POST['address'], $_POST['partner_id']]);
        $_SESSION['success'] = "Đã cập nhật thông tin đối tác!";
        header("Location: manager.php?action=partners");
        exit;
    }

    public function deletePartner()
    {
        $id = $_GET['id'];
        $check = $this->db->prepare("SELECT COUNT(*) FROM tours WHERE partner_id=?");
        $check->execute([$id]);

        if ($check->fetchColumn() > 0) {
            $_SESSION['error'] = "Không thể xóa! Đối tác này hiện đang có tour liên kết.";
            header("Location: manager.php?action=partners");
            exit;
        }

        $this->db->prepare("DELETE FROM partners WHERE partner_id=?")->execute([$id]);
        $_SESSION['success'] = "Đã xóa đối tác thành công!";
        header("Location: manager.php?action=partners");
        exit;
    }

    
    

    public function createDeparture()
    {
        // Lấy thêm cột duration (số ngày) từ bảng tours để JS tự động tính ngày kết thúc
        $tours = $this->db->query("SELECT tour_id, tour_name, duration FROM tours WHERE status = 'active'")->fetchAll(PDO::FETCH_ASSOC);
        $guides = $this->db->query("SELECT user_id, full_name FROM users WHERE role='guide'")->fetchAll(PDO::FETCH_ASSOC);
        require __DIR__ . '/../views/manager/create_departure.php';
    }

    public function storeDeparture()
    {
        if ($_POST['end_date'] < $_POST['start_date']) {
            $_SESSION['error'] = "Ngày kết thúc không được nhỏ hơn ngày khởi hành!";
            header("Location: manager.php?action=departures");
            exit;
        }

        // Luồng ghế ngồi chuẩn: Lúc mới tạo, available_seats (ghế trống) = max_seats (tối đa), booked_seats = 0
        $stmt = $this->db->prepare("
            INSERT INTO departures (tour_id, start_date, end_date, max_seats, available_seats, booked_seats, status) 
            VALUES (?, ?, ?, ?, ?, 0, 'upcoming')
        ");
        $stmt->execute([
            $_POST['tour_id'], 
            $_POST['start_date'], 
            $_POST['end_date'], 
            $_POST['max_seats'], 
            $_POST['max_seats'] // Gán số ghế trống bằng số chỗ tối đa
        ]);
        
        $departure_id = $this->db->lastInsertId();

        // Lưu thông tin Hướng dẫn viên & Bắn thông báo Realtime
        if (!empty($_POST['guides'])) {
            $stmtGuide = $this->db->prepare("INSERT INTO departure_guides (departure_id, guide_id) VALUES (?, ?)");
            
            // Khởi tạo Pusher
            try {
                require_once __DIR__ . '/../vendor/autoload.php';
                $options = ['cluster' => 'ap1', 'useTLS' => true];
                $pusher = new Pusher\Pusher('e5405b1b2139fed6f8bc', '2f482d4b39a5f0acd508', '2149497', $options);
            } catch (Exception $e) { $pusher = null; }

            foreach ($_POST['guides'] as $guide_id) {
                $stmtGuide->execute([$departure_id, $guide_id]);
                
                // Gửi thông báo đến riêng từng kênh của Hướng dẫn viên được chọn
                if ($pusher) {
                    $dateStr = date('d/m/Y', strtotime($_POST['start_date']));
                    $pusher->trigger('guide-channel-' . $guide_id, 'new-assignment', [
                        'message' => "🔔 Bạn vừa được phân công dẫn tour khởi hành vào ngày {$dateStr}!"
                    ]);
                }
            }
        }

        $_SESSION['success'] = "Đã lên lịch khởi hành mới thành công!";
        header("Location: manager.php?action=departures");
        exit;
    }

    public function editDeparture()
    {
        $id = $_GET['id'];
        $stmt = $this->db->prepare("SELECT * FROM departures WHERE departure_id=?");
        $stmt->execute([$id]);
        $departure = $stmt->fetch(PDO::FETCH_ASSOC);

        // Lấy thêm cột duration để JS tự động tính ngày
        $tours = $this->db->query("SELECT tour_id, tour_name, duration FROM tours WHERE status = 'active'")->fetchAll(PDO::FETCH_ASSOC);
        $guides = $this->db->query("SELECT user_id, full_name FROM users WHERE role='guide'")->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->db->prepare("SELECT guide_id FROM departure_guides WHERE departure_id=?");
        $stmt->execute([$id]);
        $selectedGuides = $stmt->fetchAll(PDO::FETCH_COLUMN);

        require __DIR__ . '/../views/manager/edit_departure.php';
    }

    public function updateDeparture()
    {
        $id = $_POST['departure_id'];
        
        if ($_POST['end_date'] < $_POST['start_date']) {
            $_SESSION['error'] = "Ngày kết thúc không được nhỏ hơn ngày khởi hành!";
            header("Location: manager.php?action=editDeparture&id=" . $id);
            exit;
        }

        // 1. LẤY SỐ LIỆU CŨ ĐỂ TÍNH TOÁN LOGIC GHẾ NGỒI
        $stmtOld = $this->db->prepare("SELECT max_seats, available_seats, booked_seats FROM departures WHERE departure_id=?");
        $stmtOld->execute([$id]);
        $oldDep = $stmtOld->fetch(PDO::FETCH_ASSOC);

        $new_max = (int)$_POST['max_seats'];

        // Chặn: Không cho phép sửa max_seats nhỏ hơn số khách đã đặt
        if ($new_max < $oldDep['booked_seats']) {
            $_SESSION['error'] = "Không thể giảm! Số chỗ tối đa ($new_max) không được nhỏ hơn số khách đã đặt ({$oldDep['booked_seats']} khách).";
            header("Location: manager.php?action=editDeparture&id=" . $id);
            exit;
        }

        // Tự động điều chỉnh số ghế trống (available_seats) nếu max_seats tăng hoặc giảm hợp lệ
        $diff = $new_max - $oldDep['max_seats'];
        $new_available = $oldDep['available_seats'] + $diff;

        // 2. CẬP NHẬT DATABASE
        $stmt = $this->db->prepare("UPDATE departures SET tour_id=?, start_date=?, end_date=?, max_seats=?, available_seats=?, status=? WHERE departure_id=?");
        $stmt->execute([$_POST['tour_id'], $_POST['start_date'], $_POST['end_date'], $new_max, $new_available, $_POST['status'], $id]);

        // 3. CẬP NHẬT HƯỚNG DẪN VIÊN & BẮN REALTIME
        $this->db->prepare("DELETE FROM departure_guides WHERE departure_id=?")->execute([$id]);

        if (!empty($_POST['guides'])) {
            $stmtGuide = $this->db->prepare("INSERT INTO departure_guides (departure_id, guide_id) VALUES (?, ?)");
            
            try {
                require_once __DIR__ . '/../vendor/autoload.php';
                $options = ['cluster' => 'ap1', 'useTLS' => true];
                $pusher = new Pusher\Pusher('e5405b1b2139fed6f8bc', '2f482d4b39a5f0acd508', '2149497', $options);
            } catch (Exception $e) { $pusher = null; }

            foreach ($_POST['guides'] as $g) {
                $stmtGuide->execute([$id, $g]);
                
                // Bắn thông báo cập nhật cho HDV
                if ($pusher) {
                    $dateStr = date('d/m/Y', strtotime($_POST['start_date']));
                    $pusher->trigger('guide-channel-' . $g, 'new-assignment', [
                        'message' => "🔄 Lịch trình khởi hành ngày {$dateStr} của bạn vừa được Admin cập nhật!"
                    ]);
                }
            }
        }

        $_SESSION['success'] = "Đã cập nhật thông tin chuyến khởi hành!";
        header("Location: manager.php?action=departures");
        exit;
    }
    public function departures()
    {
        $stmt = $this->db->query("
            SELECT d.*, t.tour_name, GROUP_CONCAT(DISTINCT u.full_name SEPARATOR ', ') AS guides,
                   (SELECT COALESCE(SUM(b.number_of_people), 0) FROM bookings b WHERE b.departure_id = d.departure_id AND b.status IN ('confirmed', 'completed', 'checked_in')) AS real_booked_seats
            FROM departures d
            JOIN tours t ON d.tour_id = t.tour_id
            LEFT JOIN departure_guides dg ON d.departure_id = dg.departure_id
            LEFT JOIN users u ON dg.guide_id = u.user_id
            WHERE d.status != 'cancelled'
            GROUP BY d.departure_id
            ORDER BY d.start_date DESC
        ");
        $departures = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require __DIR__ . '/../views/manager/departures.php';
    }

    public function deleteDeparture()
    {
        $id = $_GET['id'];
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM bookings WHERE departure_id=?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['error'] = "Không thể huỷ! Chuyến đi này đã có khách hàng đặt chỗ.";
            header("Location: manager.php?action=departures");
            exit;
        }

        $this->db->prepare("UPDATE departures SET status='cancelled' WHERE departure_id=?")->execute([$id]);
        $_SESSION['success'] = "Đã hủy bỏ chuyến đi thành công.";
        header("Location: manager.php?action=departures");
        exit;
    }
private function triggerPusher($userId, $bookingId, $statusText, $badgeClass) {
        try {
            require_once __DIR__ . '/../vendor/autoload.php';
            $options = ['cluster' => 'ap1', 'useTLS' => true];
            $pusher = new Pusher\Pusher('e5405b1b2139fed6f8bc', '2f482d4b39a5f0acd508', '2149497', $options);
            
            $pusherData = [
                'booking_id' => $bookingId, 
                'status_text' => $statusText, 
                'badge_class' => $badgeClass
            ];
            $pusher->trigger('user-channel-' . $userId, 'status-changed', $pusherData);
        } catch (Exception $e) { 
            error_log("Pusher Error: " . $e->getMessage()); 
        }
    }
    public function bookings()
    {
        $stmt = $this->db->query("
            SELECT b.*, t.tour_name, d.start_date, p.payment_method, p.payment_status 
            FROM bookings b
            JOIN departures d ON b.departure_id = d.departure_id
            JOIN tours t ON d.tour_id = t.tour_id
            LEFT JOIN payments p ON b.booking_id = p.booking_id
            ORDER BY b.booking_date DESC
        ");
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require __DIR__ . '/../views/manager/bookings.php';
    }

    public function confirmBooking()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        try {
            // 1. BẮT ĐẦU TRANSACTION
            $this->db->beginTransaction();

            // 2. KHÓA DÒNG BẰNG "FOR UPDATE" ĐỂ CHỐNG RACE CONDITION (CLICK ĐÚP)
            $stmtBooking = $this->db->prepare("SELECT user_id, departure_id, number_of_people, status, customer_name FROM bookings WHERE booking_id = ? FOR UPDATE");
            $stmtBooking->execute([$id]);
            $booking = $stmtBooking->fetch(PDO::FETCH_ASSOC);

            // Nếu không tìm thấy đơn hoặc đơn KHÔNG phải pending -> Bỏ qua
            if (!$booking || $booking['status'] !== 'pending') {
                $this->db->rollBack();
                header("Location: manager.php?action=bookings");
                exit;
            }

            // 3. Kiểm tra ghế trống trước khi duyệt (Khóa luôn dòng tour này)
            $stmtDep = $this->db->prepare("SELECT available_seats FROM departures WHERE departure_id = ? FOR UPDATE");
            $stmtDep->execute([$booking['departure_id']]);
            $departure = $stmtDep->fetch(PDO::FETCH_ASSOC);

            if ($departure['available_seats'] < $booking['number_of_people']) {
                $this->db->rollBack();
                $_SESSION['error'] = "Lỗi: Chuyến đi này không còn đủ chỗ trống để duyệt!";
                header("Location: manager.php?action=bookings");
                exit;
            }

            // 4. Thực thi việc trừ ghế và cập nhật trạng thái
            $this->db->prepare("UPDATE bookings SET status='confirmed' WHERE booking_id=?")->execute([$id]);

            $stmtUpdateSeats = $this->db->prepare("UPDATE departures SET booked_seats = booked_seats + ?, available_seats = available_seats - ? WHERE departure_id = ?");
            $stmtUpdateSeats->execute([$booking['number_of_people'], $booking['number_of_people'], $booking['departure_id']]);

            // 5. HOÀN TẤT TRANSACTION
            $this->db->commit();

            // REALTIME PUSHER
            $this->triggerPusher($booking['user_id'], $id, 'Đã xác nhận', 'badge-confirmed');

            $_SESSION['success'] = "Đã duyệt thành công đơn hàng <strong>#{$id}</strong>!";
            
        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['error'] = "Đã xảy ra lỗi hệ thống: " . $e->getMessage();
        }
        
        header("Location: manager.php?action=bookings");
        exit;
    }

    public function cancelBooking()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        try {
            $this->db->beginTransaction();

            $stmtBooking = $this->db->prepare("SELECT user_id, departure_id, number_of_people, status, customer_name FROM bookings WHERE booking_id = ? FOR UPDATE");
            $stmtBooking->execute([$id]);
            $booking = $stmtBooking->fetch(PDO::FETCH_ASSOC);

            // Đảm bảo đơn chưa bị hủy trước đó
            if ($booking && $booking['status'] !== 'cancelled' && $booking['status'] !== 'refunded') {
                
                // NẾU đơn đã duyệt (tức là đã trừ ghế), thì khi hủy MỚI ĐƯỢC CỘNG LẠI GHẾ
                // (Chống lỗi Vũng Tàu dội lên 21 ghế)
                if ($booking['status'] === 'confirmed') {
                    $stmtRestoreSeats = $this->db->prepare("UPDATE departures SET booked_seats = booked_seats - ?, available_seats = available_seats + ? WHERE departure_id = ?");
                    $stmtRestoreSeats->execute([$booking['number_of_people'], $booking['number_of_people'], $booking['departure_id']]);
                }

                $this->db->prepare("UPDATE bookings SET status='cancelled' WHERE booking_id=?")->execute([$id]);
                
                $this->db->commit();

                // REALTIME PUSHER
                $this->triggerPusher($booking['user_id'], $id, 'Đã hủy', 'badge-cancelled');

                $_SESSION['success'] = "Đã hủy đơn <strong>#{$id}</strong> thành công.";
            } else {
                $this->db->rollBack();
            }

        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['error'] = "Lỗi hệ thống khi hủy đơn.";
        }

        header("Location: manager.php?action=bookings");
        exit;
    }

    public function refundBooking()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        try {
            $this->db->beginTransaction();

            $stmtBooking = $this->db->prepare("SELECT user_id, status, departure_id, number_of_people FROM bookings WHERE booking_id = ? FOR UPDATE");
            $stmtBooking->execute([$id]);
            $booking = $stmtBooking->fetch(PDO::FETCH_ASSOC);

            if ($booking && $booking['status'] !== 'refunded') {
                
                // Trường hợp Admin bấm Hoàn Tiền thẳng từ đơn đang Confirmed (chưa qua Cancel)
                if ($booking['status'] === 'confirmed') {
                    $this->db->prepare("UPDATE departures SET booked_seats = booked_seats - ?, available_seats = available_seats + ? WHERE departure_id = ?")
                             ->execute([$booking['number_of_people'], $booking['number_of_people'], $booking['departure_id']]);
                }

                $this->db->prepare("UPDATE bookings SET status='refunded' WHERE booking_id=?")->execute([$id]);
                $this->db->commit();

                $this->triggerPusher($booking['user_id'], $id, 'Đã hoàn tiền', 'badge-refunded');
                $_SESSION['success'] = "Đã đánh dấu hoàn tiền thành công đơn #{$id}!";
            } else {
                $this->db->rollBack();
            }

        } catch (Exception $e) {
            $this->db->rollBack();
        }

        header("Location: manager.php?action=bookings");
        exit;
    }

    public function confirmCash()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        try {
            $this->db->beginTransaction();

            $stmtBooking = $this->db->prepare("SELECT user_id, departure_id, number_of_people, status, customer_name FROM bookings WHERE booking_id = ? FOR UPDATE");
            $stmtBooking->execute([$id]);
            $booking = $stmtBooking->fetch(PDO::FETCH_ASSOC);

            if ($booking) {
                // Update thanh toán
                $this->db->prepare("UPDATE payments SET payment_status = 'paid' WHERE booking_id = ?")->execute([$id]);

                // Nếu đơn chưa Confirm thì Confirm luôn và trừ ghế
                if ($booking['status'] === 'pending') {
                    
                    // Check ghế
                    $stmtDep = $this->db->prepare("SELECT available_seats FROM departures WHERE departure_id = ? FOR UPDATE");
                    $stmtDep->execute([$booking['departure_id']]);
                    if ($stmtDep->fetchColumn() < $booking['number_of_people']) {
                        $this->db->rollBack();
                        $_SESSION['error'] = "Không đủ ghế để duyệt!";
                        header("Location: manager.php?action=bookings");
                        exit;
                    }

                    $this->db->prepare("UPDATE bookings SET status='confirmed' WHERE booking_id=?")->execute([$id]);
                    $this->db->prepare("UPDATE departures SET booked_seats = booked_seats + ?, available_seats = available_seats - ? WHERE departure_id = ?")
                             ->execute([$booking['number_of_people'], $booking['number_of_people'], $booking['departure_id']]);
                    
                    $this->triggerPusher($booking['user_id'], $id, 'Đã xác nhận', 'badge-confirmed');
                }

                $this->db->commit();
                $_SESSION['success'] = "Đã xác nhận thanh toán tiền mặt thành công đơn #{$id}!";
            } else {
                $this->db->rollBack();
            }

        } catch (Exception $e) {
            $this->db->rollBack();
        }

        header("Location: manager.php?action=bookings");
        exit;
    }

    public function bookingDetail()
    {
        $booking_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($booking_id <= 0) {
            $_SESSION['error'] = "Mã đơn hàng không hợp lệ!";
            header("Location: manager.php?action=bookings");
            exit;
        }

        $stmt = $this->db->prepare("
            SELECT b.*, u.email as account_email, t.tour_name, t.image, d.start_date, d.end_date, 
                   p.payment_method, p.payment_status, p.transaction_code, p.payment_date
            FROM bookings b
            LEFT JOIN users u ON b.user_id = u.user_id
            JOIN departures d ON b.departure_id = d.departure_id
            JOIN tours t ON d.tour_id = t.tour_id
            LEFT JOIN payments p ON b.booking_id = p.booking_id
            WHERE b.booking_id = ?
        ");
        $stmt->execute([$booking_id]);
        $detail = $stmt->fetch(PDO::FETCH_ASSOC);
        require __DIR__ . '/../views/manager/booking_detail.php';
    }

    public function report()
    {
        $startDate = !empty($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $endDate = !empty($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
        $statusFilter = $_GET['status'] ?? '';

        $where = "WHERE DATE(b.booking_date) BETWEEN ? AND ?";
        $params = [$startDate, $endDate];

        $whereStatus = $where;
        $paramsStatus = $params;
        if ($statusFilter !== '') {
            $whereStatus .= " AND b.status = ?";
            $paramsStatus[] = $statusFilter;
        }

        $stmtRev = $this->db->prepare("SELECT COALESCE(SUM(total_price), 0) FROM bookings b $where AND b.status IN ('confirmed', 'completed', 'checked_in')");
        $stmtRev->execute($params);
        $totalRevenue = $stmtRev->fetchColumn();

        $stmtCount = $this->db->prepare("SELECT COUNT(*) FROM bookings b $whereStatus");
        $stmtCount->execute($paramsStatus);
        $totalBookings = $stmtCount->fetchColumn();

        $totalTours = $this->db->query("SELECT COUNT(*) FROM tours WHERE status = 'active'")->fetchColumn();

        $stmtChart = $this->db->prepare("
            SELECT DATE_FORMAT(booking_date, '%d/%m') as month, SUM(total_price) as revenue
            FROM bookings b $where AND b.status IN ('confirmed', 'completed', 'checked_in')
            GROUP BY month ORDER BY MIN(booking_date) ASC
        ");
        $stmtChart->execute($params);
        $revenueByMonth = $stmtChart->fetchAll(PDO::FETCH_ASSOC);

        $stmtTop = $this->db->prepare("
            SELECT t.tour_name, COUNT(b.booking_id) as total
            FROM bookings b JOIN departures d ON b.departure_id = d.departure_id JOIN tours t ON d.tour_id = t.tour_id
            $whereStatus GROUP BY t.tour_id ORDER BY total DESC LIMIT 5
        ");
        $stmtTop->execute($paramsStatus);
        $topTours = $stmtTop->fetchAll(PDO::FETCH_ASSOC);

        $stmtStatus = $this->db->prepare("SELECT status, COUNT(*) as total FROM bookings b $where GROUP BY status");
        $stmtStatus->execute($params);
        $statusStats = $stmtStatus->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/manager/report.php';
    }

    public function blogs()
    {
        $blogsList = $this->db->query("SELECT * FROM blogs ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
        $activeMenu = 'blogs';
        require __DIR__ . '/../views/manager/manager_blogs.php';
    }

    public function blogForm()
    {
        $id = $_GET['id'] ?? 0;
        $blog = null;
        if ($id > 0) {
            $stmt = $this->db->prepare("SELECT * FROM blogs WHERE blog_id = ?");
            $stmt->execute([$id]);
            $blog = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        $activeMenu = 'blogs';
        require __DIR__ . '/../views/manager/blog_form.php';
    }

    public function saveBlog()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['blog_id'] ?? 0;
            $title = $_POST['title'] ?? '';
            $category = $_POST['category'] ?? 'Cẩm nang';
            $short_desc = $_POST['short_desc'] ?? '';
            $content = $_POST['content'] ?? '';
            $imageName = $_POST['old_image'] ?? '';

            if (!empty($_FILES['image']['name'])) {
                $targetDir = __DIR__ . '/../public/uploads/';
                $newImageName = time() . '_' . $_FILES['image']['name'];
                if (!empty($imageName) && strpos($imageName, 'http') === false && file_exists($targetDir . $imageName)) {
                    unlink($targetDir . $imageName);
                }
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetDir . $newImageName)) {
                    $imageName = $newImageName;
                }
            }
            $slug = create_slug($title);
            if ($id > 0) {
                $this->db->prepare("UPDATE blogs SET title=?, slug=?, category=?, short_desc=?, content=?, image=? WHERE blog_id=?")->execute([$title, $slug, $category, $short_desc, $content, $imageName, $id]);
                $_SESSION['success'] = "Đã cập nhật bài viết thành công!";
            } else {
                $this->db->prepare("INSERT INTO blogs (title, slug, category, short_desc, content, image) VALUES (?, ?, ?, ?, ?, ?)")->execute([$title, $slug, $category, $short_desc, $content, $imageName]);
                $_SESSION['success'] = "Đã thêm bài viết mới thành công!";
            }
            header("Location: manager.php?action=blogs");
            exit;
        }
    }

    public function deleteBlog()
    {
        $id = $_GET['id'] ?? 0;
        if ($id > 0) {
            $stmt = $this->db->prepare("SELECT image FROM blogs WHERE blog_id=?");
            $stmt->execute([$id]);
            $blog = $stmt->fetch(PDO::FETCH_ASSOC);
            $path = __DIR__ . '/../public/uploads/';
            if (!empty($blog['image']) && strpos($blog['image'], 'http') === false && file_exists($path . $blog['image'])) {
                unlink($path . $blog['image']);
            }
            $this->db->prepare("DELETE FROM blogs WHERE blog_id = ?")->execute([$id]);
            $_SESSION['success'] = "Đã xóa bài viết thành công!";
        }
        header("Location: manager.php?action=blogs");
        exit;
    }

    public function chat()
    {
        $activeMenu = 'chat';
        require_once __DIR__ . '/../views/admin/chat_manage.php';
    }
}