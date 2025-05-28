<?php
include 'db_connection.php'; // Kết nối đến cơ sở dữ liệu

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Lấy dữ liệu từ form gửi lên
  $email = $_POST['email'];
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $contact = $_POST['contact'];
  $role = $_POST['role'];
  $password = isset($_POST['password']) ? $_POST['password'] : '';

  // Nếu có mật khẩu mới thì cập nhật luôn mật khẩu
  if (!empty($password)) {
    // Câu SQL cập nhật tất cả thông tin gồm cả mật khẩu
    $sql = "UPDATE staff SET firstName='$firstName', lastName='$lastName', contact='$contact', role='$role', password='$password' WHERE email='$email'";
  } else {
    // Nếu không có mật khẩu mới thì chỉ cập nhật các trường còn lại, bỏ qua mật khẩu
    $sql = "UPDATE users SET firstName='$firstName', lastName='$lastName', contact='$contact', role='$role' WHERE email='$email'";
  }

  // Thực thi câu lệnh SQL cập nhật
  if ($conn->query($sql) === TRUE) {
    echo "<script>
        alert('Đã cập nhật thành công');
        window.location.href = 'staffs.php';
    </script>";
    exit();
  } else {

    $conn->close(); // Đóng kết nối cơ sở dữ liệu

    echo "<script>
        alert('Lỗi khi cập nhật nhân viên: " . addslashes($conn->error) . "');
        window.history.back();
    </script>";
    exit();
  }

  // Chuyển hướng về trang quản lý nhân viên
  //header("Location: staffs.php");

}
?>
