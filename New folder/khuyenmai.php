<?php 
include 'header.php'; 

$sp = isset($_GET['sp']) ? $_GET['sp'] : 'bidao';

// Bổ sung biến $giaMoiInt để truyền số nguyên gốc vào giỏ hàng
if ($sp == 'sen') {
    $hero_BgColor  = '#f9659a'; 
    $hero_ImgLeft  = 'https://images.unsplash.com/photo-1522337660859-02fbefca4702?w=800&h=800&fit=crop';
    $hero_Subtitle = 'SỮA CHỐNG NẮNG VÔ CƠ SEN HẬU GIANG';
    $hero_Title    = 'Nâng tông tự nhiên<br>Bảo vệ cao cho da<br>rất nhạy cảm';
    $hero_Desc     = 'Sữa chống nắng vô cơ Sen Hậu Giang kết hợp màng lọc vô cơ Zinc Oxide non-nano (>100 nm)...';
    
    $detail_Img    = 'images/sp-sen.png'; 
    $detail_Heading= 'Lý tưởng cho';
    $giaMoiInt     = 395000;
    $detail_Price  = '395.000 đ';
    $detail_Old    = '450.000 đ';
    $detail_Text   = 'Bảo vệ toàn diện, an toàn cho mẹ bầu và làn da mỏng manh nhất:';
    $detail_List   = '<li>Da nhạy cảm tới rất nhạy cảm</li><li>Da thường tới da khô</li><li>Da treatment hoặc vừa trải qua liệu trình thẩm mỹ</li>';
        
} elseif ($sp == 'vitc') {
    $hero_BgColor  = '#2b6de3'; 
    $hero_ImgLeft  = 'https://images.unsplash.com/photo-1615397323204-7489a2eb2d48?w=800&h=800&fit=crop';
    $hero_Subtitle = 'SERUM VITAMIN C 16%';
    $hero_Title    = 'Dưỡng sáng mờ thâm<br>Chống oxy hóa<br>mạnh mẽ';
    $hero_Desc     = 'Sự kết hợp hoàn hảo giữa Vitamin C tinh khiết và các thành phần chống oxy hóa mạnh mẽ giúp da sáng mịn.';
    
    $detail_Img    = 'images/sp-vitc.png'; 
    $detail_Heading= 'Combo Ưu Đãi Độc Quyền';
    $giaMoiInt     = 350000;
    $detail_Price  = '350.000 đ';
    $detail_Old    = '420.000 đ';
    $detail_Text   = 'Mua 1 Serum Vitamin C 16% tặng ngay bộ quà tặng chăm sóc chuyên sâu:';
    $detail_List   = '<li>1 Kem dưỡng phục hồi B5 15g</li><li>2 Mặt nạ sinh học B5 Emmié cấp ẩm</li><li>Miễn phí vận chuyển toàn quốc</li>';
        
} else {
    $hero_BgColor  = '#76a6cf';
    $hero_ImgLeft  = 'https://images.unsplash.com/photo-1498622838385-cb47ce5df121?w=800&h=800&fit=crop';
    $hero_Subtitle = 'MỚI! GEL BÍ ĐAO RỬA MẶT 500ML';
    $hero_Title    = 'Sạch sâu không<br>khô căng<br>Giảm dầu & mụn';
    $hero_Desc     = 'Công thức nổi bật với chiết xuất bí đao, Betaine và Vitamin B5, giúp duy trì độ ẩm và làm dịu da trong quá trình làm sạch.';
    
    $detail_Img    = 'images/combo-bidao.png'; 
    $detail_Heading= 'Combo Ưu đãi';
    $giaMoiInt     = 441000;
    $detail_Price  = '441.000 đ';
    $detail_Old    = '879.000 đ';
    $detail_Text   = 'Mua 1 Gel Bí Đao Rửa Mặt 500ml giá 441K<br>Tặng tổng quà trị giá 438K, gồm 6 sản phẩm:';
    $detail_List   = '<li>1 Nước Sen Hậu Giang 50ml</li><li>1 Tinh Chất Xịt Dưỡng Sen Hậu Giang 50ml</li><li>1 Nước Tẩy Trang Bí Đao 50ml</li><li>1 Sữa Chống Nắng Bí Đao 5ml</li><li>1 Gel Tắm Bí Đao 50ml</li>';
}

if(!file_exists($detail_Img)) {
    $detail_Img = 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&h=800&fit=crop';
}
?>

<div class="container-fluid p-0">
    <div class="row g-0">
        <div class="col-md-6 d-none d-md-block" style="background: url('<?php echo $hero_ImgLeft; ?>') center/cover no-repeat; min-height: 550px;"></div>
        <div class="col-md-6 d-flex align-items-center" style="background-color: <?php echo $hero_BgColor; ?>; min-height: 550px; padding: 10% 8%;">
            <div class="text-white">
                <h5 class="mb-3 text-uppercase" style="letter-spacing: 1px; font-weight: 500; opacity: 0.9;"><?php echo $hero_Subtitle; ?></h5>
                <h1 class="brand-text mb-4" style="font-size: 4rem; line-height: 1.1; font-weight: 600;"><?php echo $hero_Title; ?></h1>
                <p style="font-size: 1.05rem; line-height: 1.8; opacity: 0.9;"><?php echo $hero_Desc; ?></p>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid" style="background-color: #fcfbf8; min-height: 70vh; padding: 100px 0;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 text-center mb-5 mb-md-0">
                <img src="<?php echo $detail_Img; ?>" class="img-fluid" style="max-height: 550px; object-fit: contain;" alt="Sản Phẩm Ưu Đãi">
            </div>

            <div class="col-md-6 px-lg-5">
                <h1 class="brand-text mb-4" style="font-size: 3.8rem; font-weight: 500; color: #333;"><?php echo $detail_Heading; ?></h1>
                <div class="d-flex align-items-baseline mb-4">
                    <h2 style="font-size: 2.2rem; font-weight: 600; margin-right: 15px; color: #111;"><?php echo $detail_Price; ?></h2>
                    <span class="text-muted text-decoration-line-through" style="font-size: 1.4rem; color: #b89c7d !important;"><?php echo $detail_Old; ?></span>
                </div>
                <p style="font-size: 1.1rem; color: #333; margin-bottom: 15px;"><?php echo $detail_Text; ?></p>
                <ul style="list-style-type: none; padding-left: 0; line-height: 1.8; font-size: 1.05rem; color: #222; margin-bottom: 40px;">
                    <style>.custom-list li::before { content: "✓ "; color: #9a7b4f; font-weight: bold; margin-right: 5px; }</style>
                    <div class="custom-list"><?php echo $detail_List; ?></div>
                </ul>
                
                <!-- FORM GỬI DỮ LIỆU COMBO VÀO GIỎ HÀNG -->
                <form action="giohang.php" method="POST">
                    <input type="hidden" name="id_sp" value="combo_<?php echo $sp; ?>">
                    <input type="hidden" name="ten_sp" value="<?php echo $detail_Heading; ?>">
                    <input type="hidden" name="gia_sp" value="<?php echo $giaMoiInt; ?>">
                    <input type="hidden" name="hinh_anh" value="<?php echo $detail_Img; ?>">
                    <input type="hidden" name="so_luong" value="1">
                    
                    <button type="submit" name="them_gio_hang" class="btn btn-outline-dark d-inline-flex justify-content-between align-items-center" style="border-radius: 0; padding: 12px 25px; font-weight: 500; font-size: 1.1rem; letter-spacing: 0.5px; border-color: #ddd; width: 250px;">
                        <span>MUA NGAY</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>