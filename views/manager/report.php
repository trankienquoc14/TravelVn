<?php 
include __DIR__ . '/../layouts/header.php'; 

// --- XỬ LÝ SỐ LIỆU ---
$startDate = $_GET['start_date'] ?? date('Y-m-01');
$endDate = $_GET['end_date'] ?? date('Y-m-d');
$selectedStatus = $_GET['status'] ?? '';

// Lấy text trạng thái để hiển thị cho bản In / Xuất Excel
$statusTextPrint = "Tất cả trạng thái";
if($selectedStatus == 'confirmed') $statusTextPrint = "Thành công / Đã duyệt";
if($selectedStatus == 'pending') $statusTextPrint = "Chờ xử lý";
if($selectedStatus == 'cancelled') $statusTextPrint = "Đã hủy";

$totalCancelled = 0; $totalSuccessful = 0;
if (!empty($statusStats)) {
    foreach ($statusStats as $stat) {
        if ($stat['status'] === 'cancelled' || $stat['status'] === 'refunded') {
            $totalCancelled += $stat['total'];
        }
        if (in_array($stat['status'], ['confirmed', 'completed', 'checked_in'])) {
            $totalSuccessful += $stat['total'];
        }
    }
}

$totalBookingsNum = $totalBookings ?? 0;
$cancelRate = ($totalBookingsNum > 0) ? round(($totalCancelled / $totalBookingsNum) * 100, 1) : 0;
$successRate = ($totalBookingsNum > 0) ? round(($totalSuccessful / $totalBookingsNum) * 100, 1) : 0;
$avgOrderValue = ($totalSuccessful > 0) ? round(($totalRevenue ?? 0) / $totalSuccessful) : 0;
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <style>
    :root {
        --bg-body: #f8fafc; --card-bg: #ffffff; --border-color: #e2e8f0;
        --text-main: #0f172a; --text-muted: #64748b;
        --primary: #4f46e5; --success: #10b981; --danger: #f43f5e; --warning: #f59e0b; --info: #0ea5e9;
    }

    body { background: var(--bg-body); font-family: 'Inter', sans-serif; }
    .admin-container { max-width: 1400px; margin: 30px auto; padding: 0 15px; }

    /* Header & Toolbar */
    .report-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 25px; flex-wrap: wrap; gap: 15px; }
    .report-title { font-size: 1.8rem; font-weight: 800; color: var(--text-main); margin-bottom: 5px; letter-spacing: -0.5px; }
    .report-subtitle { color: var(--text-muted); font-size: 0.95rem; }
    
    .toolbar-actions { display: flex; gap: 10px; }
    .btn-toolbar { padding: 8px 16px; border-radius: 8px; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 8px; transition: 0.2s; border: 1px solid var(--border-color); background: var(--card-bg); color: var(--text-main); }
    .btn-toolbar:hover { background: #f1f5f9; }
    .btn-primary-soft { background: #e0e7ff; color: var(--primary); border-color: #c7d2fe; }
    .btn-primary-soft:hover { background: #c7d2fe; }

    /* Filters */
    .filter-card { background: var(--card-bg); border-radius: 16px; padding: 20px; border: 1px solid var(--border-color); margin-bottom: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
    .filter-label { font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px; }
    .form-control-custom { border-radius: 10px; border: 1px solid #cbd5e1; padding: 10px 15px; font-size: 0.95rem; }
    .form-control-custom:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); outline: none; }

    /* KPI Cards */
    .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 25px; }
    .kpi-card { background: var(--card-bg); border-radius: 16px; padding: 24px; border: 1px solid var(--border-color); position: relative; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); transition: 0.3s; }
    .kpi-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); }
    .kpi-card::before { content: ''; position: absolute; top: 0; left: 0; width: 5px; height: 100%; border-radius: 16px 0 0 16px; }
    .kpi-card.revenue::before { background: var(--primary); }
    .kpi-card.bookings::before { background: var(--info); }
    .kpi-card.success::before { background: var(--success); }
    .kpi-card.cancel::before { background: var(--danger); }

    .kpi-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; }
    .kpi-title { font-size: 0.9rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; }
    .kpi-icon { width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .kpi-value { font-size: 1.8rem; font-weight: 800; color: var(--text-main); line-height: 1.2; margin-bottom: 5px; }
    .kpi-meta { font-size: 0.85rem; color: var(--text-muted); display: flex; align-items: center; gap: 5px; }

    /* Charts & Tables */
    .dashboard-panel { background: var(--card-bg); border-radius: 16px; padding: 24px; border: 1px solid var(--border-color); box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); height: 100%; }
    .panel-title { font-weight: 800; font-size: 1.1rem; color: var(--text-main); margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; }
    .chart-wrapper { position: relative; width: 100%; min-height: 320px; }

    .table-custom { margin: 0; width: 100%; }
    .table-custom th { background: #f8fafc; color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; padding: 12px 15px; border-bottom: 2px solid var(--border-color); white-space: nowrap; }
    .table-custom td { padding: 15px; border-bottom: 1px solid var(--border-color); vertical-align: middle; font-size: 0.95rem; color: var(--text-main); }
    .table-custom tr:last-child td { border-bottom: none; }
    .table-custom tbody tr:hover { background-color: #f1f5f9; }

    /* --- CSS DÀNH RIÊNG KHI BẤM IN (CTRL+P) --- */
    .print-only { display: none; }
    @media print {
        body { background: white; margin: 0; padding: 0; }
        .admin-sidebar, .toolbar-actions, .filter-card, .btn-toolbar, .d-print-none { display: none !important; }
        .admin-container { width: 100%; max-width: 100%; margin: 0; padding: 0; }
        .kpi-card { border: 1px solid #000; box-shadow: none; break-inside: avoid; }
        .dashboard-panel { border: 1px solid #000; box-shadow: none; break-inside: avoid; margin-bottom: 20px;}
        /* Hiển thị Header riêng cho bản in */
        .print-only { display: block; text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 15px; }
        .print-only h2 { font-size: 24px; text-transform: uppercase; font-weight: bold; margin-bottom: 5px; }
        .print-only p { font-size: 14px; color: #333; margin: 0; }
        .report-header { display: none; } /* Ẩn header web */
    }
</style>

<div class="admin-container">
    <div class="row g-4">

        <?php $activeMenu = 'report'; include __DIR__ . '/../layouts/sidebar_manager.php'; ?>

        <div class="col-lg-9">

            <div class="print-only">
                <h2>BÁO CÁO KẾT QUẢ KINH DOANH TRAVELVN</h2>
                <p>Kỳ báo cáo: Từ ngày <strong><?= date('d/m/Y', strtotime($startDate)) ?></strong> đến <strong><?= date('d/m/Y', strtotime($endDate)) ?></strong></p>
                <p>Lọc theo trạng thái: <strong><?= $statusTextPrint ?></strong></p>
                <p style="text-align: right; margin-top: 10px; font-style: italic;">Ngày trích xuất: <?= date('d/m/Y H:i') ?></p>
            </div>

            <div class="report-header d-print-none">
                <div>
                    <h1 class="report-title">Báo Cáo Hoạt Động Kinh Doanh</h1>
                    <p class="report-subtitle mb-0">
                        Phân tích dữ liệu từ <strong><?= date('d/m/Y', strtotime($startDate)) ?></strong>
                        đến <strong><?= date('d/m/Y', strtotime($endDate)) ?></strong>
                    </p>
                </div>
                <div class="toolbar-actions">
                    <button onclick="window.print()" class="btn-toolbar"><i class="bi bi-printer"></i> In Báo Cáo</button>
                    <button onclick="exportTableToExcel('revenueTable')" class="btn-toolbar btn-primary-soft"><i class="bi bi-file-earmark-excel"></i> Xuất Excel</button>
                </div>
            </div>

            <div class="filter-card d-print-none">
                <form id="filterForm" method="GET" class="row g-3 align-items-end">
                    <input type="hidden" name="action" value="report">
                    <div class="col-lg-3 col-md-6">
                        <label class="filter-label">Từ ngày</label>
                        <input type="date" id="inputStart" name="start_date" class="form-control form-control-custom w-100" value="<?= $startDate ?>">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="filter-label">Đến ngày</label>
                        <input type="date" id="inputEnd" name="end_date" class="form-control form-control-custom w-100" value="<?= $endDate ?>">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="filter-label">Lọc Trạng thái</label>
                        <select id="inputStatus" name="status" class="form-select form-control-custom w-100">
                            <option value="">-- Tất cả trạng thái --</option>
                            <option value="confirmed" <?= $selectedStatus=='confirmed' ? 'selected' : '' ?>>Thành công / Đã duyệt</option>
                            <option value="pending" <?= $selectedStatus=='pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                            <option value="cancelled" <?= $selectedStatus=='cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6 d-flex gap-2">
                        <button type="submit" class="btn-toolbar flex-grow-1 justify-content-center text-white" style="background: var(--primary); border: none;"><i class="bi bi-funnel"></i> Phân tích</button>
                        <a href="manager.php?action=report" class="btn-toolbar justify-content-center px-3" title="Làm mới"><i class="bi bi-arrow-clockwise"></i></a>
                    </div>
                </form>
            </div>

            <div class="kpi-grid">
                <div class="kpi-card revenue">
                    <div class="kpi-header">
                        <div class="kpi-title">Tổng Doanh Thu</div>
                        <div class="kpi-icon" style="background: #eef2ff; color: var(--primary);"><i class="bi bi-wallet2"></i></div>
                    </div>
                    <div class="kpi-value"><?= number_format($totalRevenue ?? 0) ?> <span style="font-size: 1rem;">đ</span></div>
                    <div class="kpi-meta"><span class="text-success fw-bold"><i class="bi bi-graph-up-arrow"></i> <?= number_format($avgOrderValue) ?>đ</span> / đơn thành công</div>
                </div>

                <div class="kpi-card bookings">
                    <div class="kpi-header">
                        <div class="kpi-title">Tổng Đơn Đặt</div>
                        <div class="kpi-icon" style="background: #e0f2fe; color: var(--info);"><i class="bi bi-receipt"></i></div>
                    </div>
                    <div class="kpi-value"><?= number_format($totalBookingsNum) ?> <span style="font-size: 1rem;">đơn</span></div>
                    <div class="kpi-meta">Đang mở bán <strong class="text-dark">&nbsp;<?= $totalTours ?? 0 ?>&nbsp;</strong> Tour</div>
                </div>

                <div class="kpi-card success">
                    <div class="kpi-header">
                        <div class="kpi-title">Tỷ lệ Thành Công</div>
                        <div class="kpi-icon" style="background: #d1fae5; color: var(--success);"><i class="bi bi-check-circle"></i></div>
                    </div>
                    <div class="kpi-value"><?= $successRate ?>%</div>
                    <div class="kpi-meta">Hoàn tất <?= number_format($totalSuccessful) ?> đơn hàng</div>
                </div>

                <div class="kpi-card cancel">
                    <div class="kpi-header">
                        <div class="kpi-title">Tỷ Lệ Hủy Tour</div>
                        <div class="kpi-icon" style="background: #ffe4e6; color: var(--danger);"><i class="bi bi-x-octagon"></i></div>
                    </div>
                    <div class="kpi-value"><?= $cancelRate ?>%</div>
                    <div class="kpi-meta">Thất thoát <?= number_format($totalCancelled) ?> đơn hàng</div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="dashboard-panel">
                        <div class="panel-title"><span><i class="bi bi-graph-up text-primary me-2"></i>Biểu Đồ Tăng Trưởng Doanh Thu</span></div>
                        <div class="chart-wrapper"><canvas id="revenueChart"></canvas></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="dashboard-panel">
                        <div class="panel-title"><span><i class="bi bi-pie-chart-fill text-warning me-2"></i>Cơ Cấu Trạng Thái</span></div>
                        <div class="chart-wrapper" style="min-height: 280px; margin-top: 30px;"><canvas id="statusChart"></canvas></div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-lg-6">
                    <div class="dashboard-panel p-0 overflow-hidden">
                        <div class="panel-title p-4 pb-2 border-bottom"><span><i class="bi bi-table text-success me-2"></i>Bảng Kê Doanh Thu Theo Ngày</span></div>
                        <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                            <table class="table table-custom" id="revenueTable">
                                <thead style="position: sticky; top: 0; z-index: 1;">
                                    <tr>
                                        <th>Ngày giao dịch</th>
                                        <th class="text-end">Doanh thu ghi nhận</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($revenueByMonth)): ?>
                                        <?php foreach($revenueByMonth as $row): ?>
                                        <tr>
                                            <td class="fw-bold"><?= $row['month'] ?></td>
                                            <td class="text-end fw-bold text-primary"><?= number_format($row['revenue']) ?> đ</td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <tr class="table-light">
                                            <td class="fw-bold text-danger">TỔNG CỘNG</td>
                                            <td class="text-end fw-bold text-danger fs-5"><?= number_format($totalRevenue ?? 0) ?> đ</td>
                                        </tr>
                                    <?php else: ?>
                                        <tr><td colspan="2" class="text-center text-muted py-4">Chưa có dữ liệu giao dịch.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="dashboard-panel">
                        <div class="panel-title"><span><i class="bi bi-trophy-fill text-info me-2"></i>Top 5 Tour Bán Chạy Nhất</span></div>
                        <div class="chart-wrapper" id="topTourWrapper"><canvas id="topTourChart"></canvas></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // --- 1. VALIDATE: BẮT LỖI TỪ NGÀY LỚN HƠN ĐẾN NGÀY ---
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        let start = document.getElementById('inputStart').value;
        let end = document.getElementById('inputEnd').value;
        
        if (start && end && start > end) {
            e.preventDefault(); // Chặn việc submit form
            Swal.fire({
                icon: 'error',
                title: 'Lỗi bộ lọc!',
                text: 'Thời gian "Từ ngày" không được vượt quá "Đến ngày". Vui lòng chọn lại!',
                confirmButtonColor: '#4f46e5'
            });
        }
    });

    // --- 2. XUẤT EXCEL THÔNG MINH (KÈM CONTEXT) ---
    function exportTableToExcel(tableID){
        // Lấy dữ liệu filter hiện tại để làm Tiêu đề Excel
        let start = document.getElementById('inputStart').value;
        let end = document.getElementById('inputEnd').value;
        let statusSelect = document.getElementById('inputStatus');
        let statusText = statusSelect.options[statusSelect.selectedIndex].text;

        // Tạo chuỗi ngày định dạng DD-MM-YYYY cho Tên file tải về
        let fileNameDate = start.split('-').reverse().join('') + '_Den_' + end.split('-').reverse().join('');
        let filename = 'BaoCao_DoanhThu_' + fileNameDate + '.xls';

        // Xây dựng phần Header nằm trong file Excel
        let headerHTML = `
            <tr><th colspan="2" style="font-size:20px; font-weight:bold; text-align:center; height:40px;">BÁO CÁO DOANH THU TRAVELVN</th></tr>
            <tr><th colspan="2" style="font-size:14px; text-align:center;">Kỳ báo cáo: Từ ${start} đến ${end}</th></tr>
            <tr><th colspan="2" style="font-size:14px; text-align:center; font-style:italic;">Lọc theo trạng thái: ${statusText}</th></tr>
            <tr><th colspan="2"></th></tr> `;

        let dataType = 'application/vnd.ms-excel';
        let tableSelect = document.getElementById(tableID);
        // Nối Header context vào trên cùng của bảng
        let tableHTML = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><meta charset="utf-8"></head><body><table border="1">' + headerHTML + tableSelect.innerHTML + '</table></body></html>';
        
        let downloadLink = document.createElement("a");
        document.body.appendChild(downloadLink);
        
        if(navigator.msSaveOrOpenBlob){
            var blob = new Blob(['\ufeff', tableHTML], { type: dataType });
            navigator.msSaveOrOpenBlob( blob, filename);
        }else{
            downloadLink.href = 'data:' + dataType + ', ' + encodeURIComponent(tableHTML);
            downloadLink.download = filename;
            downloadLink.click();
        }
    }

    // --- 3. CẤU HÌNH BIỂU ĐỒ (CHART.JS) ---
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#64748b';

    const statusVN = { pending: 'Chờ xử lý', confirmed: 'Đã xác nhận', cancelled: 'Đã hủy', completed: 'Hoàn thành', checked_in: 'Khách đã đến', refunded: 'Hoàn tiền' };
    const rawLabels = <?= json_encode(array_column($statusStats ?? [], 'status')) ?> || [];
    const rawData = <?= json_encode(array_column($statusStats ?? [], 'total')) ?> || [];
    const statLabels = []; const statData = [];

    rawLabels.forEach((s, i) => {
        if (s) { statLabels.push(statusVN[s] || s); statData.push(rawData[i]); }
    });

    const revLabels = <?= json_encode(array_column($revenueByMonth ?? [], 'month')) ?> || [];
    const revData = <?= json_encode(array_column($revenueByMonth ?? [], 'revenue')) ?> || [];

    const tourLabels = <?= json_encode(array_column($topTours ?? [], 'tour_name')) ?> || [];
    const tourData = <?= json_encode(array_column($topTours ?? [], 'total')) ?> || [];

    /* REVENUE LINE CHART */
    const ctxRev = document.getElementById('revenueChart').getContext('2d');
    let gradientRev = ctxRev.createLinearGradient(0, 0, 0, 400);
    gradientRev.addColorStop(0, 'rgba(79, 70, 229, 0.5)'); 
    gradientRev.addColorStop(1, 'rgba(79, 70, 229, 0.0)'); 

    new Chart(ctxRev, {
        type: 'line',
        data: {
            labels: revLabels.length ? revLabels : ['Trống'],
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: revData.length ? revData : [0],
                borderColor: '#4f46e5',
                backgroundColor: gradientRev,
                fill: true, tension: 0.4, borderWidth: 3,
                pointBackgroundColor: '#ffffff', pointBorderColor: '#4f46e5',
                pointBorderWidth: 2, pointRadius: 5, pointHoverRadius: 7
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: { callbacks: { label: function(context) { return new Intl.NumberFormat('vi-VN').format(context.raw) + ' đ'; } } }
            },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, border: { display: false }, ticks: { callback: function(value) { if(value === 0) return 0; return (value / 1000000) + ' Tr'; } } }
            }
        }
    });

    /* STATUS DOUGHNUT CHART */
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: statLabels.length ? statLabels : ['Trống'],
            datasets: [{
                data: statData.length ? statData : [1],
                backgroundColor: ['#10b981', '#3b82f6', '#f43f5e', '#f59e0b', '#8b5cf6', '#64748b'],
                borderWidth: 0, hoverOffset: 10
            }]
        },
        options: {
            maintainAspectRatio: false, cutout: '75%',
            plugins: { legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true, pointStyle: 'circle' } } }
        }
    });

    /* TOP TOUR BAR CHART */
    const dynamicHeight = Math.max(tourLabels.length * 60, 280);
    document.getElementById('topTourWrapper').style.height = dynamicHeight + 'px';

    new Chart(document.getElementById('topTourChart'), {
        type: 'bar',
        data: {
            labels: tourLabels.length ? tourLabels : ['Chưa có dữ liệu'],
            datasets: [{
                label: 'Số lượng đơn',
                data: tourData.length ? tourData : [0],
                backgroundColor: '#0ea5e9', borderRadius: 8, barThickness: 24
            }]
        },
        options: {
            indexAxis: 'y', maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                y: { grid: { display: false }, ticks: { callback: function(v) { let l = this.getLabelForValue(v); return l.length > 25 ? l.substr(0, 25) + '...' : l; } } }
            }
        }
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>