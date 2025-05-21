<?php
// Establish a connection to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant";

// Hiển thị lỗi được bật trong môi trường sản xuất - có thể lộ thông tin nhạy cảm
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('display_errors', 1); //dòng này bị lặp lại

//Thông tin đăng nhập cơ sở dữ liệu được mã hóa cứng trong mã nguồn
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error); //hiển thị thông tin lỗi chi tiết cho người dùng - nên ghi log thay vì hiển thị
}
?>
