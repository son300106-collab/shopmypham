<?php 
include 'header.php'; 
?>

<style>
    .hero-carousel-item { min-height: 550px; display: flex; align-items: center; width: 100%; }
    .slide-title { font-family: 'Playfair Display', serif; font-weight: 700; line-height: 1.2; }
    .slide-subtitle { text-transform: uppercase; letter-spacing: 2px; margin-bottom: 15px; font-weight: 500; }
    .carousel-control-prev, .carousel-control-next { width: 5%; opacity: 1; }
    .carousel-btn-box {
        background-color: #222; width: 45px; height: 45px;
        display: flex; align-items: center; justify-content: center;
        color: #fff; transition: 0.3s;
    }
    .carousel-btn-box:hover { background-color: #9a7b4f; }
</style>

<!-- ==========================================
     KHU VỰC BANNER SLIDER (3 SLIDE RIÊNG BIỆT)
=========================================== -->
<div id="mainHeroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">
    <div class="carousel-inner">
        
        <!-- SLIDE 1: COMBO GEL BÍ ĐAO -->
        <div class="carousel-item active">
            <div class="hero-carousel-item" style="background-color: #8cbbe0;">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-7 text-white">
                            <h5 class="slide-subtitle">MỚI!</h5>
                            <h1 class="slide-title mb-3" style="font-size: 4.5rem; text-shadow: 2px 2px 5px rgba(0,0,0,0.1);">GEL BÍ ĐAO<br>RỬA MẶT 500ML</h1>
                            <div style="border-left: 3px solid rgba(255,255,255,0.6); padding-left: 20px; margin-bottom: 35px;">
                                <h4 style="font-weight: 400; font-size: 1.4rem; line-height: 1.5;">SẠCH SÂU KHÔNG KHÔ CĂNG<br>GIẢM DẦU & MỤN</h4>
                            </div>
                            <!-- LINK DẪN TỚI COMBO BÍ ĐAO -->
                            <a href="khuyenmai.php?sp=bidao" class="btn fw-bold px-4 py-2" style="background-color: #e6f2d9; color: #5c7e3f; font-size: 1.2rem; border-radius: 4px;">MUA 7 TÍNH 1</a>
                        </div>
                        <div class="col-md-5 text-center mt-4 mt-md-0">
                            <!-- ĐÃ SỬA TÊN ẢNH THÀNH baner.png -->
                            <img src="images/banner1.jpip" onerror="this.src='https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=500&h=500&fit=crop'" class="img-fluid" style="max-height: 480px; filter: drop-shadow(0 15px 25px rgba(0,0,0,0.15));">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SLIDE 2: SỮA CHỐNG NẮNG SEN -->
        <div class="carousel-item">
            <div class="hero-carousel-item" style="background-color: #ff87a8;">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-center mb-4 mb-md-0">
                            <img src="images/banner-sen.png" onerror="this.src='https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=500&h=500&fit=crop'" class="img-fluid" style="max-height: 450px;">
                        </div>
                        <div class="col-md-6 text-white px-lg-5">
                            <h5 class="slide-subtitle" style="font-size: 1rem;">SẢN PHẨM MỚI</h5>
                            <h1 class="slide-title mb-3" style="font-size: 3.8rem; letter-spacing: -1px;">Sữa chống nắng<br>vô cơ sen Hậu<br>Giang</h1>
                            <p class="mb-4" style="font-size: 1.05rem; line-height: 1.6; opacity: 0.9;">Mở ra một thế hệ chống nắng vô cơ mới với lớp finish nâng tông tự nhiên và không để lại vệt trắng.</p>
                            <!-- LINK DẪN TỚI COMBO CHỐNG NẮNG -->
                            <a href="khuyenmai.php?sp=sen" class="btn bg-white text-dark fw-bold px-4 py-2 shadow-sm" style="border-radius: 0; letter-spacing: 1px;">XEM NGAY &nbsp; →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SLIDE 3: SERUM VITAMIN C -->
        <div class="carousel-item">
            <div class="hero-carousel-item" style="background-color: #f6f8f9;">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-center mb-4 mb-md-0">
                            <img src="images/banner-vitc.png" onerror="this.src='https://images.unsplash.com/photo-1629198688000-71f23e745b6e?w=500&h=500&fit=crop'" class="img-fluid" style="max-height: 450px; border-radius: 8px;">
                        </div>
                        <div class="col-md-6 px-lg-5">
                            <h5 class="slide-subtitle text-muted" style="letter-spacing: 3px;">BÁN CHẠY NHẤT</h5>
                            <div class="mb-3">
                                <h1 class="slide-title text-white d-inline-block mb-1 px-3 py-1" style="background-color: #2b6de3; font-size: 3rem;">Serum Vitamin C 16%</h1><br>
                                <h1 class="slide-title text-white d-inline-block px-3 py-1" style="background-color: #2b6de3; font-size: 3rem;">Dưỡng sáng mờ thâm</h1>
                            </div>
                            <p class="text-muted mb-4" style="font-size: 1.05rem; line-height: 1.6;">Sự kết hợp hoàn hảo giữa Vitamin C tinh khiết và các thành phần chống oxy hóa mạnh mẽ giúp da sáng mịn.</p>
                            <!-- LINK DẪN TỚI COMBO VITAMIN C -->
                            <a href="khuyenmai.php?sp=vitc" class="btn fw-bold px-4 py-2" style="background-color: #222; color: #fff; border-radius: 0; letter-spacing: 1px;">MUA NGAY &nbsp; →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#mainHeroCarousel" data-bs-slide="prev">
        <div class="carousel-btn-box shadow"><i class="bi bi-chevron-left fs-5"></i></div>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#mainHeroCarousel" data-bs-slide="next">
        <div class="carousel-btn-box shadow"><i class="bi bi-chevron-right fs-5"></i></div>
    </button>
</div>

<!-- ==========================================
     KHU VỰC DANH SÁCH SẢN PHẨM TRANG CHỦ
=========================================== -->
<div class="container my-5 pt-5">
    <h2 class="text-center mb-5 brand-text" style="font-size: 2.5rem;">Khám Phá Sản Phẩm</h2>
    <div class="row">
        <?php
        $sql = "SELECT ID_SanPham, TenSanPham, Gia, HinhAnh FROM SanPham LIMIT 8";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $anhHienThi = 'images/' . $row['HinhAnh'];
                if(!file_exists($anhHienThi)) $anhHienThi = 'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?w=400&h=500&fit=crop';
        ?>
            <div class="col-md-3 col-sm-6 mb-4">
                <a href="chitiet.php?id=<?php echo $row['ID_SanPham']; ?>" class="text-decoration-none text-dark">
                    <div class="card product-card h-100 text-center">
                        <img src="<?php echo $anhHienThi; ?>" class="card-img-top product-img" alt="Ảnh SP">
                        <div class="card-body d-flex flex-column justify-content-between p-3">
                            <h6 class="product-title"><?php echo $row['TenSanPham']; ?></h6>
                            <p class="product-price mt-2 mb-0"><?php echo number_format($row['Gia'], 0, ',', '.'); ?> đ</p>
                        </div>
                    </div>
                </a>
            </div>
        <?php 
            } 
        } else {
            echo "<div class='alert alert-warning text-center w-100'>Chưa có sản phẩm nào.</div>";
        }
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>