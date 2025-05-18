<?php

// Kết nối đến cơ sở dữ liệu
include('db_connection.php'); 
// Kiểm tra nếu phương thức là POST (gửi từ form hoặc AJAX xoá danh mục)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $catName = $_POST['catName'];

    // Làm sạch dữ liệu đầu vào để tránh SQL injection
    $catName = mysqli_real_escape_string($conn, $catName);

    // Câu truy vấn xoá danh mục khỏi bảng menucategory
    $sql = "DELETE FROM menucategory WHERE catName='$catName'";
    // Thực thi truy vấn
    if (mysqli_query($conn, $sql)) {
        echo "Category deleted successfully.";
    } else {
        echo "Error deleting category: " . mysqli_error($conn);
    }
    // Đóng kết nối
    mysqli_close($conn);
}
?>
