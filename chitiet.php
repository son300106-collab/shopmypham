<?php 
include 'header.php'; 

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM SanPham WHERE ID_SanPham = $id";
    $result = $conn->query($sql);
    
    if($result->num_rows > 0) {
        $sp = $result->fetch_assoc();
        $duongDanAnh = 'images/' . $sp['HinhAnh'];
        $anhHienThi = file_exists($duongDanAnh) ? $duongDanAnh : 'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?w=600&h=800&fit=crop';
    } else {
        echo "<h2 class='text-center mt-5 mb-5 py-5'>Không tìm thấy sản phẩm!</h2>";
        include 'footer.php'; exit;
    }
} else {
    echo "<h2 class='text-center mt-5 mb-5 py-5'>Lỗi: Bạn chưa chọn sản phẩm nào!</h2>";
    include 'footer.php'; exit;
}
?>

<div class="container my-5 pt-4">
    <div class="row align-items-start">
        
        <div class="col-md-6 mb-4 text-center">
            <img src="<?php echo $anhHienThi; ?>" alt="Ảnh Sản Phẩm" style="width: 100%; border-radius: 8px;">
        </div>

        <div class="col-md-6 px-lg-5">
            <h1 class="brand-text" style="font-size: 2.2rem; font-weight: 700; margin-bottom: 15px;"><?php echo $sp['TenSanPham']; ?></h1>
            <p style="font-size: 1.8rem; font-weight: 600; color: #9a7b4f; margin-bottom: 25px;">
                <?php echo number_format($sp['Gia'], 0, ',', '.'); ?> VNĐ
            </p>
            
            <hr class="my-4">
            
            <div class="mb-4">
                <h6 class="fw-bold text-uppercase mb-2">Loại da phù hợp:</h6>
                <p class="text-muted" style="font-size: 1.1rem;"><?php echo $sp['LoaiDa']; ?></p>
            </div>
            
            <div class="mb-4">
                <h6 class="fw-bold text-uppercase mb-2">Công dụng / Mô tả:</h6>
                <p class="text-muted" style="line-height: 1.8; font-size: 1.05rem;">
                    <?php echo nl2br($sp['MoTa']); ?>
                </p>
            </div>

            <!-- FORM GỬI DỮ LIỆU LÊN GIỎ HÀNG -->
            <form action="giohang.php" method="POST" class="d-flex mt-5">
                <!-- Chứa các thông tin ẩn để truyền đi -->
                <input type="hidden" name="id_sp" value="<?php echo $sp['ID_SanPham']; ?>">
                <input type="hidden" name="ten_sp" value="<?php echo $sp['TenSanPham']; ?>">
                <input type="hidden" name="gia_sp" value="<?php echo $sp['Gia']; ?>">
                <input type="hidden" name="hinh_anh" value="<?php echo $anhHienThi; ?>">
                
                <input type="number" name="so_luong" class="form-control text-center me-3" value="1" min="1" style="width: 80px; font-weight: bold;">
                <button type="submit" name="them_gio_hang" style="background-color: #222; color: #fff; padding: 15px 30px; font-weight: 600; width: 100%; border: none; letter-spacing: 1px;">THÊM VÀO GIỎ HÀNG</button>
            </form>

            <div class="mt-5 p-3 bg-light rounded text-center">
                <p class="mb-1 small">✅ Cam kết hàng chính hãng 100%</p>
                <p class="mb-1 small">✅ Giao hàng toàn quốc từ 1-3 ngày</p>
                <p class="mb-0 small">✅ Đổi trả miễn phí trong 7 ngày</p>
            </div>
        </div>

    </div>
</div>

<?php include 'footer.php'; ?>