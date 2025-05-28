<?php
// Cấu hình kết nối database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant";

// Bật chế độ hiển thị lỗi để debug (bạn có thể tắt khi đưa lên production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Kết nối database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Hàm kiểm tra và lọc dữ liệu đầu vào
function validate_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy và xử lý dữ liệu đầu vào
    $email = validate_input($_POST['email']);
    $firstName = validate_input($_POST['firstName']);
    $lastName = validate_input($_POST['lastName']);
    $contact = validate_input($_POST['contact']);
    $role = validate_input($_POST['role']);
    $password_raw = $_POST['password']; // Lấy mật khẩu thô để hash

    $errors = [];

    if (count($errors) > 0) {
        // Hiển thị lỗi nếu có
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
        exit; // Dừng xử lý nếu có lỗi
    }

    // Mã hóa mật khẩu trước khi lưu
    //$password = password_hash($password_raw, PASSWORD_DEFAULT);

    //Mật khẩu không cần Hash: Admin muốn xem mật khẩu (Tuỳ vào nhu cầu người dùng App)
    $password = $password_raw;

    // Chuẩn bị câu truy vấn
    $sql = "INSERT INTO staff (email, firstName, lastName, contact, role, password) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Lỗi chuẩn bị câu truy vấn: " . $conn->error);
    }

    $stmt->bind_param("ssssss", $email, $firstName, $lastName, $contact, $role, $password);

    // Thực thi và kiểm tra
    if ($stmt->execute()) {
        echo '<script>alert("Thêm nhân viên thành công!"); window.location.href="staffs.php";</script>';
    } else {
        echo "<p style='color:red;'>Lỗi: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

$conn->close();
?>
