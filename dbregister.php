

<?php
//-- Mã lặp lại - nên sử dụng tệp db_connection.php
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

//-- Kết nối được tạo hai lần
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//-- Không có xác thực đầu vào - dữ liệu người dùng được sử dụng trực tiếp
// Lấy thông tin người dùng từ form đăng ký
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];
$contact = $_POST['contact'];
$password = $_POST['password']; //-- Mật khẩu không được mã hóa - nên sử dụng password_hash()

//-- Tiết lộ thông tin cơ sở dữ liệu trong thông báo lỗi
// Chuẩn bị câu lệnh SQL để chèn dữ liệu người dùng mới
$sql = "INSERT INTO users (firstName, lastName, email, contact,  password) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $firstName, $lastName, $email, $contact, $password);

try {
    if ($stmt->execute()) {
        // Đăng ký thành công, chuyển hướng đến trang đăng nhập
        echo '<script>alert("Registered successfully!"); window.location.href="login.php";</script>';
        exit();
    } else {
        $error = $conn->error;
        // Kiểm tra lỗi trùng lặp email
        if (strpos($error, 'Duplicate entry') !== false) {
            $email = explode("'", $error)[3];
            echo '<script>alert("Email already exists: ' . $email . '"); window.location.href="login.php";</script>';
            exit();
        }
        throw new Exception($error);
    }
} catch (Exception $e) {
    // Xử lý lỗi trùng lặp email
    if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        $email = explode("'", $e->getMessage())[3];
        echo '<script>alert("Email already exists."); window.location.href="login.php";</script>';
        exit();
    }
    // Xử lý các lỗi khác
    //-- Hiển thị thông báo lỗi chi tiết cho người dùng - có thể tiết lộ thông tin nhạy cảm
    echo '<script>alert("Registration failed! Error: ' . $e->getMessage() . '"); window.location.href="login.php";</script>';
    exit();
}

// Đóng kết nối
$stmt->close();
$conn->close();
?>
