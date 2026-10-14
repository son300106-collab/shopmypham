<?php 
include 'header.php'; 

// Lấy từ khóa tìm kiếm nếu người dùng có nhập vào
$tu_khoa = isset($_GET['timkiem']) ? $_GET['timkiem'] : '';
?>

<div class="container my-5 pt-4">
    
    <!-- Tiêu đề động (Thay đổi nếu có tìm kiếm) -->
    <h2 class="text-center mb-4 brand-text" style="font-size: 2.5rem;">
        <?php echo ($tu_khoa != '') ? "Kết quả tìm kiếm: '$tu_khoa'" : "Tất Cả Sản Phẩm"; ?>
    </h2>
    
    <!-- BỘ LỌC TÌM KIẾM -->
    <div class="row justify-content-center mb-5">
        <div class="col-md-6">
            <form action="sanpham.php" method="GET" class="d-flex shadow-sm rounded">
                <input type="text" name="timkiem" class="form-control border-0 p-3 bg-light" placeholder="Nhập tên mỹ phẩm bạn muốn tìm..." value="<?php echo $tu_khoa; ?>">
                <button type="submit" class="btn btn-dark px-4 border-0" style="background-color: #222;">TÌM KIẾM</button>
            </form>
        </div>
    </div>

    <!-- DANH SÁCH SẢN PHẨM -->
    <div class="row">
        <?php
        // Xử lý câu lệnh SQL dựa trên việc có từ khóa hay không
        if ($tu_khoa != '') {
            $sql = "SELECT ID_SanPham, TenSanPham, Gia, HinhAnh FROM SanPham WHERE TenSanPham LIKE '%$tu_khoa%'";
        } else {
            $sql = "SELECT ID_SanPham, TenSanPham, Gia, HinhAnh FROM SanPham";
        }
        
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $anhHienThi = 'images/' . $row['HinhAnh'];
                if(!file_exists($anhHienThi)) $anhHienThi = 'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?w=400&h=500&fit=crop';
        ?>
            <!-- Khung 1 sản phẩm -->
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
            // Hiển thị thông báo nếu tìm không ra
            echo "<div class='alert alert-warning text-center w-100 py-4'>Rất tiếc, không tìm thấy sản phẩm nào phù hợp với từ khóa của bạn.</div>";
        }
        ?>
    </div>
</div>

<?php 
include 'footer.php'; 
?>