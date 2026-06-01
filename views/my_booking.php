<?php include 'layouts/header.php'; ?>

<style>
    :root {
        --primary-color: #0194f3;
        --primary-hover: #007bc2;
        --accent-color: #f96d00;
        --text-dark: #1a202c;
        --text-muted: #64748b;
        --bg-light: #f8fafc;
        --border-color: #e2e8f0;
    }

    body {
        background-color: #f1f5f9;
    }

    /* --- SIDEBAR MENU CÁ NHÂN --- */
    .user-sidebar-info {
        background: white;
        border-radius: 20px;
        padding: 30px 20px;
        text-align: center;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }

    .avatar-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), #00d2ff);
        color: white;
        font-size: 2rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        box-shadow: 0 4px 10px rgba(1, 148, 243, 0.3);
    }

    .user-sidebar-menu {
        background: white;
        border-radius: 20px;
        padding: 15px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .menu-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 20px;
        border-radius: 12px;
        color: var(--text-dark);
        font-weight: 600;
        text-decoration: none;
        transition: 0.2s;
        margin-bottom: 5px;
    }

    .menu-link i {
        font-size: 1.2rem;
        color: var(--text-muted);
        transition: 0.2s;
    }

    .menu-link:hover {
        background-color: var(--bg-light);
        color: var(--primary-color);
    }

    .menu-link:hover i {
        color: var(--primary-color);
    }

    .menu-link.active {
        background-color: #eef7ff;
        color: var(--primary-color);
    }

    .menu-link.active i {
        color: var(--primary-color);
    }

    .menu-link.text-danger:hover {
        background-color: #fef2f2;
        color: #dc2626;
    }

    .menu-link.text-danger:hover i {
        color: #dc2626;
    }

    /* --- PREMIUM BOOKING CARD --- */
    .premium-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        margin-bottom: 24px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .premium-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        border-color: #cbd5e1;
    }

    .card-head {
        background-color: var(--bg-light);
        padding: 16px 24px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .order-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .order-id {
        font-weight: 700;
        color: var(--text-dark);
    }

    .order-date {
        color: var(--text-muted);
        font-size: 0.85rem;
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.85rem;
    }

    .badge-pending {
        background-color: #fef3c7;
        color: #d97706;
    }

    .badge-confirmed {
        background-color: #d1fae5;
        color: #059669;
    }

    .badge-cancelled {
        background-color: #fee2e2;
        color: #dc2626;
    }

    .badge-completed {
        background-color: #e0f2fe;
        color: #0284c7;
    }

    .badge-refunded {
        background-color: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }

    .card-body-custom {
        padding: 24px;
    }

    .tour-thumbnail {
        width: 100%;
        height: 160px;
        border-radius: 12px;
        object-fit: cover;
    }

    .tour-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--text-dark);
        margin-bottom: 12px;
    }

    .tour-detail-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .tour-detail-list li {
        color: #475569;
        font-size: 0.95rem;
        margin-bottom: 8px;
        display: flex;
        gap: 10px;
    }

    .tour-detail-list li i {
        color: var(--primary-color);
        font-size: 1.1rem;
    }

    .action-column {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-end;
        height: 100%;
        padding-left: 20px;
        border-left: 1px dashed var(--border-color);
    }

    .pay-status {
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .pay-paid {
        color: #059669;
    }

    .pay-unpaid {
        color: #ea580c;
    }

    .total-price {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--accent-color);
        margin-bottom: 16px;
        line-height: 1;
    }

    .btn-action {
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: 0.2s;
        text-align: center;
        width: 100%;
        text-decoration: none;
        display: inline-block;
        border: none;
        cursor: pointer;
    }

    .btn-detail {
        background-color: #f1f5f9;
        color: var(--text-dark);
        border: 1px solid var(--border-color);
    }

    .btn-detail:hover {
        background-color: #e2e8f0;
        color: var(--text-dark);
    }

    .btn-payment {
        background-color: var(--primary-color);
        color: white;
        box-shadow: 0 4px 10px rgba(1, 148, 243, 0.3);
    }

    .btn-payment:hover {
        background-color: var(--primary-hover);
        color: white;
    }

    .btn-cancel {
        background-color: white;
        color: #dc2626;
        border: 1px solid #fca5a5;
    }

    .btn-cancel:hover {
        background-color: #fef2f2;
        color: #b91c1c;
        border-color: #fca5a5;
    }

    .btn-review {
        background-color: #ffc107;
        color: #000;
        border: 1px solid #ffb300;
    }

    .btn-review:hover {
        background-color: #ffb300;
        color: #000;
    }

    /* CSS Cancel & Refund Policy Box */
    .refund-policy-box {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .refund-policy-box p {
        margin-bottom: 5px;
        font-size: 0.9rem;
    }

    .refund-policy-box p:last-child {
        margin-bottom: 0;
    }

    .badge-refund-processing {
        background-color: #fff7ed;
        color: #ea580c;
        border: 1px solid #fed7aa;
    }

    .pay-processing {
        color: #ea580c;
    }

    .pay-refunded {
        color: #059669;
    }

    .refund-note-box {
        margin-top: 14px;
        padding: 12px 14px;
        background: #fff7ed;
        border: 1px solid #fed7aa;
        border-radius: 12px;
        color: #9a3412;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .refunded-note-box {
        margin-top: 14px;
        padding: 12px 14px;
        background: #ecfdf5;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        color: #047857;
        font-size: 0.9rem;
        font-weight: 600;
    }

    /* RESPONSIVE */
    @media (max-width: 991px) {
        .action-column {
            align-items: flex-start;
            border-left: none;
            border-top: 1px dashed var(--border-color);
            padding-left: 0;
            padding-top: 20px;
            margin-top: 15px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            width: 100%;
            flex-direction: column;
        }

        .tour-thumbnail {
            margin-bottom: 15px;
            height: 200px;
        }
    }

    /* --- CSS CHO DÃY SAO ĐÁNH GIÁ --- */
    .rating-container {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        gap: 8px;
    }

    .rating-container input[type="radio"] {
        display: none;
    }

    .rating-container label {
        font-size: 2.8rem;
        color: #d1d5db;
        cursor: pointer;
        transition: 0.2s ease-in-out;
        line-height: 1;
    }

    .rating-container label:hover,
    .rating-container label:hover~label,
    .rating-container input[type="radio"]:checked~label {
        color: #ffc107;
        text-shadow: 0 0 15px rgba(255, 193, 7, 0.4);
    }

    .rating-container label:active {
        transform: scale(1.2);
    }

    /* ================= FILTER & SORT PANEL ================= */
    .booking-page-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 20px;
    }

    .booking-title-box h3 {
        margin-bottom: 4px;
    }

    .booking-result-count {
        color: var(--text-muted);
        font-size: 0.95rem;
    }

    .booking-filter-panel {
        background: #ffffff;
        border: 1px solid var(--border-color);
        border-radius: 22px;
        padding: 20px;
        margin-bottom: 24px;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.04);
    }

    .filter-label {
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 8px;
        letter-spacing: 0.04em;
    }

    .filter-control {
        height: 46px;
        border-radius: 14px;
        border: 1px solid var(--border-color);
        font-weight: 600;
        color: var(--text-dark);
    }

    .filter-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(1, 148, 243, 0.12);
    }

    .search-box {
        position: relative;
    }

    .search-box i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
    }

    .search-box input {
        padding-left: 42px;
    }

    .btn-reset-filter {
        height: 46px;
        border-radius: 14px;
        border: 1px solid var(--border-color);
        background: #f8fafc;
        color: var(--text-dark);
        font-weight: 700;
        width: 100%;
    }

    .btn-reset-filter:hover {
        background: #e2e8f0;
    }

    .quick-filter-group {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 16px;
    }

    .quick-filter {
        border: 1px solid var(--border-color);
        background: #f8fafc;
        color: #475569;
        border-radius: 999px;
        padding: 8px 14px;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        transition: 0.2s;
    }

    .quick-filter:hover,
    .quick-filter.active {
        background: #eef7ff;
        color: var(--primary-color);
        border-color: #bae6fd;
    }

    .booking-no-result {
        display: none;
        background: white;
        border: 1px dashed #cbd5e1;
        border-radius: 20px;
        padding: 45px 20px;
        text-align: center;
        color: var(--text-muted);
    }

    @media (max-width: 768px) {
        .booking-page-head {
            flex-direction: column;
        }
    }
