<?php 
    $activeMenu = 'bookings';
    include __DIR__ . '/../layouts/header.php'; 

    // --- LOGIC TÍNH TOÁN HOÀN TIỀN ---
    $daysRemaining = floor((strtotime($detail['start_date']) - time()) / (60 * 60 * 24));
    $refundPercent = 0;
    
    if ($daysRemaining >= 30) {
        $refundPercent = 100;
    } elseif ($daysRemaining >= 15) {
        $refundPercent = 70; 
    } elseif ($daysRemaining >= 7) {
        $refundPercent = 50; 
    } else {
        $refundPercent = 0; 
    }

    $refundAmount = $detail['total_price'] * ($refundPercent / 100);
    // ===== TÍNH TOÁN CHI TIẾT HOÀN TIỀN =====

$totalPaid = (float)$detail['total_price'];

$penaltyPercent = 100 - $refundPercent;

// tiền bị trừ
$penaltyAmount = $totalPaid * ($penaltyPercent / 100);

// tiền thực trả
$refundAmount = $totalPaid - $penaltyAmount;

// format sẵn
$totalPaidFormat = number_format($totalPaid);
$penaltyAmountFormat = number_format($penaltyAmount);
$refundAmountFormat = number_format($refundAmount);

// mô tả chính sách
$refundPolicyText='';

if($daysRemaining >=30){
    $refundPolicyText='Hoàn 100%';
}
elseif($daysRemaining >=15){
    $refundPolicyText='Phạt 30% giá tour';
}
elseif($daysRemaining >=7){
    $refundPolicyText='Phạt 50% giá tour';
}
else{
    $refundPolicyText='Không hoàn tiền';
}
    // --- LOGIC XỬ LÝ TRẠNG THÁI CHUYÊN NGHIỆP ---
    $status = $detail['status'];
    $payStatus = $detail['payment_status'] ?? 'pending';
    
    $badgeClass = 'bg-secondary';
    $statusText = 'Không xác định';
    $statusIcon = 'bi-question-circle';

    if ($status === 'pending') {
        $badgeClass = 'bg-warning text-dark'; $statusText = 'Chờ duyệt'; $statusIcon = 'bi-hourglass-split';
    } elseif ($status === 'confirmed') {
        $badgeClass = 'bg-success'; $statusText = 'Đã xác nhận'; $statusIcon = 'bi-check-circle-fill';
    } elseif ($status === 'completed') {
        $badgeClass = 'bg-primary'; $statusText = 'Hoàn tất'; $statusIcon = 'bi-flag-fill';
    } elseif ($status === 'refunded') {
        $badgeClass = 'bg-dark'; $statusText = 'Đã hoàn tiền'; $statusIcon = 'bi-arrow-counterclockwise';
    } elseif ($status === 'cancelled') {
        if ($payStatus === 'paid') {
            $badgeClass = 'bg-danger shadow'; $statusText = 'Đã Hủy (CẦN HOÀN TIỀN)'; $statusIcon = 'bi-exclamation-triangle-fill';
        } else {
            $badgeClass = 'bg-danger opacity-75'; $statusText = 'Đã Hủy'; $statusIcon = 'bi-x-circle-fill';
        }
    }
?>

