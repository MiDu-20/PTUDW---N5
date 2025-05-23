<?php
// Thiết lập kết nối đến cơ sở dữ liệu  
$servername = "localhost"; // Địa chỉ máy chủ cơ sở dữ liệu
$username = "root"; // Tên người dùng cơ sở dữ liệu
$password = ""; // Mật khẩu cơ sở dữ liệu (trống)
$dbname = "restaurant"; // Tên cơ sở dữ liệu

// Hiển thị lỗi được bật trong môi trường sản xuất - có thể lộ thông tin nhạy cảm
// Bật báo cáo lỗi để dễ dàng gỡ lỗi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('display_errors', 1); //dòng này bị lặp lại

//Thông tin đăng nhập cơ sở dữ liệu được mã hóa cứng trong mã nguồn
// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error); //hiển thị thông tin lỗi chi tiết cho người dùng - nên ghi log thay vì hiển thị
}
?>
