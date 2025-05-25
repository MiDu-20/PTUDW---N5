<?php
// Kết nối đến cơ sở dữ liệu
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Lấy dữ liệu từ form
  $response = $_POST['response'];
  $order_id = $_POST['order_id'];
  

  // Chuẩn bị câu lệnh SQL với prepared statement
  $stmt = $conn->prepare("UPDATE reviews SET response=? WHERE order_id=?");
  $stmt->bind_param("si", $response, $order_id);

  // Thực thi câu lệnh và kiểm tra kết quả
  if ($stmt->execute()) {
    echo "Review updated successfully.";
  } else {
    echo "Error Updating Reservation: " . $stmt->error;
  }

  // Đóng kết nối
  $stmt->close();
  $conn->close();

  // Chuyển hướng trở lại trang reviews
  header("Location: reviews.php");
  exit();
}
?>
