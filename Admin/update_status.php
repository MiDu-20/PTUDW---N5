<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Kết nối đến cơ sở dữ liệu
  include 'db_connection.php';

  $reservation_id = $_POST['reservation_id'];
  $status = $_POST['status'];

  //Chuẩn bị và thực thi câu truy vấn cập nhật
  $stmt = $conn->prepare("UPDATE reservations SET status = ? WHERE reservation_id = ?");
  $stmt->bind_param("si", $status, $reservation_id);

  if ($stmt->execute()) {
    echo "Status updated successfully";
  } else {
    echo "Error updating status: " . $conn->error;
  }

  $stmt->close();
  $conn->close();
}




?>

