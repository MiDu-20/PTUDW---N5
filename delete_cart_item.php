<?php
session_start();
header('Content-Type: application/json');
require 'db_connection.php';

// --Kiểm tra nếu người dùng chưa đăng nhập thì trả về lỗi
if (!isset($_SESSION['userloggedin']) || $_SESSION['userloggedin'] !== true) {
  echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập']);
  exit();
}

// --Lấy email từ session
$email = $_SESSION['email'];

// --Lấy ID sản phẩm cần xóa từ POST, ép kiểu về số nguyên
$ma_muc_gio = intval($_POST['id']);

// --Chuẩn bị truy vấn xóa mục giỏ hàng theo ID và email
$stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND email = ?");
$stmt->bind_param('is', $ma_muc_gio, $email);
$result = $stmt->execute();

// --Phản hồi về kết quả xóa
if ($result) {
  echo json_encode(['success' => true]);
} else {
  echo json_encode(['success' => false, 'message' => 'Xóa sản phẩm khỏi giỏ hàng thất bại']);
}

// --Đóng kết nối
$stmt->close();
$conn->close();
?>
