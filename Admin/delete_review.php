<?php
// Kết nối đến cơ sở dữ liệu
include 'db_connection.php';

// Lấy dữ liệu đầu vào dạng JSON từ HTTP request body
$input = file_get_contents('php://input'); // Đọc toàn bộ nội dung gửi đến
$data = json_decode($input, true);

// Trích xuất orderId và email từ dữ liệu JSON
$orderId = $data['orderId'];
$email = $data['email'];

// Kiểm tra dữ liệu đầu vào có hợp lệ không
if (empty($orderId) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit();
}

// Chuẩn bị câu lệnh SQL để xóa đánh giá từ bảng reviews
$stmt = $conn->prepare("DELETE FROM reviews WHERE order_id = ? AND email = ?");
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Statement preparation failed']);
    exit();
}

// Gán tham số cho câu lệnh SQL
$stmt->bind_param('is', $orderId, $email);
// Thực thi câu lệnh xóa
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Xóa đánh giá thành công.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi xóa đánh giá.']);
}

// Đóng statement và kết nối
$stmt->close();
$conn->close();
?>
