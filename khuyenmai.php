<?php 
include 'header.php'; 

$sp = isset($_GET['sp']) ? $_GET['sp'] : 'bidao';
$id_that = 0; 
$detail_Img = '';
$giaMoiInt = 0;
$detail_Heading = '';
$detail_Price = '';
$detail_Text = '';

$is_2010 = ($sp == '2010');

// ==========================================
// 1. CẤU HÌNH BANNER TRÊN CÙNG (TRẢ VỀ NGUYÊN BẢN 100% CỦA ĐẠI CA)
// ==========================================
if ($sp == 'drceutics') {
    $hero_BgColor  = '#f5f5f5'; 
    $hero_ImgLeft  = 'images/combo2.jpg'; 
    $hero_Subtitle = 'DR.CEUTICS';
    $hero_Title    = 'Sản phẩm hỗ trợ<br>ngăn ngừa và<br>cải thiện mụn';
    $hero_Desc     = 'Bộ đôi tinh chất BHA 2% + Zinc 1% và Niacinamide 12% + NAG 5% giúp làm sạch sâu lỗ chân lông, kiểm soát bã nhờn, làm dịu da và mờ thâm mụn chuẩn y khoa.';
    $tim_sp = $conn->query("SELECT * FROM SanPham WHERE TenSanPham LIKE '%Combo%' AND (TenSanPham LIKE '%Dr.Ceutics%' OR ThuongHieu = 'Dr.Ceutics') LIMIT 1");

} elseif ($sp == 'emmie') {
    $hero_BgColor  = '#005a5a'; 
    $hero_ImgLeft  = 'images/combo3.webp'; 
    $hero_Subtitle = 'EMMIÉ BY HAPPY SKIN';
    $hero_Title    = 'Combo 2 Hộp 10<br>Mặt Nạ Vi Sợi<br>Phục Hồi & Trắng Da';
    $hero_Desc     = 'Tự hào đạt giải thưởng công nghệ đột phá ELLE Beauty Awards 2026. Trải nghiệm siêu tinh chất 28Gx20 với bộ đôi mặt nạ Cherimoya và Carica giúp phục hồi chuyên sâu và dưỡng trắng rạng rỡ.';
    $tim_sp = $conn->query("SELECT * FROM SanPham WHERE TenSanPham LIKE '%Combo%' AND (TenSanPham LIKE '%Mặt Nạ%' OR ThuongHieu = 'Emmié') LIMIT 1");

} elseif ($sp == 'skinheritage') {
    $hero_BgColor  = '#eef3e8'; 
    $hero_ImgLeft  = 'images/combo4.png'; 
    $hero_Subtitle = 'SKIN HERITAGE x HASAKI';
    $hero_Title    = 'Combo Serum Hạ Long<br>& Toner Sơn Đoòng';
    $hero_Desc     = 'Mua Combo tinh chất cấp ẩm sâu tặng ngay bộ quà trị giá 267K gồm: 2 Nước tẩy trang Phú Xuân 55ml và 1 Sữa rửa mặt Bến Thành 40ml. Số lượng quà tặng có hạn, nhanh tay lên!';
    $tim_sp = $conn->query("SELECT * FROM SanPham WHERE TenSanPham LIKE '%Combo%' AND (TenSanPham LIKE '%Skin Heritage%' OR TenSanPham LIKE '%Hạ Long%') LIMIT 1");

} elseif ($sp == 'sen') {
    $hero_BgColor  = '#fdf2f2'; 
    $hero_ImgLeft  = 'images/khuyenmai.png'; 
    $hero_Subtitle = 'ƯU ĐÃI ĐỘC QUYỀN';
    $hero_Title    = 'Sữa Chống Nắng<br>Bảo Vệ Toàn Diện';
    $hero_Desc     = 'Bảo vệ làn da trước tia UV và môi trường. Nâng tông tự nhiên, bảo vệ cao cho da rất nhạy cảm. Tặng kèm bộ sample dùng thử cho mọi đơn hàng.';
    $tim_sp = $conn->query("SELECT * FROM SanPham WHERE TenSanPham LIKE '%Sữa Chống Nắng%' AND TenSanPham LIKE '%Sen%' LIMIT 1");

} elseif ($sp == '2010') {
    // TAB 20/10 SẼ DÙNG GIAO DIỆN RIÊNG BIỆT BÊN DƯỚI
    $tim_sp = null;

} else {
    // TRẢ LẠI ĐÚNG BANNER 1 CHO COCOON 
    $hero_BgColor  = '#88bde1';
    $hero_ImgLeft  = 'images/banner1.png'; 
    $hero_Subtitle = 'MỚI! GEL BÍ ĐAO RỬA MẶT 500ML';
    $hero_Title    = 'Sạch sâu không<br>khô căng<br>Giảm dầu & mụn';
    $hero_Desc     = 'Công thức nổi bật với chiết xuất bí đao, Betaine và Vitamin B5, giúp duy trì độ ẩm và làm dịu da trong quá trình làm sạch.';
    $tim_sp = $conn->query("SELECT * FROM SanPham WHERE TenSanPham LIKE '%Combo Ưu đãi%' OR (TenSanPham LIKE '%Combo%' AND TenSanPham LIKE '%Bí Đao%') LIMIT 1");
}

