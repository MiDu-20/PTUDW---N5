<?php
//Kiểm tra xem phương thức gửi dữ liệu là POST (tức là form đã được gửi)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Kết nối đến cơ sở dữ liệu (gồm file chứa thông tin kết nối)
  include 'db_connection.php';
// Lấy ID của đặt chỗ cần xóa từ dữ liệu gửi lên
  $reservation_id = $_POST['reservation_id'];

  // Chuẩn bị và thực thi câu lệnh SQL để xóa đặt chỗ
  $stmt = $conn->prepare("DELETE FROM reservations WHERE reservation_id = ?");
  $stmt->bind_param("i", $reservation_id); // "i" nghĩa là kiểu số nguyên

    // Kiểm tra kết quả thực thi
  if ($stmt->execute()) {
    echo "Xóa đặt bàn thành công.";
  } else {
    echo "Lỗi khi xóa đặt bàn: " . $conn->error;
  }

    // Đóng câu lệnh và kết nối
  $stmt->close();
  $conn->close();
}
?>
