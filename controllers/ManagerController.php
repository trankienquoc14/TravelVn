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
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($id <= 0) {
        $_SESSION['error'] = "Tour không hợp lệ!";
        header("Location: manager.php?action=tours");
        exit;
    }

    // Kiểm tra tour có lịch khởi hành chưa
    $checkDeparture = $this->db->prepare("
        SELECT COUNT(*) 
        FROM departures 
        WHERE tour_id = ? 
        AND status != 'cancelled'
    ");
    $checkDeparture->execute([$id]);

    if ($checkDeparture->fetchColumn() > 0) {
        $_SESSION['error'] = "Không thể xóa tour này vì tour đang có lịch khởi hành.";
        header("Location: manager.php?action=tours");
        exit;
    }

    // Lấy ảnh tour
    $stmt = $this->db->prepare("SELECT image FROM tours WHERE tour_id=?");
    $stmt->execute([$id]);
    $tour = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tour) {
        $_SESSION['error'] = "Không tìm thấy tour cần xóa!";
        header("Location: manager.php?action=tours");
        exit;
    }

    // Xóa ảnh nếu có
    $path = __DIR__ . '/../public/uploads/';
    if (!empty($tour['image']) && file_exists($path . $tour['image'])) {
        unlink($path . $tour['image']);
    }

    // Xóa tour
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

    public function createPartner()
    {
        require __DIR__ . '/../views/manager/create_partner.php';
    }


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

    private function getGuideScheduleConflicts($guideIds, $startDate, $endDate, $excludeDepartureId = null)
    {
        if (empty($guideIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($guideIds), '?'));

        $sql = "
        SELECT u.full_name, d.departure_id, d.start_date, d.end_date, t.tour_name
        FROM departure_guides dg JOIN departures d ON dg.departure_id = d.departure_id
        JOIN tours t ON d.tour_id = t.tour_id JOIN users u ON dg.guide_id = u.user_id
        WHERE dg.guide_id IN ($placeholders) AND d.status != 'cancelled'
        AND d.start_date <= ?
        AND d.end_date >= ?
    ";

        $params = array_merge($guideIds, [$endDate, $startDate]);

        if (!empty($excludeDepartureId)) {
            $sql .= " AND d.departure_id != ?";
            $params[] = $excludeDepartureId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function storeDeparture()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: manager.php?action=departures");
            exit;
        }

        $tour_id = $_POST['tour_id'] ?? null;
        $start_date = $_POST['start_date'] ?? null;
        $end_date = $_POST['end_date'] ?? null;
        $max_seats = isset($_POST['max_seats']) ? (int) $_POST['max_seats'] : 0;
        $selectedGuides = $_POST['guides'] ?? [];

        // Kiểm tra dữ liệu cơ bản
        if (empty($tour_id) || empty($start_date) || empty($end_date) || $max_seats <= 0) {
            $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin lịch khởi hành!";
            header("Location: manager.php?action=createDeparture");
            exit;
        }

        if ($end_date < $start_date) {
            $_SESSION['error'] = "Ngày kết thúc không được nhỏ hơn ngày khởi hành!";
            header("Location: manager.php?action=createDeparture");
            exit;
        }

        // Kiểm tra HDV có bị trùng lịch không
        if (!empty($selectedGuides)) {
            $placeholders = implode(',', array_fill(0, count($selectedGuides), '?'));

            $sql = "
            SELECT 
                u.full_name,
                t.tour_name,
                d.start_date,
                d.end_date
            FROM departure_guides dg
            JOIN departures d ON dg.departure_id = d.departure_id
            JOIN tours t ON d.tour_id = t.tour_id
            JOIN users u ON dg.guide_id = u.user_id
            WHERE dg.guide_id IN ($placeholders)
            AND d.status != 'cancelled'
            AND d.start_date <= ?
            AND d.end_date >= ?
        ";

            $params = array_merge($selectedGuides, [$end_date, $start_date]);

            $stmtCheck = $this->db->prepare($sql);
            $stmtCheck->execute($params);
            $conflicts = $stmtCheck->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($conflicts)) {
                $messages = [];

                foreach ($conflicts as $c) {
                    $messages[] = htmlspecialchars($c['full_name'])
                        . " đang phụ trách tour \"" . htmlspecialchars($c['tour_name']) . "\" từ "
                        . date('d/m/Y', strtotime($c['start_date']))
                        . " đến "
                        . date('d/m/Y', strtotime($c['end_date']));
                }

                $_SESSION['error'] = "Không thể phân công HDV vì bị trùng lịch:<br>" . implode('<br>', $messages);
                header("Location: manager.php?action=createDeparture");
                exit;
            }
        }

        try {
            $this->db->beginTransaction();

           $realStatus = $this->getRealDepartureStatus($start_date, $end_date);

$stmt = $this->db->prepare("
    INSERT INTO departures 
    (tour_id, start_date, end_date, max_seats, available_seats, booked_seats, status) 
    VALUES (?, ?, ?, ?, ?, 0, ?)
");
        

            $stmt->execute([
    $tour_id,
    $start_date,
    $end_date,
    $max_seats,
    $max_seats,
    $realStatus
]);

            $departure_id = $this->db->lastInsertId();

            // Lưu HDV và gửi thông báo realtime
            if (!empty($selectedGuides)) {
                $stmtGuide = $this->db->prepare("
                INSERT INTO departure_guides (departure_id, guide_id) 
                VALUES (?, ?)
            ");

                // Khởi tạo Pusher
                try {
                    require_once __DIR__ . '/../vendor/autoload.php';
                    $options = ['cluster' => 'ap1', 'useTLS' => true];
                    $pusher = new Pusher\Pusher(
                        'e5405b1b2139fed6f8bc',
                        '2f482d4b39a5f0acd508',
                        '2149497',
                        $options
                    );
                } catch (Exception $e) {
                    $pusher = null;
                }

                foreach ($selectedGuides as $guide_id) {
                    $stmtGuide->execute([$departure_id, $guide_id]);

                    if ($pusher) {
                        $dateStr = date('d/m/Y', strtotime($start_date));

                        $pusher->trigger('guide-channel-' . $guide_id, 'new-assignment', [
                            'message' => "🔔 Bạn vừa được phân công dẫn tour khởi hành vào ngày {$dateStr}!"
                        ]);
                    }
                }
            }

            $this->db->commit();

            $_SESSION['success'] = "Đã lên lịch khởi hành mới thành công!";
            header("Location: manager.php?action=departures");
            exit;

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            $_SESSION['error'] = "Đã xảy ra lỗi khi thêm lịch khởi hành: " . $e->getMessage();
            header("Location: manager.php?action=createDeparture");
            exit;
        }
    }
    private function syncDepartureStatuses()
{
    date_default_timezone_set('Asia/Ho_Chi_Minh');

    $today = date('Y-m-d');

    // Chuyến đang diễn ra
    $stmtOngoing = $this->db->prepare("
        UPDATE departures
        SET status = 'ongoing'
        WHERE status NOT IN ('cancelled', 'completed')
        AND start_date <= ?
        AND end_date >= ?
    ");
    $stmtOngoing->execute([$today, $today]);

    // Chuyến đã hoàn thành
    $stmtCompleted = $this->db->prepare("
        UPDATE departures
        SET status = 'completed'
        WHERE status != 'cancelled'
        AND end_date < ?
    ");
    $stmtCompleted->execute([$today]);
}
    private function getRealDepartureStatus($startDate, $endDate)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $today = date('Y-m-d');
        $start = date('Y-m-d', strtotime($startDate));
        $end = date('Y-m-d', strtotime($endDate));

        if ($today < $start) {
            return 'upcoming';
        }

        if ($today >= $start && $today <= $end) {
            return 'ongoing';
        }

        return 'completed';
    }
    public function editDeparture()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        $stmt = $this->db->prepare("SELECT * FROM departures WHERE departure_id=?");
        $stmt->execute([$id]);
        $departure = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$departure) {
            $_SESSION['error'] = "Không tìm thấy lịch vận hành!";
            header("Location: manager.php?action=departures");
            exit;
        }

        // Tự động đồng bộ trạng thái theo ngày thực tế
        $realStatus = $this->getRealDepartureStatus(
            $departure['start_date'],
            $departure['end_date']
        );

        // Nếu ngày thực tế đã kết thúc mà DB chưa cập nhật thì cập nhật luôn
        if ($realStatus === 'completed' && $departure['status'] !== 'completed') {
            $stmtUpdateStatus = $this->db->prepare("
            UPDATE departures 
            SET status = 'completed' 
            WHERE departure_id = ?
        ");
            $stmtUpdateStatus->execute([$id]);

            $departure['status'] = 'completed';
        }

        // Nếu đang trong thời gian tour diễn ra thì cập nhật ongoing
        if ($realStatus === 'ongoing' && $departure['status'] !== 'ongoing') {
            $stmtUpdateStatus = $this->db->prepare("
            UPDATE departures 
            SET status = 'ongoing' 
            WHERE departure_id = ?
        ");
            $stmtUpdateStatus->execute([$id]);

            $departure['status'] = 'ongoing';
        }

        $tours = $this->db->query("
        SELECT tour_id, tour_name, duration 
        FROM tours 
        WHERE status = 'active'
    ")->fetchAll(PDO::FETCH_ASSOC);

        $guides = $this->db->query("
        SELECT user_id, full_name 
        FROM users 
        WHERE role='guide'
    ")->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $this->db->prepare("
        SELECT guide_id 
        FROM departure_guides 
        WHERE departure_id=?
    ");
        $stmt->execute([$id]);
        $selectedGuides = $stmt->fetchAll(PDO::FETCH_COLUMN);

        require __DIR__ . '/../views/manager/edit_departure.php';
    }

    public function updateDeparture()
    {
        $id = $_POST['departure_id'];

        $selectedGuides = $_POST['guides'] ?? [];

        $conflicts = $this->getGuideScheduleConflicts(
            $selectedGuides,
            $_POST['start_date'],
            $_POST['end_date'],
            $id
        );

        if (!empty($conflicts)) {
            $messages = [];

            foreach ($conflicts as $c) {
                $messages[] = "{$c['full_name']} đang phụ trách tour \"{$c['tour_name']}\" từ "
                    . date('d/m/Y', strtotime($c['start_date']))
                    . " đến "
                    . date('d/m/Y', strtotime($c['end_date']));
            }

            $_SESSION['error'] = "Không thể cập nhật vì HDV bị trùng lịch:<br>" . implode('<br>', $messages);
            header("Location: manager.php?action=editDeparture&id=" . $id);
            exit;
        }

        // 1. LẤY SỐ LIỆU CŨ ĐỂ TÍNH TOÁN LOGIC GHẾ NGỒI
        $stmtOld = $this->db->prepare("SELECT max_seats, available_seats, booked_seats FROM departures WHERE departure_id=?");
        $stmtOld->execute([$id]);
        $oldDep = $stmtOld->fetch(PDO::FETCH_ASSOC);

        $new_max = (int) $_POST['max_seats'];

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
        $realStatus = $this->getRealDepartureStatus(
            $_POST['start_date'],
            $_POST['end_date']
        );

        // Nếu chuyến đã kết thúc hoặc đang diễn ra thì ưu tiên trạng thái thực tế
        if ($realStatus === 'completed' || $realStatus === 'ongoing') {
            $finalStatus = $realStatus;
        } else {
            // Nếu chưa khởi hành thì cho admin chọn upcoming hoặc closed
            $finalStatus = $_POST['status'] ?? 'upcoming';
        }

        $stmt = $this->db->prepare("
    UPDATE departures 
    SET tour_id=?, start_date=?, end_date=?, max_seats=?, available_seats=?, status=? 
    WHERE departure_id=?
");

        $stmt->execute([
            $_POST['tour_id'],
            $_POST['start_date'],
            $_POST['end_date'],
            $new_max,
            $new_available,
            $finalStatus,
            $id
        ]);

        // 3. CẬP NHẬT HƯỚNG DẪN VIÊN & BẮN REALTIME
        $this->db->prepare("DELETE FROM departure_guides WHERE departure_id=?")->execute([$id]);

        if (!empty($_POST['guides'])) {
            $stmtGuide = $this->db->prepare("INSERT INTO departure_guides (departure_id, guide_id) VALUES (?, ?)");

            try {
                require_once __DIR__ . '/../vendor/autoload.php';
                $options = ['cluster' => 'ap1', 'useTLS' => true];
                $pusher = new Pusher\Pusher('e5405b1b2139fed6f8bc', '2f482d4b39a5f0acd508', '2149497', $options);
            } catch (Exception $e) {
                $pusher = null;
            }

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
        $this->syncDepartureStatuses();
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
    private function triggerPusher($userId, $bookingId, $statusText, $badgeClass)
    {
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
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        try {
            $this->db->beginTransaction();

            $stmtBooking = $this->db->prepare("
            SELECT user_id, status, customer_name
            FROM bookings
            WHERE booking_id = ?
            FOR UPDATE
        ");
            $stmtBooking->execute([$id]);
            $booking = $stmtBooking->fetch(PDO::FETCH_ASSOC);

            if (!$booking || $booking['status'] !== 'pending') {
                $this->db->rollBack();
                header("Location: manager.php?action=bookings");
                exit;
            }

            // Chỉ xác nhận đơn, KHÔNG trừ ghế nữa
            $this->db->prepare("
            UPDATE bookings
            SET status='confirmed'
            WHERE booking_id=?
        ")->execute([$id]);

            // Thông báo cho khách
            $link_user = "index.php?action=myBookings";
            $message_user = "✅ Đơn đặt tour #" . str_pad($id, 6, '0', STR_PAD_LEFT) . " của bạn đã được hệ thống xác nhận!";

            $this->db->prepare("
            INSERT INTO notifications
            (user_id, booking_id, type, link, message)
            VALUES (?, ?, 'Xác Nhận', ?, ?)
        ")->execute([
                        $booking['user_id'],
                        $id,
                        $link_user,
                        $message_user
                    ]);

            $this->db->commit();
            $_SESSION['realtime_notify'] = [
                'target_user_id' => $booking['user_id'],
                'type' => 'Xác Nhận',
                'title' => '✅ Đã xác nhận',
                'message' => "Đơn đặt tour #" . str_pad($id, 6, '0', STR_PAD_LEFT) . " đã được hệ thống duyệt!",
                'booking_id' => $id,
                'is_silent' => false
            ];

            $customerName = htmlspecialchars($booking['customer_name'] ?? 'Khách hàng');
            $_SESSION['success'] = "Đã duyệt thành công đơn hàng <strong>#{$id}</strong> của khách {$customerName}!";

        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['error'] = "Đã xảy ra lỗi hệ thống: " . $e->getMessage();
        }

        header("Location: manager.php?action=bookings");
        exit;
    }

    // ================= 4. XÁC NHẬN TIỀN MẶT (Báo cho Khách) =================
    public function confirmCash()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        try {
            $this->db->beginTransaction();

            $stmtBooking = $this->db->prepare("
            SELECT user_id, status, customer_name
            FROM bookings
            WHERE booking_id = ?
            FOR UPDATE
        ");
            $stmtBooking->execute([$id]);
            $booking = $stmtBooking->fetch(PDO::FETCH_ASSOC);

            if ($booking) {

                // Cập nhật thanh toán
                $this->db->prepare("
                UPDATE payments
                SET payment_status = 'paid'
                WHERE booking_id = ?
            ")->execute([$id]);

                // Nếu đơn đang pending thì chỉ confirm
                if ($booking['status'] === 'pending') {

                    $this->db->prepare("
                    UPDATE bookings
                    SET status='confirmed'
                    WHERE booking_id=?
                ")->execute([$id]);

                    // Thông báo cho khách
                    $link_user = "index.php?action=myBookings";
                    $message_user = "💰 Đơn hàng #" . str_pad($id, 6, '0', STR_PAD_LEFT) . " của bạn đã được xác nhận đã thanh toán bằng tiền mặt!";

                    $this->db->prepare("
                    INSERT INTO notifications
                    (user_id, booking_id, type, link, message)
                    VALUES (?, ?, 'Thanh Toán', ?, ?)
                ")->execute([
                                $booking['user_id'],
                                $id,
                                $link_user,
                                $message_user
                            ]);
                }

                $this->db->commit();

                $customerName = htmlspecialchars($booking['customer_name'] ?? 'Khách hàng');
                $_SESSION['success'] = "Đã xác nhận thu tiền mặt thành công đơn #{$id} của khách {$customerName}!";

            } else {
                $this->db->rollBack();
            }

        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['error'] = "Lỗi khi xác nhận thanh toán: " . $e->getMessage();
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
    public function cancelBooking()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            $_SESSION['error'] = "Mã đơn hàng không hợp lệ!";
            header("Location: manager.php?action=bookings");
            exit;
        }

        try {
            $this->db->beginTransaction();

            // 1. Khóa row và lấy thêm departure_id, number_of_people để biết số ghế cần trả
            $stmtBooking = $this->db->prepare("
                SELECT user_id, status, customer_name, departure_id, number_of_people
                FROM bookings
                WHERE booking_id = ?
                FOR UPDATE
            ");
            $stmtBooking->execute([$id]);
            $booking = $stmtBooking->fetch(PDO::FETCH_ASSOC);

            // Kiểm tra xem đơn có tồn tại hoặc đã bị hủy trước đó chưa
            if (!$booking || $booking['status'] === 'cancelled') {
                $this->db->rollBack();
                $_SESSION['error'] = "Đơn hàng không tồn tại hoặc đã bị hủy trước đó!";
                header("Location: manager.php?action=bookings");
                exit;
            }

            // 2. Cập nhật trạng thái đơn hàng thành 'cancelled' (Đã hủy)
            $this->db->prepare("
                UPDATE bookings
                SET status='cancelled'
                WHERE booking_id=?
            ")->execute([$id]);

            // 3. HOÀN TRẢ GHẾ: Cộng lại ghế trống và trừ ghế đã đặt trong bảng departures
            $slotsToReturn = (int) $booking['number_of_people'];
            $this->db->prepare("
                UPDATE departures 
                SET available_seats = available_seats + ?, 
                    booked_seats = booked_seats - ? 
                WHERE departure_id = ?
            ")->execute([$slotsToReturn, $slotsToReturn, $booking['departure_id']]);

            // 4. Ghi thông báo vào CSDL cho khách hàng
            $link_user = "index.php?action=myBookings";
            $message_user = "❌ Đơn đặt tour #" . str_pad($id, 6, '0', STR_PAD_LEFT) . " của bạn đã bị hủy bởi quản trị viên. Số chỗ đã được hoàn lại.";

            $this->db->prepare("
                INSERT INTO notifications
                (user_id, booking_id, type, link, message)
                VALUES (?, ?, 'Hủy Đơn', ?, ?)
            ")->execute([
                        $booking['user_id'],
                        $id,
                        $link_user,
                        $message_user
                    ]);

            $this->db->commit();

            $customerName = htmlspecialchars($booking['customer_name'] ?? 'Khách hàng');
            $_SESSION['success'] = "Đã hủy đơn hàng <strong>#{$id}</strong> của khách {$customerName} và hoàn trả {$slotsToReturn} chỗ!";

        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['error'] = "Đã xảy ra lỗi hệ thống: " . $e->getMessage();
        }

        header("Location: manager.php?action=bookings");
        exit;
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