</style>

<div class="container mt-5 mb-5">
    <div class="row g-4">

        <div class="col-lg-3">
            <div class="sticky-top" style="top: 100px; z-index: 1;">
                <div class="user-sidebar-info">
                    <div class="avatar-circle">
                        <?= mb_strtoupper(mb_substr($user_name, 0, 1, 'UTF-8')) ?>
                    </div>
                    <h5 class="fw-bold mb-1 text-dark"><?= htmlspecialchars($user_name) ?></h5>
                    <p class="text-muted small mb-3"><?= htmlspecialchars($user_email) ?></p>
                    <span class="badge bg-light text-dark border"><i
                            class="bi bi-shield-check text-success me-1"></i>Tài khoản xác thực</span>
                </div>

                <div class="user-sidebar-menu">
                    <a href="index.php?action=profile" class="menu-link">
                        <i class="bi bi-person-circle"></i> Tài khoản của tôi
                    </a>
                    <a href="index.php?action=myBookings" class="menu-link active">
                        <i class="bi bi-briefcase"></i> Chuyến đi của tôi
                        <span class="badge bg-primary rounded-pill ms-auto"><?= $totalBookings ?></span>
                    </a>
                    <hr class="my-2" style="border-color: var(--border-color);">
                    <a href="index.php?action=logout" class="menu-link text-danger">
                        <i class="bi bi-box-arrow-right"></i> Đăng xuất
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <h3 class="fw-bold text-dark mb-4">Danh sách chuyến đi</h3>
            <?php if (!empty($bookings)): ?>
                <div class="booking-filter-panel">
                    <div class="row g-3 align-items-end">
                        <div class="col-lg-4 col-md-6">
                            <div class="filter-label">Tìm kiếm</div>
                            <div class="search-box">
                                <i class="bi bi-search"></i>
                                <input type="text" id="bookingSearchInput" class="form-control filter-control"
                                    placeholder="Tìm mã đơn, tên tour,..">
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <div class="filter-label">Trạng thái</div>
                            <select id="bookingStatusFilter" class="form-select filter-control">
                                <option value="all">Tất cả</option>
                                <option value="pending">Chờ xác nhận</option>
                                <option value="confirmed">Đã xác nhận</option>
                                <option value="completed">Hoàn tất</option>
                                <option value="refund_processing">Đang xử lý hoàn tiền</option>
                                <option value="refunded">Đã hoàn tiền</option>
                                <option value="cancelled">Đã hủy</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <div class="filter-label">Thanh toán</div>
                            <select id="bookingPaymentFilter" class="form-select filter-control">
                                <option value="all">Tất cả</option>
                                <option value="paid">Đã thanh toán</option>
                                <option value="unpaid">Chưa thanh toán</option>
                                <option value="refunded">Đã hoàn tiền</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <div class="filter-label">Sắp xếp</div>
                            <select id="bookingSortSelect" class="form-select filter-control">
                                <option value="newest">Mới nhất</option>
                                <option value="oldest">Cũ nhất</option>
                                <option value="start_soon">Sắp khởi hành</option>
                                <option value="price_desc">Giá cao nhất</option>
                                <option value="price_asc">Giá thấp nhất</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-6">
                            <button type="button" class="btn-reset-filter" id="bookingResetFilter">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Đặt lại
                            </button>
                        </div>
                    </div>

                    <div class="quick-filter-group">
                        <button type="button" class="quick-filter active" data-status="all">
                            Tất cả
                        </button>
                        <button type="button" class="quick-filter" data-status="confirmed">
                            Đã xác nhận
                        </button>
                        <button type="button" class="quick-filter" data-status="refund_processing">
                            Đang hoàn tiền
                        </button>
                        <button type="button" class="quick-filter" data-status="refunded">
                            Đã hoàn tiền
                        </button>
                        <button type="button" class="quick-filter" data-status="completed">
                            Hoàn tất
                        </button>
                    </div>
                </div>

                <div id="bookingNoResult" class="booking-no-result">
                    <i class="bi bi-search fs-1 d-block mb-3 text-primary"></i>
                    <h5 class="fw-bold text-dark">Không tìm thấy đơn phù hợp</h5>
                    <p class="mb-0">Bạn hãy thử đổi từ khóa, trạng thái hoặc cách sắp xếp.</p>
                </div>
            <?php endif; ?>
            <?php if (empty($bookings)): ?>
                <div class="text-center bg-white p-5 rounded-4 shadow-sm border" style="border-radius: 20px !important;">
                    <img src="https://cdn-icons-png.flaticon.com/512/3284/3284615.png" alt="Empty" width="120"
                        class="mb-3 opacity-50">
                    <h4 class="fw-bold text-dark">Bạn chưa có chuyến đi nào</h4>
                    <p class="text-muted">Hãy lên kế hoạch cho kỳ nghỉ tuyệt vời tiếp theo của bạn ngay hôm nay!</p>
                    <a href="index.php?action=tours" class="btn btn-primary btn-lg mt-3 px-5"
                        style="border-radius: 50px;">Khám phá tour ngay</a>
                </div>
            <?php else: ?>

                <?php foreach ($bookings as $b): ?>
                    <?php
                    $statusClass = 'badge-pending';
                    $statusText = 'Chờ xác nhận';
                    $statusIcon = 'bi-hourglass-split';

                    $payStatus = $b['payment_status'] ?? 'pending';
                    $isPaid = $payStatus === 'paid';

                    if ($b['status'] == 'confirmed') {
                        $statusClass = 'badge-confirmed';
                        $statusText = 'Đã xác nhận';
                        $statusIcon = 'bi-check-circle-fill';

                    } elseif ($b['status'] == 'cancelled' && $isPaid) {
                        $statusClass = 'badge-refund-processing';
                        $statusText = 'Đang xử lý hoàn tiền';
                        $statusIcon = 'bi-arrow-repeat';

                    } elseif ($b['status'] == 'cancelled') {
                        $statusClass = 'badge-cancelled';
                        $statusText = 'Đã hủy';
                        $statusIcon = 'bi-x-circle-fill';

                    } elseif ($b['status'] == 'completed') {
                        $statusClass = 'badge-completed';
                        $statusText = 'Hoàn tất';
                        $statusIcon = 'bi-flag-fill';

                    } elseif ($b['status'] == 'refunded') {
                        $statusClass = 'badge-refunded';
                        $statusText = 'Đã hoàn tiền';
                        $statusIcon = 'bi-cash-coin';
                    }

                    $payMethod = strtoupper($b['payment_method'] ?? '');
                    $payMethodText = ($payMethod == 'QR') ? 'Chuyển khoản QR' : (($payMethod == 'COD') ? 'Thu tiền mặt' : 'Chưa chọn');

                    if ($b['status'] == 'refunded') {
                        $payHTML = '<div class="pay-status pay-refunded"><i class="bi bi-cash-coin"></i> Đã hoàn tiền</div>';
                    } elseif ($b['status'] == 'cancelled' && $isPaid) {
                        $payHTML = '<div class="pay-status pay-processing"><i class="bi bi-arrow-repeat"></i> Đang xử lý hoàn tiền</div>';
                    } elseif ($isPaid) {
                        $payHTML = '<div class="pay-status pay-paid"><i class="bi bi-shield-check"></i> Đã thanh toán</div>';
                    } else {
                        $payHTML = '<div class="pay-status pay-unpaid"><i class="bi bi-exclamation-circle"></i> Chưa thanh toán</div>';
                    }

                    // Logic tính hoàn tiền
                    $daysRemaining = floor((strtotime($b['start_date']) - time()) / (60 * 60 * 24));
                    $refundPercent = ($daysRemaining >= 30) ? 100 : (($daysRemaining >= 15) ? 70 : (($daysRemaining >= 7) ? 50 : 0));
                    if ($daysRemaining >= 30) {
                        $refundPercent = 100;
                    } elseif ($daysRemaining >= 15) {
                        $refundPercent = 70; // Phạt 30% -> Hoàn 70%
                    } elseif ($daysRemaining >= 7) {
                        $refundPercent = 50; // Phạt 50% -> Hoàn 50%
                    } else {
                        $refundPercent = 0;  // Dưới 7 ngày phạt 100% -> Không hoàn
                    }
                    $filterStatus = $b['status'];

                    if ($b['status'] == 'cancelled' && $isPaid) {
                        $filterStatus = 'refund_processing';
                    }

                    $filterPayment = 'unpaid';

                    if ($b['status'] == 'refunded') {
                        $filterPayment = 'refunded';
                    } elseif ($isPaid) {
                        $filterPayment = 'paid';
                    }

                    $bookingSearchText = mb_strtolower(
                        $b['booking_id'] . ' ' .
                        ($b['tour_name'] ?? '') . ' ' .
                        ($b['customer_name'] ?? '') . ' ' .
                        ($b['phone'] ?? '') . ' ' .
                        $statusText . ' ' .
                        $payMethodText,
                        'UTF-8'
                    );

                    $bookingDateTs = !empty($b['booking_date']) ? strtotime($b['booking_date']) : 0;
                    $startDateTs = !empty($b['start_date']) ? strtotime($b['start_date']) : 0;
                    $totalPriceNum = (float) ($b['total_price'] ?? 0);
                    ?>

                    <div class="premium-card booking-card" data-status="<?= htmlspecialchars($filterStatus) ?>"
                        data-payment="<?= htmlspecialchars($filterPayment) ?>" data-method="<?= htmlspecialchars($payMethod) ?>"
                        data-price="<?= $totalPriceNum ?>" data-booking-date="<?= $bookingDateTs ?>"
                        data-start-date="<?= $startDateTs ?>" data-search="<?= htmlspecialchars($bookingSearchText) ?>">
                        <div class="card-head">
                            <div class="order-info">
                                <span class="order-id"><i class="bi bi-receipt me-1 text-muted"></i> Mã đơn:
                                    #<?= str_pad($b['booking_id'], 6, '0', STR_PAD_LEFT) ?></span>
                                <span class="order-date d-none d-sm-inline-block"><i class="bi bi-clock"></i> Đặt lúc:
                                    <?= !empty($b['booking_date']) ? date('H:i - d/m/Y', strtotime($b['booking_date'])) : '--' ?></span>
                            </div>
                            <span class="status-badge <?= $statusClass ?>" id="badge-<?= $b['booking_id'] ?>">
                                <i class="bi <?= $statusIcon ?> me-1"></i>
                                <?= $statusText ?>
                            </span>
                        </div>

                        <div class="card-body-custom">
                            <div class="row">
                                <div class="col-lg-3 col-md-4">
                                    <img src="<?= !empty($b['image']) ? '/uploads/' . $b['image'] : 'https://images.unsplash.com/photo-1501785888041-af3ef285b470' ?>"
                                        class="tour-thumbnail" alt="Tour">
                                </div>

                                <div class="col-lg-6 col-md-8">
                                    <h4 class="tour-title"><?= htmlspecialchars($b['tour_name']) ?></h4>
                                    <ul class="tour-detail-list">
                                        <li><i class="bi bi-calendar2-check"></i><span>Khởi hành:
                                                <strong><?= date('d/m/Y', strtotime($b['start_date'])) ?></strong> <i
                                                    class="bi bi-arrow-right mx-1 text-muted"></i>
                                                <?= date('d/m/Y', strtotime($b['end_date'])) ?></span></li>
                                        <li><i class="bi bi-people"></i><span>Hành khách: <strong><?= $b['number_of_people'] ?>
                                                    người</strong> <span
                                                    class="text-muted">(<?= htmlspecialchars($b['customer_name']) ?>)</span></span>
                                        </li>
                                        <li><i class="bi bi-wallet2"></i><span>Phương thức:
                                                <strong><?= $payMethodText ?></strong></span></li>
                                    </ul>
                                    <?php if ($b['status'] == 'cancelled' && $isPaid): ?>
                                        <div class="refund-note-box" id="refund-note-<?= $b['booking_id'] ?>">
                                            <i class="bi bi-hourglass-split me-1"></i>
                                            Đơn đã hủy. TravelVN đang xử lý hoàn tiền cho bạn trong 3 - 5 ngày làm việc.
                                        </div>
                                    <?php elseif ($b['status'] == 'refunded'): ?>
                                        <div class="refunded-note-box" id="refund-note-<?= $b['booking_id'] ?>">
                                            <i class="bi bi-check-circle-fill me-1"></i>
                                            TravelVN đã hoàn tiền thành công cho đơn hàng này.
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-lg-3 col-12">
                                    <div class="action-column">
                                        <div class="text-lg-end text-start w-100">
                                            <?= $payHTML ?>
                                            <div class="text-muted" style="font-size: 0.85rem;">Tổng tiền</div>
                                            <div class="total-price"><?= number_format($b['total_price']) ?> <span
                                                    style="font-size: 1rem;">đ</span></div>
                                        </div>

                                        <div class="action-buttons w-100 d-flex flex-column gap-2 mt-auto">
                                            <?php if ($payMethod == 'QR' && $payStatus == 'pending' && $b['status'] != 'cancelled' && $b['status'] != 'refunded'): ?>
                                                <a href="index.php?action=payment&payment_id=<?= encode_id($b['payment_id'] ?? 0) ?>&booking_id=<?= encode_id($b['booking_id']) ?>"
                                                    class="btn-action btn-payment">Thanh toán ngay</a>
                                            <?php endif; ?>

                                            <a href="index.php?action=bookingDetail&booking_id=<?= encode_id($b['booking_id']) ?>"
                                                class="btn-action btn-detail">Xem chi tiết</a>

                                            <?php if ($b['status'] == 'completed'): ?>
                                                <button type="button" class="btn-action btn-review" data-bs-toggle="modal"
                                                    data-bs-target="#reviewModal<?= $b['booking_id'] ?>">
                                                    <i class="bi bi-star-fill me-1"></i> Đánh giá tour
                                                </button>
                                            <?php endif; ?>

                                            <?php if ($b['status'] !== 'cancelled' && $b['status'] !== 'completed' && $b['status'] !== 'refunded' && $daysRemaining >= 7): ?>
                                                <button type="button" class="btn-action btn-cancel" data-bs-toggle="modal"
                                                    data-bs-target="#cancelModal<?= $b['booking_id'] ?>">
                                                    Hủy & Hoàn tiền
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($b['status'] !== 'cancelled' && $b['status'] !== 'completed' && $daysRemaining >= 7): ?>
                        <div class="modal fade" id="cancelModal<?= $b['booking_id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <form action="index.php?action=requestCancel" method="POST" class="modal-content border-0 shadow"
                                    style="border-radius: 20px;">
                                    <div class="modal-header border-0 p-4 pb-0">
                                        <h5 class="modal-title fw-bold text-dark"><i class="bi bi-x-circle text-danger me-2"></i>Yêu
                                            cầu Hủy & Hoàn tiền</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4">

                                        <div class="mb-4">
                                            <p class="text-muted small mb-1">Chuyến đi bạn muốn hủy:</p>
                                            <h6 class="fw-bold text-dark"><?= htmlspecialchars($b['tour_name']) ?></h6>
                                            <div class="d-flex gap-3 text-muted small mt-2">
                                                <span><i class="bi bi-upc-scan"></i> Mã:
                                                    #<?= str_pad($b['booking_id'], 6, '0', STR_PAD_LEFT) ?></span>
                                                <span><i class="bi bi-calendar"></i> Khởi hành:
                                                    <?= date('d/m/Y', strtotime($b['start_date'])) ?></span>
                                            </div>
                                        </div>

                                        <input type="hidden" name="booking_id" value="<?= encode_id($b['booking_id']) ?>">

                                        <div class="refund-policy-box">
                                            <h6 class="fw-bold text-primary mb-2"><i class="bi bi-info-circle me-1"></i> Chính sách
                                                Hủy & Hoàn tiền</h6>
                                            <p><i class="bi bi-check2 text-success me-1"></i> Hủy trước 30 ngày: <strong>Hoàn
                                                    100%</strong></p>
                                            <p><i class="bi bi-check2 text-success me-1"></i> Hủy từ 15 - 29 ngày: <strong>Hoàn
                                                    70%</strong> (Phí phạt 30%)</p>
                                            <p><i class="bi bi-check2 text-success me-1"></i> Hủy từ 7 - 14 ngày: <strong>Hoàn
                                                    50%</strong> (Phí phạt 50%)</p>
                                            <p><i class="bi bi-x text-danger me-1"></i> Hủy dưới 7 ngày: <strong>Không được hoàn
                                                    tiền</strong></p>

                                            <div class="mt-3 p-3 bg-white border rounded">
                                                Dự kiến hoàn: <strong class="text-danger fs-5"><?= $refundPercent ?>%</strong>
                                                <span class="text-muted small">(Dựa trên việc bạn hủy trước <?= $daysRemaining ?>
                                                    ngày)</span>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Lý do hủy chuyến đi <span
                                                    class="text-danger">*</span></label>
                                            <select name="cancel_reason" class="form-select mb-2 shadow-sm" required
                                                style="border-radius: 10px;">
                                                <option value="" selected disabled>-- Vui lòng chọn lý do --</option>
                                                <option value="Thay đổi kế hoạch">Tôi thay đổi kế hoạch / Lịch trình</option>
                                                <option value="Sức khỏe">Lý do sức khỏe / Cá nhân</option>
                                                <option value="Thời tiết">E ngại thời tiết xấu</option>
                                                <option value="Đặt nhầm">Tôi đặt nhầm / Sai thông tin</option>
                                                <option value="Khác">Lý do khác</option>
                                            </select>
                                            <textarea name="cancel_note" class="form-control shadow-sm mt-2" rows="2"
                                                placeholder="Ghi chú thêm (Không bắt buộc)..."
                                                style="border-radius: 10px;"></textarea>
                                        </div>

                                        <?php if ($payStatus == 'paid'): ?>
                                            <div class="p-3 mb-2"
                                                style="background-color: #eef7ff; border: 1px solid #bae6fd; border-radius: 12px;">
                                                <h6 class="fw-bold text-primary mb-3"><i class="bi bi-bank me-1"></i> Thông tin nhận
                                                    tiền hoàn</h6>
                                                <div class="row g-2">
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control shadow-sm" name="bank_name"
                                                            placeholder="Tên Ngân hàng (VD: Vietcombank)" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control shadow-sm" name="bank_account"
                                                            placeholder="Số tài khoản" required>
                                                    </div>
                                                    <div class="col-12 mt-2">
                                                        <input type="text" class="form-control shadow-sm" name="account_holder"
                                                            placeholder="Tên chủ tài khoản (Viết in hoa, không dấu)" required>
                                                    </div>
                                                </div>
                                                <p class="text-muted small mt-2 mb-0"><i class="bi bi-hourglass-split"></i> Tiền hoàn sẽ
                                                    được xử lý và chuyển khoản về tài khoản của bạn trong vòng 3 - 5 ngày làm việc sau
                                                    khi xác nhận duyệt.</p>
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-warning mb-0" style="border-radius: 10px;">
                                                <i class="bi bi-exclamation-triangle me-1"></i> Đơn hàng này chưa được thanh toán, hệ
                                                thống sẽ tiến hành hủy trực tiếp mà không cần chờ hoàn tiền.
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                    <div class="modal-footer border-0 p-4 pt-0 gap-2">
                                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal"
                                            style="border-radius: 10px;">Đóng</button>
                                        <button type="submit" class="btn btn-danger px-4 shadow" style="border-radius: 10px;">Xác
                                            nhận Hủy chuyến</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($b['status'] == 'completed'): ?>
                        <div class="modal fade" id="reviewModal<?= $b['booking_id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="index.php?action=submitReview" method="POST" class="modal-content border-0 shadow"
                                    style="border-radius: 20px;">
                                    <div class="modal-header border-0 p-4 pb-0">
                                        <h5 class="modal-title fw-bold text-dark">Đánh giá trải nghiệm</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="mb-3">
                                            <p class="text-muted small mb-1">Tour đã đi:</p>
                                            <h6 class="fw-bold text-primary"><?= htmlspecialchars($b['tour_name']) ?></h6>
                                        </div>
                                        <input type="hidden" name="tour_id" value="<?= $b['tour_id'] ?? $b['id_tour'] ?? 0 ?>">
                                        <input type="hidden" name="booking_id" value="<?= $b['booking_id'] ?>">
                                        <div class="mb-4 text-center">
                                            <label class="form-label d-block fw-bold mb-2 fs-5 text-dark">Bạn chấm tour này mấy
                                                sao?</label>

                                            <div class="rating-container mb-2">
                                                <input type="radio" id="star5_<?= $b['booking_id'] ?>" name="rating" value="5"
                                                    required />
                                                <label for="star5_<?= $b['booking_id'] ?>" title="5 sao - Tuyệt vời">★</label>
                                                <input type="radio" id="star4_<?= $b['booking_id'] ?>" name="rating" value="4" />
                                                <label for="star4_<?= $b['booking_id'] ?>" title="4 sao - Rất tốt">★</label>
                                                <input type="radio" id="star3_<?= $b['booking_id'] ?>" name="rating" value="3" />
                                                <label for="star3_<?= $b['booking_id'] ?>" title="3 sao - Bình thường">★</label>
                                                <input type="radio" id="star2_<?= $b['booking_id'] ?>" name="rating" value="2" />
                                                <label for="star2_<?= $b['booking_id'] ?>" title="2 sao - Kém">★</label>
                                                <input type="radio" id="star1_<?= $b['booking_id'] ?>" name="rating" value="1" />
                                                <label for="star1_<?= $b['booking_id'] ?>" title="1 sao - Rất tệ">★</label>
                                            </div>

                                            <div class="text-muted small fw-medium">(Vui lòng chạm để chọn số sao)</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Cảm nhận của bạn</label>
                                            <textarea name="comment" class="form-control shadow-sm" rows="4"
                                                placeholder="Hãy chia sẻ điều bạn hài lòng..." required
                                                style="border-radius: 12px;"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 p-4 pt-0">
                                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow"
                                            style="border-radius: 12px;">Gửi đánh giá ngay</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['review_success'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            title: 'Thành công!',
            text: '<?= $_SESSION['review_success'] ?>',
            icon: 'success',
            confirmButtonText: 'Đóng',
            confirmButtonColor: '#0194f3'
        });
    </script>
    <?php unset($_SESSION['review_success']); ?>
