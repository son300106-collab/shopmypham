<?php 
include 'header.php'; 
?>

<!-- STYLE CHO TRANG SALE 10/10 -->
<style>
    body { background-color: #fdfbf7; }
    .carousel-indicators [data-bs-target] {
        background-color: #d92d53;
        height: 4px;
        border-radius: 2px;
    }
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        padding: 20px;
    }
    
    /* NÚT MUA NGAY */
    .btn-mua-ngay {
        background-color: transparent;
        border: 1px solid #d1d1d1;
        color: #111;
        font-weight: 500;
        transition: all 0.3s ease;
        border-radius: 2px;
        display: inline-flex;
        justify-content: space-between;
        align-items: center;
        width: 180px;
        letter-spacing: 0.5px;
    }
    .btn-mua-ngay:hover {
        background-color: #f5f5f5;
        border-color: #999;
    }
    
    /* KHUNG SẢN PHẨM BO TRÒN MỀM MẠI */
    .khung-san-pham {
        border-radius: 30px; 
        background-color: #ffffff;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .khung-san-pham:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
    }
</style>

<!-- SLIDER 2 BANNER CHẠY QUA CHẠY LẠI -->
<div class="container-fluid p-0 mb-5 border-bottom border-danger border-5">
    <div id="sale1010Carousel" class="carousel slide carousel-fade shadow-lg" data-bs-ride="carousel" data-bs-interval="3500">
        
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#sale1010Carousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
            <button type="button" data-bs-target="#sale1010Carousel" data-bs-slide-to="1"></button>
        </div>

        <div class="carousel-inner text-center">
            <div class="carousel-item active">
                <img src="images/salecocooon.jpg" class="img-fluid" style="width: 100%; height: auto; max-height: 600px; object-fit: contain;" alt="Siêu Sale Cocoon 10/10" onerror="this.src='https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=1200&h=400&fit=crop'">
            </div>
            
            <div class="carousel-item">
                <img src="images/saleskin.jpg" class="img-fluid" style="width: 100%; height: auto; max-height: 600px; object-fit: contain;" alt="Siêu Sale Skin Heritage 10/10" onerror="this.src='https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=1200&h=400&fit=crop'">
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#sale1010Carousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#sale1010Carousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

<!-- KHU VỰC TIÊU ĐỀ -->
<div class="container text-center pt-3 pb-2">
    <span class="badge bg-warning text-dark px-3 py-2 fs-6 mb-3 shadow-sm" style="border-radius: 20px;">
        <i class="bi bi-star-fill"></i> ĐẠI TIỆC 10/10 BÙNG NỔ
    </span>
    <h1 class="fw-bold mb-3" style="font-size: 3.5rem; color: #111;">SĂN DEAL ĐỒNG GIÁ</h1>
    <p class="text-muted fs-5 mb-5">Hàng ngàn Voucher Freeship & Quà tặng độc quyền đang chờ đón. Nhanh tay kẻo lỡ!</p>
</div>

