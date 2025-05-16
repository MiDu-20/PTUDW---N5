<?php
// Bắt đầu hoặc tiếp tục session hiện tại để có thể truy cập vào các biến session
session_start(); 
// Xoá toàn bộ dữ liệu session hiện tại (đăng xuất người dùng)
session_destroy();
// Chuyển hướng người dùng về trang đăng nhập (login.php)
header("Location: login.php");
// Đảm bảo script dừng hẳn, không chạy thêm gì sau khi chuyển hướng
exit();
?>