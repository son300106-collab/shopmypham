<?php
include 'header.php';

if (!isset($_SESSION['VaiTro']) || $_SESSION['VaiTro'] !== 'Admin') {
    echo "<script>alert('⛔ Bạn không có quyền truy cập!'); window.location.href='index.php';</script>";
    exit;
}

// ==========================================
// 1. TÍNH TỔNG DOANH THU & ĐƠN HÀNG (Bỏ qua đơn Đã Hủy)
// ==========================================
$sql_doanhthu = "SELECT SUM(TongTien) AS TongDoanhThu, COUNT(ID_DonHang) AS TongDon FROM DonHang WHERE TrangThai != 'Đã hủy'";
$res_doanhthu = $conn->query($sql_doanhthu);
$data_doanhthu = $res_doanhthu->fetch_assoc();
$tongDoanhThu = $data_doanhthu['TongDoanhThu'] ?? 0;
$tongDon = $data_doanhthu['TongDon'] ?? 0;

// ==========================================
// 2. THUẬT TOÁN TÍNH TIỀN VỐN & LỢI NHUẬN
// ==========================================
$tongVon = 0;
// JOIN bảng Chi Tiết Đơn Hàng với bảng Sản Phẩm để lấy Giá Nhập
$sql_von = "SELECT ct.ID_SanPham, ct.SoLuong, ct.GiaBan, sp.GiaNhap 
            FROM ChiTietDonHang ct 
            JOIN DonHang dh ON ct.ID_DonHang = dh.ID_DonHang 
            LEFT JOIN SanPham sp ON ct.ID_SanPham = sp.ID_SanPham 
            WHERE dh.TrangThai != 'Đã hủy'";
$res_von = $conn->query($sql_von);

if($res_von && $res_von->num_rows > 0) {
    while($row = $res_von->fetch_assoc()) {
        if($row['GiaNhap'] != null) {
            // Sản phẩm thường: Lấy Giá Nhập * Số Lượng
            $tongVon += ($row['GiaNhap'] * $row['SoLuong']);
        } else {
            // Là các sản phẩm Combo khuyến mãi (chưa có trong bảng SanPham)
            // Giả định vốn của Combo chiếm 60% giá bán ra
            $tongVon += ($row['GiaBan'] * 0.6 * $row['SoLuong']); 
        }
    }
}

// CÔNG THỨC: LỢI NHUẬN = DOANH THU - VỐN
$loiNhuan = $tongDoanhThu - $tongVon;

// ==========================================
// 3. LẤY DỮ LIỆU TOP 5 SẢN PHẨM BÁN CHẠY ĐỂ VẼ BIỂU ĐỒ
// ==========================================
$sql_chart = "SELECT ID_SanPham, SUM(SoLuong) AS TongBan FROM ChiTietDonHang GROUP BY ID_SanPham ORDER BY TongBan DESC LIMIT 5";
$res_chart = $conn->query($sql_chart);

$labels_array = [];
$data_array = [];

