<?php
// Thiết lập kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Truy vấn đếm tổng số món ăn trong bảng menuitem
$sql = "SELECT COUNT(*) AS totalItems FROM menuitem";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $totalItems = $row["totalItems"];
} else {
  $totalItems = 0;
}
// Đóng kết nối CSDL
$conn->close();

// Trả về kết quả dạng JSON
header('Content-Type: application/json');
echo json_encode(['totalItems' => $totalItems]);
?>



