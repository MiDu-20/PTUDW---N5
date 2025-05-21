<?php
// Bắt đầu phiên làm việc (session) để lưu thông tin người dùng
session_start();

// Kết nối đến cơ sở dữ liệu
require 'db_connection.php';

// Kiểm tra xem các dữ liệu cần thiết có được gửi từ client (thông qua phương thức POST) hay không
if (isset($_POST['id']) && isset($_POST['quantity']) && isset($_POST['total_price'])) {
    // Lấy giá trị từ form hoặc AJAX gửi lên
    $id = $_POST['id'];                   // ID của sản phẩm trong giỏ hàng
    $quantity = $_POST['quantity'];       // Số lượng mới mà người dùng muốn cập nhật
    $total_price = $_POST['total_price']; // Thành tiền mới tương ứng với số lượng

    // Cập nhật số lượng và thành tiền trong giỏ hàng với id tương ứng
    $stmt = $conn->prepare('UPDATE cart SET quantity=?, total_price=? WHERE id=?');
    $stmt->bind_param('idi', $quantity, $total_price, $id); // i: integer, d: double
    $stmt->execute(); // Thực thi câu truy vấn cập nhật
}
?>

