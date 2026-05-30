<?php include __DIR__ . '/../layouts/header.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    :root {
        --admin-primary: #0194f3;
        --admin-primary-light: #eef7ff;
        --admin-success: #10b981;
        --admin-warning: #f59e0b;
        --admin-danger: #ef4444;
        --admin-bg: #f8fafc; 
        --admin-surface: #ffffff;
        --admin-border: #e2e8f0;
        --admin-text-main: #0f172a; 
        --admin-text-muted: #64748b; 
    }

    body { background-color: var(--admin-bg); font-family: 'Inter', sans-serif; }
    .admin-container { max-width: 1000px; margin: 40px auto; padding: 0 15px; }

    /* Header & Card */
    .admin-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px; }
    .admin-title { font-size: 1.8rem; font-weight: 800; color: var(--admin-text-main); margin-bottom: 5px; letter-spacing: -0.5px;}

    .admin-card {
        background: var(--admin-surface);
        border-radius: 20px;
        padding: 40px;
        border: 1px solid var(--admin-border);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
    }

    .form-label { font-weight: 700; color: var(--admin-text-main); font-size: 0.95rem; margin-bottom: 10px; }
    .form-control, .form-select { border-radius: 12px; padding: 14px 15px; border: 1px solid #cbd5e1; background-color: #f8fafc; font-size: 0.95rem; transition: 0.2s; }
    .form-control:focus, .form-select:focus { box-shadow: 0 0 0 4px rgba(1, 148, 243, 0.15); border-color: var(--admin-primary); background-color: #fff; }
    
    /* Readonly style chuyên nghiệp */
    .form-control[readonly] { 
        background-color: #f1f5f9; 
        border-color: #e2e8f0; 
        color: var(--admin-primary); 
        cursor: not-allowed; 
        font-weight: 800;
        opacity: 1;
    }

    select[multiple] { height: auto; min-height: 140px; padding: 10px; }
    select[multiple] option { padding: 12px 15px; border-radius: 8px; margin-bottom: 4px; border: 1px solid transparent; transition: 0.2s; font-weight: 500;}
    select[multiple] option:hover { background-color: #f1f5f9; }
    select[multiple] option:checked {
        background-color: var(--admin-primary-light) !important;
        color: var(--admin-primary) !important;
        font-weight: 800;
        border-color: #bae6fd;
    }
</style>

<div class="admin-container">
    <div class="row g-4">
        
        <?php $activeMenu = 'departures'; include __DIR__ . '/../layouts/sidebar_manager.php'; ?>

        <div class="col-lg-9">
            
            <div class="admin-header">
                <div>
                    <h1 class="admin-title">Thêm Lịch Khởi Hành</h1>
                    <p class="text-muted mb-0 fw-medium">Lên lịch chạy tour mới, thiết lập chỗ ngồi và phân công công việc.</p>
                </div>
                <div>
                    <a href="manager.php?action=departures" class="btn border bg-white shadow-sm rounded-pill fw-bold text-dark px-4 py-2">
                        <i class="bi bi-arrow-left me-1"></i> Trở về
                    </a>
                </div>
            </div>

            <div class="admin-card">
                <form method="POST" action="manager.php?action=storeDeparture">
                    
                    <div class="mb-4 pb-4 border-bottom">
                        <label class="form-label"><i class="bi bi-map text-primary me-2"></i> Chọn Tour cần lên lịch <span class="text-danger">*</span></label>
                        <select name="tour_id" id="tourSelect" class="form-select fw-bold" required>
                            <option value="" data-duration="1">-- Click để chọn Tour --</option>
                            <?php if (!empty($tours)): ?>
                                <?php foreach ($tours as $t): ?>
                                    <option value="<?= $t['tour_id'] ?>" data-duration="<?= (int)$t['duration'] ?>">
                                        <?= htmlspecialchars($t['tour_name']) ?> (Hành trình: <?= (int)$t['duration'] ?> ngày)
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="" disabled>Chưa có tour nào trên hệ thống</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-calendar-event text-success me-2"></i> Ngày bắt đầu <span class="text-danger">*</span></label>
                            <input type="date" id="startDate" name="start_date" class="form-control fw-bold" min="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-calendar-check text-danger me-2"></i> Ngày kết thúc <span class="text-danger">*</span></label>
                            <input type="date" id="endDate" name="end_date" class="form-control" readonly required>
                            <div class="form-text text-muted mt-2"><i class="bi bi-robot text-primary"></i> Hệ thống tự động tính dựa trên số ngày của tour.</div>
                        </div>
                    </div>

                    <div class="row g-4 mb-4 pb-4 border-bottom">
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-car-front-fill text-info me-2"></i> Số chỗ khả dụng <span class="text-danger">*</span></label>
                            <input type="number" name="max_seats" class="form-control fw-bold" placeholder="Ví dụ: 30" min="1" required>
                            <div class="form-text mt-2 text-muted">Hệ thống sẽ dùng con số này làm Tổng số ghế trống ban đầu.</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label"><i class="bi bi-person-badge text-warning me-2"></i> Phân công HDV <span class="text-muted fw-normal">(Không bắt buộc)</span></label>
                            <select name="guides[]" class="form-select" multiple>
                                <?php if (!empty($guides)): ?>
                                    <?php foreach ($guides as $g): ?>
                                        <option value="<?= $g['user_id'] ?>">
                                            &nbsp;<i class="bi bi-person"></i> <?= htmlspecialchars($g['full_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>Chưa có hướng dẫn viên nào</option>
                                <?php endif; ?>
                            </select>
                            <div class="form-text text-muted mt-2">
                                <i class="bi bi-info-circle"></i> Giữ phím <b>Ctrl</b> (Windows) hoặc <b>Cmd</b> (Mac) để chọn nhiều HDV.
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-3">
                        <button type="reset" class="btn btn-light px-4 py-2 border fw-bold text-muted rounded-3">Làm lại</button>
                        <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm rounded-3">
                            <i class="bi bi-check2-circle me-2"></i> Khởi Tạo Chuyến Đi
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

        function autoCalculateEndDate() {
            if (!tourSelect.value || !startDateInput.value) {
                endDateInput.value = '';
                return;
            }

            // Lấy duration từ data-attribute của Tour đang chọn (Ép kiểu thành số nguyên)
            const selectedOption = tourSelect.options[tourSelect.selectedIndex];
            const durationDays = parseInt(selectedOption.getAttribute('data-duration'), 10) || 1;

            // Khởi tạo đối tượng Date từ ngày bắt đầu
            const startDate = new Date(startDateInput.value);
            
            // Cộng thêm số ngày (Trừ 1 vì ngày bắt đầu được tính là ngày 1)
            startDate.setDate(startDate.getDate() + (durationDays - 1));

            // Format lại ra định dạng YYYY-MM-DD
            const yyyy = startDate.getFullYear();
            const mm = String(startDate.getMonth() + 1).padStart(2, '0');
            const dd = String(startDate.getDate()).padStart(2, '0');
            
            endDateInput.value = `${yyyy}-${mm}-${dd}`;
            
            // Hiệu ứng UX: Nháy viền xanh báo hiệu dữ liệu vừa tự động điền
            endDateInput.style.transition = '0.3s';
            endDateInput.style.backgroundColor = '#eef2ff';
            endDateInput.style.borderColor = '#4f46e5';
            setTimeout(() => {
                endDateInput.style.backgroundColor = '#f1f5f9';
                endDateInput.style.borderColor = '#e2e8f0';
            }, 600);
        }

        // Kích hoạt tính toán khi đổi Tour hoặc đổi Ngày khởi hành
        tourSelect.addEventListener('change', autoCalculateEndDate);
        startDateInput.addEventListener('change', autoCalculateEndDate);
    });
</script>
<?php if (!empty($_SESSION['error'])): ?>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Không thể phân công HDV',
        html: `<?= $_SESSION['error'] ?>`,
        confirmButtonColor: '#0194f3'
    });
</script>
<?php unset($_SESSION['error']); endif; ?>
<?php include __DIR__ . '/../layouts/footer.php'; ?>