<?php 
include 'header.php'; 

// Truy vấn Top các sản phẩm bán chạy nhất từ Database
$sql_banchay = "SELECT sp.*, COALESCE(SUM(ct.SoLuong), 0) AS TongBan 
                FROM SanPham sp 
                LEFT JOIN ChiTietDonHang ct ON sp.ID_SanPham = ct.ID_SanPham 
                GROUP BY sp.ID_SanPham 
                ORDER BY TongBan DESC 
                LIMIT 6";
$res_banchay = $conn->query($sql_banchay);
?>

<!-- ==========================================
     TOP BANNER SALE 10/10 (CHUYỂN TRANG TRONG CÙNG TAB)
=========================================== -->
<div class="top-sale-banner" style="background: linear-gradient(90deg, #d92d53 0%, #8a152e 100%); color: white; padding: 12px 15px; position: relative; z-index: 1000; box-shadow: 0 4px 10px rgba(0,0,0,0.15);">
    <div class="container d-flex flex-column flex-md-row justify-content-center align-items-center text-center">
        <div class="d-flex align-items-center mb-2 mb-md-0">
            <span class="badge bg-warning text-dark fw-bold fs-6 me-3" style="animation: pulse 1.5s infinite;">🔥 10/10 SIÊU SALE KÉP</span>
            <span class="fw-bold" style="letter-spacing: 1px; font-size: 0.95rem;">BÙNG NỔ ƯU ĐÃI - FREESHIP MỌI ĐƠN HÀNG DUY NHẤT HÔM NAY!</span>
        </div>
        <!-- Đã xóa target="_blank" để giữ nguyên trong 1 tab -->
        <a href="sale1010.php" class="btn btn-sm btn-light fw-bold ms-md-4 mt-2 mt-md-0" style="color: #d92d53; border-radius: 20px; padding: 5px 20px; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
            Săn Ngay kẻo lỡ <i class="bi bi-caret-right-fill"></i>
        </a>
    </div>
</div>

