<?php
include 'header.php';

// Kiểm tra xem trên URL có truyền tham số 'brand' không
// VD: thuonghieu.php?brand=Cocoon
if (isset($_GET['brand']) && !empty($_GET['brand'])) {
    $thuongHieu = $conn->real_escape_string($_GET['brand']);
} else {
    // Nếu không có, đá về trang sản phẩm chung
    echo "<script>window.location.href='sanpham.php';</script>";
    exit;
}
?>

<div class="container my-5 pt-4 pb-5" style="min-height: 60vh;">
    <!-- Tiêu đề trang hiển thị đúng tên Thương hiệu -->
    <h2 class="text-center mb-5 brand-text" style="font-size: 2.5rem;">
        Thương Hiệu: <span style="color: #9a7b4f;"><?php echo $thuongHieu; ?></span>
    </h2>

    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php
        // CÂU LỆNH SQL: Lấy tất cả sản phẩm CÓ CỘT ThuongHieu GIỐNG VỚI TÊN TRÊN URL
        $sql = "SELECT * FROM SanPham WHERE ThuongHieu = '$thuongHieu' ORDER BY ID_SanPham DESC";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Xử lý ảnh: Ưu tiên ảnh trong máy, nếu chưa tải về thì dùng ảnh mẫu
                $anh = 'images/' . $row['HinhAnh'];
                if (!file_exists($anh) || empty($row['HinhAnh'])) {
                    $anh = 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=400&h=400&fit=crop';
                }
        ?>
            <!-- Bắt đầu 1 thẻ Card Sản phẩm -->
            <div class="col">
                <a href="chitiet.php?id=<?php echo $row['ID_SanPham']; ?>" class="text-decoration-none">
                    <div class="card product-card h-100 border-0">
                        <!-- Vùng chứa ảnh có hiệu ứng -->
                        <div class="position-relative overflow-hidden">
                            <img src="<?php echo $anh; ?>" class="card-img-top product-img" alt="<?php echo $row['TenSanPham']; ?>">
                        </div>
                        
                        <!-- Thông tin chữ -->
                        <div class="card-body text-center px-2 pb-0 pt-3">
                            <h5 class="product-title" style="font-size: 0.95rem; line-height: 1.4; color: #333;">
                                <?php echo $row['TenSanPham']; ?>
                            </h5>
                            <p class="product-price mt-2" style="color: #d9534f; font-weight: bold; font-size: 1.1rem;">
                                <?php echo number_format($row['Gia'], 0, ',', '.'); ?> đ
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Kết thúc 1 thẻ Card -->
        <?php
            }
        } else {
            // Nếu thương hiệu đó chưa có sản phẩm nào
            echo '<div class="col-12 text-center py-5">
                    <h4 class="text-muted"><i class="bi bi-box-seam fs-1"></i><br><br>Hiện chưa có sản phẩm nào thuộc thương hiệu '.$thuongHieu.'!</h4>
                    <p class="mt-3"><a href="sanpham.php" class="btn btn-outline-dark px-4 py-2">Xem Tất Cả Sản Phẩm</a></p>
                  </div>';
        }
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>