while($row = $res_chart->fetch_assoc()) {
    $id_sp = $row['ID_SanPham'];
    if(strpos((string)$id_sp, 'combo') !== false) {
        $ten_sp = "Combo Khuyến Mãi Ưu Đãi";
    } else {
        // Tránh lỗi kiểu dữ liệu
        $id_sp_clean = intval($id_sp);
        $sql_ten = "SELECT TenSanPham FROM SanPham WHERE ID_SanPham = $id_sp_clean OR ID_SanPham = '$id_sp'";
        $res_ten = $conn->query($sql_ten);
        if($res_ten && $res_ten->num_rows > 0) {
            $ten_sp = mb_substr($res_ten->fetch_assoc()['TenSanPham'], 0, 25) . '...';
        } else {
            $ten_sp = "Sản phẩm ID: " . $id_sp;
        }
    }
    $labels_array[] = $ten_sp;
    $data_array[] = $row['TongBan'];
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- STYLE TÙY CHỈNH CHO GIAO DIỆN MỚI -->
<style>
    .stat-card {
        border-radius: 1rem;
        border: none;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .stat-icon-bg {
        position: absolute;
        right: -10%;
        bottom: -20%;
        font-size: 8rem;
        opacity: 0.1;
        z-index: 0;
    }
    .stat-content {
        position: relative;
        z-index: 1;
    }
    .bg-revenue { background: linear-gradient(135deg, #e0f7fa 0%, #b3e5fc 100%); color: #0277bd; }
    .bg-cost { background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%); color: #e65100; }
    .bg-profit { background: linear-gradient(135deg, #1a4a38 0%, #2a6a50 100%); color: #ffffff; }
</style>

<div class="container-fluid px-4 my-5 pt-4 pb-5" style="min-height: 70vh;">
    <!-- TIÊU ĐỀ -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2 class="brand-text m-0 d-flex align-items-center" style="color: #d9534f; font-weight: 600; font-family: serif;">
            <i class="bi bi-pie-chart-fill me-3 fs-3"></i> Báo Cáo Tài Chính
        </h2>
        <a href="admin.php" class="btn btn-outline-dark px-4 py-2" style="border-radius: 8px; font-weight: 500;">
            ← Về Dashboard
        </a>
    </div>

    <!-- 3 KHỐI BÁO CÁO TÀI CHÍNH (GIAO DIỆN CAO CẤP) -->
    <div class="row g-4 mb-5">
        
        <!-- DOANH THU -->
        <div class="col-md-4">
            <div class="card stat-card bg-revenue shadow-sm h-100 p-4">
                <i class="bi bi-wallet2 stat-icon-bg text-primary"></i>
                <div class="stat-content text-center pt-2">
                    <h6 class="text-uppercase fw-bold mb-3" style="letter-spacing: 1px; color: #0277bd; opacity: 0.8;">Tổng Doanh Thu (Thu Vào)</h6>
                    <h2 class="fw-bold mb-2" style="font-size: 2.5rem;"><?php echo number_format($tongDoanhThu, 0, ',', '.'); ?> đ</h2>
                    <p class="mb-0 fw-medium" style="opacity: 0.8;">Từ <?php echo $tongDon; ?> đơn hàng</p>
                </div>
            </div>
        </div>

        <!-- VỐN -->
        <div class="col-md-4">
            <div class="card stat-card bg-cost shadow-sm h-100 p-4">
                <i class="bi bi-box-seam stat-icon-bg text-danger"></i>
                <div class="stat-content text-center pt-2">
                    <h6 class="text-uppercase fw-bold mb-3" style="letter-spacing: 1px; color: #e65100; opacity: 0.8;">Tổng Tiền Vốn (Chi Ra)</h6>
                    <h2 class="fw-bold mb-2" style="font-size: 2.5rem;"><?php echo number_format($tongVon, 0, ',', '.'); ?> đ</h2>
                    <p class="mb-0 fw-medium" style="opacity: 0.8;">Chi phí nhập hàng hóa</p>
                </div>
            </div>
        </div>

        <!-- LỢI NHUẬN -->
        <div class="col-md-4">
            <div class="card stat-card bg-profit shadow h-100 p-4">
                <i class="bi bi-graph-up-arrow stat-icon-bg text-white"></i>
                <div class="stat-content text-center pt-2">
                    <h6 class="text-uppercase fw-bold mb-3 text-light" style="letter-spacing: 1px; opacity: 0.9;">Lợi Nhuận Ròng (Tiền Lãi)</h6>
                    <h2 class="fw-bold mb-2" style="font-size: 2.5rem; color: #4ade80;">
                        + <?php echo number_format($loiNhuan, 0, ',', '.'); ?> đ
                    </h2>
                    <p class="mb-0 fw-medium text-light" style="opacity: 0.9;">Tiền lãi thực tế bỏ túi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- KHU VỰC VẼ BIỂU ĐỒ -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white border-bottom-0 pt-4 pb-0 text-center">
            <h4 class="fw-bold text-dark" style="font-family: serif;">Biểu Đồ Top 5 Sản Phẩm Bán Chạy Nhất</h4>
        </div>
        <div class="card-body p-4" style="position: relative; height: 50vh; width: 100%;">
            <canvas id="topProductChart"></canvas>
        </div>
    </div>
</div>

<script>
    const labels = <?php echo json_encode($labels_array); ?>;
    const dataValues = <?php echo json_encode($data_array); ?>;

    // Bộ màu đồng bộ với thương hiệu
    const brandColors = [
        '#d9534f', // Đỏ rượu
        '#9a7b4f', // Nâu vàng
        '#2a6a50', // Xanh rêu
        '#1c1c1c', // Đen xám
        '#88bde1'  // Xanh nhạt
    ];

    const ctx = document.getElementById('topProductChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar', 
        data: {
            labels: labels,
            datasets: [{
                label: 'Đã bán ra (hộp/chai)',
                data: dataValues,
                backgroundColor: brandColors,
                borderWidth: 0,
                borderRadius: 8,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: { usePointStyle: true, boxWidth: 10, font: { size: 13 } }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 } 
                },
                x: {
                    ticks: { maxRotation: 25, minRotation: 0 }
                }
            }
        }
    });
</script>

<?php include 'footer.php'; ?>