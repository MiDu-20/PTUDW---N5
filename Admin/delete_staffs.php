<?php
session_start();

// Kiểm tra xem người dùng đã đăng nhập với quyền admin chưa
if (!isset($_SESSION['adminloggedin'])) {
    // Nếu chưa đăng nhập, trả về mã lỗi 403 (Forbidden) và thoát
    http_response_code(403);
    exit();
}

include 'db_connection.php'; // Kết nối tới cơ sở dữ liệu

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy email của nhân viên cần xóa từ dữ liệu POST
    $email = $_POST['email'];

    // Chuẩn bị câu lệnh SQL để xóa nhân viên theo email
    $stmt = $conn->prepare("DELETE FROM staff WHERE email = ?");
    $stmt->bind_param("s", $email); // Gán tham số email vào câu lệnh

    // Thực thi câu lệnh xóa
    if ($stmt->execute()) {
        // Nếu thành công, in ra thông báo xóa thành công
        echo 'Xóa nhân viên thành công';
    } else {
        // Nếu có lỗi xảy ra, trả về mã lỗi 500 (Lỗi máy chủ) và thông báo lỗi
        http_response_code(500);
        echo 'Lỗi khi xóa nhân viên';
    }

    // Đóng câu lệnh chuẩn bị
    $stmt->close();
    // Đóng kết nối cơ sở dữ liệu
    $conn->close();
}
?>
