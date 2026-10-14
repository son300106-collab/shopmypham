<?php
include 'header.php';

if (!isset($_SESSION['VaiTro']) || $_SESSION['VaiTro'] !== 'Admin') {
    echo "<script>alert('⛔ Bạn không có quyền truy cập!'); window.location.href='index.php';</script>";
    exit;
}

if (!isset($_GET['id'])) {
    echo "<script>window.location.href='admin_donhang.php';</script>";
    exit;
}

$id_donhang = $_GET['id'];

if (isset($_POST['btn_capnhat'])) {
    $trang_thai_moi = $_POST['trang_thai'];
    
    $sql_update = "UPDATE DonHang SET TrangThai = '$trang_thai_moi' WHERE ID_DonHang = '$id_donhang'";
    if ($conn->query($sql_update) === TRUE) {
        echo "<script>alert('✅ Cập nhật trạng thái đơn hàng thành công!');</script>";
    } else {
        echo "<script>alert('⛔ Lỗi cập nhật: " . $conn->error . "');</script>";
    }
}

$sql_donhang = "SELECT * FROM DonHang WHERE ID_DonHang = '$id_donhang'";
$res_donhang = $conn->query($sql_donhang);

if ($res_donhang->num_rows == 0) {
    echo "<div class='container my-5'><h3 class='text-center text-danger'>Không tìm thấy đơn hàng này!</h3></div>";
    include 'footer.php';
    exit;
}
$donhang = $res_donhang->fetch_assoc();

$badge_color = 'bg-warning text-dark';
if($donhang['TrangThai'] == 'Hoàn thành') $badge_color = 'bg-success';
if($donhang['TrangThai'] == 'Đã hủy') $badge_color = 'bg-danger';
if($donhang['TrangThai'] == 'Đang giao hàng') $badge_color = 'bg-primary';
?>

<div class="container my-5 pt-4 pb-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="brand-text m-0" style="color: #d9534f; font-weight: 500;">
                Chi Tiết Đơn Hàng #<?php echo $donhang['ID_DonHang']; ?>
            </h2>
            <span class="text-muted small">Ngày đặt: <?php echo date('d/m/Y H:i', strtotime($donhang['NgayDat'])); ?></span>
        </div>
        <a href="admin_donhang.php" class="btn btn-outline-dark">← Quay lại danh sách</a>
    </div>

    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white fw-bold py-3">
                    <i class="bi bi-cart-check-fill me-2" style="color: #9a7b4f;"></i> SẢN PHẨM TRONG ĐƠN
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle text-center mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Sản Phẩm</th>
                                <th>Đơn Giá</th>
                                <th>Số Lượng</th>
                                <th>Thành Tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Lấy tất cả mã sản phẩm trong đơn hàng này
                            $sql_chitiet = "SELECT * FROM ChiTietDonHang WHERE ID_DonHang = '$id_donhang'";
                            $res_chitiet = $conn->query($sql_chitiet);
                            
                            if ($res_chitiet && $res_chitiet->num_rows > 0) {
                                while($item = $res_chitiet->fetch_assoc()) {
                                    $id_sp = $item['ID_SanPham'];
                                    
                                    // Giá trị mặc định
                                    $anh_sp = 'images/banner1.png'; 
                                    $ten_sp = "Sản phẩm đã bị xóa (Mã: $id_sp)";
                                    
                                    // Bắn thẳng 1 lệnh SQL phụ để lấy đúng Tên và Ảnh của mã đó
                                    $sql_sp = "SELECT TenSanPham, HinhAnh FROM SanPham WHERE ID_SanPham = '$id_sp'";
                                    $res_sp = $conn->query($sql_sp);
                                    
                                    if ($res_sp && $res_sp->num_rows > 0) {
                                        $sp_info = $res_sp->fetch_assoc();
                                        $ten_sp = $sp_info['TenSanPham'];
                                        $anh_sp = 'images/' . $sp_info['HinhAnh'];
                                    }
                                    
                                    $thanh_tien = $item['GiaBan'] * $item['SoLuong'];
                            ?>
                            <tr>
                                <td class="text-start ps-3">
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo $anh_sp; ?>" onerror="this.src='images/banner1.png'" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;" class="me-3 border">
                                        <span class="fw-bold"><?php echo $ten_sp; ?></span>
                                    </div>
                                </td>
                                <td style="color: #6c757d;"><?php echo number_format($item['GiaBan'], 0, ',', '.'); ?> đ</td>
                                <td class="fw-bold">x<?php echo $item['SoLuong']; ?></td>
                                <td style="color: #d9534f; font-weight: bold;"><?php echo number_format($thanh_tien, 0, ',', '.'); ?> đ</td>
                            </tr>
                            <?php 
                                }
                            } else {
                                echo "<tr><td colspan='4'>Không có dữ liệu chi tiết!</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white text-end py-3">
                    <h4 class="fw-bold m-0">Tổng thanh toán: <span class="text-danger"><?php echo number_format($donhang['TongTien'], 0, ',', '.'); ?> đ</span></h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header text-white fw-bold py-3" style="background-color: #9a7b4f;">
                    <i class="bi bi-person-lines-fill me-2"></i> THÔNG TIN GIAO HÀNG
                </div>
                <div class="card-body" style="background-color: #fcfbf8; font-size: 0.95rem;">
                    <p class="mb-2"><strong>👤 Khách hàng:</strong> <?php echo $donhang['HoTenGiaoHang']; ?></p>
                    <p class="mb-2"><strong>📞 Điện thoại:</strong> <?php echo $donhang['SDTGiaoHang']; ?></p>
                    <p class="mb-2"><strong>📍 Địa chỉ:</strong> <?php echo $donhang['DiaChiGiaoHang']; ?></p>
                    <p class="mb-2"><strong>💳 Thanh toán:</strong> <?php echo $donhang['PhuongThucThanhToan']; ?></p>
                    
                    <hr class="my-3">
                    <strong>📝 Ghi chú của khách:</strong><br>
                    <div class="bg-white p-2 border rounded mt-2 text-muted">
                        <?php echo empty($donhang['GhiChu']) ? "Không có ghi chú." : nl2br($donhang['GhiChu']); ?>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white fw-bold py-3">
                    <i class="bi bi-truck me-2" style="color: #4ade80;"></i> TRẠNG THÁI ĐƠN HÀNG
                </div>
                <div class="card-body text-center p-4">
                    <h5 class="mb-3">Hiện tại: <span class="badge <?php echo $badge_color; ?> fs-6 p-2"><?php echo $donhang['TrangThai']; ?></span></h5>
                    
                    <form action="" method="POST" class="mt-4">
                        <label class="form-label fw-bold text-muted small text-start w-100">Cập nhật trạng thái mới:</label>
                        <select name="trang_thai" class="form-select mb-3 fw-bold">
                            <option value="Chờ xử lý" <?php if($donhang['TrangThai']=='Chờ xử lý') echo 'selected'; ?>>Chờ xử lý</option>
                            <option value="Đang giao hàng" <?php if($donhang['TrangThai']=='Đang giao hàng') echo 'selected'; ?>>Đang giao hàng</option>
                            <option value="Hoàn thành" <?php if($donhang['TrangThai']=='Hoàn thành') echo 'selected'; ?>>Hoàn thành</option>
                            <option value="Đã hủy" <?php if($donhang['TrangThai']=='Đã hủy') echo 'selected'; ?>>Đã hủy</option>
                        </select>
                        <button type="submit" name="btn_capnhat" class="btn btn-dark w-100 fw-bold py-2" style="background-color: #222; letter-spacing: 0.5px;">CẬP NHẬT</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>