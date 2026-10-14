<?php
include 'header.php';

if (!isset($_SESSION['VaiTro']) || $_SESSION['VaiTro'] !== 'Admin') {
    echo "<script>alert('⛔ Bạn không có quyền truy cập!'); window.location.href='index.php';</script>";
    exit;
}

// 1. TÍNH TỔNG DOANH THU & ĐƠN HÀNG (Bỏ qua đơn Đã Hủy)
$sql_doanhthu = "SELECT SUM(TongTien) AS TongDoanhThu, COUNT(ID_DonHang) AS TongDon FROM DonHang WHERE TrangThai != 'Đã hủy'";
$res_doanhthu = $conn->query($sql_doanhthu);
$data_doanhthu = $res_doanhthu->fetch_assoc();
$tongDoanhThu = $data_doanhthu['TongDoanhThu'] ?? 0;
$tongDon = $data_doanhthu['TongDon'] ?? 0;

// 2. THUẬT TOÁN TÍNH TIỀN VỐN & LỢI NHUẬN
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

// 3. LẤY DỮ LIỆU TOP 5 SẢN PHẨM BÁN CHẠY ĐỂ VẼ BIỂU ĐỒ
$sql_chart = "SELECT ID_SanPham, SUM(SoLuong) AS TongBan FROM ChiTietDonHang GROUP BY ID_SanPham ORDER BY TongBan DESC LIMIT 5";
$res_chart = $conn->query($sql_chart);

$labels_array = [];
$data_array = [];

while($row = $res_chart->fetch_assoc()) {
    $id_sp = $row['ID_SanPham'];
    if(strpos($id_sp, 'combo') !== false) {
        $ten_sp = "Combo Khuyến Mãi Ưu Đãi";
    } else {
        $sql_ten = "SELECT TenSanPham FROM SanPham WHERE ID_SanPham = '$id_sp'";
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

<div class="container my-5 pt-4 pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="brand-text" style="color: #d9534f; font-weight: 500;">Báo Cáo Tài Chính</h2>
        <a href="admin.php" class="btn btn-outline-dark">← Về Dashboard</a>
    </div>

    <!-- 3 KHỐI VUÔNG BÁO CÁO TÀI CHÍNH -->
    <div class="row mb-5 text-center">
        <!-- DOANH THU -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 py-4 rounded" style="background-color: #f8f9fa;">
                <h5 class="mb-2 text-muted fw-bold">TỔNG DOANH THU (THU VÀO)</h5>
                <h2 class="fw-bold m-0" style="color: #2b6de3;"><?php echo number_format($tongDoanhThu, 0, ',', '.'); ?> đ</h2>
                <small class="text-muted mt-2">Từ <?php echo $tongDon; ?> đơn hàng</small>
            </div>
        </div>
        <!-- VỐN -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm border-0 py-4 rounded" style="background-color: #fcfbf8;">
                <h5 class="mb-2 text-muted fw-bold">TỔNG TIỀN VỐN (CHI RA)</h5>
                <h2 class="fw-bold m-0 text-secondary"><?php echo number_format($tongVon, 0, ',', '.'); ?> đ</h2>
                <small class="text-muted mt-2">Chi phí nhập hàng hóa</small>
            </div>
        </div>
        <!-- LỢI NHUẬN -->
        <div class="col-md-4 mb-3">
            <div class="card shadow border-0 bg-dark text-white py-4 rounded" style="transform: scale(1.05);">
                <h5 class="mb-2 fw-bold" style="color: #4ade80;">LỢI NHUẬN RÒNG (TIỀN LÃI)</h5>
                <h2 class="fw-bold m-0" style="color: #4ade80;">+ <?php echo number_format($loiNhuan, 0, ',', '.'); ?> đ</h2>
                <small class="text-light mt-2 opacity-75">Tiền lãi thực tế bỏ túi</small>
            </div>
        </div>
    </div>

    <!-- KHU VỰC VẼ BIỂU ĐỒ -->
    <div class="card shadow-sm border-0 p-4 rounded mt-4">
        <h4 class="fw-bold mb-4 text-center">Biểu Đồ Top 5 Sản Phẩm Bán Chạy Nhất</h4>
        <div style="height: 400px; width: 100%;">
            <canvas id="topProductChart"></canvas>
        </div>
    </div>
</div>

<script>
    const labels = <?php echo json_encode($labels_array); ?>;
    const dataValues = <?php echo json_encode($data_array); ?>;

    const ctx = document.getElementById('topProductChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar', 
        data: {
            labels: labels,
            datasets: [{
                label: 'Đã bán ra (hộp/chai)',
                data: dataValues,
                backgroundColor: [
                    'rgba(217, 83, 79, 0.8)',
                    'rgba(154, 123, 79, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 206, 86, 0.8)'
                ],
                borderWidth: 0,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 } 
                }
            }
        }
    });
</script>

<?php include 'footer.php'; ?>