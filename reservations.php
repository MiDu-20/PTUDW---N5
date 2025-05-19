<?php
//-- Cấu hình thông tin kết nối đến cơ sở dữ liệu
$servername = "localhost"; //-- Tên máy chủ cơ sở dữ liệu
$username = "root";
$password = "";
$dbname = "restaurant"; //--Tên cơ sở dữ liệu cần kết nối

//-- Bật chế độ hiển thị lỗi để dễ dàng debug 
ini_set('display_errors', 1); //-- Hiển thị lỗi khi có lỗi xảy ra
ini_set('display_startup_errors', 1); //-- Hiển thị lỗi khi khởi động
error_reporting(E_ALL); //-- Hiển thị tất cả các lỗi

//-- Tạo kết nối đến cơ sở dữ liệu MySQL 
$conn = new mysqli($servername, $username, $password, $dbname);

//-- Nếu kết nối thất bại thì dừng chương trình và hiển thị lỗi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//-- Thiết lập múi giờ mặc định
date_default_timezone_set('Asia/Colombo');

//-- Kiểm tra xem yêu cầu từ form gửi đến server có phải là phương thức POST không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //-- Lấy dữ liệu người dùng nhập từ form
    $email = $_POST['email'];
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $noOfGuests = $_POST['noOfGuests'];
    $reservedTime = $_POST['reservedTime']; //-- Định dạng HH:MM
    $reservedDate = $_POST['reservedDate']; //-- Định dạng YYYY-MM-DD

    

    //== Chuyển đổi thời gian đặt sang định dạng có bao gồm giây (HH:MM:SS)
    $reservedTimeWithSeconds = date('H:i:s', strtotime($reservedTime));
    
   
    //-- Câu truy vấn SQL để chèn dữ liệu đặt bàn vào bảng reservations
    $sql = "INSERT INTO reservations (email, name, contact, noOfGuests, reservedTime, reservedDate) 
            VALUES (?, ?, ?, ?, ?, ?)";

    // -- Chuẩn bị truy vấn SQL bằng prepare statement để tránh lỗi SQL injection
    $stmt = $conn->prepare($sql);
    //-- Nếu chuẩn bị truy vấn thất bại thì dừng chương trình và in lỗi
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    //-- Gán các biến dữ liệu người dùng vào các tham số trong truy vấn
    //-- "sssiis" là định dạng dữ liệu: s = string, i = integer
    $stmt->bind_param("sssiis", $email, $name, $contact, $noOfGuests, $reservedTimeWithSeconds, $reservedDate);

    //-- Thực thi truy vấn (chèn dữ liệu vào bảng)
    if ($stmt->execute()) {
        //-- Nếu thành công thì hiện thông báo và chuyển hướng về trang chính
        echo '<script>alert("Reservation successful!"); window.location.href="index.php";</script>';
    } else {
        //-- Nếu có lỗi khi thực thi thì in ra lỗi
        echo "Error: " . $stmt->error;
    }

    //-- Đóng đối tượng statement sau khi thực hiện xong
    $stmt->close();
}

//-- Đóng kết nối cơ sở dữ liệu sau khi xử lý xong
$conn->close();
?>
