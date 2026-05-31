<?php
$data = $booking;

$status = $data['status'] ?? 'pending';
$payStatus = $data['payment_status'] ?? 'pending';
$payMethod = strtoupper($data['payment_method'] ?? 'CASH');

$isPaid = ($payStatus === 'paid');
$isRefundProcessing = ($status === 'cancelled' && $isPaid);
$isRefunded = ($status === 'refunded');

// 1. BADGE TRẠNG THÁI CHÍNH
$badgeClass = 'status-neutral';
$statusText = 'Không xác định';
$statusIcon = 'bi-question-circle';

if ($status === 'pending') {
    $badgeClass = 'status-warning';
    $statusText = 'Chờ xác nhận';
    $statusIcon = 'bi-hourglass-split';

} elseif ($status === 'confirmed') {
    $badgeClass = 'status-success';
    $statusText = 'Đã xác nhận';
    $statusIcon = 'bi-check-circle-fill';

} elseif ($status === 'completed' || $status === 'checked_in') {
    $badgeClass = 'status-primary';
    $statusText = 'Hoàn tất';
    $statusIcon = 'bi-flag-fill';

} elseif ($isRefunded) {
    $badgeClass = 'status-refunded';
    $statusText = 'Đã hoàn tiền';
    $statusIcon = 'bi-cash-coin';

} elseif ($isRefundProcessing) {
    $badgeClass = 'status-refund-processing';
    $statusText = 'Đang xử lý hoàn tiền';
    $statusIcon = 'bi-arrow-repeat';

} elseif ($status === 'cancelled') {
    $badgeClass = 'status-danger';
    $statusText = 'Đã hủy';
    $statusIcon = 'bi-x-circle-fill';
}

// 2. TIMELINE
$step1 = 'completed';
$step2 = ($isPaid) ? 'completed' : 'active';
$step3 = '';
if (in_array($status, ['confirmed', 'completed', 'checked_in'])) {
    $step3 = 'completed';
} elseif ($status === 'pending') {
    $step3 = 'active';
}
$step4 = ($status === 'completed' || $status === 'checked_in') ? 'completed' : '';
?>

<?php include 'layouts/header.php'; ?>

<style>
    :root {
        --primary-color: #0194f3;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --text-main: #0f172a;
        --text-muted: #64748b;
        --bg-body: #f8fafc;
        --border-color: #e2e8f0;
    }

    body { background-color: var(--bg-body); font-family: 'Inter', sans-serif; }
    
    .ticket-container {
    max-width: 1380px;
    margin: 32px auto 60px;
    padding: 0 18px;
}

    /* Main Card */
    .detail-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        border: 1px solid var(--border-color);
        padding: 35px;
        position: relative;
        overflow: hidden;
    }

    /* Timeline */
    .timeline-container { position: relative; margin: 30px 0 40px; padding: 0 20px; }
    .timeline-line { position: absolute; top: 20px; left: 40px; right: 40px; height: 3px; background: var(--border-color); z-index: 1; }
    .timeline-steps { display: flex; justify-content: space-between; position: relative; z-index: 2; }
    .t-step { display: flex; flex-direction: column; align-items: center; background: #ffffff; padding: 0 10px; width: 100px; }
    .t-icon { 
        width: 42px; height: 42px; border-radius: 50%; background: #f1f5f9; color: #94a3b8; 
        display: flex; align-items: center; justify-content: center; font-size: 1.2rem; border: 4px solid #ffffff;
        transition: 0.3s; margin-bottom: 8px;
    }
    .t-label { font-size: 0.85rem; font-weight: 700; color: var(--text-muted); text-align: center; line-height: 1.2;}
    
    /* Trạng thái Timeline */
    .t-step.active .t-icon { background: #eff6ff; color: var(--primary-color); box-shadow: 0 0 0 4px #eff6ff; }
    .t-step.active .t-label { color: var(--primary-color); }
    .t-step.completed .t-icon { background: var(--success-color); color: white; }
    .t-step.completed .t-label { color: var(--success-color); }

    /* Info Grid */
    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 30px; }
    .info-section h5 { font-size: 1.1rem; font-weight: 800; color: var(--text-main); margin-bottom: 20px; display: flex; align-items: center; gap: 8px; border-bottom: 2px solid var(--bg-body); padding-bottom: 10px; }
    .info-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px dashed var(--border-color); }
    .info-row:last-child { border-bottom: none; }
    .info-lbl { color: var(--text-muted); font-size: 0.9rem; font-weight: 500; }
    .info-val { color: var(--text-main); font-weight: 700; text-align: right; max-width: 60%; }

    /* Payment Box */
    .payment-summary {
        background: linear-gradient(145deg, #f8fafc, #f1f5f9);
        border-radius: 16px; padding: 25px; display: flex; justify-content: space-between; align-items: center;
        border: 1px solid var(--border-color); margin-top: 30px;
    }
    .total-price { font-size: 2.2rem; font-weight: 800; color: var(--warning-color); line-height: 1; }

    /* E-Ticket Sidebar */
    .boarding-pass {
        background: #ffffff; border-radius: 20px; border: 1px solid var(--border-color); box-shadow: 0 10px 30px rgba(0,0,0,0.03); overflow: hidden; margin-bottom: 24px; position: relative;
    }
    .bp-header { height: 160px; position: relative; overflow: hidden; }
    .bp-header img { width: 100%; height: 100%; object-fit: cover; }
    .bp-overlay { position: absolute; inset: 0; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); display: flex; align-items: flex-end; padding: 20px; color: white; }
    
    /* Hiệu ứng cắt vé */
    .bp-divider { position: relative; height: 30px; background: white; border-bottom: 2px dashed var(--border-color); }
    .bp-divider::before, .bp-divider::after { content: ""; position: absolute; width: 30px; height: 30px; background: var(--bg-body); border-radius: 50%; top: -15px; border: 1px solid var(--border-color); }
    .bp-divider::before { left: -16px; border-right: none; }
    .bp-divider::after { right: -16px; border-left: none; }

    .bp-body { padding: 30px 20px; text-align: center; background: white; }
    .qr-box { background: white; padding: 15px; border-radius: 16px; border: 1px solid var(--border-color); display: inline-block; margin-bottom: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); }
    .qr-box img { width: 180px; height: 180px; }
