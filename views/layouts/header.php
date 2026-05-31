<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🔥 TRUY VẤN DATABASE LẤY THÔNG BÁO CHO CHUÔNG (ĐÃ CHỐNG LỖI 500)
$notifications = [];
$unreadCount = 0;

if (isset($_SESSION['user'])) {
    try {
        // Tự động tìm đúng đường dẫn file database
        $dbPath = __DIR__ . '/../../config/database.php';
        if (!file_exists($dbPath)) {
            $dbPath = __DIR__ . '/../config/database.php'; // Dự phòng
        }

        if (file_exists($dbPath)) {
            require_once $dbPath;
            if (class_exists('Database')) {
                $dbHeader = (new Database())->connect();

                $userRole = $_SESSION['user']['role'];
                $userId = $_SESSION['user']['user_id'];

                // Nếu là Admin/Manager thì lấy thông báo chung (user_id IS NULL)
                if ($userRole === 'admin' || $userRole === 'tour_manager') {
                    $stmtNotif = $dbHeader->prepare("SELECT * FROM notifications WHERE user_id IS NULL ORDER BY created_at DESC LIMIT 10");
                    $stmtNotif->execute();
                } else {
                    $stmtNotif = $dbHeader->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
                    $stmtNotif->execute([$userId]);
                }

                $notifications = $stmtNotif->fetchAll(PDO::FETCH_ASSOC);

                // Đếm số lượng chưa đọc
                foreach ($notifications as $n) {
                    if (isset($n['is_read']) && $n['is_read'] == 0) {
                        $unreadCount++;
                    }
                }
            }
        }
    } catch (Exception $e) {
        // 🛡️ NẾU CÓ LỖI (VD: Chưa tạo cột type, link) => Im lặng bỏ qua để KHÔNG SẬP WEB!
        error_log("Lỗi thông báo Header: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>TravelVN</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* Tùy chỉnh Navbar mang phong cách Traveloka */
        .navbar-custom {
            background-color: #66CCFF;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .navbar-brand {
            padding: 0 !important;
            margin: 0;
            display: inline-flex;
            align-items: center;
        }

        .navbar-logo {
            height: 100px;
            max-height: none !important;
            width: auto;
            object-fit: contain;
            background-color: transparent;
            margin-top: -30px;
            margin-bottom: -30px;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
            transition: transform 0.3s ease;
        }

        .navbar-logo:hover {
            transform: scale(1.05);
        }

        .nav-link-custom {
            color: #434343 !important;
            font-weight: 500;
            padding: 8px 16px !important;
            transition: color 0.3s;
        }

        .nav-link-custom:hover {
            color: #0194f3 !important;
        }

        /* Nút Đăng nhập */
        .btn-login {
            color: #0194f3;
            border: 1px solid #0194f3;
            font-weight: 600;
            border-radius: 8px;
            padding: 8px 20px;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-login:hover {
            background-color: #f0f8ff;
            color: #007bc2;
        }

        /* Nút Đăng ký */
        .btn-register {
            background-color: #0194f3;
            color: white;
            font-weight: 600;
            border-radius: 8px;
            padding: 8px 20px;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-register:hover {
            background-color: #007bc2;
            color: white;
        }

        /* Nút User Dropdown */
        .user-dropdown-toggle {
            background-color: #f4f6f9;
            color: #0194f3 !important;
            font-weight: 600;
            border-radius: 20px;
            padding: 6px 16px !important;
            border: 1px solid #e0e0e0;
        }

        nav.navbar-custom {
            position: relative;
            z-index: 1050 !important;
        }

        .dropdown-menu {
            z-index: 1060 !important;
        }

        @media (max-width: 991.98px) {
            .navbar-collapse {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background-color: white;
                padding: 1rem;
                box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
                z-index: 1055 !important;
            }

            .navbar-logo {
                height: 70px;
                margin-top: -15px;
                margin-bottom: -15px;
            }
        }

        /* CSS cho danh sách thông báo */
        .notif-dropdown-menu {
            width: 350px;
            max-height: 450px;
            overflow-y: auto;
            padding: 0;
            border-radius: 12px;
        }

        .notif-item {
            white-space: normal;
            transition: background 0.2s;
        }

        .notif-item:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light navbar-custom sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="uploads/logo.png" alt="TravelVN Logo" class="navbar-logo me-2">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">

                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="index.php?action=tours">
                            <i class="bi bi-map me-1"></i> Khám phá Tours
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="index.php?action=blogs">
                            <i class="bi bi-journal-text me-1"></i> Bài viết
                        </a>
                    </li>

                    <li class="nav-item me-3">
                        <?php
                        $role = $_SESSION['user']['role'] ?? 'customer';
                        $supportLink = 'javascript:void(0);';
                        $onClick = 'toggleChat()';

                        if ($role == 'admin') {
                            $supportLink = 'admin.php?action=chat';
                            $onClick = '';
                        } elseif ($role == 'tour_manager') {
                            $supportLink = 'manager.php?action=chat';
                            $onClick = '';
                        } elseif ($role == 'guide') {
                            $supportLink = 'guide.php?action=chat';
                            $onClick = '';
                        }
                        ?>
                        <a class="nav-link nav-link-custom" href="<?= $supportLink ?>" onclick="<?= $onClick ?>">
                            <i class="bi bi-headset me-1"></i> Hỗ trợ
                        </a>
                    </li>
                    <li class="nav-item dropdown position-relative mx-2 d-flex align-items-center">
                        <a class="nav-link nav-link-custom" href="#" id="notifDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false" title="Thông báo hệ thống">
                            <i class="bi bi-bell-fill fs-5"></i>
                            <span id="global-notif-badge"
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger <?= $unreadCount > 0 ? '' : 'd-none' ?>"
                                style="font-size: 0.65rem; border: 2px solid white;">
                                <?= $unreadCount ?>
                            </span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow notif-dropdown-menu"
                            aria-labelledby="notifDropdown">
                            <li class="p-3 border-bottom bg-light sticky-top" style="z-index: 10;">
                                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-bell"></i> Thông báo hệ thống</h6>
                            </li>

                            <?php if (empty($notifications)): ?>
                                <li class="p-4 text-center text-muted">
                                    <i class="bi bi-bell-slash fs-2 d-block mb-2 text-secondary"></i>
                                    Bạn chưa có thông báo nào
                                </li>
                            <?php else: ?>
                                <?php foreach ($notifications as $notif): ?>
                                    <li>
                                        <a href="<?= htmlspecialchars($notif['link'] ?? '#') ?>"
                                            class="dropdown-item d-flex align-items-center py-3 border-bottom notif-item <?= $notif['is_read'] == 0 ? 'bg-light' : '' ?>">
                                            <div class="me-3">
                                                <?php
                                                // Xác định icon và màu theo loại thông báo
                                                $bgClass = 'bg-primary';
                                                $icon = 'bi-info-circle';

                                                if ($notif['type'] == 'Thanh Toán') {
                                                    $bgClass = 'bg-success';
                                                    $icon = 'bi-currency-dollar';
                                                } elseif ($notif['type'] == 'Xác Nhận') {
                                                    $bgClass = 'bg-success';
                                                    $icon = 'bi-check-circle';
                                                } elseif ($notif['type'] == 'Hủy Đơn') {
                                                    $bgClass = 'bg-danger';
                                                    $icon = 'bi-x-circle';
                                                } elseif ($notif['type'] == 'Đơn Hàng') {
                                                    $bgClass = 'bg-warning text-dark';
                                                    $icon = 'bi-cart-check';
                                                } elseif ($notif['type'] == 'Tin nhắn') {
                                                    $bgClass = 'bg-info';
                                                    $icon = 'bi-chat-dots';
                                                }
                                                ?>
                                                <div class="rounded-circle <?= $bgClass ?> text-white d-flex align-items-center justify-content-center shadow-sm"
                                                    style="width: 42px; height: 42px;">
                                                    <i class="bi <?= $icon ?> fs-5"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="small text-uppercase fw-bold mb-1"
                                                    style="color: #64748b; font-size: 0.75rem;">
                                                    <?= htmlspecialchars($notif['type'] ?? 'Hệ thống') ?>
                                                </div>
                                                <div class="text-dark <?= $notif['is_read'] == 0 ? 'fw-bold' : '' ?>"
                                                    style="font-size: 0.9rem; line-height: 1.4;">
                                                    <?= htmlspecialchars($notif['message']) ?>
                                                </div>
                                                <div class="small text-muted mt-1" style="font-size: 0.8rem;">
                                                    <i
                                                        class="bi bi-clock me-1"></i><?= date('d/m/Y H:i', strtotime($notif['created_at'])) ?>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <?php if (!empty($notifications)): ?>
                                <li><a class="dropdown-item text-center py-2 text-primary fw-bold" href="#"
                                        style="font-size: 0.9rem;">Xem tất cả</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>

                    <?php if (isset($_SESSION['user'])): ?>

                        <?php if ($_SESSION['user']['role'] == 'tour_manager'): ?>
                            <li class="nav-item">
                                <a class="nav-link nav-link-custom fw-semibold text-warning"
                                    href="manager.php?action=dashboard">
                                    <i class="bi bi-gear-fill me-1"></i> Quản lý
                                </a>
                            </li>

                        <?php elseif ($_SESSION['user']['role'] == 'guide'): ?>
                            <li class="nav-item">
                                <a class="nav-link nav-link-custom fw-semibold text-success" href="guide.php?action=schedule">
                                    <i class="bi bi-briefcase-fill me-1"></i> Công việc
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin'): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-danger fw-bold" href="#" role="button"
                                    data-bs-toggle="dropdown">
                                    <i class="bi bi-shield-lock-fill me-1"></i> Admin
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                    <li>
                                        <a class="dropdown-item" href="admin.php">
                                            <i class="bi bi-people me-2"></i> Quản lý User
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="manager.php?action=dashboard">
                                            <i class="bi bi-briefcase me-2"></i> Quản lý Tour
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>

                    <?php endif; ?>

                    <?php if (isset($_SESSION['user'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle user-dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i>
                                <?= htmlspecialchars($_SESSION['user']['full_name'] ?? 'Người dùng') ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                <li><a class="dropdown-item py-2" href="index.php?action=profile"><i
                                            class="bi bi-person me-2"></i>Tài khoản của tôi</a></li>
                                <li><a class="dropdown-item py-2" href="index.php?action=myBookings"><i
                                            class="bi bi-bag-check me-2"></i>Chuyến đi của tôi </a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item py-2 text-danger" href="index.php?action=logout"><i
                                            class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item d-flex gap-2 mt-3 mt-lg-0">
                            <a class="btn-login" href="index.php?action=login">Đăng nhập</a>
                            <a class="btn-register" href="index.php?action=register">Đăng ký</a>
                        </li>
                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleChat() {
            const chatBox = document.getElementById('customerChatBox');
            if (chatBox) {
                if (chatBox.style.display === 'none' || chatBox.style.display === '') {
                    chatBox.style.display = 'flex';
                } else {
                    chatBox.style.display = 'none';
                }
            }
        }

        // TẮT SỐ ĐỎ VÀ ĐÁNH DẤU ĐÃ XEM KHI CLICK VÀO CHUÔNG
        document.getElementById('notifDropdown')?.addEventListener('click', function () {
            let badge = document.getElementById('global-notif-badge');
            // Nếu có số lượng chưa đọc đang hiển thị
            if (badge && !badge.classList.contains('d-none')) {
                badge.classList.add('d-none'); // Ẩn ngay lập tức trên UI
                badge.innerText = '0';

                // Bắn API ngầm xuống PHP để cập nhật is_read = 1 trong Database
                fetch('index.php?action=markNotifRead').catch(e => console.log(e));
            }
        });
    </script>
    <div class="main-content">