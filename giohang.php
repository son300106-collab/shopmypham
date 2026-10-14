<?php
// Gọi header sẽ tự động start session
include 'header.php';

// KHỞI TẠO BIẾN GIỎ HÀNG NẾU CHƯA CÓ
if(!isset($_SESSION['giohang'])) {
    $_SESSION['giohang'] = array();
}

// 1. XỬ LÝ KHI BẤM NÚT "THÊM VÀO GIỎ HÀNG" TỪ CÁC TRANG TRƯỚC
if(isset($_POST['them_gio_hang'])) {
    $id_sp = $_POST['id_sp'];
    $ten_sp = $_POST['ten_sp'];
    $gia_sp = $_POST['gia_sp'];
    
    // Ép lấy đúng tên file ảnh (ví dụ: combo1.jpg), loại bỏ mọi đường dẫn thừa
    $hinh_anh = basename($_POST['hinh_anh']); 
    
    $so_luong = $_POST['so_luong'];

    // Lấy tồn kho thật để chặn ngay từ lúc mới Thêm vào giỏ
    $kiem_tra_kho = $conn->query("SELECT SoLuongTon FROM SanPham WHERE ID_SanPham = '$id_sp'");
    $ton_kho_hien_tai = 0;
    if($kiem_tra_kho && $kiem_tra_kho->num_rows > 0){
        $row_kho = $kiem_tra_kho->fetch_assoc();
        $ton_kho_hien_tai = (int)$row_kho['SoLuongTon'];
    }

    $kiem_tra = false;
    for ($i = 0; $i < count($_SESSION['giohang']); $i++) {
        if ($_SESSION['giohang'][$i]['id'] == $id_sp) {
            $sl_moi = $_SESSION['giohang'][$i]['soluong'] + $so_luong;
            // Nếu cộng thêm mà vượt kho thì ép bằng đúng số kho
            if($sl_moi > $ton_kho_hien_tai) {
                $_SESSION['giohang'][$i]['soluong'] = $ton_kho_hien_tai;
            } else {
                $_SESSION['giohang'][$i]['soluong'] = $sl_moi;
            }
            $kiem_tra = true;
            break;
        }
    }
    
    // Chưa có thì thêm mới
    if ($kiem_tra == false) {
        if($so_luong > $ton_kho_hien_tai) $so_luong = $ton_kho_hien_tai; // Chặn nếu khách gõ lố
        $item = array('id' => $id_sp, 'ten' => $ten_sp, 'gia' => $gia_sp, 'hinhanh' => $hinh_anh, 'soluong' => $so_luong);
        $_SESSION['giohang'][] = $item;
    }
    echo "<script>window.location.href='giohang.php';</script>";
}

// 2. XỬ LÝ NÚT TĂNG / GIẢM SỐ LƯỢNG KHI BẤM [+] HOẶC [-]
if(isset($_GET['action']) && isset($_GET['id_update'])) {
    $action = $_GET['action'];
    $id_update = $_GET['id_update'];

    // Lấy số lượng tồn kho thực tế từ DB để đối chiếu
    $res_kho_update = $conn->query("SELECT SoLuongTon FROM SanPham WHERE ID_SanPham = '$id_update'");
    $ton_kho = 0;
    if($res_kho_update && $res_kho_update->num_rows > 0){
        $row_k = $res_kho_update->fetch_assoc();
        $ton_kho = (int)$row_k['SoLuongTon'];
    }

    for ($i = 0; $i < count($_SESSION['giohang']); $i++) {
        if ($_SESSION['giohang'][$i]['id'] == $id_update) {
            
            if ($action == 'tang') {
                // CHỈ TĂNG KHI SỐ LƯỢNG TRONG GIỎ NHỎ HƠN TỒN KHO
                if($_SESSION['giohang'][$i]['soluong'] < $ton_kho) {
                    $_SESSION['giohang'][$i]['soluong']++;
                } else {
                    echo "<script>alert('Sản phẩm này chỉ còn tối đa ".$ton_kho." chiếc trong kho!');</script>";
                }
            } 
            elseif ($action == 'giam') {
                $_SESSION['giohang'][$i]['soluong']--; 
                
                // Nếu khách bấm giảm tụt xuống 0 thì xóa luôn sản phẩm
                if ($_SESSION['giohang'][$i]['soluong'] <= 0) {
                    array_splice($_SESSION['giohang'], $i, 1);
                }
            }
            break;
        }
    }
    // Load lại trang để cập nhật tổng tiền mà không làm mất thông báo alert
    echo "<script>window.location.href='giohang.php';</script>";
}

// 3. XỬ LÝ XÓA SẢN PHẨM KHỎI GIỎ HÀNG (NÚT X ĐỎ)
if(isset($_GET['xoa'])) {
    $id_xoa = $_GET['xoa'];
    for ($i = 0; $i < count($_SESSION['giohang']); $i++) {
        if ($_SESSION['giohang'][$i]['id'] == $id_xoa) {
            array_splice($_SESSION['giohang'], $i, 1);
            break;
        }
    }
    echo "<script>window.location.href='giohang.php';</script>";
}
?>

