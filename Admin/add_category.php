<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $catName = trim($_POST['catName'] ?? '');

    if ($catName !== '') {
        $catName = mysqli_real_escape_string($conn, $catName);

        $sql = "INSERT INTO menucategory (catName) VALUES ('$catName')";
        if (mysqli_query($conn, $sql)) {
            // Thêm thành công, redirect về trang quản lý với thông báo
            header("Location: admin_menu.php?success=add_category");
            exit();
        } else {
            echo "Lỗi thêm danh mục: " . mysqli_error($conn);
        }
    } else {
        echo "Tên danh mục không được để trống!";
    }
} else {
    echo "Phương thức không hợp lệ!";
}
?>
