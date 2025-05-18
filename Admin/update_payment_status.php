<?php
// Bắt đầu session và kiểm tra quyền truy cập admin
session_start();
if (!isset($_SESSION['adminloggedin'])) {
    header("Location: ../login.php");
    exit();
}

// Kết nối đến cơ sở dữ liệu
include 'db_connection.php';

// Nhận dữ liệu từ form POST
$orderId = isset($_POST['order_id']) ? $_POST['order_id'] : '';
$paymentStatus = isset($_POST['payment_status']) ? $_POST['payment_status'] : '';

// Kiểm tra dữ liệu đầu vào hợp lệ
if ($orderId && $paymentStatus) {
    // Câu truy vấn cập nhật trạng thái thanh toán trong bảng orders
    $updateQuery = "UPDATE orders SET payment_status = ? WHERE order_id = ?";
    
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('si', $paymentStatus, $orderId);
    
    // Thực thi truy vấn và phản hồi
    if ($stmt->execute()) {
        echo "Success"; // Thành công
    } else {
        echo "Lỗi khi cập nhật trạng thái thanh toán.";
    }
    
    $stmt->close();
} else {
    echo "Thiếu mã đơn hàng hoặc trạng thái thanh toán.";
}

// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>