// ==========================================
// 2. LẤY DỮ LIỆU ĐỘNG TỪ DATABASE CHO GIAO DIỆN CŨ (CÁC HÃNG)
// ==========================================
if(!$is_2010 && isset($tim_sp) && $tim_sp && $tim_sp->num_rows > 0) {
    $row_sp = $tim_sp->fetch_assoc();
    $id_that = $row_sp['ID_SanPham'];
    $giaMoiInt = isset($row_sp['Gia']) ? $row_sp['Gia'] : (isset($row_sp['GiaBan']) ? $row_sp['GiaBan'] : 0);
    
    $giaGocAo = $giaMoiInt * 1.4; 
    
    $detail_Price = number_format($giaMoiInt, 0, ',', '.') . ' đ';
    $detail_PriceOld = number_format($giaGocAo, 0, ',', '.') . ' đ';
    
    $detail_Img = 'images/' . $row_sp['HinhAnh']; 
    $detail_Heading = $row_sp['TenSanPham'];
    $detail_Text = nl2br($row_sp['MoTa']); 
}
?>

<?php if ($is_2010): ?>

<!-- ==========================================
     [TAB MỚI] GIAO DIỆN SỰ KIỆN 20/10 ĐỘC LẬP
=========================================== -->
<style>
    .gift-box { background-color: #fff9fa; border: 2px dashed #f5a3b7; border-radius: 8px; padding: 20px; margin-bottom: 35px; }
    .btn-2010 { background: linear-gradient(135deg, #d92d53 0%, #b21f3f 100%); color: white; border: none; }
    .btn-2010:hover { background: linear-gradient(135deg, #b21f3f 0%, #8a152e 100%); color: white; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(217, 45, 83, 0.4); }
</style>

<!-- Banner To Trên Cùng -->
<div class="container-fluid text-center" style="background-color: #fdfbf7; padding: 50px 15px;">
    <img src="images/banerskin.jpg" class="img-fluid rounded shadow" style="max-width: 1000px; width: 100%; height: auto;" alt="Banner 20/10">
</div>

<div class="container-fluid" style="background-color: #fcfbf8; padding: 60px 0 80px 0;">
    <div class="container">
        
        <!-- TIÊU ĐỀ -->
        <div class="row text-center mb-5">
            <div class="col-12">
                <span class="badge bg-danger mb-3 px-3 py-2" style="font-size: 0.85rem; letter-spacing: 1px; border-radius: 20px;">
                    <i class="bi bi-stars"></i> ĐẶC QUYỀN PHÁI ĐẸP
                </span>
                <h1 class="brand-text fw-bold mb-4" style="font-size: 3rem; color: #333;">BST Toner Pad Cổ Phục</h1>
            </div>
            <div class="col-12 d-flex justify-content-center mb-4">
                <div class="gift-box text-start w-100" style="max-width: 800px; background-color: #fff;">
                    <h6 class="fw-bold mb-3" style="color: #d92d53;"><i class="bi bi-gift-fill me-2"></i> QUÀ TẶNG KÈM DỊP LỄ 20/10:</h6>
                    <ul class="mb-0" style="color: #555; line-height: 1.8;">
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Tặng 1 Túi Tote VietSkin phiên bản giới hạn.</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Voucher giảm thêm 100K cho đơn hàng tiếp theo.</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Freeship toàn quốc mọi đơn hàng đặt trong hôm nay.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- KHỐI 3 SẢN PHẨM LẺ -->
        <div class="row justify-content-center g-4 mb-5">
            <?php
            $sql_3sp = "SELECT * FROM SanPham WHERE ThuongHieu = 'Skin Heritage' AND TenSanPham LIKE '%Toner Pad%' AND TenSanPham NOT LIKE '%Combo%' ORDER BY ID_SanPham ASC LIMIT 3";
            $res_3sp = $conn->query($sql_3sp);
            $db_pads = [];
            
            if ($res_3sp && $res_3sp->num_rows > 0) {
                while($r = $res_3sp->fetch_assoc()) {
                    $db_pads[] = $r;
                }
            }

            $toner_pads_fallback = [
                ['id' => 42, 'name' => 'Toner Pad Nhật Bình 63 miếng làm dịu và cấp ẩm', 'img' => 'images/tonergreen.png'],
                ['id' => 43, 'name' => 'Toner Pad Tứ Thân 63 miếng cấp ẩm đa tầng da', 'img' => 'images/tonerblu.png'],
                ['id' => 44, 'name' => 'Toner Pad Ngũ Thân 63 miếng dưỡng ẩm và tẩy tế bào chết', 'img' => 'images/tonerpink.png']
            ];

            for ($i = 0; $i < 3; $i++) {
                $p_id = isset($db_pads[$i]) ? $db_pads[$i]['ID_SanPham'] : $toner_pads_fallback[$i]['id'];
                $p_name = isset($db_pads[$i]) ? $db_pads[$i]['TenSanPham'] : $toner_pads_fallback[$i]['name'];
                $p_price = 280000; 
                
                // Hỗ trợ cả trường Gia và GiaBan
                $p_old = 399000;
                if (isset($db_pads[$i]['Gia'])) {
                    $p_old = $db_pads[$i]['Gia'];
                } elseif (isset($db_pads[$i]['GiaBan'])) {
                    $p_old = $db_pads[$i]['GiaBan'];
                }

                $p_img = isset($db_pads[$i]) ? 'images/'.$db_pads[$i]['HinhAnh'] : $toner_pads_fallback[$i]['img'];
                
                $name_parts = explode('miếng', $p_name);
                $short_name = $name_parts[0] . 'miếng';
                $p_desc = isset($name_parts[1]) ? trim($name_parts[1]) : 'BST Cổ Phục Việt';
            ?>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="p-4 d-flex justify-content-center align-items-center" style="background-color: #faebd7; height: 280px;">
                            <img src="<?php echo $p_img; ?>" onerror="this.src='https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=400&h=400&fit=crop'" class="img-fluid" style="max-height: 220px; object-fit: contain;">
                        </div>
                        <div class="card-body p-4 d-flex flex-column justify-content-between text-center">
                            <div>
                                <h5 class="fw-bold mb-2 text-dark" style="font-size: 1.15rem; line-height: 1.4;"><?php echo $short_name; ?></h5>
                                <p class="text-muted small mb-3 text-capitalize"><?php echo $p_desc; ?></p>
                                <div class="d-flex justify-content-center align-items-center mb-4">
                                    <span class="fs-4 fw-bold text-danger me-3"><?php echo number_format($p_price, 0, ',', '.'); ?> đ</span>
                                    <span class="text-decoration-line-through text-muted"><?php echo number_format($p_old, 0, ',', '.'); ?> đ</span>
                                </div>
                            </div>
                            <form action="giohang.php" method="POST" class="mt-auto">
                                <input type="hidden" name="id_sp" value="<?php echo $p_id; ?>">
                                <input type="hidden" name="ten_sp" value="<?php echo $p_name; ?>">
                                <input type="hidden" name="gia_sp" value="<?php echo $p_price; ?>">
                                <input type="hidden" name="hinh_anh" value="<?php echo $p_img; ?>">
                                <input type="hidden" name="so_luong" value="1">
                                <button type="submit" name="them_gio_hang" class="btn btn-2010 w-100 rounded-3 py-2 fw-bold shadow-sm" style="letter-spacing: 0.5px;">
                                    MUA NGAY <i class="bi bi-bag-heart-fill ms-1"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- KHỐI ĐẶC BIỆT: COMBO MUA 3 TẶNG 1 ĐÃ SỬA LẠI ĐÚNG DATABASE -->
        <?php
            // Lấy chính xác Siêu Combo ID 45 từ Database để giỏ hàng không bị lỗi Hết Hàng
            $sql_combo = "SELECT * FROM SanPham WHERE TenSanPham LIKE '%Siêu Combo 3 Toner Pad%' LIMIT 1";
            $res_combo = $conn->query($sql_combo);
            
            $combo_id = 45; // ID mặc định đại ca cung cấp
            $combo_name = "Siêu Combo 3 Toner Pad + Tặng Mask 20/10";
            $combo_price = 840000;
            $combo_img = "images/combotoner.jfif";
            
            if ($res_combo && $res_combo->num_rows > 0) {
                $row_combo = $res_combo->fetch_assoc();
                $combo_id = $row_combo['ID_SanPham'];
                $combo_name = $row_combo['TenSanPham'];
                
                if(isset($row_combo['Gia'])) { 
                    $combo_price = $row_combo['Gia']; 
                } elseif(isset($row_combo['GiaBan'])) { 
                    $combo_price = $row_combo['GiaBan']; 
                }
                $combo_img = 'images/' . $row_combo['HinhAnh'];
            }
        ?>
        <div class="row mt-5 pt-4">
            <div class="col-12">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="background: linear-gradient(135deg, #fff9fa 0%, #faebd7 100%);">
                    <div class="row g-0 align-items-center">
                        
                        <div class="col-md-5 p-4 text-center">
                            <!-- Ảnh chính là Combo lấy từ Database của đại ca -->
                            <img src="<?php echo $combo_img; ?>" onerror="this.src='images/combotoner.jfif'" class="img-fluid rounded" style="max-height: 280px; object-fit: contain; filter: drop-shadow(0px 10px 15px rgba(0,0,0,0.1));" alt="Combo 3 Toner Pad">
                            
                            <!-- Ảnh mặt nạ Quà Tặng được nhét xuống dưới với khung phân biệt rõ ràng -->
                            <div class="mt-4 p-2 bg-white rounded-3 shadow-sm d-inline-block border border-danger">
                                <div class="text-danger fw-bold small mb-1"><i class="bi bi-gift-fill"></i> QUÀ TẶNG KÈM</div>
                                <img src="images/tang20_10.png" class="img-fluid" style="max-height: 80px; object-fit: contain;" alt="Mặt nạ tặng kèm">
                            </div>
                        </div>

                        <div class="col-md-7 p-5">
                            <span class="badge bg-danger mb-3 px-3 py-2" style="font-size: 0.9rem; letter-spacing: 1px;"><i class="bi bi-fire"></i> SIÊU COMBO DỊP LỄ</span>
                            <h2 class="fw-bold mb-2" style="color: #333; font-size: 2.2rem;"><?php echo $combo_name; ?></h2>
                            <h4 class="text-danger fw-bold mb-4" style="line-height: 1.4;">MUA 3 SẢN PHẨM<br>TẶNG NGAY 1 HỘP MẶT NẠ CAO CẤP</h4>
                            
                            <div class="d-flex align-items-center mb-4">
                                <span class="fs-1 fw-bold text-danger me-4"><?php echo number_format($combo_price, 0, ',', '.'); ?> đ</span>
                                <span class="text-decoration-line-through text-muted fs-4">1.197.000 đ</span>
                            </div>
                            
                            <div class="text-muted mb-4" style="font-size: 1.05rem;">
                                <p class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Trọn bộ 3 sản phẩm: Nhật Bình, Tứ Thân, Ngũ Thân.</p>
                                <p class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> <strong>Quà tặng:</strong> 1 Hộp mặt nạ dưỡng da chuyên sâu (Như hình đính kèm).</p>
                                <p class="mb-0"><i class="bi bi-check-circle-fill text-success me-2"></i> Miễn phí giao hàng toàn quốc.</p>
                            </div>
                            
                            <!-- Đã điền ID 45 chuẩn từ Database để giỏ hàng duyệt qua được bước Tồn Kho -->
                            <form action="giohang.php" method="POST">
                                <input type="hidden" name="id_sp" value="<?php echo $combo_id; ?>">
                                <input type="hidden" name="ten_sp" value="<?php echo $combo_name; ?>">
                                <input type="hidden" name="gia_sp" value="<?php echo $combo_price; ?>">
                                <input type="hidden" name="hinh_anh" value="<?php echo $combo_img; ?>">
                                <input type="hidden" name="so_luong" value="1">
                                <button type="submit" name="them_gio_hang" class="btn btn-2010 px-5 py-3 fw-bold shadow" style="font-size: 1.15rem; border-radius: 8px;">
                                    CHỐT COMBO & NHẬN QUÀ <i class="bi bi-gift-fill ms-2"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php else: ?>

<!-- ==========================================
     [CÁC TRANG CÒN LẠI] GIAO DIỆN CŨ NGUYÊN BẢN 100% CỦA ĐẠI CA
=========================================== -->
<div class="container-fluid p-0">
    <div class="row g-0" style="min-height: 550px; background-color: #fdfbf7;">
        <div class="col-md-6 p-0 d-flex justify-content-center align-items-center" style="background-color: <?php echo $hero_BgColor; ?>; min-height: 550px;">
            <img src="<?php echo $hero_ImgLeft; ?>" onerror="this.src='images/banner1.jfif'" class="w-100" style="height: auto; object-fit: cover;" alt="Banner Khuyến Mãi">
        </div>
        
        <div class="col-md-6 d-flex align-items-center p-5">
            <div style="max-width: 520px; margin: 0 auto;">
                <h5 class="text-uppercase mb-3" style="letter-spacing: 1px; font-size: 1.1rem; color: #222; font-weight: 500;"><?php echo $hero_Subtitle; ?></h5>
                <h1 class="brand-text mb-4" style="font-size: 3.8rem; line-height: 1.15; color: #111; font-weight: 500; letter-spacing: -1px;">
                    <?php echo $hero_Title; ?>
                </h1>
                <p class="mb-5" style="font-size: 1rem; line-height: 1.7; color: #444;">
                    <?php echo $hero_Desc; ?>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid" style="background-color: #fcfbf8; min-height: 70vh; padding: 100px 0;">
    <div class="container">
        <div class="row align-items-center">
            
            <?php if($id_that > 0): ?>
                <div class="col-md-6 d-flex justify-content-center mb-5 mb-md-0 px-lg-4">
                    <img src="<?php echo $detail_Img; ?>" onerror="this.src='https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&h=800&fit=crop'" class="img-fluid rounded shadow-sm" style="max-width: 450px; object-fit: contain;" alt="<?php echo $detail_Heading; ?>">
                </div>

                <div class="col-md-6 px-lg-5">
                    <h1 class="brand-text mb-4" style="font-size: 3.5rem; font-weight: 500; color: #333;"><?php echo $detail_Heading; ?></h1>
                    
                    <div class="d-flex align-items-baseline mb-4">
                        <h2 style="font-size: 2.2rem; font-weight: 600; margin-right: 15px; color: #d9534f;"><?php echo $detail_Price; ?></h2>
                        <span class="text-decoration-line-through text-muted" style="font-size: 1.3rem; margin-bottom: 2px;"><?php echo $detail_PriceOld; ?></span>
                    </div>
                    
                    <div style="font-size: 1.1rem; color: #444; line-height: 1.8; margin-bottom: 40px;">
                        <?php echo $detail_Text; ?>
                    </div>
                    
                    <form action="giohang.php" method="POST">
                        <input type="hidden" name="id_sp" value="<?php echo $id_that; ?>">
                        <input type="hidden" name="ten_sp" value="<?php echo $detail_Heading; ?>">
                        <input type="hidden" name="gia_sp" value="<?php echo $giaMoiInt; ?>">
                        <input type="hidden" name="hinh_anh" value="<?php echo $detail_Img; ?>">
                        <input type="hidden" name="so_luong" value="1">
                        
                        <button type="submit" name="them_gio_hang" class="btn btn-dark d-inline-flex justify-content-between align-items-center shadow" style="border-radius: 4px; padding: 15px 30px; font-weight: 500; font-size: 1.1rem; letter-spacing: 0.5px; width: 250px;">
                            <span>THÊM VÀO GIỎ</span>
                            <i class="bi bi-cart-plus fs-5"></i>
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-box-seam text-muted" style="font-size: 4rem;"></i>
                    <h3 class="mt-4 text-muted">Sản phẩm ưu đãi đang được cập nhật</h3>
                    <p class="text-muted">Không tìm thấy sản phẩm. Hãy kiểm tra lại tên sản phẩm trong Admin.</p>
                    <a href="index.php" class="btn btn-outline-dark mt-3">Quay lại trang chủ</a>
                </div>
            <?php endif; ?>
            
        </div>
    </div>
</div>

<?php endif; ?>

<?php include 'footer.php'; ?>