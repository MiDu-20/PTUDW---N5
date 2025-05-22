<?php
// Thiết lập thông tin kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant";

// Tạo kết nối đến cơ sở dữ liệu MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối, nếu có lỗi thì dừng chương trình và in ra lỗi
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Câu truy vấn để đếm tổng số lượt đặt chỗ (dòng dữ liệu) trong bảng reservations
$sql = "SELECT COUNT(*) AS totalReservations FROM reservations";
$result = $conn->query($sql);

// Kiểm tra nếu có kết quả trả về
if ($result->num_rows > 0) {
  // Lấy dòng kết quả dưới dạng mảng kết hợp (associative array)
  $row = $result->fetch_assoc();
  $totalReservations = $row["totalReservations"];
} else {
  // Nếu không có kết quả nào (bảng rỗng), gán giá trị 0
  $totalReservations = 0;
}

// Đóng kết nối đến cơ sở dữ liệu
$conn->close();

// Trả về kết quả dưới dạng JSON cho phía client (thường dùng trong Ajax)
header('Content-Type: application/json');
echo json_encode(['totalReservations' => $totalReservations]);
?>

