<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemName = trim($_POST['itemName'] ?? '');
    $price = $_POST['price'] ?? '';
    $description = trim($_POST['description'] ?? '');
    $catName = trim($_POST['catName'] ?? '');
    $image = $_FILES['image'] ?? null;

    if ($itemName !== '' && $price !== '' && $description !== '' && $catName !== '' && $image && $image['error'] === 0) {
        $itemName = mysqli_real_escape_string($conn, $itemName);
        $price = floatval($price);
        $description = mysqli_real_escape_string($conn, $description);
        $catName = mysqli_real_escape_string($conn, $catName);

        $uploadDir = '../uploads/';
        $imageName = basename($image['name']);
        $targetFile = $uploadDir . $imageName;

        if (move_uploaded_file($image['tmp_name'], $targetFile)) {
            $sql = "INSERT INTO menuitem (itemName, price, description, catName, image) VALUES ('$itemName', $price, '$description', '$catName', '$imageName')";
            if (mysqli_query($conn, $sql)) {
                // Thêm thành công, redirect về admin_menu với thông báo
                header("Location: admin_menu.php?success=add_item");
                exit();
            } else {
                echo "Lỗi thêm món ăn: " . mysqli_error($conn);
            }
        } else {
            echo "Lỗi upload hình ảnh!";
        }
    } else {
        echo "Dữ liệu nhập không hợp lệ hoặc thiếu!";
    }
} else {
    echo "Phương thức không hợp lệ!";
}
?>
