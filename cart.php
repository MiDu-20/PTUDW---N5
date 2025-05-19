<?php
session_start();
require 'db_connection.php';

// --Kiểm tra đăng nhập
if (!isset($_SESSION['userloggedin']) || $_SESSION['userloggedin'] !== true) {
  header('location:login.php');
  exit;
}

// --Lấy email người dùng từ session
$email = $_SESSION['email'];

// --Truy vấn thông tin người dùng từ bảng users
$stmt = $conn->prepare('SELECT * FROM users WHERE email=?');
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// --Truy vấn sản phẩm trong giỏ hàng tương ứng với người dùng
$stmt = $conn->prepare('SELECT * FROM cart WHERE email=?');
$stmt->bind_param('s', $email);
$stmt->execute();
$itemsResult = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css' />
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css' />
  <link rel='stylesheet' href='cart.css' />
  <title>Giỏ hàng</title>
</head>

<body>
<?php include 'navbar.php'; ?>

<!-- Nội dung trang giỏ hàng sẽ được hiển thị tiếp ở dưới phần HTML -->