<div class="container my-5 pt-4 pb-5">
    <h2 class="text-center mb-5 brand-text" style="font-size: 2.5rem;">Giỏ Hàng Của Bạn</h2>

    <?php if(count($_SESSION['giohang']) > 0): ?>
        <div class="table-responsive shadow-sm">
            <table class="table table-bordered align-middle text-center mb-0 bg-white">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Hình Ảnh</th>
                        <th scope="col">Sản Phẩm</th>
                        <th scope="col">Quà Tặng</th> <!-- THÊM CỘT QUÀ TẶNG MỚI NÀY -->
                        <th scope="col">Đơn Giá</th>
                        <th scope="col" style="width: 150px;">Số Lượng</th>
                        <th scope="col">Thành Tiền</th>
                        <th scope="col">Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $tong_tien = 0;
                    $co_loi_kho = false;

                    foreach($_SESSION['giohang'] as $key => $sp): 
                        // Kiểm tra tồn kho lần cuối khi render HTML
                        $id_hien_tai = $sp['id'];
                        $check_kho = $conn->query("SELECT SoLuongTon FROM SanPham WHERE ID_SanPham = '$id_hien_tai'");
                        $kho_thuc_te = 0;
                        if($check_kho && $check_kho->num_rows > 0){
                            $r = $check_kho->fetch_assoc();
                            $kho_thuc_te = (int)$r['SoLuongTon'];
                        }

                        // Nếu kho hụt bất ngờ, ép giỏ hàng xuống
                        if($sp['soluong'] > $kho_thuc_te) {
                            $_SESSION['giohang'][$key]['soluong'] = $kho_thuc_te;
                            $sp['soluong'] = $kho_thuc_te;
                            $co_loi_kho = true;
                        }

                        $thanh_tien = $sp['gia'] * $sp['soluong'];
                        $tong_tien += $thanh_tien;
                        
                        // Luôn luôn ghép thư mục images/ với tên file ảnh để không bao giờ bị lỗi
                        $anh_hien_thi = 'images/' . basename($sp['hinhanh']);
                    ?>
                        <tr>
                            <td><img src="<?php echo $anh_hien_thi; ?>" alt="SP" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;"></td>
                            <td class="fw-bold">
                                <?php echo $sp['ten']; ?>
                                <?php if($kho_thuc_te == 0): ?>
                                    <br><span class="badge bg-danger">Hết hàng</span>
                                <?php endif; ?>
                            </td>
                            
                            <!-- LOGIC HIỂN THỊ CỘT QUÀ TẶNG NẾU LÀ COMBO 20/10 -->
                            <td>
                                <?php if($sp['id'] == 45 || strpos($sp['ten'], 'Tặng Mask') !== false): ?>
                                    <div class="d-flex flex-column align-items-center justify-content-center">
                                        <img src="images/tang20_10.png" style="width: 45px; height: 45px; object-fit: contain;" alt="Quà tặng">
                                        <span class="badge bg-danger mt-1 px-2 py-1"><i class="bi bi-gift"></i> Tặng 1 Mask</span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>

                            <td style="color: #9a7b4f; font-weight: 600;"><?php echo number_format($sp['gia'], 0, ',', '.'); ?> đ</td>
                            
                            <!-- BỘ NÚT TĂNG GIẢM SỐ LƯỢNG -->
                            <td>
                                <div class="d-flex justify-content-center align-items-center">
                                    <a href="giohang.php?action=giam&id_update=<?php echo $sp['id']; ?>" class="btn btn-sm btn-outline-dark d-flex justify-content-center align-items-center" style="width: 30px; height: 30px; text-decoration: none; font-weight: bold;">-</a>
                                    
                                    <input type="text" class="form-control text-center mx-2 p-0 border-0 fw-bold" value="<?php echo $sp['soluong']; ?>" readonly style="width: 40px; background-color: transparent;">
                                    
                                    <a href="giohang.php?action=tang&id_update=<?php echo $sp['id']; ?>" class="btn btn-sm btn-outline-dark d-flex justify-content-center align-items-center" style="width: 30px; height: 30px; text-decoration: none; font-weight: bold;">+</a>
                                </div>
                            </td>

                            <td class="text-danger fw-bold"><?php echo number_format($thanh_tien, 0, ',', '.'); ?> đ</td>
                            <td>
                                <a href="giohang.php?xoa=<?php echo $sp['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">X</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if($co_loi_kho): ?>
            <div class="alert alert-warning mt-3">
                <i class="bi bi-exclamation-triangle-fill text-warning"></i> Hệ thống đã tự động điều chỉnh số lượng một số sản phẩm do kho không đủ hàng.
            </div>
        <?php endif; ?>

        <div class="row mt-4 align-items-center">
            <div class="col-md-6 mb-3 mb-md-0">
                <a href="index.php" class="btn btn-outline-dark px-4 py-2">← TIẾP TỤC MUA SẮM</a>
            </div>
            <div class="col-md-6 text-md-end text-center">
                <h4 class="mb-3">Tổng cộng: <span class="text-danger fw-bold fs-2"><?php echo number_format($tong_tien, 0, ',', '.'); ?> đ</span></h4>
                <a href="thanhtoan.php" class="btn btn-dark px-5 py-3 fw-bold" style="background-color: #222; letter-spacing: 1px;">TIẾN HÀNH THANH TOÁN</a>
            </div>
        </div>

    <?php else: ?>
        <div class="alert alert-warning text-center py-5 shadow-sm border-0">
            <i class="bi bi-cart-x" style="font-size: 3rem; color: #9a7b4f;"></i>
            <h4 class="mb-3 mt-3">Giỏ hàng của bạn đang trống!</h4>
            <p class="text-muted mb-4">Hãy lướt xem các sản phẩm ưu đãi và quay lại đây nhé.</p>
            <a href="index.php" class="btn btn-dark px-4 py-2" style="background-color: #222;">MUA SẮM NGAY</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>