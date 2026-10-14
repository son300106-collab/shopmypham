<?php 
include 'header.php'; 

// ==========================================
// 1. KIỂM TRA ĐĂNG NHẬP (BẮT BUỘC)
// ==========================================
if(!isset($_SESSION['ID_User'])) {
    echo "<script>
            alert('Vui lòng đăng nhập tài khoản để tiến hành đặt hàng bạn nhé!'); 
            window.location.href='dangnhap.php';
          </script>";
    exit;
}

// ==========================================
// 2. KIỂM TRA GIỎ HÀNG (KHÔNG ĐỂ TRỐNG)
// ==========================================
if(empty($_SESSION['giohang'])) {
    echo "<script>alert('Giỏ hàng của bạn đang trống! Không thể thanh toán.'); window.location.href='giohang.php';</script>";
    exit;
}

// Lấy sẵn tên User đã đăng nhập để tự điền vào form
$hoTen_san = $_SESSION['HoTen'];

// ==========================================
// 3. XỬ LÝ LƯU VÀO CƠ SỞ DỮ LIỆU KHI BẤM ĐẶT HÀNG
// ==========================================
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['dat_hang'])) {
    
    $hoten = $conn->real_escape_string($_POST['hoten']);
    $sdt = $conn->real_escape_string($_POST['sdt']);
    $tinh_thanh = $conn->real_escape_string($_POST['tinh_thanh']);
    $quan_huyen = $conn->real_escape_string($_POST['quan_huyen']);
    $dia_chi = $conn->real_escape_string($_POST['dia_chi']);
    $ghi_chu = $conn->real_escape_string($_POST['ghi_chu']);
    $pt_thanhtoan = $conn->real_escape_string($_POST['pt_thanhtoan']);
    
    $dia_chi_day_du = $dia_chi . ", " . $quan_huyen . ", " . $tinh_thanh;

    $tong_tien = 0;
    foreach($_SESSION['giohang'] as $sp) {
        $tong_tien += ($sp['gia'] * $sp['soluong']);
    }

    // Vì đã bắt buộc đăng nhập ở trên, nên chắc chắn có ID_User
    $id_user_sql = $_SESSION['ID_User'];

    // LƯU VÀO BẢNG DonHang
    $sql_donhang = "INSERT INTO DonHang (ID_User, HoTenGiaoHang, SDTGiaoHang, DiaChiGiaoHang, GhiChu, PhuongThucThanhToan, TongTien, TrangThai) 
                    VALUES ($id_user_sql, '$hoten', '$sdt', '$dia_chi_day_du', '$ghi_chu', '$pt_thanhtoan', $tong_tien, 'Chờ xử lý')";
    
    if($conn->query($sql_donhang) === TRUE) {
        
        $id_donhang_vua_tao = $conn->insert_id; 

        // LƯU VÀO BẢNG ChiTietDonHang
        foreach($_SESSION['giohang'] as $sp) {
            $id_sp_db = $conn->real_escape_string($sp['id']); 
            $soluong_db = $sp['soluong'];
            $giaban_db = $sp['gia']; 

            $sql_chitiet = "INSERT INTO ChiTietDonHang (ID_DonHang, ID_SanPham, SoLuong, GiaBan) 
                            VALUES ($id_donhang_vua_tao, '$id_sp_db', $soluong_db, $giaban_db)";
            $conn->query($sql_chitiet);
        }

        unset($_SESSION['giohang']);
        
        echo "<script>
                alert('🎉 ĐẶT HÀNG THÀNH CÔNG!\\n\\nMã đơn hàng: #$id_donhang_vua_tao\\nSản phẩm sẽ được giao đến: $dia_chi_day_du\\nChúng tôi sẽ sớm liên hệ qua số $sdt để xác nhận đơn hàng.'); 
                window.location.href='index.php';
              </script>";
        exit;
    } else {
        echo "<script>alert('Lỗi khi lưu đơn hàng: " . $conn->error . "');</script>";
    }
}
?>

