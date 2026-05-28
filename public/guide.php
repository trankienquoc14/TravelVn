<?php
require_once __DIR__ . '/../controllers/GuideController.php';
// 1. Nhúng ChatController vào để xử lý API tin nhắn
require_once __DIR__ . '/../controllers/ChatController.php';
require_once '../config/helpers.php';
$controller = new GuideController();
$chatController = new ChatController(); // Khởi tạo controller chat

$action = $_GET['action'] ?? 'schedule';

switch ($action) {
    case 'schedule':
        $controller->schedule();
        break;

    case 'viewDeparture':
        $controller->viewDeparture();
        break;

    case 'checkin':
        $controller->checkin();
        break;

    case 'updateStatus':
        $controller->updateStatus();
        break;

    // =======================================================
    // 🔥 CÁC CASE DÀNH CHO PHẦN CHAT CỦA HDV
    // =======================================================
    case 'chat':
        // Gọi hàm chat() trong GuideController để load views/guide/chat_guide.php
        $controller->chat();
        break;

    case 'getSessions':
        // API lấy danh sách khách hàng trong đoàn của HDV này
        $chatController->getSessions();
        break;

    case 'getHistory':
        // API lấy lịch sử chat
        $chatController->getHistory();
        break;

    case 'sendMessage':
        // API để HDV gửi tin nhắn trả lời
        $chatController->sendMessage();
        break;

    case 'markAsRead':
        $chatController->markAsRead();
        break;

    case 'getTotalUnread':
        $chatController->getTotalUnread();
        break;

    // =======================================================
    // 🔥 BỔ SUNG CÁC CASE MỚI CHO GIAO DIỆN CHAT CHUYÊN NGHIỆP
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

    case 'deleteSession':
        $chatController->deleteSession();
        break;

    case 'triggerCleanup':
        $chatController->triggerCleanup();
        break;
    // =======================================================

    default:
        echo "404 Not Found";
}