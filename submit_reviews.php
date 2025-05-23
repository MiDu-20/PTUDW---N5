<?php
session_start(); // Bắt đầu phiên làm việc
include 'db_connection.php'; // Đảm bảo bạn có file db_connection.php để kết nối đến cơ sở dữ liệu

//-- Không có xác thực đầu vào
if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Kiểm tra nếu phương thức yêu cầu là POST
    $orderId = $_POST['orderId']; // Lấy ID đơn hàng từ form
    $reviewText = $_POST['reviewText']; // Lấy nội dung đánh giá từ form
    $rating = $_POST['rating']; // Lấy số sao đánh giá từ form
    $email = $_SESSION['email']; // Lấy email từ phiên làm việc (đảm bảo email này hợp lệ và tồn tại trong bảng users)

    // Xác thực email
    $emailQuery = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $emailQuery->bind_param('s', $email);
    $emailQuery->execute();
    $emailResult = $emailQuery->get_result();
    if ($emailResult->num_rows === 0) {
        die('Error: The email does not exist in the users table.'); //--Hiển thị thông báo lỗi trực tiếp
    }
    $emailQuery->close();

    // Chèn hoặc cập nhật đánh giá
    $stmt = $conn->prepare("INSERT INTO reviews (order_id, email, rating, review_text, response) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE review_text = VALUES(review_text)");
    $stmt->bind_param('isiss', $orderId, $email, $rating, $reviewText, $reviewResponse);//--Biến $reviewResponse không được khai báo

    if ($stmt->execute()) {
        echo '<script>alert("Review submitted successfully!");</script>';
        echo '<script>window.location.href = "orders.php";</script>';
    } else {
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    //-- Không có xử lý cho trường hợp không phải POST
    //-- Không kiểm tra xem đơn hàng có thuộc về người dùng không
}
?>
