<?php
// Bắt đầu session và kiểm tra quyền truy cập admin
session_start();
if (!isset($_SESSION['adminloggedin'])) {
    header("Location: ../login.php");
    exit();
}
// Kết nối đến cơ sở dữ liệu
include 'db_connection.php';
// Kiểm tra nếu phương thức là POST (form cập nhật trạng thái đơn hàng)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'];
    $orderStatus = $_POST['order_status'];
    $cancelReason = $_POST['cancel_reason'] ?? '';
    // Nếu trạng thái là Cancelled nhưng không có lý do thì trả lỗi
    if ($orderStatus === 'Cancelled' && empty($cancelReason)) {
        $_SESSION['message'] = "Cancellation reason is required.";
        header("Location: view.php?orderId=" . $orderId);
        exit();
    }
    // Cập nhật trạng thái đơn hàng trong bảng orders
    $updateQuery = "UPDATE orders SET order_status = ?, cancel_reason = ? WHERE order_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('ssi', $orderStatus, $cancelReason, $orderId);
    // Thực thi và xử lý phản hồi
    if ($stmt->execute()) {
        $_SESSION['message'] = "Order status updated successfully.";
    } else {
        $_SESSION['message'] = "Failed to update order status.";
    }
    // Quay lại trang xem đơn hàng
    header("Location: view_order.php?orderId=" . $orderId);
    exit();
} else {
    header("Location: admin_orders.php");
    exit();
}
?>