<!-- DANH SÁCH 10 SẢN PHẨM ƯU ĐÃI LẤY TRỰC TIẾP TỪ DATABASE -->
<div class="container pb-5">
    <?php
    // Lấy 10 sản phẩm Combo trực tiếp từ DB để đảm bảo ID và Tồn Kho chính xác 100%
    $sql_sale = "SELECT * FROM SanPham WHERE TenSanPham LIKE '%Combo%' OR TenSanPham LIKE '%Toner Phong Nha%' ORDER BY ID_SanPham DESC LIMIT 10";
    $res_sale = $conn->query($sql_sale);
    
    // Mảng map ảnh chuẩn của đại ca
    $hinh_anh_chuan = [
        'images/10.10.1.png', 'images/10.10.2.png', 'images/10.10.3.png', 'images/10.10.4.png', 'images/10.10.5.png',
        'images/10.10.6.png', 'images/10.10.7.jpg', 'images/10.10.8.jpg', 'images/10.10.9.jpg', 'images/10.10.10.jpg'
    ];

    $count = 1;
    if ($res_sale && $res_sale->num_rows > 0) {
        // Lưu kết quả vào mảng để đảo ngược thứ tự (hiển thị ID nhỏ trước)
        $products = [];
        while ($r = $res_sale->fetch_assoc()) {
            $products[] = $r;
        }
        $products = array_reverse($products);

        foreach ($products as $index => $row) {
            $p_id = $row['ID_SanPham']; // ĐÂY LÀ ID THẬT
            $p_name = $row['TenSanPham']; // ĐÂY LÀ TÊN THẬT
            
            // Xử lý giá
            $p_price = isset($row['Gia']) ? $row['Gia'] : (isset($row['GiaBan']) ? $row['GiaBan'] : 329000);
            $p_old = isset($row['GiaGoc']) && $row['GiaGoc'] > $p_price ? $row['GiaGoc'] : ($p_price * 1.8);
            
            $p_desc = $row['MoTa'] ? strip_tags($row['MoTa']) : "Mua Combo ưu đãi đặc biệt duy nhất ngày 10/10.";
            
            // Lấy ảnh chuẩn từ mảng (nếu có), nếu không có thì lấy ảnh trong DB
            $p_img = isset($hinh_anh_chuan[$index]) ? $hinh_anh_chuan[$index] : 'images/' . basename($row['HinhAnh']);
            
            $flex_direction = ($count % 2 == 0) ? 'flex-row-reverse' : '';
    ?>
            <div class="card border-0 shadow-sm mb-5 overflow-hidden khung-san-pham">
                <div class="row align-items-center p-4 p-md-5 <?php echo $flex_direction; ?>">
                    
                    <div class="col-md-6 text-center py-3">
                        <img src="<?php echo $p_img; ?>" 
                             onerror="this.src='https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=600&h=600&fit=crop'" 
                             class="img-fluid" 
                             style="max-height: 400px; object-fit: contain; filter: drop-shadow(0px 10px 15px rgba(0,0,0,0.05));" 
                             alt="<?php echo $p_name; ?>">
                    </div>
                    
                    <div class="col-md-6 px-md-5 mt-4 mt-md-0 text-start">
                        <h2 class="serif-font mb-4 fw-normal" style="font-size: 2.5rem; color: #222;">Ưu đãi <?php echo $count; ?></h2>
                        
                        <div class="d-flex align-items-baseline mb-3">
                            <span class="fs-2 fw-bold me-3 text-dark" style="letter-spacing: -0.5px;"><?php echo number_format($p_price, 0, ',', '.'); ?> đ</span>
                            <span class="text-decoration-line-through fs-5" style="color: #e1b34c; font-weight: 500;"><?php echo number_format($p_old, 0, ',', '.'); ?> đ</span>
                        </div>
                        
                        <p class="text-dark mb-5" style="line-height: 1.8; font-size: 1.05rem;">
                            <strong><?php echo $p_name; ?></strong><br>
                            <span style="color: #555;"><?php echo mb_substr($p_desc, 0, 150) . '...'; ?></span>
                        </p>
                        
                        <form action="giohang.php" method="POST">
                            <input type="hidden" name="id_sp" value="<?php echo $p_id; ?>">
                            <input type="hidden" name="ten_sp" value="<?php echo $p_name; ?>">
                            <input type="hidden" name="gia_sp" value="<?php echo $p_price; ?>">
                            <input type="hidden" name="hinh_anh" value="<?php echo $p_img; ?>">
                            <input type="hidden" name="so_luong" value="1">
                            
                            <button type="submit" name="them_gio_hang" class="btn btn-mua-ngay px-4 py-2 fs-6">
                                <span>MUA NGAY</span>
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
    <?php
            $count++;
        }
    } else {
        echo "<div class='alert alert-warning text-center'>Đang cập nhật sản phẩm ưu đãi... Xin vui lòng kiểm tra lại Database.</div>";
    }
    ?>
</div>

<!-- SCRIPT CHẠY HIỆU ỨNG RƠI PHÁO GIẤY -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var duration = 3 * 1000;
        var end = Date.now() + duration;

        (function frame() {
            confetti({
                particleCount: 3,
                angle: 60,
                spread: 55,
                origin: { x: 0 },
                colors: ['#d92d53', '#ffc107', '#ffffff']
            });
            confetti({
                particleCount: 3,
                angle: 120,
                spread: 55,
                origin: { x: 1 },
                colors: ['#d92d53', '#ffc107', '#ffffff']
            });

            if (Date.now() < end) {
                requestAnimationFrame(frame);
            }
        }());
    });
</script>

<?php include 'footer.php'; ?>