.ticket-container {
    max-width: 1380px;
    margin: 32px auto 60px;
    padding: 0 18px;
}
.status-alert {
    display: flex;
    gap: 16px;
    align-items: flex-start;
    padding: 22px 24px;
    border-radius: 20px;
    border: 1px solid;
}

.status-alert-icon {
    width: 54px;
    height: 54px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.status-alert-processing {
    background: #fff7ed;
    border-color: #fdba74;
    color: #9a3412;
}
.status-alert-processing .status-alert-icon {
    background: #ffedd5;
    color: #ea580c;
}

.status-alert-success {
    background: #ecfdf5;
    border-color: #86efac;
    color: #065f46;
}
.status-alert-success .status-alert-icon {
    background: #d1fae5;
    color: #059669;
}

.status-alert-danger {
    background: #fef2f2;
    border-color: #fca5a5;
    color: #991b1b;
}
.status-alert-danger .status-alert-icon {
    background: #fee2e2;
    color: #dc2626;
}
.detail-card {
    background: #ffffff;
    border-radius: 24px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
    border: 1px solid var(--border-color);
    padding: 36px;
    position: relative;
    overflow: hidden;
}

.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 999px;
    font-size: 0.95rem;
    font-weight: 800;
    border: 1px solid transparent;
    white-space: nowrap;
}

.status-warning {
    background: #fef3c7;
    color: #b45309;
    border-color: #fcd34d;
}

.status-success {
    background: #dcfce7;
    color: #15803d;
    border-color: #86efac;
}

.status-primary {
    background: #dbeafe;
    color: #1d4ed8;
    border-color: #93c5fd;
}

.status-danger {
    background: #fee2e2;
    color: #dc2626;
    border-color: #fca5a5;
}

.status-refund-processing {
    background: #fff7ed;
    color: #ea580c;
    border-color: #fdba74;
}

.status-refunded {
    background: #ecfdf5;
    color: #047857;
    border-color: #86efac;
}

.status-neutral {
    background: #f1f5f9;
    color: #475569;
    border-color: #cbd5e1;
}
    @media (max-width: 768px) { .info-grid { grid-template-columns: 1fr; gap: 0; } }
</style>

<div class="ticket-container">
    
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="index.php?action=myBookings" class="text-decoration-none text-muted">Đơn của tôi</a></li>
            <li class="breadcrumb-item active fw-bold text-primary" aria-current="page">Vé #<?= str_pad($data['booking_id'], 6, '0', STR_PAD_LEFT) ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        
        <div class="col-lg-8">
            <div class="detail-card">
                
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-2">
                    <div>
                        <h3 class="fw-bold mb-1 text-dark">Chi tiết hành trình</h3>
                        <div class="text-muted small">
                            Mã vé: <strong class="text-dark">#<?= str_pad($data['booking_id'], 6, '0', STR_PAD_LEFT) ?></strong> • 
                            Ngày đặt: <?= date('d/m/Y H:i', strtotime($data['booking_date'])) ?>
                        </div>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <span class="status-pill <?= $badgeClass ?>">
    <i class="bi <?= $statusIcon ?>"></i>
    <?= $statusText ?>
