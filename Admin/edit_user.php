<?php
// Kết nối đến cơ sở dữ liệu (file db_connection.php chứa thông tin kết nối $conn)
include 'db_connection.php';

// Kiểm tra nếu request gửi đến bằng phương thức POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Lấy dữ liệu từ form gửi lên
  $email = $_POST['email'];
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $contact = $_POST['contact'];
  // Kiểm tra nếu có trường password thì lấy, còn không thì để rỗng
  $password = isset($_POST['password']) ? $_POST['password'] : '';

 // Cập nhật dữ liệu người dùng
  // Nếu có thay đổi mật khẩu thì cập nhật cả password
  if (!empty($password)) {
    $sql = "UPDATE users SET firstName='$firstName', lastName='$lastName', contact='$contact', password='$password' WHERE email='$email'";
  } else {
    // Nếu không có password thì chỉ cập nhật các trường còn lại
    $sql = "UPDATE users SET firstName='$firstName', lastName='$lastName', contact='$contact' WHERE email='$email'";
  }
// Thực thi câu truy vấn và kiểm tra kết quả
  if ($conn->query($sql) === TRUE) {
    echo "User updated successfully.";
  } else {
    echo "Error updating user: " . $conn->error;
  }
// Đóng kết nối đến cơ sở dữ liệu
  $conn->close();
  // Chuyển hướng trở lại trang danh sách người dùng
  header("Location: users.php");
  exit(); // Đảm bảo script kết thúc sau khi chuyển hướng
}
?>
