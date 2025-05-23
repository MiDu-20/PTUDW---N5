<?php
session_start(); // Bắt đầu phiên làm việc

// Lấy email và mật khẩu từ form đăng nhập
$email = $_POST['email'];
$password = $_POST['password'];

//-- Biến $password bị ghi đè - sử dụng cùng tên biến cho hai mục đích khác nhau
// Thiết lập kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant";

// Bật báo cáo lỗi
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//-- Lấy email và password từ POST hai lần - mã lặp lại
// Lấy email và mật khẩu từ form đăng nhập
$email = $_POST['email'];
$password = $_POST['password'];

//-- So sánh mật khẩu văn bản thuần túy - không an toàn, nên sử dụng password_verify() 
// Kiểm tra email và mật khẩu có khớp với người dùng hoặc quản trị viên không
// Chuẩn bị truy vấn SQL cho bảng users
$sql_users = "SELECT * FROM users WHERE email = ? AND password = ?";
$stmt_users = $conn->prepare($sql_users);
$stmt_users->bind_param("ss", $email, $password);
$stmt_users->execute();
$result_users = $stmt_users->get_result();

//-- So sánh mật khẩu văn bản thuần túy - không an toàn, nên sử dụng password_verify()
// Chuẩn bị truy vấn SQL cho bảng staff
$sql_staff = "SELECT * FROM staff WHERE email = ? AND password = ?";
$stmt_staff = $conn->prepare($sql_staff);
$stmt_staff->bind_param("ss", $email, $password);
$stmt_staff->execute();
$result_staff = $stmt_staff->get_result();

try {
    // Kiểm tra thông tin đăng nhập cho người dùng thông thường
    if ($result_users->num_rows > 0) {
        // Lưu email người dùng vào phiên làm việc
        $_SESSION['email'] = $email;
        $_SESSION['userloggedin'] = true;

        echo '<script>alert("User is logged in!"); window.location.href="menu.php";</script>';
        exit();
    } 
    // Kiểm tra thông tin đăng nhập cho nhân viên (admin hoặc superadmin)
    else if ($result_staff->num_rows > 0) {
        $staff = $result_staff->fetch_assoc();
        if ($staff['role'] === 'superadmin' || $staff['role'] === 'admin') {
            // Lưu email admin vào phiên làm việc
            $_SESSION['email'] = $email;
            $_SESSION['adminloggedin'] = true;

            echo '<script>alert("Admin is logged in!"); window.location.href="Admin/index.php";</script>';
            exit();
        } else {
            // Nếu vai trò không phải admin hoặc superadmin, chuyển hướng đến trang đăng nhập với thông báo lỗi
            header('Location: login.php?error=not_authorized');
            exit();
        }
    } else {
        // Chuyển hướng đến trang đăng nhập với thông báo lỗi
        header('Location: login.php?error');
        exit();
    }
} catch (Exception $e) {
    // Xử lý lỗi (ví dụ: ghi nhật ký lỗi)
    header('Location: login.php?error');
    exit();
}

//-- Không có bảo vệ chống tấn công brute force - không giới hạn số lần đăng nhập thất bại
//-- Không có bảo vệ CSRF - không có token xác thực
//-- Không tạo lại ID phiên khi đăng nhập thành công - dễ bị tấn công session fixation
// Đóng kết nối
$conn->close();
