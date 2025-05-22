<?php
// Kết nối đến cơ sở dữ liệu từ file db_connection.php
include 'db_connection.php';

// Kiểm tra nếu request gửi đến là phương thức POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   // Lấy dữ liệu được gửi từ form (qua phương thức POST)
  $email = $_POST['email'];
  $name = $_POST['name'];
  $contact = $_POST['contact'];
  $noOfGuests = $_POST['noOfGuests'];
  $reservedTime = $_POST['reservedTime']; // Định dạng là 'HH:MM'
  $reservedDate = $_POST['reservedDate']; // Định dạng 'YYYY-MM-DD'
  $status = $_POST['status'];
  $reservation_id = $_POST['reservation_id']; // ID đặt chỗ (được giả định là gửi kèm theo POST)

  // Chuẩn bị câu truy vấn SQL để cập nhật thông tin đặt chỗ
  $stmt = $conn->prepare("UPDATE reservations SET email=?, name=?, contact=?, noOfGuests=?, reservedTime=?, reservedDate=?, status=? WHERE reservation_id=?");
  // Gán giá trị vào các tham số của câu truy vấn, đảm bảo an toàn (tránh SQL injection)
  // Kiểu dữ liệu: s = string, i = integer
  $stmt->bind_param("sssssssi", $email, $name, $contact, $noOfGuests, $reservedTime, $reservedDate, $status, $reservation_id);

  // Thực thi câu truy vấn và kiểm tra kết quả
  if ($stmt->execute()) {
    echo "Reservation updated successfully."; // Thông báo khi thành công
  } else {
    echo "Error Updating Reservation: " . $stmt->error; // Thông báo lỗi nếu thất bại
  }

  // Đóng câu lệnh và kết nối cơ sở dữ liệu
  $stmt->close();
  $conn->close();

  // Chuyển hướng người dùng về trang danh sách đặt chỗ
  header("Location: reservations.php");
  exit(); // Dừng script sau khi chuyển hướng
}
?>
