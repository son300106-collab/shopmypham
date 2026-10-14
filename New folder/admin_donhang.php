<?php
include 'header.php';

if (!isset($_SESSION['VaiTro']) || $_SESSION['VaiTro'] !== 'Admin') {
    echo "<script>alert('⛔ Bạn không có quyền truy cập!'); window.location.href='index.php';</script>";
    exit;
}

$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $conn->real_escape_string($_GET['search']);
}
?>

<div class="container-fluid px-4 my-5 pt-4 pb-5" style="min-height: 60vh;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="brand-text" style="color: #d9534f; font-weight: 500;">Quản Lý Đơn Hàng</h2>
        
        <!-- THÊM Ô TÌM KIẾM ĐƠN HÀNG CÓ GỢI Ý -->
        <?php
        $sg_dh = $conn->query("SELECT DISTINCT HoTenGiaoHang, SDTGiaoHang, ID_DonHang FROM DonHang");
        ?>
        <datalist id="suggestDH">
            <?php 
            if($sg_dh) {
                while($r = $sg_dh->fetch_assoc()) {
                    echo "<option value='".htmlspecialchars($r['HoTenGiaoHang'])."'>"; 
                    echo "<option value='".htmlspecialchars($r['SDTGiaoHang'])."'>"; 
                    echo "<option value='".htmlspecialchars($r['ID_DonHang'])."'>"; 
                }
            }
            ?>
        </datalist>

        <form action="admin_donhang.php" method="GET" class="d-flex w-25">
            <input type="text" name="search" list="suggestDH" autocomplete="off" class="form-control me-2" placeholder="Tìm tên KH, SĐT hoặc Mã ĐH..." value="<?php echo $search_query; ?>">
            <button type="submit" class="btn btn-outline-dark">Tìm</button>
        </form>

        <a href="admin.php" class="btn btn-outline-dark">← Về Dashboard</a>
    </div>

    <div class="table-responsive shadow-sm bg-white p-3 rounded border">
        <table class="table table-hover align-middle text-center mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Mã ĐH</th>
                    <th>Ngày Đặt</th>
                    <th class="text-start">Thông Tin Khách Hàng</th>
                    <th>Tổng Tiền</th>
                    <th>Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Áp dụng điều kiện tìm kiếm vào SQL
                $sql = "SELECT * FROM DonHang ";
                if (!empty($search_query)) {
                    $sql .= " WHERE HoTenGiaoHang LIKE '%$search_query%' OR SDTGiaoHang LIKE '%$search_query%' OR ID_DonHang = '$search_query' ";
                }
                $sql .= " ORDER BY ID_DonHang DESC";

                $result = $conn->query($sql);
                if($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $badge_color = 'bg-warning text-dark';
                        if($row['TrangThai'] == 'Hoàn thành') $badge_color = 'bg-success';
                        if($row['TrangThai'] == 'Đã hủy') $badge_color = 'bg-danger';
                        if($row['TrangThai'] == 'Đang giao hàng') $badge_color = 'bg-primary';
                ?>
                <tr>
                    <td class="fw-bold">#<?php echo $row['ID_DonHang']; ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($row['NgayDat'])); ?></td>
                    <td class="text-start">
                        <strong><?php echo $row['HoTenGiaoHang']; ?></strong><br>
                        <small class="text-muted"><i class="bi bi-telephone-fill"></i> <?php echo $row['SDTGiaoHang']; ?></small><br>
                        <small class="text-muted"><i class="bi bi-geo-alt-fill"></i> <?php echo $row['DiaChiGiaoHang']; ?></small>
                    </td>
                    <td style="color: #d9534f; font-weight: bold; font-size: 1.1rem;"><?php echo number_format($row['TongTien'], 0, ',', '.'); ?> đ</td>
                    <td><span class="badge <?php echo $badge_color; ?> px-2 py-1"><?php echo $row['TrangThai']; ?></span></td>
                    <td>
                        <a href="admin_chitietdonhang.php?id=<?php echo $row['ID_DonHang']; ?>" class="btn btn-sm btn-info text-white fw-bold">Xem Chi Tiết</a>
                    </td>
                </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center py-4 text-danger fw-bold'>Không tìm thấy đơn hàng nào phù hợp!</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>