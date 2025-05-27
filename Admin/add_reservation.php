<?php
//--Cấu hình thông tin kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "restaurant";

//--Bật hiển thị lỗi (phục vụ việc debug trong quá trình phát triển)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//--Tạo kết nối đến cơ sở dữ liệu
$conn = new mysqli($servername, $username, $password, $dbname);

//-- Kiểm tra kết nối có thành công không
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//--Xử lý khi người dùng gửi form (phương thức POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form người dùng nhập
    $email = $_POST['email'];
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $noOfGuests = $_POST['noOfGuests'];
    $reservedTime = $_POST['reservedTime']; // Định dạng nhập vào: 'HH:MM'
    $reservedDate = $_POST['reservedDate']; //Định dạng nhập vào: 'YYYY-MM-DD'

    // Debug: Hiển thị giờ đặt chỗ gốc (người dùng nhập)
    echo "Raw Reserved Time: " . htmlspecialchars($reservedTime) . "<br>";

    // Xử lý định dạng giờ để bổ sung thêm giây (chuẩn hóa về 'HH:MM:SS')
    $reservedTimeWithSeconds = date('H:i:s', strtotime($reservedTime));
    
    // Debug: Hiển thị giờ đặt chỗ sau khi xử lý
    echo "Processed Reserved Time: " . htmlspecialchars($reservedTimeWithSeconds) . "<br>";

    // Chuẩn bị câu lệnh SQL để thêm dữ liệu vào bảng reservations
    $sql = "INSERT INTO reservations (email, name, contact, noOfGuests, reservedTime, reservedDate) 
            VALUES (?, ?, ?, ?, ?, ?)";

    // Chuẩn bị và gán giá trị cho các tham số
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sssiis", $email, $name, $contact, $noOfGuests, $reservedTimeWithSeconds, $reservedDate);

    // Thực thi câu lệnh
    if ($stmt->execute()) {
        echo '<script>alert("Đặt bàn thành công!"); window.location.href="reservations.php";</script>';
    } else {
        echo "Error: " . $stmt->error;
    }

    // Đóng câu lệnh
    $stmt->close();
}

// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>
