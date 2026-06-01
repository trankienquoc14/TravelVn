<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

$today = date('Y-m-d');

function getRealDepartureStatus($startDate, $endDate)
{
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

$realStatus = getRealDepartureStatus($departure['start_date'], $departure['end_date']);

$realStatusText = [
    'upcoming' => 'Chờ khởi hành',
    'ongoing' => 'Đang diễn ra',
    'completed' => 'Đã hoàn thành'
][$realStatus] ?? 'Không xác định';

$realStatusIcon = [
    'upcoming' => 'bi-hourglass-split',
    'ongoing' => 'bi-play-circle-fill',
    'completed' => 'bi-check-circle-fill'
][$realStatus] ?? 'bi-question-circle';

$realStatusClass = [
    'upcoming' => 'status-upcoming',
    'ongoing' => 'status-ongoing',
    'completed' => 'status-completed'
][$realStatus] ?? 'status-unknown';

// Nếu ngày đi trong quá khứ, cho phép giữ nguyên ngày cũ.
// Nếu ở tương lai, không cho chọn ngày quá khứ.
$minDate = ($departure['start_date'] < $today) ? $departure['start_date'] : $today;

$isCompletedByDate = ($realStatus === 'completed');

include __DIR__ . '/../layouts/header.php';
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* Nâng cấp Container rộng hơn */
    .admin-container { max-width: 1300px; margin: 40px auto; padding: 0 15px; }

    /* Card thiết kế dạng Panel cao cấp */
    .admin-card {
        background: var(--admin-surface); 
        border-radius: 20px; 
        padding: 40px; 
        border: 1px solid var(--admin-border); 
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
    }

    /* Tối ưu hóa các nhóm Form */
    .form-group-section {
        background: #fcfcfd;
        padding: 25px;
        border-radius: 16px;
        border: 1px solid var(--admin-border);
        margin-bottom: 25px;
    }
    
    .section-title {
        font-size: 1rem;
        font-weight: 800;
        color: var(--admin-text-main);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    /* Input & Select */
    .form-label { font-weight: 700; color: var(--admin-text-main); font-size: 0.9rem; margin-bottom: 8px; }
    .form-control, .form-select { 
        border-radius: 12px; padding: 12px 16px; border: 1px solid #cbd5e1; 
        background-color: #fff; font-size: 0.95rem; transition: 0.2s; 
    }
    .form-control:focus { box-shadow: 0 0 0 4px rgba(1, 148, 243, 0.1); border-color: var(--admin-primary); }
    .status-overview {
    background: #ffffff;
    border: 1px solid var(--admin-border);
    border-radius: 18px;
    padding: 18px 20px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    box-shadow: 0 8px 18px rgba(15, 23, 42, 0.04);
}

.status-overview-left {
    display: flex;
    align-items: center;
    gap: 14px;
}

.status-overview-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
}

.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 14px;
    border-radius: 999px;
    font-size: 0.85rem;
    font-weight: 800;
    border: 1px solid transparent;
    white-space: nowrap;
}

.status-upcoming {
    background: #e0f2fe;
    color: #0284c7;
    border-color: #bae6fd;
}

.status-ongoing {
    background: #dcfce7;
    color: #16a34a;
    border-color: #86efac;
}

.status-completed {
    background: #f1f5f9;
    color: #64748b;
    border-color: #cbd5e1;
}

.status-warning-box {
    background: #fff7ed;
    border: 1px solid #fed7aa;
    color: #9a3412;
    border-radius: 14px;
    padding: 14px 16px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 18px;
}

.form-control[readonly],
.form-select:disabled {
    background-color: #f1f5f9;
    cursor: not-allowed;
}
</style>

<div class="admin-container">
    <div class="row g-4">
        <?php $activeMenu = 'departures'; include __DIR__ . '/../layouts/sidebar_manager.php'; ?>

        <div class="col-lg-9">
            <div class="admin-header">
                <div>
                    <h1 class="admin-title">Cập Nhật Lịch Vận Hành</h1>
                    <p class="text-muted mb-0 fw-medium">Chỉnh sửa chi tiết chuyến đi #<?= htmlspecialchars($departure['departure_id']) ?></p>
                </div>
                <a href="manager.php?action=departures" class="btn border bg-white shadow-sm rounded-pill fw-bold text-dark px-4 py-2">
                    <i class="bi bi-arrow-left me-1"></i> Trở về
                </a>
            </div>
