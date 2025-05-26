<?php
// Bắt đầu session để sử dụng biến phiên (session variables)
session_start();
// Kiểm tra nếu chưa đăng nhập với quyền admin thì chặn truy cập
if (!isset($_SESSION['adminloggedin'])) {
    http_response_code(403); // Gửi mã lỗi HTTP 403 - Forbidden (cấm truy cập)
    exit(); // Dừng thực thi script
}
// Kết nối tới cơ sở dữ liệu (file db_connection.php nên chứa kết nối mysqli)
include 'db_connection.php';

// Kiểm tra nếu request gửi lên bằng phương thức POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Chuẩn bị câu lệnh SQL để xóa người dùng theo email
    $stmt = $conn->prepare("DELETE FROM users WHERE email = ?");
    // Gắn giá trị email vào câu truy vấn
    $stmt->bind_param("s", $email);

    // Thực thi câu truy vấn
    if ($stmt->execute()) {
        echo 'User deleted successfully'; // Thông báo xóa thành công
    } else {
        http_response_code(500);
        echo 'Error deleting reservation'; // Thông báo lỗi
    }

    // Đóng statement và kết nối
    $stmt->close();
    $conn->close();
}
?>
