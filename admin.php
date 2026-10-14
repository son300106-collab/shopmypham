<?php
include 'header.php';
if (!isset($_SESSION['VaiTro']) || $_SESSION['VaiTro'] !== 'Admin') {
    echo "<script>alert('⛔ Bạn không có quyền truy cập!'); window.location.href='index.php';</script>";
    exit;
}
?>
<style>
    .admin-card { transition: all 0.3s ease; border-radius: 8px; }
    .admin-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
    .admin-title { color: #d9534f; font-weight: 500; letter-spacing: 1px; }
</style>

<div class="container my-5 pt-4 pb-5" style="min-height: 60vh;">
    <h2 class="text-center mb-5 brand-text admin-title" style="font-size: 2.5rem;">Bảng Điều Khiển Quản Trị</h2>
    <div class="row justify-content-center px-lg-5">
        
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm border-0 py-4 px-3 text-center admin-card">
                <div class="card-body d-flex flex-column">
                    <h5 class="fw-bold mb-3">Sản Phẩm & Tồn Kho</h5>
                    <a href="admin_sanpham.php" class="btn btn-dark mt-auto w-100 py-2 fw-bold" style="background-color: #222;">Xem Sản Phẩm</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm border-0 py-4 px-3 text-center admin-card">
                <div class="card-body d-flex flex-column">
                    <h5 class="fw-bold mb-3">Quản Lý Đơn Hàng</h5>
                    <a href="admin_donhang.php" class="btn btn-dark mt-auto w-100 py-2 fw-bold" style="background-color: #222;">Xem Đơn Hàng</a>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm border-0 py-4 px-3 text-center admin-card">
                <div class="card-body d-flex flex-column">
                    <h5 class="fw-bold mb-3">Tài Khoản KH</h5>
                    <a href="admin_taikhoan.php" class="btn btn-dark mt-auto w-100 py-2 fw-bold" style="background-color: #222;">Xem Tài Khoản</a>
                </div>
            </div>
        </div>

        <!-- THÊM NÚT VÀO TRANG THỐNG KÊ DOANH THU -->
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm border-0 py-4 px-3 text-center admin-card" style="border-bottom: 4px solid #d9534f !important;">
                <div class="card-body d-flex flex-column">
                    <h5 class="fw-bold mb-3 text-danger">Thống Kê & Biểu Đồ</h5>
                    <a href="admin_thongke.php" class="btn btn-danger mt-auto w-100 py-2 fw-bold">Xem Báo Cáo</a>
                </div>
            </div>
        </div>

    </div>
</div>
<?php include 'footer.php'; ?>