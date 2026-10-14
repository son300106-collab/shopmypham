<?php
$servername = "localhost";
$username = "root";
$password = ""; // XAMPP mặc định mật khẩu để trống
$dbname = "vietskin_cosmetics"; // Đã đổi sang Database mới

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối CSDL thất bại: " . $conn->connect_error);
}

// Set charset utf8mb4 để đọc ghi tiếng Việt có dấu không bị lỗi font
$conn->set_charset("utf8mb4");
?>