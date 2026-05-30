<?php
// Bật báo lỗi để nếu có trục trặc nó sẽ in ra màn hình
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🔥 1. NHÚNG CÁC CONTROLLER CẦN THIẾT
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../controllers/ManagerController.php';
require_once __DIR__ . '/../controllers/ChatController.php'; // <-- Dòng bạn bị thiếu
require_once '../config/helpers.php';

// 🔥 2. KHỞI TẠO BIẾN
$controller = new ManagerController();
$chatController = new ChatController(); // <-- Dòng bạn bị thiếu

$action = $_GET['action'] ?? 'dashboard';

switch ($action) {
    case 'dashboard':
        $controller->dashboard();
        break;

    case 'tours':
        $controller->tours();
        break;

    case 'createTour':
        $controller->createTour();
        break;

    case 'storeTour':
        $controller->storeTour();
        break;

    case 'editTour':
        $controller->editTour();
        break;

    case 'updateTour':
        $controller->updateTour();
        break;

    case 'deleteTour':
        $controller->deleteTour();
        break;

    case 'partners':
        $controller->partners();
        break;

    case 'createPartner':
        $controller->createPartner();
        break;

    case 'storePartner':
        $controller->storePartner();
        break;

    case 'editPartner':
        $controller->editPartner();
        break;

    case 'updatePartner':
        $controller->updatePartner();
        break;

    case 'deletePartner':
        $controller->deletePartner();
        break;

    case 'departures':
        $controller->departures();
        break;

    case 'createDeparture':
        $controller->createDeparture();
        break;

    case 'storeDeparture':
        $controller->storeDeparture();
        break;

    case 'editDeparture':
        $controller->editDeparture();
        break;

    case 'updateDeparture':
        $controller->updateDeparture();
        break;

    case 'deleteDeparture':
        $controller->deleteDeparture();
        break;

    case 'bookings':
        $controller->bookings();
        break;

    case 'bookingDetail':
        $controller->bookingDetail();
        break;

    case 'confirmBooking':
        $controller->confirmBooking();
        break;

    case 'confirmCash':
        $controller->confirmCash();
        break;

    case 'cancelBooking':
        $controller->cancelBooking();
        break;

    case 'refundBooking':
        $controller->refundBooking();
        break;

    case 'report':
        $controller->report();
        break;

    // ================= QUẢN LÝ BÀI VIẾT (CẨM NANG) =================
    case 'blogs':
        $controller->blogs();
        break;

    case 'blogForm':
        $controller->blogForm();
        break;

    case 'saveBlog':
        $controller->saveBlog();
        break;

    case 'deleteBlog':
        $controller->deleteBlog();
        break;

    // =======================================================
    // CÁC CASE DÀNH CHO GIAO DIỆN CHAT MANAGER
    // =======================================================
    case 'chat':
        $controller->chat();
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

    case 'uploadFile':
        $chatController->uploadFile();
        break;

    case 'uploadVoice':
        $chatController->uploadVoice();
        break;

    case 'sendLocation':
        $chatController->sendLocation();
        break;

    default:
        echo "<h2 style='text-align:center; margin-top:50px;'>Lỗi 404: Không tìm thấy action '{$action}'</h2>";
}