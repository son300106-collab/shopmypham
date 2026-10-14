<?php
session_start();
session_unset(); // Xóa toàn bộ biến session
session_destroy(); // Phá hủy session
header("Location: index.php"); // Chuyển về trang chủ
exit();
?>