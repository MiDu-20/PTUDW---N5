<?php
// Kết nối đến cơ sở dữ liệu
include 'db_connection.php';

// Kiểm tra nếu có tham số id được truyền qua URL
if (isset($_GET['id'])) {
    $itemId = $_GET['id'];

    // Câu truy vấn xóa món ăn dựa theo itemId
    $sql = "DELETE FROM menuitem WHERE itemId='$itemId'";

    // Thực thi câu truy vấn
    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    // Đóng kết nối cơ sở dữ liệu
    $conn->close();
    // Chuyển hướng về lại trang quản lý thực đơn
    header("Location: admin_menu.php");
    exit();
}
?>
