<?php
// Kết nối đến cơ sở dữ liệu
include 'db_connection.php';

// Kiểm tra nếu là phương thức POST (gửi từ form chỉnh sửa món ăn)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Debug: In ra toàn bộ dữ liệu POST để kiểm tra
  echo "<pre>";
  print_r($_POST);
  echo "</pre>";

  // Lấy dữ liệu từ form
  $itemId = $_POST['itemId'];
  $itemName = $_POST['itemName'];
  $description = $_POST['description'];
  $price = $_POST['price'];
  $status = $_POST['status'];
  $catName = $_POST['catName'];
  $image = $_FILES['image']['name'] ? $_FILES['image']['name'] : $_POST['existingImage'];

  // In ra thông tin để kiểm tra (tạm thời dùng cho debug)
  echo "Item ID: $itemId<br>";
  echo "Item Name: $itemName<br>";
  echo "Description: $description<br>";
  echo "Price: $price<br>";
  echo "Status: $status<br>";
  echo "Category Name: $catName<br>";
  echo "Image: $image<br>";

    // Xử lý upload ảnh nếu có ảnh mới
    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($image);

        // Kiểm tra xem có đúng là file ảnh không
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Upload thành công
            } else {
                // Lỗi khi upload
                die("Lỗi khi tải ảnh lên.");
            }
        } else {
            die("File không phải là ảnh.");
        }
    } else {
        // Nếu không có ảnh mới thì dùng ảnh cũ
        $image = $_POST['existingImage'];
    }

    // Câu truy vấn cập nhật thông tin món ăn
    $sql = "UPDATE menuitem SET itemName=?, description=?, price=?, status=?, catName=?, image=? WHERE itemId=?";

    // Chuẩn bị truy vấn và bind tham số để tránh SQL injection
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssssi", $itemName, $description, $price, $status, $catName, $image, $itemId);
        if ($stmt->execute()) {
            // Thành công: quay về trang quản lý menu
            header("Location: admin_menu.php");
            exit();
        } else {
            echo "Lỗi khi cập nhật món: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Lỗi khi chuẩn bị câu lệnh: " . $conn->error;
    }

    // Đóng kết nối
    $conn->close();
}
?>