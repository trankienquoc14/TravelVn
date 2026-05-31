<?php
// 1. Phải có dòng này ở ĐẦU TIÊN để nhận diện người dùng đã đăng nhập hay chưa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Nhúng các file Controller
require_once '../controllers/TourController.php';
require_once '../controllers/PaymentController.php';
require_once '../controllers/AuthController.php';
require_once '../controllers/ReviewController.php';
require_once '../config/helpers.php';
// 🔥 BỔ SUNG: Nhúng ChatController vào hệ thống
require_once '../controllers/ChatController.php';

// 3. Lấy hành động từ URL
$action = $_GET['action'] ?? 'home';

// 4. Phân luồng Controller
// 🔥 BỔ SUNG: XỬ LÝ API NGẦM ĐÁNH DẤU ĐÃ ĐỌC THÔNG BÁO CHUÔNG
if ($action === 'markNotifRead') {
    require_once '../config/database.php';
    $db = (new Database())->connect();

    $role = $_SESSION['user']['role'] ?? '';
    $uid = $_SESSION['user']['user_id'] ?? 0;

    // Nếu là Admin thì đánh dấu đọc các thông báo hệ thống (user_id IS NULL)
    if ($role === 'admin' || $role === 'tour_manager') {
        $db->query("UPDATE notifications SET is_read = 1 WHERE user_id IS NULL");
    } elseif ($uid > 0) {
        // Khách hàng thì chỉ đánh dấu đọc thông báo của riêng họ
        $stmt = $db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
        $stmt->execute([$uid]);
    }

    // Trả về JSON và kết thúc luồng, không load giao diện
    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
    exit;
}
if ($action === 'payment' || $action === 'confirmPayment' || $action === 'webhook' || $action === 'checkPaymentStatus' || $action === 'cancelBooking') {
    $c = new PaymentController();
} elseif ($action === 'login' || $action === 'register' || $action === 'logout' || $action === 'profile' || $action === 'updateProfile' || $action === 'updatePassword') {
    $c = new AuthController();
} elseif ($action === 'submitReview') {
    $c = new ReviewController();

    // 🔥 BỔ SUNG THÊM 'getCustomerUnreadCount' và 'markAsRead' VÀO ĐÂY:
} elseif (
    $action === 'sendMessage' ||
    $action === 'getHistory' ||
    $action === 'getCustomerUnreadCount' ||
    $action === 'markAsRead' ||
    $action === 'uploadFile' ||       // 🔥 Bổ sung Upload File đính kèm
    $action === 'uploadVoice' ||      // 🔥 Bổ sung Upload Ghi âm
    $action === 'sendLocation' ||     // 🔥 Bổ sung Gửi định vị
    $action === 'triggerCleanup'      // 🔥 Bổ sung Dọn dẹp rác
) {
    // Định tuyến TẤT CẢ các hành động liên quan đến Chat về đúng ChatController
    $c = new ChatController();

} else {
    // Các action: home, tours, detail, booking, confirmBooking, myBookings, bookingDetail
    $c = new TourController();
}

// 5. Kiểm tra hàm có tồn tại không
if (!method_exists($c, $action)) {
    die("404 - Không tìm thấy hành động: " . htmlspecialchars($action) . " trong " . get_class($c));
}

// 6. Chạy hàm
$c->$action();