<?php 
include 'header.php'; 

// Xử lý khi người dùng bấm nút Đăng Nhập
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $matkhau = $_POST['matkhau'];
    
    // Câu lệnh kiểm tra trong CSDL
    $sql = "SELECT * FROM NguoiDung WHERE Email = '$email' AND MatKhau = '$matkhau'";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Lưu thông tin vào SESSION
        $_SESSION['ID_User'] = $user['ID_User'];
        $_SESSION['HoTen'] = $user['HoTen'];
        $_SESSION['VaiTro'] = $user['VaiTro'];
        
        // Chuyển hướng
        if ($user['VaiTro'] == 'Admin') {
            echo "<script>alert('Đăng nhập Quản trị viên thành công!'); window.location.href='admin.php';</script>";
        } else {
            echo "<script>alert('Đăng nhập thành công!'); window.location.href='index.php';</script>";
        }
    } else {
        $error = "Email hoặc Mật khẩu không chính xác!";
    }
}
?>

<div class="container my-5 pt-4 pb-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <h2 class="text-center mb-4 brand-text" style="font-size: 2rem;">Đăng Nhập</h2>
            
            <?php if(isset($error)): ?>
                <div class="alert alert-danger text-center"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="dangnhap.php" method="POST" class="p-4 border rounded shadow-sm bg-light">
                <div class="mb-3">
                    <label class="form-label fw-bold">Địa chỉ Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="Nhập email...">
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Mật khẩu</label>
                    <input type="password" name="matkhau" class="form-control" required placeholder="Nhập mật khẩu...">
                </div>
                
                <button type="submit" class="btn btn-custom-dark w-100 mb-3">ĐĂNG NHẬP</button>
                
                <!-- BỔ SUNG: Dòng chữ và link chuyển sang trang Đăng Ký -->
                <p class="text-center small mb-0">
                    Chưa có tài khoản? <a href="dangky.php" class="text-decoration-none fw-bold" style="color: #9a7b4f;">Tạo tài khoản ngay</a>
                </p>
                
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>