</span>
                    </div>
                </div>

                <hr class="my-4" style="border-color: var(--border-color); opacity: 1;">

                <?php if ($isRefundProcessing): ?>
    <div class="status-alert status-alert-processing my-4">
        <div class="status-alert-icon">
            <i class="bi bi-arrow-repeat"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-1">Đơn hàng đang được xử lý hoàn tiền</h5>
            <p class="mb-0">
                TravelVN đã ghi nhận yêu cầu hủy. Bộ phận kế toán đang xử lý hoàn trả
                <strong><?= number_format($data['total_price']) ?>đ</strong>
                cho bạn trong vòng <strong>3 - 5 ngày làm việc</strong>.
            </p>
        </div>
    </div>

<?php elseif ($isRefunded): ?>
    <div class="status-alert status-alert-success my-4">
        <div class="status-alert-icon">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-1">Đã hoàn tiền thành công</h5>
            <p class="mb-0">
                TravelVN đã hoàn tất việc hoàn trả
                <strong><?= number_format($data['total_price']) ?>đ</strong>
                vào tài khoản đăng ký nhận hoàn tiền của bạn.
            </p>
        </div>
    </div>

<?php elseif ($status === 'cancelled'): ?>
    <div class="status-alert status-alert-danger my-4">
        <div class="status-alert-icon">
            <i class="bi bi-x-octagon-fill"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-1">Đơn hàng đã bị hủy</h5>
            <p class="mb-0">
                Đơn đặt chỗ đã được hủy thành công. Hẹn gặp lại bạn trong những chuyến đi tiếp theo!
            </p>
        </div>
    </div>

<?php else: ?>
                    <div class="timeline-container">
                        <div class="timeline-line"></div>
                        <div class="timeline-steps">
                            <div class="t-step <?= $step1 ?>">
                                <div class="t-icon"><i class="bi bi-cart-check"></i></div>
                                <div class="t-label">Đặt Tour</div>
                            </div>
                            <div class="t-step <?= $step2 ?>">
                                <div class="t-icon"><i class="bi bi-credit-card"></i></div>
                                <div class="t-label"><?= ($payStatus == 'paid') ? 'Đã Thanh toán' : 'Thanh toán' ?></div>
                            </div>
                            <div class="t-step <?= $step3 ?>">
                                <div class="t-icon"><i class="bi bi-person-check"></i></div>
                                <div class="t-label">Xác nhận</div>
                            </div>
                            <div class="t-step <?= $step4 ?>">
                                <div class="t-icon"><i class="bi bi-emoji-smile"></i></div>
                                <div class="t-label">Khởi hành</div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($payMethod == 'QR' && $payStatus !== 'paid' && $status !== 'cancelled' && $status !== 'refunded'): ?>
                    <div class="alert alert-warning d-flex flex-column flex-md-row align-items-md-center justify-content-between my-4 border-0 shadow-sm" style="background-color: #fffbeb; border-radius: 16px; padding: 20px;">
                        <div class="d-flex align-items-center mb-3 mb-md-0">
                            <i class="bi bi-qr-code-scan fs-1 text-warning me-3"></i>
                            <div>
                                <strong class="fs-5 text-dark">Thanh toán vé điện tử</strong><br>
                                <span class="text-muted small">Vui lòng quét mã QR chuyển khoản để hệ thống tự động xuất vé.</span>
                            </div>
                        </div>
                        <a href="index.php?action=payment&payment_id=<?= encode_id($data['payment_id'] ?? 0) ?>&booking_id=<?= encode_id($data['booking_id'] ?? 0) ?>"
                            class="btn btn-warning px-4 py-2 fw-bold text-white shadow-sm rounded-3" style="background-color: #f59e0b; border: none; white-space: nowrap;">
                            <i class="bi bi-wallet2 me-1"></i> Thanh toán ngay
                        </a>
                    </div>
                <?php endif; ?>

                <div class="info-grid">
                    <div class="info-section">
                        <h5><i class="bi bi-person-vcard text-primary"></i> Khách hàng</h5>
                        <div class="info-row"><span class="info-lbl">Họ và tên</span><span class="info-val"><?= htmlspecialchars($data['customer_name']) ?></span></div>
                        <div class="info-row"><span class="info-lbl">Số điện thoại</span><span class="info-val text-primary"><?= htmlspecialchars($data['phone'] ?? '--') ?></span></div>
                        <div class="info-row"><span class="info-lbl">Email liên hệ</span><span class="info-val text-truncate" title="<?= htmlspecialchars($data['email'] ?? '--') ?>"><?= htmlspecialchars($data['email'] ?? '--') ?></span></div>
                        <div class="info-row"><span class="info-lbl">Số lượng khách</span><span class="info-val"><span class="badge bg-light text-dark border px-2 py-1"><?= $data['number_of_people'] ?> Người</span></span></div>
                    </div>

                    <div class="info-section">
                        <h5><i class="bi bi-map text-primary"></i> Chuyến đi</h5>
                        <div class="info-row"><span class="info-lbl">Tên Tour</span><span class="info-val text-truncate text-primary" title="<?= htmlspecialchars($data['tour_name']) ?>"><?= htmlspecialchars($data['tour_name']) ?></span></div>
                        <div class="info-row"><span class="info-lbl">Khởi hành</span><span class="info-val"><?= date('d/m/Y', strtotime($data['start_date'])) ?></span></div>
                        <div class="info-row"><span class="info-lbl">Kết thúc</span><span class="info-val"><?= date('d/m/Y', strtotime($data['end_date'])) ?></span></div>
                        <div class="info-row border-0 pb-0"><span class="info-lbl">Điểm đón</span></div>
                        <div class="info-val text-start text-dark fw-bold mt-1"><i class="bi bi-geo-alt-fill text-danger me-1"></i><?= htmlspecialchars($data['pickup_address'] ?? '--') ?></div>
                    </div>
                </div>

                <div class="payment-summary">
                    <div>
    <span class="d-block text-muted fw-bold mb-2 text-uppercase" style="font-size: 0.85rem;">Tổng thanh toán</span>

    <?php if ($isRefunded): ?>
        <span class="status-pill status-refunded">
            <i class="bi bi-cash-coin"></i> Đã hoàn tiền
        </span>

    <?php elseif ($isRefundProcessing): ?>
        <span class="status-pill status-refund-processing">
            <i class="bi bi-arrow-repeat"></i> Đang xử lý hoàn tiền
        </span>

    <?php elseif ($payStatus === 'paid'): ?>
        <span class="status-pill status-success">
            <i class="bi bi-check-circle-fill"></i> Đã thanh toán
        </span>

    <?php else: ?>
        <span class="status-pill status-warning">
            <i class="bi bi-hourglass-split"></i> Chưa thanh toán (<?= $payMethod ?>)
        </span>
    <?php endif; ?>
