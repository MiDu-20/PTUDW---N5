<?php
// Kết nối đến cơ sở dữ liệu
include 'db_connection.php';
// Kiểm tra nếu phương thức là POST (từ toggle popular)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = $_POST['itemId'];
    $isPopular = $_POST['is_popular'];
    // Câu lệnh cập nhật trạng thái phổ biến trong bảng menuitem
    $sql = "UPDATE menuitem SET is_popular = ? WHERE itemId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $isPopular, $itemId);
    // Thực thi câu lệnh và trả kết quả JSON
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
}
// Đóng kết nối CSDL
$conn->close();
?>
