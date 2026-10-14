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
            <input type="text" name="search" list="suggestDH" autocomplete="off" class="form-control me-2" placeholder="Tìm tên KH, SĐT hoặc Mã ĐH..." value="<?php echo htmlspecialchars($search_query); ?>">
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
                    <th class="text-start" style="width: 25%;">Sản Phẩm</th>
                    <th class="text-start">Thông Tin Khách Hàng</th>
                    <th>Tổng Tiền</th>
                    <th>Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM DonHang ";
                if (!empty($search_query)) {
                    $sql .= " WHERE HoTenGiaoHang LIKE '%$search_query%' OR SDTGiaoHang LIKE '%$search_query%' OR ID_DonHang = '$search_query' ";
                }
                $sql .= " ORDER BY ID_DonHang DESC";

                $result = $conn->query($sql);
                if($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $id_donhang = $row['ID_DonHang'];
                        $badge_color = 'bg-warning text-dark';
                        if($row['TrangThai'] == 'Hoàn thành') $badge_color = 'bg-success';
                        if($row['TrangThai'] == 'Đã hủy') $badge_color = 'bg-danger';
                        if($row['TrangThai'] == 'Đang giao hàng') $badge_color = 'bg-primary';

                        $sql_count = "SELECT COUNT(*) AS total_sp FROM ChiTietDonHang WHERE ID_DonHang = '$id_donhang'";
                        $res_count = $conn->query($sql_count);
                        $count_sp = $res_count->fetch_assoc()['total_sp'];

                        $sql_first = "SELECT ID_SanPham FROM ChiTietDonHang WHERE ID_DonHang = '$id_donhang' LIMIT 1";
                        $res_first = $conn->query($sql_first);
                        
                        $anh_sp = 'images/banner1.png'; 
                        $ten_sp = 'Đang tải dữ liệu...';
                        
                        if ($res_first && $res_first->num_rows > 0) {
                            $first_item = $res_first->fetch_assoc();
                            $id_sp_raw = $first_item['ID_SanPham'];
                            
                            // ÉP KIỂU VỀ SỐ NGUYÊN (INT) ĐỂ CHỐNG LỖI KHOẢNG TRẮNG HOẶC SAI KIỂU DỮ LIỆU
                            $id_sp_clean = intval($id_sp_raw);
                            
                            $sql_sp = "SELECT TenSanPham, HinhAnh FROM SanPham WHERE ID_SanPham = $id_sp_clean OR ID_SanPham = '$id_sp_raw'";
                            $res_sp = $conn->query($sql_sp);
                            
                            if ($res_sp && $res_sp->num_rows > 0) {
                                $sp_info = $res_sp->fetch_assoc();
                                $ten_sp = $sp_info['TenSanPham'];
                                $anh_sp = 'images/' . trim($sp_info['HinhAnh']);
                            } else {
                                $ten_sp = "Sản phẩm đã bị xóa (Mã: $id_sp_clean)";
                            }
                        }
                ?>
                <tr>
                    <td class="fw-bold">#<?php echo $row['ID_DonHang']; ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($row['NgayDat'])); ?></td>
                    
                    <td class="text-start">
                        <div class="d-flex align-items-center">
                            <img src="<?php echo $anh_sp; ?>" onerror="this.src='images/khuyenmai.png'" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;" class="me-3 border" alt="Ảnh SP">
                            <div>
                                <span class="fw-bold d-block text-truncate" style="max-width: 200px; font-size: 0.95rem;" title="<?php echo $ten_sp; ?>">
                                    <?php echo $ten_sp; ?>
                                </span>
                                <?php if ($count_sp > 1): ?>
                                    <small class="text-danger fw-bold">+ <?php echo ($count_sp - 1); ?> sản phẩm khác</small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>

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
                    echo "<tr><td colspan='7' class='text-center py-4 text-danger fw-bold'>Không tìm thấy đơn hàng nào phù hợp!</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>