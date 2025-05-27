<?php
// Cấu hình kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant";

// Bật chế độ hiển thị lỗi (giúp debug trong quá trình phát triển, có thể tắt khi chạy thực tế)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); // Hiển thị tất cả lỗi

// Tạo kết nối đến cơ sở dữ liệu bằng MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra xem kết nối có thành công không
if ($conn->connect_error) {
    // Nếu thất bại, dừng chương trình và hiển thị lỗi
    die("Connection failed: " . $conn->connect_error);
}

// Kiểm tra nếu form được gửi lên bằng phương thức POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu người dùng nhập từ form
    $email = $_POST['email'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $contact = $_POST['contact'];
    $password = $_POST['password'];
   


    // Chuẩn bị câu lệnh SQL để chèn dữ liệu vào bảng `users`
    $sql = "INSERT INTO users (email, firstName, lastName, contact, password) 
            VALUES (?, ?, ?, ?, ?)";

    // Chuẩn bị statement và gán dữ liệu vào
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        // Nếu có lỗi khi chuẩn bị statement, hiển thị lỗi và dừng lại
        die("Prepare failed: " . $conn->error);
    }
    // Gán giá trị vào các placeholder ? trong câu SQL theo đúng thứ tự
    // sssss: tương ứng 5 chuỗi (string)
    $stmt->bind_param("sssss", $email, $firstName, $lastName, $contact, $password);

    // Thực thi câu lệnh SQL đã chuẩn bị
    if ($stmt->execute()) {
        // Nếu thành công, hiển thị thông báo và chuyển hướng về trang users.php
        echo '<script>alert("Thêm người dùng thành công"); window.location.href="users.php";</script>';
    } else {
        // Nếu thất bại, hiển thị lỗi chi tiết
        echo "Error: " . $stmt->error;
    }

    // Đóng statement sau khi sử dụng xong
    $stmt->close();
}

// Đóng kết nối cơ sở dữ liệu sau khi hoàn tất
$conn->close();
?>
