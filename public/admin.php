<?php
session_start();
$allowedRoles = ['admin', 'tour_manager', 'guide'];
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], $allowedRoles)) {
    die("Bạn không có quyền truy cập vào khu vực hỗ trợ khách hàng.");
}

require_once "../controllers/AdminController.php";
// 🔥 NHÚNG THÊM ChatController
require_once "../controllers/ChatController.php";
require_once '../config/helpers.php';
$controller = new AdminController();
// 🔥 KHỞI TẠO ChatController
$chatController = new ChatController();

$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'create':
        $controller->create();
        break;
    case 'edit':
        $controller->edit();
        break;
    case 'delete':
        $controller->delete();
        break;
    case 'toggle':
        $controller->toggle();
        break;
    case 'reset':
        $controller->reset();
        break;

    // =======================================================
    // CÁC CASE DÀNH CHO GIAO DIỆN CHAT ADMIN
    // =======================================================
    case 'chat':
        $controller->chat(); // Gọi hàm chat() trong AdminController để hiện giao diện
        break;

    case 'getSessions':
        $chatController->getSessions();
        break;

    case 'getHistory':
        $chatController->getHistory();
        break;

    case 'sendMessage':
        $chatController->sendMessage();
        break;

    case 'deleteSession':
        $chatController->deleteSession();
        break;
        
    case 'markAsRead':
        $chatController->markAsRead();
        break;

    case 'getTotalUnread':
        $chatController->getTotalUnread();
        break;

    // =======================================================
    // 🔥 BỔ SUNG CÁC CASE XỬ LÝ MEDIA CHO ADMIN TẠI ĐÂY
    // =======================================================
    case 'uploadFile':
        $chatController->uploadFile();
        break;

    case 'uploadVoice':
        $chatController->uploadVoice();
        break;

    case 'sendLocation':
        $chatController->sendLocation();
        break;

    case 'triggerCleanup':
        $chatController->triggerCleanup();
        break;

    default:
        $controller->index();

}