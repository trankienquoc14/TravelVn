<?php 
    include __DIR__ . '/../layouts/header.php'; 
    // Logic mở khóa thông minh: Nếu ngày đi trong quá khứ, cho phép sửa giữ nguyên ngày đó. Nếu ở tương lai, chặn chọn về quá khứ.
    $minDate = ($departure['start_date'] < date('Y-m-d')) ? $departure['start_date'] : date('Y-m-d');
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
                                <select name="status" class="form-select fw-bold">
                                    <option value="upcoming" <?= $departure['status'] == 'upcoming' ? 'selected' : '' ?>>Đang mở bán (Upcoming)</option>
                                    <option value="closed" <?= $departure['status'] == 'closed' ? 'selected' : '' ?>>Đóng / Chốt sổ (Closed)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group-section">
                        <div class="section-title"><i class="bi bi-calendar-range text-success"></i> Thời gian & Phân bổ chỗ</div>
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label class="form-label">Ngày bắt đầu</label>
                                <input type="date" id="startDate" name="start_date" value="<?= htmlspecialchars($departure['start_date']) ?>" class="form-control fw-bold" min="<?= $minDate ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ngày kết thúc</label>
                                <input type="date" id="endDate" name="end_date" value="<?= htmlspecialchars($departure['end_date']) ?>" class="form-control fw-bold" readonly required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Số chỗ tối đa</label>
                                <input type="number" id="maxSeats" name="max_seats" value="<?= htmlspecialchars($departure['max_seats']) ?>" class="form-control fw-bold" min="1" required>
                            </div>
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
                        <select name="guides[]" class="form-select" multiple>
                            <?php foreach ($guides as $g): ?>
                                <option value="<?= $g['user_id'] ?>" <?= (isset($selectedGuides) && in_array($g['user_id'], $selectedGuides)) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($g['full_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary px-5 py-3 fw-bold shadow-lg" style="border-radius: 12px;">
                            <i class="bi bi-save me-2"></i> Lưu Cập Nhật
                        </button>
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