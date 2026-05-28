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

    .badge-pending { background-color: #fef3c7; color: #d97706; }
    .badge-confirmed { background-color: #d1fae5; color: #059669; }
    .badge-cancelled { background-color: #fee2e2; color: #dc2626; }
    .badge-completed { background-color: #e0f2fe; color: #0284c7; }
    .badge-refunded { background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
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

    .pay-paid { color: #059669; }
    .pay-unpaid { color: #ea580c; }

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

    .btn-detail { background-color: #f1f5f9; color: var(--text-dark); border: 1px solid var(--border-color); }
    .btn-detail:hover { background-color: #e2e8f0; color: var(--text-dark); }
    .btn-payment { background-color: var(--primary-color); color: white; box-shadow: 0 4px 10px rgba(1, 148, 243, 0.3); }
    .btn-payment:hover { background-color: var(--primary-hover); color: white; }
    .btn-cancel { background-color: white; color: #dc2626; border: 1px solid #fca5a5; }
    .btn-cancel:hover { background-color: #fef2f2; color: #b91c1c; border-color: #fca5a5; }
    .btn-review { background-color: #ffc107; color: #000; border: 1px solid #ffb300; }
    .btn-review:hover { background-color: #ffb300; color: #000; }

    /* CSS Cancel & Refund Policy Box */
    .refund-policy-box {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 20px;
    }
    
    .refund-policy-box p { margin-bottom: 5px; font-size: 0.9rem; }
    .refund-policy-box p:last-child { margin-bottom: 0; }

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

        .tour-thumbnail { margin-bottom: 15px; height: 200px; }
    }

    /* --- CSS CHO DÃY SAO ĐÁNH GIÁ --- */
    .rating-container { display: flex; flex-direction: row-reverse; justify-content: center; gap: 8px; }
    .rating-container input[type="radio"] { display: none; }
    .rating-container label { font-size: 2.8rem; color: #d1d5db; cursor: pointer; transition: 0.2s ease-in-out; line-height: 1; }
    .rating-container label:hover,
    .rating-container label:hover~label,
    .rating-container input[type="radio"]:checked~label { color: #ffc107; text-shadow: 0 0 15px rgba(255, 193, 7, 0.4); }
    .rating-container label:active { transform: scale(1.2); }
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
                    <span class="badge bg-light text-dark border"><i class="bi bi-shield-check text-success me-1"></i>Tài khoản xác thực</span>
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

            <?php if (empty($bookings)): ?>
                <div class="text-center bg-white p-5 rounded-4 shadow-sm border" style="border-radius: 20px !important;">
                    <img src="https://cdn-icons-png.flaticon.com/512/3284/3284615.png" alt="Empty" width="120" class="mb-3 opacity-50">
                    <h4 class="fw-bold text-dark">Bạn chưa có chuyến đi nào</h4>
                    <p class="text-muted">Hãy lên kế hoạch cho kỳ nghỉ tuyệt vời tiếp theo của bạn ngay hôm nay!</p>
                    <a href="index.php?action=tours" class="btn btn-primary btn-lg mt-3 px-5" style="border-radius: 50px;">Khám phá tour ngay</a>
                </div>
            <?php else: ?>

                <?php foreach ($bookings as $b): ?>
                    <?php
                    $statusClass = 'badge-pending';
                    $statusText = 'Chờ xác nhận';
                    if ($b['status'] == 'confirmed') {
                        $statusClass = 'badge-confirmed';
                        $statusText = 'Đã xác nhận';
                    } elseif ($b['status'] == 'cancelled') {
                        $statusClass = 'badge-cancelled';
                        $statusText = 'Đã hủy';
                    } elseif ($b['status'] == 'completed') {
                        $statusClass = 'badge-completed';
                        $statusText = 'Hoàn tất';
                    } elseif ($b['status'] == 'refunded') { // BỔ SUNG TRẠNG THÁI NÀY
                        $statusClass = 'badge-refunded';
                        $statusText = 'Đã hoàn tiền';
                    }

                    $payMethod = strtoupper($b['payment_method'] ?? '');
                    $payMethodText = ($payMethod == 'QR') ? 'Chuyển khoản QR' : (($payMethod == 'COD') ? 'Thu tiền mặt' : 'Chưa chọn');

                    $payStatus = $b['payment_status'] ?? 'pending';
                    if ($payStatus == 'paid') {
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
                    ?>

                    <div class="premium-card">
                        <div class="card-head">
                            <div class="order-info">
                                <span class="order-id"><i class="bi bi-receipt me-1 text-muted"></i> Mã đơn: #<?= str_pad($b['booking_id'], 6, '0', STR_PAD_LEFT) ?></span>
                                <span class="order-date d-none d-sm-inline-block"><i class="bi bi-clock"></i> Đặt lúc: <?= !empty($b['booking_date']) ? date('H:i - d/m/Y', strtotime($b['booking_date'])) : '--' ?></span>
                            </div>
                            <span class="status-badge <?= $statusClass ?>" id="badge-<?= $b['booking_id'] ?>"><?= $statusText ?></span>
                        </div>

                        <div class="card-body-custom">
                            <div class="row">
                                <div class="col-lg-3 col-md-4">
                                    <img src="<?= !empty($b['image']) ? '/uploads/' . $b['image'] : 'https://images.unsplash.com/photo-1501785888041-af3ef285b470' ?>" class="tour-thumbnail" alt="Tour">
                                </div>

                                <div class="col-lg-6 col-md-8">
                                    <h4 class="tour-title"><?= htmlspecialchars($b['tour_name']) ?></h4>
                                    <ul class="tour-detail-list">
                                        <li><i class="bi bi-calendar2-check"></i><span>Khởi hành: <strong><?= date('d/m/Y', strtotime($b['start_date'])) ?></strong> <i class="bi bi-arrow-right mx-1 text-muted"></i> <?= date('d/m/Y', strtotime($b['end_date'])) ?></span></li>
                                        <li><i class="bi bi-people"></i><span>Hành khách: <strong><?= $b['number_of_people'] ?> người</strong> <span class="text-muted">(<?= htmlspecialchars($b['customer_name']) ?>)</span></span></li>
                                        <li><i class="bi bi-wallet2"></i><span>Phương thức: <strong><?= $payMethodText ?></strong></span></li>
                                    </ul>
                                </div>

                                <div class="col-lg-3 col-12">
                                    <div class="action-column">
                                        <div class="text-lg-end text-start w-100">
                                            <?= $payHTML ?>
                                            <div class="text-muted" style="font-size: 0.85rem;">Tổng tiền</div>
                                            <div class="total-price"><?= number_format($b['total_price']) ?> <span style="font-size: 1rem;">đ</span></div>
                                        </div>

                                        <div class="action-buttons w-100 d-flex flex-column gap-2 mt-auto">
                                            <?php if ($payMethod == 'QR' && $payStatus == 'pending' && $b['status'] != 'cancelled' && $b['status'] != 'refunded'): ?>
                                                <a href="index.php?action=payment&payment_id=<?= encode_id($b['payment_id'] ?? 0) ?>&booking_id=<?= encode_id($b['booking_id']) ?>" class="btn-action btn-payment">Thanh toán ngay</a>
                                            <?php endif; ?>

                                            <a href="index.php?action=bookingDetail&booking_id=<?= encode_id($b['booking_id']) ?>" class="btn-action btn-detail">Xem chi tiết</a>

                                            <?php if ($b['status'] == 'completed'): ?>
                                                <button type="button" class="btn-action btn-review" data-bs-toggle="modal" data-bs-target="#reviewModal<?= $b['booking_id'] ?>">
                                                    <i class="bi bi-star-fill me-1"></i> Đánh giá tour
                                                </button>
                                            <?php endif; ?>

                                            <?php if ($b['status'] !== 'cancelled' && $b['status'] !== 'completed' && $b['status'] !== 'refunded' && $daysRemaining >= 7): ?>
                                                <button type="button" class="btn-action btn-cancel" data-bs-toggle="modal" data-bs-target="#cancelModal<?= $b['booking_id'] ?>">
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
                                <form action="index.php?action=requestCancel" method="POST" class="modal-content border-0 shadow" style="border-radius: 20px;">
                                    <div class="modal-header border-0 p-4 pb-0">
                                        <h5 class="modal-title fw-bold text-dark"><i class="bi bi-x-circle text-danger me-2"></i>Yêu cầu Hủy & Hoàn tiền</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        
                                        <div class="mb-4">
                                            <p class="text-muted small mb-1">Chuyến đi bạn muốn hủy:</p>
                                            <h6 class="fw-bold text-dark"><?= htmlspecialchars($b['tour_name']) ?></h6>
                                            <div class="d-flex gap-3 text-muted small mt-2">
                                                <span><i class="bi bi-upc-scan"></i> Mã: #<?= str_pad($b['booking_id'], 6, '0', STR_PAD_LEFT) ?></span>
                                                <span><i class="bi bi-calendar"></i> Khởi hành: <?= date('d/m/Y', strtotime($b['start_date'])) ?></span>
                                            </div>
                                        </div>

                                        <input type="hidden" name="booking_id" value="<?= encode_id($b['booking_id']) ?>">
                                        
                                        <div class="refund-policy-box">
                                            <h6 class="fw-bold text-primary mb-2"><i class="bi bi-info-circle me-1"></i> Chính sách Hủy & Hoàn tiền</h6>
                                            <p><i class="bi bi-check2 text-success me-1"></i> Hủy trước 30 ngày: <strong>Hoàn 100%</strong></p>
                                            <p><i class="bi bi-check2 text-success me-1"></i> Hủy từ 15 - 29 ngày: <strong>Hoàn 70%</strong> (Phí phạt 30%)</p>
                                            <p><i class="bi bi-check2 text-success me-1"></i> Hủy từ 7 - 14 ngày: <strong>Hoàn 50%</strong> (Phí phạt 50%)</p>
                                            <p><i class="bi bi-x text-danger me-1"></i> Hủy dưới 7 ngày: <strong>Không được hoàn tiền</strong></p>
                                            
                                            <div class="mt-3 p-3 bg-white border rounded">
                                                Dự kiến hoàn: <strong class="text-danger fs-5"><?= $refundPercent ?>%</strong>
                                                <span class="text-muted small">(Dựa trên việc bạn hủy trước <?= $daysRemaining ?> ngày)</span>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Lý do hủy chuyến đi <span class="text-danger">*</span></label>
                                            <select name="cancel_reason" class="form-select mb-2 shadow-sm" required style="border-radius: 10px;">
                                                <option value="" selected disabled>-- Vui lòng chọn lý do --</option>
                                                <option value="Thay đổi kế hoạch">Tôi thay đổi kế hoạch / Lịch trình</option>
                                                <option value="Sức khỏe">Lý do sức khỏe / Cá nhân</option>
                                                <option value="Thời tiết">E ngại thời tiết xấu</option>
                                                <option value="Đặt nhầm">Tôi đặt nhầm / Sai thông tin</option>
                                                <option value="Khác">Lý do khác</option>
                                            </select>
                                            <textarea name="cancel_note" class="form-control shadow-sm mt-2" rows="2" placeholder="Ghi chú thêm (Không bắt buộc)..." style="border-radius: 10px;"></textarea>
                                        </div>

                                        <?php if ($payStatus == 'paid'): ?>
                                            <div class="p-3 mb-2" style="background-color: #eef7ff; border: 1px solid #bae6fd; border-radius: 12px;">
                                                <h6 class="fw-bold text-primary mb-3"><i class="bi bi-bank me-1"></i> Thông tin nhận tiền hoàn</h6>
                                                <div class="row g-2">
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control shadow-sm" name="bank_name" placeholder="Tên Ngân hàng (VD: Vietcombank)" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="text" class="form-control shadow-sm" name="bank_account" placeholder="Số tài khoản" required>
                                                    </div>
                                                    <div class="col-12 mt-2">
                                                        <input type="text" class="form-control shadow-sm" name="account_holder" placeholder="Tên chủ tài khoản (Viết in hoa, không dấu)" required>
                                                    </div>
                                                </div>
                                                <p class="text-muted small mt-2 mb-0"><i class="bi bi-hourglass-split"></i> Tiền hoàn sẽ được xử lý và chuyển khoản về tài khoản của bạn trong vòng 3 - 5 ngày làm việc sau khi xác nhận duyệt.</p>
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-warning mb-0" style="border-radius: 10px;">
                                                <i class="bi bi-exclamation-triangle me-1"></i> Đơn hàng này chưa được thanh toán, hệ thống sẽ tiến hành hủy trực tiếp mà không cần chờ hoàn tiền.
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                    <div class="modal-footer border-0 p-4 pt-0 gap-2">
                                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 10px;">Đóng</button>
                                        <button type="submit" class="btn btn-danger px-4 shadow" style="border-radius: 10px;">Xác nhận Hủy chuyến</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($b['status'] == 'completed'): ?>
                        <div class="modal fade" id="reviewModal<?= $b['booking_id'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="index.php?action=submitReview" method="POST" class="modal-content border-0 shadow" style="border-radius: 20px;">
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
                                            <label class="form-label d-block fw-bold mb-2 fs-5 text-dark">Bạn chấm tour này mấy sao?</label>

                                            <div class="rating-container mb-2">
                                                <input type="radio" id="star5_<?= $b['booking_id'] ?>" name="rating" value="5" required />
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
                                            <textarea name="comment" class="form-control shadow-sm" rows="4" placeholder="Hãy chia sẻ điều bạn hài lòng..." required style="border-radius: 12px;"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 p-4 pt-0">
                                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow" style="border-radius: 12px;">Gửi đánh giá ngay</button>
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
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var pusher = new Pusher('e5405b1b2139fed6f8bc', { cluster: 'ap1' });
        var userChannel = pusher.subscribe('user-channel-<?= $_SESSION['user']['user_id'] ?>');
        
        userChannel.bind('status-changed', function(data) {
            // Tìm badge trạng thái của đơn hàng trên giao diện và update ngay
            var badge = document.getElementById('badge-' + data.booking_id);
            if (badge) {
                // Xóa các class màu cũ (pending, confirmed, cancelled, refunded)
                badge.classList.remove('badge-pending', 'badge-confirmed', 'badge-cancelled', 'badge-refunded');
                // Thêm class màu mới và text mới
                badge.classList.add(data.badge_class);
                badge.innerText = data.status_text;
                
                // Hiện thông báo Toast góc phải
                Swal.fire({
                    toast: true, position: 'top-end', icon: 'success',
                    title: 'Cập nhật đơn hàng',
                    text: 'Đơn hàng #' + data.booking_id + ' của bạn vừa được chuyển sang: ' + data.status_text,
                    showConfirmButton: false, timer: 6000
                });
            }
        });
    });
</script>
<?php include 'layouts/footer.php'; ?>