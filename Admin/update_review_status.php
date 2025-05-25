<?php
// Kiểm tra xem yêu cầu gửi đến có phải là POST không
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Kết nối đến cơ sở dữ liệu (file này chứa thông tin kết nối)
  include 'db_connection.php';

  // Lấy dữ liệu được gửi từ client (JavaScript)
  $order_id = $_POST['order_id'];
  $status = $_POST['status'];

  // Ghi log để kiểm tra dữ liệu đã nhận (dùng để debug)
  error_log("Received order_id: $order_id, status: $status");

   // Chuẩn bị câu truy vấn cập nhật bằng prepared statement để chống SQL injection
  $stmt = $conn->prepare("UPDATE reviews SET status = ? WHERE order_id = ?");
  if ($stmt) {
    // Gán giá trị cho các tham số trong câu truy vấn: 
    // "s" là string (status), "i" là integer (order_id)
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
      echo "Status updated successfully";
    } else {
      echo "Error updating status: " . $conn->error;
    }

    $stmt->close();
  } else {
    echo "Prepare statement failed: " . $conn->error;
  }

  $conn->close();
}
?>