<div class="status-overview">
    <div class="status-overview-left">
        <div class="status-overview-icon <?= $realStatusClass ?>">
            <i class="bi <?= $realStatusIcon ?>"></i>
        </div>

        <div>
            <div class="fw-bold text-dark mb-1">Trạng thái thực tế theo ngày</div>
            <div class="text-muted small">
                Hôm nay: <strong><?= date('d/m/Y') ?></strong> · 
                Chuyến đi từ <strong><?= date('d/m/Y', strtotime($departure['start_date'])) ?></strong>
                đến <strong><?= date('d/m/Y', strtotime($departure['end_date'])) ?></strong>
            </div>
        </div>
    </div>

    <span class="status-pill <?= $realStatusClass ?>">
        <i class="bi <?= $realStatusIcon ?>"></i>
        <?= $realStatusText ?>
    </span>
</div>

<?php if ($isCompletedByDate): ?>
    <div class="status-warning-box">
        <i class="bi bi-info-circle-fill me-1"></i>
        Chuyến đi này đã kết thúc theo ngày thực tế. Admin chỉ nên xem lại thông tin, không nên chỉnh ngày khởi hành hoặc phân công HDV.
    </div>
<?php endif; ?>
            <div class="admin-card">
                <form method="POST" action="manager.php?action=updateDeparture" id="editForm">
                    <input type="hidden" name="departure_id" value="<?= htmlspecialchars($departure['departure_id']) ?>">
                    <input type="hidden" id="bookedSeats" value="<?= htmlspecialchars($departure['booked_seats']) ?>">

                    <div class="form-group-section">
                        <div class="section-title"><i class="bi bi-info-circle text-primary"></i> Thông tin tổng quát</div>
                        <div class="row g-4">
                            <div class="col-md-8">
                                <label class="form-label">Chọn Tour <span class="text-danger">*</span></label>
                                <select name="tour_id" id="tourSelect" class="form-select fw-bold" required>
                                    <?php foreach ($tours as $t): ?>
                                        <option value="<?= $t['tour_id'] ?>" data-duration="<?= (int)$t['duration'] ?>" <?= ($t['tour_id'] == $departure['tour_id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($t['tour_name']) ?> (Hành trình: <?= (int)$t['duration'] ?> ngày)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
    <label class="form-label">Trạng thái chuyến đi</label>

    <?php if ($realStatus === 'ongoing'): ?>
        <div class="form-control fw-bold d-flex align-items-center"
             style="background:#ecfdf5; cursor:not-allowed;">
            <span class="status-pill status-ongoing">
                <i class="bi bi-play-circle-fill"></i>
                Đang diễn ra
            </span>
        </div>

        <input type="hidden" name="status" value="ongoing">

        <div class="small text-muted mt-2">
            Chuyến đi đang trong thời gian diễn ra nên trạng thái được tự động ghi nhận là Đang diễn ra.
        </div>

    <?php elseif ($realStatus === 'completed'): ?>
        <div class="form-control fw-bold d-flex align-items-center"
             style="background:#f1f5f9; cursor:not-allowed;">
            <span class="status-pill status-completed">
                <i class="bi bi-check-circle-fill"></i>
                Đã hoàn thành
            </span>
        </div>

        <input type="hidden" name="status" value="completed">

        <div class="small text-muted mt-2">
            Chuyến đi đã kết thúc theo ngày thực tế nên trạng thái được tự động ghi nhận là Đã hoàn thành.
        </div>

    <?php else: ?>
        <select name="status" class="form-select fw-bold">
            <option value="upcoming" <?= $departure['status'] == 'upcoming' ? 'selected' : '' ?>>
                Đang mở bán
            </option>
            <option value="closed" <?= $departure['status'] == 'closed' ? 'selected' : '' ?>>
                Đóng / Chốt sổ
            </option>
        </select>

        <div class="small text-muted mt-2">
            Chuyến đi chưa khởi hành, admin có thể mở bán hoặc đóng/chốt sổ.
        </div>
    <?php endif; ?>
</div>
                    </div>

                    <div class="form-group-section">
                        <div class="section-title"><i class="bi bi-calendar-range text-success"></i> Thời gian & Phân bổ chỗ</div>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label">Ngày bắt đầu</label>
                                <input type="date" id="startDate" name="start_date"
    value="<?= htmlspecialchars($departure['start_date']) ?>"
    class="form-control fw-bold"
    min="<?= $minDate ?>"
    <?= $isCompletedByDate ? 'readonly' : '' ?>
    required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ngày kết thúc</label>
                                <input type="date" id="endDate" name="end_date" value="<?= htmlspecialchars($departure['end_date']) ?>" class="form-control fw-bold" readonly required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Số chỗ tối đa</label>
                                <input type="number" id="maxSeats" name="max_seats"
    value="<?= htmlspecialchars($departure['max_seats']) ?>"
    class="form-control fw-bold"
    min="1"
    <?= $isCompletedByDate ? 'readonly' : '' ?>
    required>
                        </div>
                        <?php
    $soldSeats = $departure['max_seats'] - $departure['available_seats'];
?>

<div class="mt-3">
    <span class="badge bg-light text-muted border px-3 py-2">
        <i class="bi bi-pie-chart-fill"></i> 
        Đã bán: <strong><?= $soldSeats ?></strong> 
        / Còn trống: <strong><?= $departure['available_seats'] ?></strong>
    </span>
</div>
                    </div>

                    <div class="form-group-section mb-0">
                        <div class="section-title"><i class="bi bi-people text-warning"></i> Phân công Hướng dẫn viên</div>
                        <label class="form-label">Chọn HDV phụ trách</label>
                        <select name="guides[]" class="form-select" multiple <?= $isCompletedByDate ? 'disabled' : '' ?>>
                            <?php foreach ($guides as $g): ?>
                                <option value="<?= $g['user_id'] ?>" <?= (isset($selectedGuides) && in_array($g['user_id'], $selectedGuides)) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($g['full_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($isCompletedByDate && !empty($selectedGuides)): ?>
    <?php foreach ($selectedGuides as $guideId): ?>
        <input type="hidden" name="guides[]" value="<?= htmlspecialchars($guideId) ?>">
    <?php endforeach; ?>
<?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <?php if ($isCompletedByDate): ?>
    <a href="manager.php?action=departures" class="btn btn-secondary px-5 py-3 fw-bold shadow-sm" style="border-radius: 12px;">
        <i class="bi bi-arrow-left me-2"></i> Quay lại danh sách
    </a>
<?php else: ?>
    <button type="submit" class="btn btn-primary px-5 py-3 fw-bold shadow-lg" style="border-radius: 12px;">
        <i class="bi bi-save me-2"></i> Lưu Cập Nhật
    </button>
<?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tourSelect = document.getElementById('tourSelect');
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        const editForm = document.getElementById('editForm');
        const maxSeatsInput = document.getElementById('maxSeats');
        const bookedSeats = parseInt(document.getElementById('bookedSeats').value, 10);
const isCompletedByDate = <?= $isCompletedByDate ? 'true' : 'false' ?>;

if (isCompletedByDate) {
    return;
}
        // Hàm tự động tính ngày kết thúc
        function autoCalculateEndDate() {
            if (!tourSelect.value || !startDateInput.value) return;

            const selectedOption = tourSelect.options[tourSelect.selectedIndex];
            const durationDays = parseInt(selectedOption.getAttribute('data-duration'), 10) || 1;
            const startDate = new Date(startDateInput.value);
            
            startDate.setDate(startDate.getDate() + (durationDays - 1));

            const yyyy = startDate.getFullYear();
            const mm = String(startDate.getMonth() + 1).padStart(2, '0');
            const dd = String(startDate.getDate()).padStart(2, '0');
            
            endDateInput.value = `${yyyy}-${mm}-${dd}`;
            
            // Hiệu ứng đổi màu báo hiệu auto-fill
            endDateInput.style.transition = '0.3s';
            endDateInput.style.backgroundColor = '#eef2ff';
            endDateInput.style.borderColor = '#4f46e5';
            setTimeout(() => {
                endDateInput.style.backgroundColor = '#f1f5f9';
                endDateInput.style.borderColor = '#e2e8f0';
            }, 600);
        }

        // Kích hoạt khi người dùng thay đổi dữ liệu
        tourSelect.addEventListener('change', autoCalculateEndDate);
        startDateInput.addEventListener('change', autoCalculateEndDate);

        // Bắt lỗi Validation JS trước khi gửi lên Server
        editForm.addEventListener('submit', function(e) {
            const newMax = parseInt(maxSeatsInput.value, 10);
            
            if (newMax < bookedSeats) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi Logic Ghế Ngồi!',
                    text: `Chuyến đi này đã có ${bookedSeats} khách đặt chỗ. Bạn không thể giảm tổng số ghế xuống ${newMax} được.`,
                    confirmButtonColor: '#0194f3'
                });
            }
        });
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>