</div>
                    <div class="total-price"><?= number_format($data['total_price']) ?> <span style="font-size: 1.2rem; color: var(--text-main);">VND</span></div>
                </div>

            </div>
        </div>

        <div class="col-lg-4">
            
            <?php if ($status !== 'cancelled' && $status !== 'refunded'): ?>
            <div class="boarding-pass">
                <div class="bp-header">
                    <img src="<?= !empty($data['image']) ? '/uploads/' . $data['image'] : 'https://images.unsplash.com/photo-1501785888041-af3ef285b470' ?>" alt="Destination">
                    <div class="bp-overlay">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-geo-alt-fill text-danger me-1"></i><?= htmlspecialchars($data['destination'] ?? 'Điểm đến') ?></h5>
                    </div>
                </div>
                
                <div class="bp-divider"></div>
                
                <div class="bp-body">
                    <h5 class="fw-bold text-primary mb-1">MÃ VÉ LÊN XE</h5>
                    <p class="small text-muted mb-3">Vui Lòng xuất trình mã này cho Hướng dẫn viên để check-in</p>
                    
                    <div class="qr-box">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=<?= $data['booking_id'] ?>" alt="QR Code">
                    </div>
                    
                    <div class="fw-bold fs-5 tracking-widest text-dark mt-2">ID: <?= str_pad($data['booking_id'], 6, '0', STR_PAD_LEFT) ?></div>
                </div>
            </div>
            <?php endif; ?>

            <div class="detail-card p-4" style="border-radius: 20px;">
                <h6 class="fw-bold mb-4 text-uppercase text-muted" style="letter-spacing: 1px;">Cần hỗ trợ?</h6>
                <div class="d-flex gap-3 align-items-center mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle d-flex"><i class="bi bi-headset fs-4"></i></div>
                    <div>
                        <div class="text-muted small fw-medium">Hotline 24/7</div>
                        <div class="fw-bold text-dark fs-5">1900 1234</div>
                    </div>
                </div>
                <div class="d-flex gap-3 align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle d-flex"><i class="bi bi-envelope-at fs-4"></i></div>
                    <div>
                        <div class="text-muted small fw-medium">Email CSKH</div>
                        <div class="fw-bold text-dark">support@travelvn.com</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include 'layouts/footer.php'; ?>