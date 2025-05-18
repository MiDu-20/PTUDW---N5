<?php
//-- Cấu hình thông tin kết nối đến cơ sở dữ liệu
$servername = "localhost"; //-- Tên máy chủ cơ sở dữ liệu
$username = "root";
$password = "";
$dbname = "restaurant"; //--Tên cơ sở dữ liệu cần kết nối

//-- Bật chế độ hiển thị lỗi để dễ dàng debug 
ini_set('display_errors', 1); //-- Hiển thị lỗi khi có lỗi xảy ra
ini_set('display_startup_errors', 1); //-- Hiển thị lỗi khi khởi động
error_reporting(E_ALL); //-- Hiển thị 

// Establishing connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
date_default_timezone_set('Asia/Colombo');

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collecting form data
    $email = $_POST['email'];
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $noOfGuests = $_POST['noOfGuests'];
    $reservedTime = $_POST['reservedTime']; // Input format is 'HH:MM'
    $reservedDate = $_POST['reservedDate']; // Input format is 'YYYY-MM-DD'

    

    // Process reservedTime to ensure it includes seconds
    $reservedTimeWithSeconds = date('H:i:s', strtotime($reservedTime));
    
   
    // Prepare SQL statement to insert data into reservations table
    $sql = "INSERT INTO reservations (email, name, contact, noOfGuests, reservedTime, reservedDate) 
            VALUES (?, ?, ?, ?, ?, ?)";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sssiis", $email, $name, $contact, $noOfGuests, $reservedTimeWithSeconds, $reservedDate);

    // Execute the statement
    if ($stmt->execute()) {
        echo '<script>alert("Reservation successful!"); window.location.href="index.php";</script>';
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
