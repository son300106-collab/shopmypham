<?php include 'header.php'; ?>

<?php 
$id_dm = isset($_GET['id']) ? $_GET['id'] : 0;
// Lấy tên danh mục
$ten_dm = "";
$rs = $conn->query("SELECT TenDanhMuc FROM DanhMuc WHERE ID_DanhMuc = $id_dm");
if($rs->num_rows > 0) {
    $ten_dm = $rs->fetch_assoc()['TenDanhMuc'];
}
?>

<div class="container my-5 pt-4">
    <h2 class="text-center mb-5 brand-text" style="font-size: 2.5rem;"><?php echo $ten_dm; ?></h2>
    <div class="row">
        <?php
        // Lệnh lọc sản phẩm theo ID Danh Mục
        $sql = "SELECT ID_SanPham, TenSanPham, Gia, HinhAnh FROM SanPham WHERE ID_DanhMuc = $id_dm";
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
            echo "<div class='alert alert-warning text-center w-100'>Chưa có sản phẩm thuộc danh mục này.</div>";
        }
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>