<?php endif; ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {

        // --- GIỮ NGUYÊN CÁC HÀM FILTER & SẮP XẾP CỦA BẠN TỪ ĐÂY ---
        const searchInput = document.getElementById("bookingSearchInput");
        const statusFilter = document.getElementById("bookingStatusFilter");
        const paymentFilter = document.getElementById("bookingPaymentFilter");
        const sortSelect = document.getElementById("bookingSortSelect");
        const resetBtn = document.getElementById("bookingResetFilter");
        const visibleCount = document.getElementById("bookingVisibleCount");
        const noResultBox = document.getElementById("bookingNoResult");

        function getBookingCards() { return Array.from(document.querySelectorAll(".booking-card")); }
        function normalizeText(text) { return (text || "").toString().toLowerCase().trim(); }

        function filterAndSortBookings() {
            // (Phần logic filter của bạn giữ nguyên, không thay đổi)
            const keyword = normalizeText(searchInput?.value || "");
            const selectedStatus = statusFilter?.value || "all";
            const selectedPayment = paymentFilter?.value || "all";
            const selectedSort = sortSelect?.value || "newest";

            const cards = getBookingCards();
            let visibleCards = [];

            cards.forEach(card => {
                const cardSearch = normalizeText(card.dataset.search || "");
                const cardStatus = card.dataset.status || "";
                const cardPayment = card.dataset.payment || "";

                const matchKeyword = keyword === "" || cardSearch.includes(keyword);
                const matchStatus = selectedStatus === "all" || cardStatus === selectedStatus;
                const matchPayment = selectedPayment === "all" || cardPayment === selectedPayment;

                const isVisible = matchKeyword && matchStatus && matchPayment;
                card.style.display = isVisible ? "" : "none";

                if (isVisible) visibleCards.push(card);
            });

            // Logic sort giữ nguyên...
            const parent = cards[0]?.parentElement;
            if (parent) visibleCards.forEach(card => parent.appendChild(card));
            if (noResultBox) noResultBox.style.display = visibleCards.length === 0 ? "block" : "none";
        }

        if (searchInput) searchInput.addEventListener("input", filterAndSortBookings);
        if (statusFilter) statusFilter.addEventListener("change", filterAndSortBookings);
        if (paymentFilter) paymentFilter.addEventListener("change", filterAndSortBookings);
        if (sortSelect) sortSelect.addEventListener("change", filterAndSortBookings);
        if (resetBtn) {
            resetBtn.addEventListener("click", function () {
                if (searchInput) searchInput.value = "";
                if (statusFilter) statusFilter.value = "all";
                if (paymentFilter) paymentFilter.value = "all";
                if (sortSelect) sortSelect.value = "newest";
                document.querySelectorAll(".quick-filter").forEach(btn => btn.classList.remove("active"));
                document.querySelector('.quick-filter[data-status="all"]')?.classList.add("active");
                filterAndSortBookings();
            });
        }

        document.querySelectorAll(".quick-filter").forEach(btn => {
            btn.addEventListener("click", function () {
                const status = this.dataset.status || "all";
                if (statusFilter) statusFilter.value = status;
                document.querySelectorAll(".quick-filter").forEach(item => item.classList.remove("active"));
                this.classList.add("active");
                filterAndSortBookings();
            });
        });

        filterAndSortBookings();
        // --- KẾT THÚC PHẦN FILTER ---

        // 🔥 LOGIC CẬP NHẬT GIAO DIỆN REAL-TIME (TỐI ƯU HÓA DOM)
        let checkSocketInterval = setInterval(() => {
            // Chờ cho đến khi file footer.php khởi tạo xong socket
            if (typeof window.globalSocket !== 'undefined') {
                clearInterval(checkSocketInterval);

                window.globalSocket.on("system_notification", function (data) {
                    console.log("👉 Dữ liệu Socket nhận được:", data);

                    // 1. TÌM ID ĐƠN HÀNG (Dùng Regex bóc tách số 57 từ chuỗi "#000057")
                    let bId = data.booking_id;
                    if (!bId && data.message) {
                        let match = data.message.match(/#0*(\d+)/);
                        if (match) bId = match[1]; // Sẽ lấy ra được đúng số 57
                    }

                    // 2. NẾU CÓ ID, TÌM THẺ HTML VÀ ĐỔI MÀU GIAO DIỆN
                    if (bId) {
                        let badge = document.getElementById("badge-" + bId);

                        if (badge) {
                            let typeName = data.type || '';
                            let bookingCard = badge.closest('.booking-card');

                            // ==========================================
                            // KỊCH BẢN 1: DUYỆT ĐƠN / THANH TOÁN
                            // ==========================================
                            if (typeName.includes('Xác Nhận') || typeName.includes('Thanh Toán')) {
                                // Đổi màu Badge Vàng sang Xanh
                                badge.className = "status-badge badge-confirmed";
                                badge.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i> Đã xác nhận';

                                // Đổi dòng chữ "Chưa thanh toán" sang "Đã thanh toán"
                                let payStatusDiv = bookingCard.querySelector('.pay-status');
                                if (payStatusDiv) {
                                    payStatusDiv.className = "pay-status pay-paid";
                                    payStatusDiv.innerHTML = '<i class="bi bi-shield-check me-1"></i> Đã thanh toán';
                                }

                                // Ẩn luôn nút "Thanh toán ngay" và "Hủy tour" đi
                                let payBtn = bookingCard.querySelector('.btn-payment');
                                if (payBtn) payBtn.remove();

                                let cancelBtn = bookingCard.querySelector('.btn-cancel');
                                if (cancelBtn) cancelBtn.remove();
                            }

                            // ==========================================
                            // KỊCH BẢN 2: HỦY ĐƠN HÀNG
                            // ==========================================
                            else if (typeName.includes('Hủy Đơn')) {
                                // Đổi màu Badge Vàng sang Đỏ
                                badge.className = "status-badge badge-cancelled";
                                badge.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i> Đã hủy';

                                // Nếu đã thanh toán, đổi trạng thái sang "Đang hoàn tiền"
                                let payStatusDiv = bookingCard.querySelector('.pay-status');
                                if (payStatusDiv && payStatusDiv.classList.contains('pay-paid')) {
                                    payStatusDiv.className = "pay-status pay-processing";
                                    payStatusDiv.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i> Đang xử lý hoàn tiền';
                                }

                                // Ẩn các nút hành động
                                let payBtn = bookingCard.querySelector('.btn-payment');
                                if (payBtn) payBtn.remove();

                                let cancelBtn = bookingCard.querySelector('.btn-cancel');
                                if (cancelBtn) cancelBtn.remove();
                            }
                        }
                    }
                });
            }
        }, 500);

    });
</script>

<?php include 'layouts/footer.php'; ?>