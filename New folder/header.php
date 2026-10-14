<?php 
session_start(); 
include 'connect.php'; 

// ==========================================
// THUẬT TOÁN ĐẾM SỐ LƯỢNG SẢN PHẨM TRONG GIỎ HÀNG
// ==========================================
$so_luong_gio_hang = 0;
if(isset($_SESSION['giohang']) && is_array($_SESSION['giohang'])) {
    foreach($_SESSION['giohang'] as $item) {
        $so_luong_gio_hang += $item['soluong'];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VietSkin Cosmetics - Tinh Hoa Mỹ Phẩm Việt</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&family=Playfair+Display:ital,wght@0,500;0,700;1,500&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Montserrat', sans-serif; background-color: #fff; color: #333; }
        h1, h2, h3, .brand-text { font-family: 'Playfair Display', serif; }
        
        /* Hiệu ứng click cho thanh Top Bar */
        .top-bar { 
            background-color: #1a1a1a; color: #fff; font-size: 13px; padding: 10px 0; letter-spacing: 0.5px; 
            cursor: pointer; transition: 0.3s;
        }
        .top-bar:hover { color: #d1bfae; }
        
        .navbar { padding: 15px 0; border-bottom: 1px solid #f0f0f0; }
        .nav-link { color: #333 !important; font-size: 14px; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; padding: 10px 15px; }
        .nav-link:hover { color: #888 !important; }
        .navbar-brand { font-size: 28px; font-weight: 700; letter-spacing: 2px; color: #9a7b4f !important; }
        .dropdown-menu { border-radius: 0; border: 1px solid #eee; margin-top: 15px; }
        .dropdown-item { font-size: 14px; padding: 10px 20px; font-weight: 500; }
        .dropdown-item:hover { background-color: #f8f9fa; color: #9a7b4f; }
        
        .product-card { border: none; transition: all 0.3s ease; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .product-img { height: 350px; object-fit: cover; border-radius: 4px; }
        .product-title { font-size: 16px; font-weight: 600; margin-top: 15px; color: #333; }
        .product-price { font-size: 15px; font-weight: 600; color: #555; }
        
        .btn-custom-dark { background-color: #222; color: #fff; border-radius: 0; padding: 12px 40px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; border: 1px solid #222; transition: 0.3s; text-decoration: none; display: inline-block; }
        .btn-custom-dark:hover { background-color: #fff; color: #222; }

        .search-sidebar { width: 450px !important; border-right: none; padding: 30px; background-color: #fffaf6; }
        .search-input-line { border: none; border-bottom: 1px solid #ccc; border-radius: 0; padding-left: 0; background: transparent; font-size: 1.1rem; box-shadow: none !important; }
        .search-input-line:focus { border-bottom: 1px solid #333; }
        .search-suggestion-link { color: #333; text-decoration: none; font-size: 1rem; margin-bottom: 15px; display: block; transition: 0.2s; }
        .search-suggestion-link:hover { color: #9a7b4f; transform: translateX(5px); }
        .suggestion-item:hover { background-color: #f8f9fa !important; }
    </style>
</head>
<body>

    <!-- ==========================================
         OFFCANVAS TÌM KIẾM (CÓ GỢI Ý SẢN PHẨM TRỰC TIẾP)
    ========================================== -->
    <div class="offcanvas offcanvas-start search-sidebar" tabindex="-1" id="searchSidebar">
        <div class="offcanvas-header pb-0">
            <button type="button" class="btn-close text-reset ms-0 me-auto" data-bs-dismiss="offcanvas" aria-label="Close" style="font-size: 1.2rem;"></button>
        </div>
        <div class="offcanvas-body mt-4 pt-2">
            
            <form action="sanpham.php" method="GET" class="d-flex align-items-center mb-3 position-relative">
                <input type="text" name="timkiem" id="inputSearch" autocomplete="off" class="form-control search-input-line" placeholder="Nhập từ khóa bạn muốn tìm kiếm..." required autofocus>
                <button type="submit" class="btn btn-link text-dark p-0 ms-2 text-decoration-none"><i class="bi bi-arrow-right fs-4"></i></button>
            </form>

            <!-- KHUNG HIỂN THỊ GỢI Ý TỰ ĐỘNG (AJAX) -->
            <div id="searchSuggestionsBox" class="shadow-sm rounded bg-white overflow-hidden mb-4" style="max-height: 400px; overflow-y: auto;"></div>

            <div class="search-suggestions mt-4">
                <p class="text-muted small mb-3">Gợi ý tìm kiếm nhanh:</p>
                <a href="sanpham.php?timkiem=Sữa rửa mặt" class="search-suggestion-link">Sữa rửa mặt</a>
                <a href="sanpham.php?timkiem=Nước tẩy trang" class="search-suggestion-link">Nước tẩy trang</a>
                <a href="sanpham.php?timkiem=Vitamin C" class="search-suggestion-link">Vitamin C</a>
                <a href="sanpham.php?timkiem=Kem chống nắng" class="search-suggestion-link">Kem chống nắng</a>
            </div>
        </div>
    </div>

    <!-- ==========================================
         TOP BAR (Gắn sự kiện click mở Modal Vận Chuyển)
    ========================================== -->
    <div class="top-bar text-center" data-bs-toggle="modal" data-bs-target="#shippingModal" title="Nhấn để xem chi tiết">
        Tận hưởng giao hàng miễn phí toàn quốc với hoá đơn từ 99.000 đ +
    </div>

    <!-- ==========================================
         MENU CHÍNH (NAVBAR)
    ========================================== -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top mb-4 shadow-sm">
        <div class="container-fluid px-4">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse w-100 order-1 order-lg-0" id="navbarNav">
                <ul class="navbar-nav me-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link fs-5" href="#" data-bs-toggle="offcanvas" data-bs-target="#searchSidebar"><i class="bi bi-search"></i></a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="index.php">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="sanpham.php">Sản phẩm</a></li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Danh mục</a>
                        <ul class="dropdown-menu shadow">
                            <?php
                            $sql_dm = "SELECT * FROM DanhMuc";
                            $kq_dm = $conn->query($sql_dm);
                            if($kq_dm && $kq_dm->num_rows > 0) {
                                while($dm = $kq_dm->fetch_assoc()) {
                                    echo '<li><a class="dropdown-item" href="danhmuc.php?id='.$dm['ID_DanhMuc'].'">'.$dm['TenDanhMuc'].'</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Thương hiệu</a>
                        <ul class="dropdown-menu shadow">
                            <li><a class="dropdown-item" href="thuonghieu.php?brand=Cocoon">Cocoon</a></li>
                            <li><a class="dropdown-item" href="thuonghieu.php?brand=Dr.Ceutics">Dr.Ceutics</a></li>
                            <li><a class="dropdown-item" href="thuonghieu.php?brand=Emmié">Emmié</a></li>
                            <li><a class="dropdown-item" href="thuonghieu.php?brand=Skin Heritage">Skin Heritage</a></li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="d-flex w-100 justify-content-center order-0 order-lg-1">
                <a class="navbar-brand brand-text m-0" href="index.php">VietSkin.</a>
            </div>

            <div class="collapse navbar-collapse w-100 order-2 order-lg-2">
                <ul class="navbar-nav ms-auto">
                    <?php if(isset($_SESSION['VaiTro'])): ?>
                        <?php if($_SESSION['VaiTro'] == 'Admin'): ?>
                            <li class="nav-item"><a class="nav-link text-danger fw-bold" href="admin.php">⚙️ Quản Trị</a></li>
                        <?php endif; ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle fw-bold" href="#" data-bs-toggle="dropdown" style="color: #9a7b4f !important;">
                                Chào, <?php echo $_SESSION['HoTen']; ?>
                            </a>
                            <ul class="dropdown-menu shadow dropdown-menu-end">
                                <li><a class="dropdown-item" href="dangxuat.php">Đăng xuất</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="dangnhap.php">Đăng nhập</a></li>
                        <li class="nav-item"><a class="nav-link" href="dangky.php">Đăng ký</a></li>
                    <?php endif; ?>
                    
                    <li class="nav-item">
                        <a class="nav-link fw-bold" href="giohang.php" style="color: #9a7b4f !important;">Giỏ hàng (<?php echo $so_luong_gio_hang; ?>)</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ==========================================
         MODAL: CHÍNH SÁCH VẬN CHUYỂN & GIAO HÀNG
    ========================================== -->
    <div class="modal fade" id="shippingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="background-color: #fdfaf5; border-radius: 0; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.2);">
                <div class="modal-header border-0 pb-0 pe-4 pt-4">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="font-size: 0.9rem;"></button>
                </div>
                <div class="modal-body px-5 pb-5 pt-3" style="color: #333; line-height: 1.8; font-size: 1rem;">
                    <h5 class="fw-bold mb-3" style="color: #111;">Phí vận chuyển</h5>
                    <p class="mb-1">Với hóa đơn từ 99.000đ: miễn phí vận chuyển toàn quốc.</p>
                    <p class="mb-5">Với hóa đơn dưới 99.000đ: phí vận chuyển mặc định 30.000đ áp dụng toàn quốc.</p>
                    
                    <h5 class="fw-bold mb-3" style="color: #111;">Thời gian giao hàng</h5>
                    <p class="mb-1 fw-bold">• Đơn hàng nội thành TP.HCM và Hà Nội:</p>
                    <p class="mb-4">Thời gian giao hàng là 2-7 ngày sau khi đặt hàng.</p>
                    
                    <p class="mb-1 fw-bold">• Đơn hàng ở ngoại thành và các tỉnh thành khác:</p>
                    <p class="mb-4">Thời gian là 2-15 ngày đối với khu vực trung tâm tỉnh thành phố, 5-15 ngày đối với khu vực huyện, xã, thị trấn...</p>

                    <p class="mb-1 fw-bold">• Lưu ý:</p>
                    <p class="mb-5">Đơn hàng đặt mua tại website VietSkin sẽ được chúng tôi chuyển phát đến thông qua GIAO HÀNG TIẾT KIỆM Hoặc NETPOST.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT AJAX TÌM KIẾM SẢN PHẨM TRỰC TIẾP -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const inputSearch = document.getElementById('inputSearch');
        const suggestBox = document.getElementById('searchSuggestionsBox');

        if(inputSearch) {
            inputSearch.addEventListener('keyup', function() {
                let keyword = this.value.trim();

                if (keyword.length > 0) {
                    fetch('ajax_timkiem.php?keyword=' + encodeURIComponent(keyword))
                        .then(response => response.text())
                        .then(data => {
                            suggestBox.innerHTML = data;
                            suggestBox.style.display = 'block';
                        });
                } else {
                    suggestBox.innerHTML = '';
                    suggestBox.style.display = 'none';
                }
            });
        }
    });
    </script>