<div class="container my-5 pt-4 pb-5">
    <h2 class="text-center mb-5 brand-text" style="font-size: 2.5rem;">Thanh Toán Đơn Hàng</h2>
    
    <form action="thanhtoan.php" method="POST">
        <div class="row">
            
            <!-- CỘT TRÁI: THÔNG TIN GIAO HÀNG -->
            <div class="col-md-7 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header text-white fw-bold py-3" style="background-color: #222;">
                        <i class="bi bi-geo-alt-fill me-2" style="color: #9a7b4f;"></i> THÔNG TIN GIAO HÀNG
                    </div>
                    <div class="card-body p-4 bg-light">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Họ và Tên người nhận <span class="text-danger">*</span></label>
                                <input type="text" name="hoten" class="form-control" value="<?php echo $hoTen_san; ?>" required placeholder="Nhập tên người nhận...">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" name="sdt" class="form-control" required placeholder="Nhập số điện thoại liên hệ...">
                            </div>
                        </div>

                        <!-- Dropdown API Tỉnh Thành -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tỉnh / Thành phố <span class="text-danger">*</span></label>
                                <select name="tinh_thanh" id="province" class="form-select" required>
                                    <option value="">-- Chọn Tỉnh/Thành phố --</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Quận / Huyện <span class="text-danger">*</span></label>
                                <select name="quan_huyen" id="district" class="form-select" required>
                                    <option value="">-- Chọn Quận/Huyện --</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                            <input type="text" name="dia_chi" class="form-control" required placeholder="Số nhà, Tên đường, Phường/Xã...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú thêm (Tùy chọn)</label>
                            <textarea name="ghi_chu" class="form-control" rows="2" placeholder="Ví dụ: Giao hàng trong giờ hành chính..."></textarea>
                        </div>
                        
                        <hr class="my-4">
                        
                        <h5 class="fw-bold mb-3">Phương thức thanh toán</h5>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="pt_thanhtoan" id="cod" value="Thanh toán khi nhận hàng (COD)" checked>
                            <label class="form-check-label" for="cod">
                                Thanh toán tiền mặt khi nhận hàng (COD)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pt_thanhtoan" id="bank" value="Chuyển khoản ngân hàng">
                            <label class="form-check-label" for="bank">
                                Chuyển khoản ngân hàng (Sẽ có nhân viên gọi xác nhận)
                            </label>
                        </div>

                    </div>
                </div>
            </div>

            <!-- CỘT PHẢI: TÓM TẮT ĐƠN HÀNG -->
            <div class="col-md-5">
                <div class="card shadow-sm border-0 sticky-top" style="top: 100px;">
                    <div class="card-header text-white fw-bold py-3" style="background-color: #222;">
                        <i class="bi bi-bag-check-fill me-2" style="color: #9a7b4f;"></i> TÓM TẮT ĐƠN HÀNG
                    </div>
                    <div class="card-body p-4">
                        
                        <?php 
                        $tong_tien_hien_thi = 0;
                        foreach($_SESSION['giohang'] as $sp): 
                            $thanh_tien = $sp['gia'] * $sp['soluong'];
                            $tong_tien_hien_thi += $thanh_tien;
                        ?>
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo $sp['hinhanh']; ?>" style="width: 55px; height: 55px; object-fit: cover; border-radius: 4px; border: 1px solid #eee;" class="me-3">
                                    <div>
                                        <h6 class="mb-1" style="font-size: 0.95rem; font-weight: 600;"><?php echo $sp['ten']; ?></h6>
                                        <small class="text-muted fw-bold">SL: <?php echo $sp['soluong']; ?></small>
                                    </div>
                                </div>
                                <div class="fw-bold" style="color: #9a7b4f; font-size: 1.1rem;">
                                    <?php echo number_format($thanh_tien, 0, ',', '.'); ?> đ
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <!-- Tổng tiền -->
                        <div class="d-flex justify-content-between mt-4 mb-2">
                            <h5 class="fw-bold mb-0">TỔNG CỘNG:</h5>
                            <h3 class="fw-bold text-danger mb-0"><?php echo number_format($tong_tien_hien_thi, 0, ',', '.'); ?> đ</h3>
                        </div>
                        <p class="text-end text-muted small mb-4">(Đã bao gồm VAT & Miễn phí vận chuyển)</p>

                        <!-- Nút Chốt -->
                        <button type="submit" name="dat_hang" class="btn btn-dark w-100 py-3 fw-bold" style="background-color: #222; letter-spacing: 1px; font-size: 1.1rem; transition: 0.3s;">
                            XÁC NHẬN ĐẶT HÀNG
                        </button>
                        
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<!-- API Việt Nam Lấy Tỉnh Thành -->
<script>
    fetch('https://provinces.open-api.vn/api/?depth=2')
        .then(response => response.json())
        .then(data => {
            let provinceSelect = document.getElementById('province');
            let districtSelect = document.getElementById('district');
            
            data.forEach(province => {
                let option = document.createElement('option');
                option.value = province.name;         
                option.dataset.code = province.code;  
                option.text = province.name;
                provinceSelect.add(option);
            });

            provinceSelect.addEventListener('change', function() {
                districtSelect.innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>'; 
                let selectedCode = this.options[this.selectedIndex].dataset.code;
                
                if (selectedCode) {
                    let selectedProvince = data.find(p => p.code == selectedCode);
                    if (selectedProvince && selectedProvince.districts) {
                        selectedProvince.districts.forEach(district => {
                            let option = document.createElement('option');
                            option.value = district.name;
                            option.text = district.name;
                            districtSelect.add(option);
                        });
                    }
                }
            });
        });
</script>

<?php include 'footer.php'; ?>