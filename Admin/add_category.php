<?php
// Kết nối đến cơ sở dữ liệu
include 'db_connection.php';
// Kiểm tra nếu là phương thức POST (gửi form thêm danh mục mới)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $catName = $conn->real_escape_string($_POST['catName']);
    $dateCreated = date("Y-m-d H:i:s");
    // Câu lệnh SQL thêm danh mục mới vào bảng menucategory
    $sql = "INSERT INTO menucategory (catName, dateCreated) VALUES ('$catName', '$dateCreated')";
    // Thực thi câu truy vấn
    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Category added successfully."); window.location.href="admin_menu.php";</script>';
        exit();
    } else {
        // Thất bại: in lỗi ra màn hình
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    // Đóng kết nối
    $conn->close();
    // Chuyển hướng lại trang admin menu (dự phòng nếu JS không chạy)
    header("Location: admin_menu.php");
    exit();
}
?>