<style>
    @keyframes pulse {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7); }
        50% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
    }

    /* STYLE CHO SLIDER & GIAN HÀNG CŨ */
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
    .brand-product-list { animation: fadeIn 0.5s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    /* STYLE CHO KHỐI BÁN CHẠY */
    .scroll-horizontal {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none; 
        -ms-overflow-style: none;  
        padding-bottom: 15px;
    }
    .scroll-horizontal::-webkit-scrollbar {
        display: none; 
    }
    .serif-font {
        font-family: 'Playfair Display', serif; 
    }

    /* STYLE CHO DÒNG CHỮ CHẠY (MARQUEE) CAO CẤP */
    .marquee-container {
        width: 100%;
        overflow: hidden;
        background-color: #111; 
        padding: 15px 0;
        position: relative;
        display: flex;
        align-items: center;
    }
    .marquee-content {
        display: inline-block;
        white-space: nowrap;
        animation: marquee-scroll 25s linear infinite;
        font-family: 'Playfair Display', serif;
        font-size: 1.1rem;
        color: #ebdccc; 
        letter-spacing: 3px;
        text-transform: uppercase;
    }
    .marquee-content span {
        margin: 0 40px;
    }
    @keyframes marquee-scroll {
        0% { transform: translateX(100vw); }
        100% { transform: translateX(-100%); }
    }
</style>

<!-- ==========================================
     1. KHU VỰC BANNER SLIDER (4 SLIDE - 4 THƯƠNG HIỆU)
=========================================== -->
<div id="mainHeroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="container-fluid p-0">
                <div class="row g-0" style="background-color: #fdfbf7; min-height: 550px;">
                    <div class="col-md-6 p-0 d-flex justify-content-center align-items-center" style="background-color: #88bde1;">
                        <img src="images/banner1.png" onerror="this.src='images/banner1.jfif'" class="w-100" style="height: auto; object-fit: cover;" alt="Cocoon">
                    </div>
                    <div class="col-md-6 d-flex align-items-center p-5">
                        <div style="max-width: 520px; margin: 0 auto;">
                            <h5 class="text-uppercase mb-3" style="letter-spacing: 1px; font-size: 1.1rem; color: #222; font-weight: 500;">COCOON VIỆT NAM</h5>
                            <h1 class="brand-text mb-4" style="font-size: 3.8rem; line-height: 1.15; color: #111; font-weight: 500; letter-spacing: -1px;">
                                Gel bí đao rửa<br>mặt 500ML<br>Sạch sâu, giảm<br>dầu & mụn
                            </h1>
                            <p class="mb-5" style="font-size: 1rem; line-height: 1.7; color: #444;">
                                Công thức nổi bật với chiết xuất bí đao, Betaine và Vitamin B5, giúp duy trì độ ẩm và làm dịu da trong quá trình làm sạch, hạn chế cảm giác khô căng sau khi rửa mặt.
                            </p>
                            <a href="khuyenmai.php?sp=bidao" class="btn btn-dark d-inline-flex justify-content-between align-items-center" style="background-color: #1c1c1c; border-radius: 0; padding: 15px 25px; font-weight: 500; font-size: 0.95rem; letter-spacing: 1px; width: 230px; border: none;">
                                <span>MUA COMBO NGAY</span>
                                <span>→</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="carousel-item">
            <div class="container-fluid p-0">
                <div class="row g-0" style="background-color: #ffffff; min-height: 550px;">
                    <div class="col-md-6 p-0 d-flex justify-content-center align-items-center" style="background-color: #f5f5f5;">
                        <img src="images/combo2.jpg" onerror="this.src='https://images.unsplash.com/photo-1629198688000-71f23e745b6e?w=800&h=800&fit=crop'" class="w-100" style="height: auto; object-fit: cover;" alt="Dr.Ceutics">
                    </div>
                    <div class="col-md-6 d-flex align-items-center p-5">
                        <div style="max-width: 520px; margin: 0 auto;">
                            <h5 class="text-uppercase mb-3" style="letter-spacing: 1px; font-size: 1.1rem; color: #5a3c22; font-weight: 600;">DR.CEUTICS</h5>
                            <h1 class="brand-text mb-4" style="font-size: 3.2rem; line-height: 1.25; color: #111; font-weight: 600; letter-spacing: -1px;">
                                Sản phẩm hỗ trợ<br>ngăn ngừa và<br>cải thiện mụn
                            </h1>
                            <p class="mb-5" style="font-size: 1rem; line-height: 1.7; color: #444;">
                                Bộ đôi tinh chất BHA 2% + Zinc 1% và Niacinamide 12% + NAG 5% giúp làm sạch sâu lỗ chân lông, kiểm soát bã nhờn, làm dịu da và mờ thâm mụn chuẩn y khoa.
                            </p>
                            <a href="khuyenmai.php?sp=drceutics" class="btn btn-dark d-inline-flex justify-content-between align-items-center" style="background-color: #5a3c22; border-radius: 0; padding: 15px 25px; font-weight: 500; font-size: 0.95rem; letter-spacing: 1px; width: 230px; border: none;">
                                <span>MUA COMBO NGAY</span>
                                <span>→</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="carousel-item">
            <div class="container-fluid p-0">
                <div class="row g-0" style="background-color: #f4f9f9; min-height: 550px;">
                    <div class="col-md-6 p-0 d-flex justify-content-center align-items-center" style="background-color: #005a5a;">
                        <img src="images/combo3.webp" onerror="this.src='https://images.unsplash.com/photo-1615397323204-7489a2eb2d48?w=800&h=800&fit=crop'" class="w-100" style="height: auto; object-fit: cover;" alt="Emmié">
                    </div>
                    <div class="col-md-6 d-flex align-items-center p-5">
                        <div style="max-width: 520px; margin: 0 auto;">
                            <h5 class="text-uppercase mb-3" style="letter-spacing: 1px; font-size: 1.1rem; color: #007676; font-weight: 600;">EMMIÉ BY HAPPY SKIN</h5>
                            <h1 class="brand-text mb-4" style="font-size: 3.2rem; line-height: 1.25; color: #111; font-weight: 600; letter-spacing: -1px;">
                                Combo 2 Hộp 10<br>Mặt Nạ Vi Sợi<br>Phục Hồi & Trắng Da
                            </h1>
                            <p class="mb-5" style="font-size: 1rem; line-height: 1.7; color: #444;">
                                Tự hào đạt giải thưởng công nghệ đột phá ELLE Beauty Awards 2026. Trải nghiệm siêu tinh chất 28Gx20 với bộ đôi mặt nạ Cherimoya và Carica giúp phục hồi chuyên sâu và dưỡng trắng rạng rỡ.
                            </p>
                            <a href="khuyenmai.php?sp=emmie" class="btn btn-dark d-inline-flex justify-content-between align-items-center" style="background-color: #007676; border-radius: 0; padding: 15px 25px; font-weight: 500; font-size: 0.95rem; letter-spacing: 1px; width: 230px; border: none;">
                                <span>MUA COMBO NGAY</span>
                                <span>→</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="carousel-item">
            <div class="container-fluid p-0">
                <div class="row g-0" style="background-color: #f4f5f0; min-height: 550px;">
                    <div class="col-md-6 p-0 d-flex justify-content-center align-items-center" style="background-color: #eef3e8;">
                        <img src="images/combo4.png" onerror="this.src='https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=800&h=800&fit=crop'" class="w-100" style="height: auto; object-fit: cover;" alt="Skin Heritage">
                    </div>
                    <div class="col-md-6 d-flex align-items-center p-5">
                        <div style="max-width: 520px; margin: 0 auto;">
                            <h5 class="text-uppercase mb-3" style="letter-spacing: 1px; font-size: 1.1rem; color: #1a4a38; font-weight: 600;">SKIN HERITAGE x HASAKI</h5>
                            <h1 class="brand-text mb-4" style="font-size: 3.2rem; line-height: 1.25; color: #111; font-weight: 600; letter-spacing: -1px;">
                                Combo Serum Hạ Long<br>& Toner Sơn Đoòng
                            </h1>
                            <p class="mb-5" style="font-size: 1rem; line-height: 1.7; color: #444;">
                                Mua Combo tinh chất cấp ẩm sâu tặng ngay bộ quà trị giá 267K gồm: 2 Nước tẩy trang Phú Xuân 55ml và 1 Sữa rửa mặt Bến Thành 40ml.
                            </p>
                            <a href="khuyenmai.php?sp=skinheritage" class="btn btn-dark d-inline-flex justify-content-between align-items-center" style="background-color: #1a4a38; border-radius: 0; padding: 15px 25px; font-weight: 500; font-size: 0.95rem; letter-spacing: 1px; width: 230px; border: none;">
                                <span>MUA COMBO NGAY</span>
                                <span>→</span>
                            </a>
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
</div>

<!-- ==========================================
     2. KHU VỰC DANH SÁCH SẢN PHẨM THEO HÃNG
=========================================== -->
<div class="container my-5 pt-5" style="min-height: 600px;">
    <h2 class="text-center mb-5 brand-text" style="font-size: 2.5rem;" id="danhMucTieuDe">Khám Phá Sản Phẩm Cocoon</h2>
    
    <?php
    $thuonghieu_list = ['Cocoon', 'Dr.Ceutics', 'Emmié', 'Skin Heritage'];
    
    foreach ($thuonghieu_list as $index =>$th) {
        $displayClass = ($index == 0) ? '' : 'd-none';
        echo "<div class='row brand-product-list $displayClass' id='brand-products-$index'>";
        
        $sql = "SELECT ID_SanPham, TenSanPham, Gia, HinhAnh FROM SanPham WHERE ThuongHieu = '$th' LIMIT 8";
        $result = $conn->query($sql);

        if ($result &&$result->num_rows > 0) {
            while ($row =$result->fetch_assoc()) {
                $anhHienThi = 'images/' .$row['HinhAnh'];
                if(!file_exists($anhHienThi))$anhHienThi = 'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?w=400&h=500&fit=crop';
        ?>
                <div class="col-md-3 col-sm-6 mb-4">
                    <a href="chitiet.php?id=<?php echo $row['ID_SanPham']; ?>" class="text-decoration-none text-dark">
                        <div class="card product-card h-100 text-center border-0 shadow-sm" style="transition: 0.3s;">
                            <div class="p-4" style="background-color: #f8f9fa;">
                                <img src="<?php echo $anhHienThi; ?>" class="card-img-top product-img" style="object-fit: contain; height: 250px;" alt="Ảnh SP">
                            </div>
                            <div class="card-body d-flex flex-column justify-content-between p-3">
                                <h6 class="product-title" style="font-size: 1rem; color: #333; line-height: 1.5;"><?php echo $row['TenSanPham']; ?></h6>
                                <p class="product-price mt-2 mb-0 fw-bold" style="color: #111; font-size: 1.1rem;"><?php echo number_format($row['Gia'], 0, ',', '.'); ?> đ</p>
                            </div>
                        </div>
                    </a>
                </div>
        <?php 
            } 
        } else {
            echo "<div class='alert alert-warning text-center w-100'>Gian hàng $th đang cập nhật sản phẩm.</div>";
        }
        echo "</div>"; 
    }
    ?>
</div>

<!-- ==========================================
     3. BANNER VIDEO (SỮA CHỐNG NẮNG)
=========================================== -->
<div class="container-fluid my-5 p-0">
    <div class="row g-0" style="background-color: #fdf2f2; min-height: 400px;">
        <div class="col-md-6 p-5 d-flex flex-column justify-content-center text-center text-md-start">
            <h5 class="text-danger fw-bold mb-3" style="letter-spacing: 2px;">ƯU ĐÃI ĐỘC QUYỀN</h5>
            <h2 class="display-5 fw-bold mb-4" style="color: #333;">Sữa Chống Nắng<br>Bảo Vệ Toàn Diện</h2>
            <p class="lead mb-4" style="color: #555;">Bảo vệ làn da trước tia UV và môi trường. Đặt hàng ngay hôm nay để nhận ưu đãi đặc biệt và quà tặng kèm cực chất.</p>
            <div>
                <a href="khuyenmai.php?sp=sen" class="btn btn-dark px-4 py-3" style="border-radius: 0; letter-spacing: 1px; font-weight: 500;">
                    SĂN DEAL NGAY →
                </a>
            </div>
        </div>
        <div class="col-md-6 p-0 position-relative" style="min-height: 400px; background-color: #000;">
            <video autoplay loop muted playsinline class="position-absolute top-0 start-0 w-100 h-100" style="object-fit: cover;">
                <source src="images/1.mp4" type="video/mp4">
            </video>
        </div>
    </div>
</div>

<!-- ==========================================
     4. KHỐI SẢN PHẨM BÁN CHẠY (VUỐT NGANG)
=========================================== -->
<div class="container-fluid py-5" style="background-color: #fdfbf7;">
    <div class="container py-4">
        <div class="row align-items-center">
            
            <div class="col-md-3 mb-5 mb-md-0 pe-md-4">
                <h1 class="serif-font fw-bold" style="font-size: 3.5rem; line-height: 1.1; color: #1c1c1c;">
                    Sản phẩm<br>
                    <span class="text-uppercase" style="font-size: 2.8rem;">Bán Chạy</span>
                </h1>
                <p class="text-muted mt-4" style="font-size: 0.95rem; line-height: 1.7;">
                    VietSkin tự hào khi các sản phẩm mà chúng tôi tạo ra mang đến những thay đổi tuyệt vời trên làn da, mái tóc của bạn.
                </p>
            </div>

            <div class="col-md-9">
                <div class="scroll-horizontal">
                    <?php 
                    if ($res_banchay &&$res_banchay->num_rows > 0) {
                        while ($row =$res_banchay->fetch_assoc()) {
                            $id_sp =$row['ID_SanPham'];
                            $ten_sp =$row['TenSanPham'];
                            $gia_sp = number_format($row['Gia'], 0, ',', '.') . ' đ';
                            
                            $anh_sp = 'images/' .$row['HinhAnh'];
                            if (!file_exists($anh_sp) || empty($row['HinhAnh'])) {$anh_sp = 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=400&h=400&fit=crop';
                            }

                            $mo_ta_ngan = mb_substr(strip_tags($row['MoTa']), 0, 45) . '...';
                    ?>
                    <div class="col-10 col-sm-6 col-lg-4 flex-shrink-0 pe-3 pe-lg-4">
                        <div class="card h-100 border-0 bg-transparent">
                            <div class="card-img-top d-flex align-items-center justify-content-center" style="background-color: #f4f0e9; height: 280px; border-radius: 6px;">
                                <img src="<?php echo $anh_sp; ?>" class="img-fluid" style="max-height: 220px; object-fit: contain; mix-blend-mode: multiply;" alt="<?php echo $ten_sp; ?>">
                            </div>
                            <div class="card-body px-0 pb-0 d-flex justify-content-between align-items-end">
                                <div class="pe-2">
                                    <h6 class="card-title fw-bold mb-1 text-dark" style="font-size: 0.95rem; line-height: 1.4;">
                                        <?php echo $ten_sp; ?>
                                    </h6>
                                    <p class="text-muted text-uppercase mb-3" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                        <?php echo $mo_ta_ngan; ?>
                                    </p>
                                    <div class="fw-bold text-dark" style="font-size: 1rem;">
                                        <?php echo $gia_sp; ?>
                                    </div>
                                    <small class="text-muted" style="font-size: 0.75rem;">Đã bán <?php echo $row['TongBan']; ?></small>
                                </div>
                                <form action="giohang.php" method="POST" class="m-0">
                                    <input type="hidden" name="id_sp" value="<?php echo $id_sp; ?>">
                                    <input type="hidden" name="ten_sp" value="<?php echo $ten_sp; ?>">
                                    <input type="hidden" name="gia_sp" value="<?php echo $row['Gia']; ?>">
                                    <input type="hidden" name="hinh_anh" value="<?php echo $anh_sp; ?>">
                                    <input type="hidden" name="so_luong" value="1">
                                    <button type="submit" name="them_gio_hang" class="btn border-0 shadow-sm" style="background-color: #ebdccc; border-radius: 4px; padding: 10px 12px; transition: 0.2s;">
                                        <i class="bi bi-bag-plus text-dark"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php 
                        }
                    } 
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================
     5. BANNER SALE ĐẶC BIỆT NẰM DƯỚI 
=========================================== -->
<div class="container-fluid text-center text-white" style="background-image: linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.65)), url('images/nen.png'); background-size: cover; background-position: center; background-repeat: no-repeat; padding: 75px 15px;">
    <h5 class="text-uppercase mb-3" style="letter-spacing: 2px; color: #ebdccc; font-weight: 600;">Mừng Ngày Phụ Nữ Việt Nam</h5>
    <h1 class="serif-font fw-bold mb-3" style="font-size: 3rem; letter-spacing: 1px; color: #fff;">
        ƯU ĐÃI ĐẶC QUYỀN 20/10
    </h1>
    <p class="mb-5 mx-auto" style="font-size: 1.15rem; line-height: 1.6; opacity: 0.95; max-width: 700px;">
        Trao gửi yêu thương đến phái đẹp với mức giảm giá lên đến 50% cho các dòng mỹ phẩm cao cấp. Tặng kèm túi Tote siêu xinh và Voucher 100K cho mọi đơn hàng từ 500.000đ.
    </p>
    <a href="khuyenmai.php?sp=2010" class="btn rounded-0 px-5 py-3 fw-bold shadow-sm" style="background-color: #ebdccc; color: #111; letter-spacing: 1px; border: none; transition: 0.3s; font-size: 1.05rem;">
        SĂN QUÀ NGAY
    </a>
</div>

<!-- ==========================================
     6. KHỐI CHỮ CHẠY CAO CẤP (MARQUEE)
=========================================== -->
<div class="marquee-container">
    <div class="marquee-content">
        <span>✨ VIETSKIN COSMETICS - TINH HOA MỸ PHẨM VIỆT</span>
        <span>✨ CAM KẾT CHÍNH HÃNG 100%</span>
        <span>✨ CHĂM SÓC DA CHUẨN CHUYÊN GIA</span>
        <span>✨ LƯU GIỮ NÉT THANH XUÂN RẠNG NGỜI</span>
        <span>✨ FREESHIP TOÀN QUỐC CHO ĐƠN TỪ 500K</span>
        <span>✨ VIETSKIN COSMETICS - TINH HOA MỸ PHẨM VIỆT</span>
        <span>✨ CAM KẾT CHÍNH HÃNG 100%</span>
        <span>✨ CHĂM SÓC DA CHUẨN CHUYÊN GIA</span>
        <span>✨ LƯU GIỮ NÉT THANH XUÂN RẠNG NGỜI</span>
        <span>✨ FREESHIP TOÀN QUỐC CHO ĐƠN TỪ 500K</span>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myCarousel = document.getElementById('mainHeroCarousel');
        var tieuDe = document.getElementById('danhMucTieuDe');
        var tenCacHang = ['Cocoon', 'Dr.Ceutics', 'Emmié by Happy Skin', 'Skin Heritage'];

        myCarousel.addEventListener('slide.bs.carousel', function (e) {
            var nextIndex = e.to; 
            tieuDe.innerHTML = "Khám Phá Sản Phẩm " + tenCacHang[nextIndex];

            var allLists = document.querySelectorAll('.brand-product-list');
            allLists.forEach(function(list) {
                list.classList.add('d-none');
            });
            
            var activeList = document.getElementById('brand-products-' + nextIndex);
            if(activeList) {
                activeList.classList.remove('d-none');
            }
        });
    });
</script>

<?php include 'footer.php'; ?>