<style>
    :root {
        --admin-primary: #0194f3;
        --admin-bg: #f8fafc; 
        --admin-surface: #ffffff;
        --admin-border: #e2e8f0;
        --admin-text-main: #0f172a; 
        --admin-text-muted: #64748b; 
    }

    body { background-color: var(--admin-bg); font-family: 'Inter', sans-serif; }
    .admin-container { max-width: 1400px; margin: 40px auto; padding: 0 15px; }
    
    /* Card Styles */
    .admin-card { 
        background: var(--admin-surface); 
        border-radius: 16px; 
        padding: 24px; 
        border: 1px solid var(--admin-border); 
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02); 
    }
    
    .card-header-title { font-size: 1.1rem; font-weight: 800; color: var(--admin-text-main); margin-bottom: 20px; display: flex; align-items: center; gap: 8px; border-bottom: 1px solid var(--admin-border); padding-bottom: 15px;}
    
    .info-list { list-style: none; padding: 0; margin: 0; }
    .info-list li { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px dashed var(--admin-border); }
    .info-list li:last-child { border-bottom: none; padding-bottom: 0; }
    .info-label { color: var(--admin-text-muted); font-size: 0.9rem; font-weight: 500;}
    .info-value { color: var(--admin-text-main); font-weight: 700; text-align: right;}
    
    .tour-img { width: 60px; height: 60px; object-fit: cover; border-radius: 10px; }

    /* Timeline Đơn hàng */
    .order-timeline { display: flex; justify-content: space-between; align-items: center; position: relative; margin: 30px 0; z-index: 1;}
    .order-timeline::before {
        content: ''; position: absolute; top: 15px; left: 0; right: 0; height: 3px; background: #e2e8f0; z-index: -1;
    }
    .timeline-step { text-align: center; background: var(--admin-surface); padding: 0 10px; position: relative; }
    .timeline-icon { 
        width: 34px; height: 34px; border-radius: 50%; background: #e2e8f0; color: #94a3b8; 
        display: flex; align-items: center; justify-content: center; margin: 0 auto 8px; font-size: 1.1rem; border: 4px solid var(--admin-surface);
        transition: 0.3s;
    }
    .timeline-label { font-size: 0.8rem; font-weight: 700; color: var(--admin-text-muted); }
    
    /* Trạng thái Active của Timeline */
    .timeline-step.active .timeline-icon { background: var(--admin-primary); color: white; box-shadow: 0 0 0 3px rgba(1, 148, 243, 0.2); }
    .timeline-step.active .timeline-label { color: var(--admin-primary); }
    
    .timeline-step.success .timeline-icon { background: #10b981; color: white; }
    .timeline-step.success .timeline-label { color: #10b981; }

    .timeline-step.danger .timeline-icon { background: #ef4444; color: white; }
    .timeline-step.danger .timeline-label { color: #ef4444; }

    /* Action Bar */
    .action-bar {
        background: white; padding: 20px 24px; border-radius: 16px; margin-top: 25px;
        border: 1px solid var(--admin-border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;
        box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.02);
    }
    .btn-custom { padding: 10px 24px; border-radius: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; }
    
    /* Refund Box */
    .refund-calc-box { background-color: #f8fafc; border: 1px solid var(--admin-border); border-radius: 12px; padding: 20px; }
    .refund-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 0.95rem; }
    .refund-row.total { border-top: 1px dashed #cbd5e1; padding-top: 15px; margin-top: 5px; font-size: 1.2rem; font-weight: 800; color: #ef4444; }
</style>

<div class="admin-container">
    <div class="row g-4">
        
        <?php include __DIR__ . '/../layouts/sidebar_manager.php'; ?>

        <div class="col-lg-9">
            
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div>
                    <h2 class="fw-bold mb-2 text-dark" style="letter-spacing: -0.5px;">Đơn hàng #<?= str_pad($detail['booking_id'], 6, '0', STR_PAD_LEFT) ?></h2>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small">Cập nhật lần cuối: <?= date('d/m/Y H:i', strtotime($detail['booking_date'])) ?></span>
                        <span class="badge <?= $badgeClass ?> px-3 py-2 rounded-pill" style="font-size: 0.8rem;"><i class="bi <?= $statusIcon ?> me-1"></i><?= $statusText ?></span>
                    </div>
                </div>
                <a href="manager.php?action=bookings" class="btn btn-white border bg-white shadow-sm fw-bold px-4 rounded-3"><i class="bi bi-arrow-left"></i> Trở về danh sách</a>
            </div>

            <?php if($status !== 'cancelled' && $status !== 'refunded'): ?>
            <div class="admin-card mb-4 py-3 px-md-5">
                <div class="order-timeline">
                    <div class="timeline-step success">
                        <div class="timeline-icon"><i class="bi bi-cart-check"></i></div>
                        <div class="timeline-label">Đặt Tour</div>
                    </div>
                    
                    <div class="timeline-step <?= ($payStatus == 'paid') ? 'success' : 'active' ?>">
                        <div class="timeline-icon"><i class="bi bi-credit-card"></i></div>
                        <div class="timeline-label"><?= ($payStatus == 'paid') ? 'Đã thanh toán' : 'Chờ thanh toán' ?></div>
                    </div>

                    <div class="timeline-step <?= ($status == 'confirmed' || $status == 'completed') ? 'success' : (($payStatus == 'paid' && $status == 'pending') ? 'active' : '') ?>">
                        <div class="timeline-icon"><i class="bi bi-file-earmark-check"></i></div>
                        <div class="timeline-label">Xác nhận</div>
                    </div>

                    <div class="timeline-step <?= ($status == 'completed') ? 'success' : '' ?>">
                        <div class="timeline-icon"><i class="bi bi-flag"></i></div>
                        <div class="timeline-label">Hoàn tất</div>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="alert alert-danger shadow-sm border-0 mb-4 d-flex align-items-center rounded-4 p-4">
                <i class="bi bi-exclamation-octagon-fill fs-1 me-3"></i>
                <div>
                    <h5 class="fw-bold mb-1">Đơn hàng này đã bị hủy!</h5>
                    <p class="mb-0 text-dark opacity-75">Tiến trình phục vụ đã bị dừng lại. Bạn có thể xem lịch sử giao dịch bên dưới.</p>
                </div>
            </div>
            <?php endif; ?>

            <div class="row g-4">
                
                <div class="col-md-6">
                    <div class="admin-card h-100">
                        <div class="card-header-title"><i class="bi bi-person-vcard text-primary fs-4"></i> Thông tin Hành khách</div>
                        <ul class="info-list">
                            <li><span class="info-label">Người đặt</span><span class="info-value"><?= htmlspecialchars($detail['customer_name']) ?></span></li>
                            <li><span class="info-label">Số điện thoại</span><span class="info-value text-primary"><i class="bi bi-telephone-fill me-1"></i><?= htmlspecialchars($detail['phone'] ?? '--') ?></span></li>
                            <li><span class="info-label">Email</span><span class="info-value"><?= htmlspecialchars($detail['email'] ?? '--') ?></span></li>
                            <li><span class="info-label">Số lượng đi</span><span class="info-value"><span class="badge bg-light text-dark border px-2"><?= $detail['number_of_people'] ?> Người</span></span></li>
                        </ul>

                        <div class="mt-4 pt-3 border-top">
                            <span class="info-label d-block mb-2"><i class="bi bi-journal-text me-1"></i> Lịch sử & Ghi chú</span>
                            <div class="bg-light p-3 rounded-3" style="font-size: 0.9rem; border: 1px dashed #cbd5e1; min-height: 80px;">
                                <?php if(!empty($detail['note'])): ?>
                                    <?= nl2br(htmlspecialchars($detail['note'])) ?>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">Không có ghi chú nào từ khách hàng.</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="admin-card h-100">
                        <div class="card-header-title"><i class="bi bi-box-seam text-primary fs-4"></i> Dịch vụ & Thanh toán</div>
                        
                        <div class="d-flex gap-3 mb-4 p-2 rounded-3 border bg-light">
                            <img src="<?= !empty($detail['image']) ? '/uploads/'.$detail['image'] : 'https://images.unsplash.com/photo-1501785888041-af3ef285b470' ?>" class="tour-img" alt="Tour">
                            <div class="overflow-hidden d-flex flex-column justify-content-center">
                                <h6 class="fw-bold text-truncate mb-1 text-primary"><?= htmlspecialchars($detail['tour_name']) ?></h6>
                                <p class="mb-0 text-muted small"><i class="bi bi-calendar2-check"></i> Khởi hành: <strong class="text-dark"><?= date('d/m/Y', strtotime($detail['start_date'])) ?></strong></p>
                            </div>
                        </div>

                        <ul class="info-list mb-4">
                            <li><span class="info-label">Phương thức thanh toán</span><span class="info-value"><span class="badge bg-light text-dark border"><?= strtoupper($detail['payment_method'] ?? 'CASH') ?></span></span></li>
                            <li>
                                <span class="info-label">Tình trạng quỹ</span>
                                <span class="info-value">
                                    <?php if ($payStatus === 'paid'): ?>
                                        <span class="text-success"><i class="bi bi-check-circle-fill"></i> Đã thu tiền</span>
                                    <?php else: ?>
                                        <span class="text-warning text-dark"><i class="bi bi-hourglass-split"></i> Chưa thu tiền</span>
                                    <?php endif; ?>
                                </span>
                            </li>
                        </ul>

                        <div class="bg-dark text-white p-4 rounded-4 mt-auto position-relative overflow-hidden">
                            <i class="bi bi-wallet2 position-absolute" style="font-size: 5rem; right: -10px; bottom: -20px; opacity: 0.1;"></i>
                            <div class="position-relative z-index-1">
                                <div class="text-white-50 small text-uppercase fw-bold mb-1">Tổng cộng đơn hàng</div>
                                <div class="text-warning fw-bold" style="font-size: 2rem; line-height: 1;"><?= number_format($detail['total_price']) ?> <span class="fs-5">VND</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-bar">
                <div class="text-muted small fw-medium">
                    <i class="bi bi-shield-lock text-success me-1"></i> Mọi thao tác đều được lưu lại trên hệ thống.
                </div>
                
                <div class="d-flex gap-2">
                    <?php if ($status != 'cancelled' && $status != 'refunded' && $status != 'completed'): ?>
                        <a href="manager.php?action=cancelBooking&id=<?= $detail['booking_id'] ?>" 
                           class="btn btn-light border text-danger btn-custom" 
                           onclick="return confirm('Bạn có chắc muốn hủy đơn đặt này?')">
                            <i class="bi bi-x-octagon"></i> Hủy đơn
                        </a>
                    <?php endif; ?>

                    <?php if ($status == 'cancelled' && $payStatus == 'paid'): ?>
                        <button type="button" class="btn btn-warning btn-custom shadow-sm" data-bs-toggle="modal" data-bs-target="#refundModal">
                            <i class="bi bi-arrow-counterclockwise"></i> Xử lý Hoàn tiền ngay
                        </button>
                    <?php endif; ?>

                    <?php if ($status == 'pending'): ?>
                        <a href="manager.php?action=confirmBooking&id=<?= $detail['booking_id'] ?>" 
                           class="btn btn-success btn-custom shadow" 
                           onclick="return confirm('Xác nhận duyệt đơn hàng này?')">
                            <i class="bi bi-check2-circle"></i> Duyệt đơn
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
        </div>
    </div>
</div>

<div class="modal fade" id="refundModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">
            <div class="modal-header bg-light border-0 p-4 pb-3">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-calculator text-warning me-2"></i>Bảng Kê Hoàn Tiền Khách Hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 pt-2">
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3 text-muted">Chi tiết chi phí</h6>
                        <div class="refund-calc-box">

    <div class="refund-row">
        <span class="text-muted">
            Tổng tiền khách đã thanh toán
        </span>

        <strong>
            <?= $totalPaidFormat ?> đ
        </strong>
    </div>

    <div class="refund-row">
        <span class="text-muted">
            Còn lại trước ngày khởi hành
        </span>

        <strong>
            <?= $daysRemaining ?> ngày
        </strong>
    </div>

    <div class="refund-row">
        <span class="text-muted">
            Chính sách áp dụng
        </span>

        <strong class="text-primary">
            <?= $refundPolicyText ?>
        </strong>
    </div>

    <div class="refund-row">
        <span class="text-muted">
            Tỷ lệ phạt
        </span>

        <strong class="text-danger">
            <?= $penaltyPercent ?>%
        </strong>
    </div>

    <div class="refund-row">
        <span class="text-muted">
            Tiền bị giữ lại
        </span>

        <strong class="text-danger">
            <?= $penaltyAmountFormat ?> đ
        </strong>
    </div>

    <div class="refund-row total">
        <span>
            THỰC HOÀN CHO KHÁCH
        </span>

        <span class="text-success">
            <?= $refundAmountFormat ?> đ
        </span>
    </div>

</div>
                    </div>

                    <div class="col-md-6">
                        <h6 class="fw-bold mb-3 text-muted">Thông tin tài khoản nhận</h6>
                        <div class="bg-white border rounded-3 p-3 h-100" style="border-style: dashed !important;">
                            <p class="small text-muted mb-2"><i class="bi bi-info-circle"></i> Hệ thống tự động trích xuất STK từ Form yêu cầu hủy của khách:</p>
                            
                            <div class="alert alert-secondary p-2 mb-0" style="font-family: monospace; font-size: 0.9rem;">
                                <?php
                                    $noteLines = explode("\n", $detail['note']);
                                    $bankInfo = "Khách chưa nhập thông tin ngân hàng lúc hủy.";
                                    foreach ($noteLines as $line) {
                                        if (strpos($line, 'THÔNG TIN NHẬN HOÀN TIỀN') !== false) {
                                            $bankInfo = str_replace('THÔNG TIN NHẬN HOÀN TIỀN:', '', $line);
                                            break;
                                        }
                                    }
                                    echo htmlspecialchars(trim($bankInfo));
                                ?>
                            </div>

                            <p class="text-danger small mt-3 fw-bold mb-0">
                                <i class="bi bi-exclamation-triangle-fill"></i> Vui lòng copy thông tin trên để chuyển khoản cho khách trước khi bấm xác nhận!
                            </p>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer border-0 p-4 pt-0 justify-content-between">
                <span class="text-muted small">ID: #<?= $detail['booking_id'] ?></span>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                    <form method="POST" action="manager.php?action=refundBooking&id=<?= $detail['booking_id'] ?>" class="m-0">
                        <button type="submit" class="btn btn-warning fw-bold px-4">
                            <i class="bi bi-check-circle"></i> Xác nhận đã Chuyển tiền
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>