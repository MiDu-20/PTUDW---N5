<?php
// Kết nối đến cơ sở dữ liệu
include 'db_connection.php';

// Kiểm tra nếu là phương thức POST (khi gửi form thêm món mới)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $itemName = $_POST['itemName'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $catName = $_POST['catName'];
    $dateCreated = date("Y-m-d H:i:s");
    $updatedDate = date("Y-m-d H:i:s");

    // Xử lý upload ảnh
    $target_dir = "../uploads/"; // Thư mục lưu ảnh
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1; // Mặc định cho phép upload

    // Lấy đuôi file ảnh
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Kiểm tra xem file có phải là ảnh không (không bắt buộc nếu muốn nhận mọi định dạng)
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check !== false) {
        // Là ảnh hợp lệ
        $uploadOk = 1;
    } else {
        // Nếu không cần kiểm tra ảnh thì bỏ đoạn này
        // echo "File is not an image.";
        // $uploadOk = 0;
    }

    // Giới hạn kích thước file (có thể điều chỉnh giới hạn này nếu cần)
    if ($_FILES["image"]["size"] > 50000000) {
        echo "Xin lỗi, file của bạn quá lớn.";
        $uploadOk = 0;
    }

    // Kiểm tra nếu có lỗi khi upload
    if ($uploadOk == 0) {
        echo "Xin lỗi, file của bạn không được tải lên.";
    } else {
        // Nếu không có lỗi, tiến hành upload file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Upload thành công, lấy tên ảnh để lưu vào DB
            $image = $_FILES["image"]["name"];

            // Dùng prepared statement để chống SQL injection
            $stmt = $conn->prepare("INSERT INTO menuitem (itemName, price, description, image, status, catName, dateCreated, updatedDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $itemName, $price, $description, $image, $status, $catName, $dateCreated, $updatedDate);

            if ($stmt->execute()) {
                // Thành công: hiển thị thông báo và chuyển hướng về trang admin_menu
                echo '<script>alert("Thêm món mới thành công."); window.location.href="admin_menu.php";</script>';
                exit();
            } else {
                echo "Lỗi: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Xin lỗi, có lỗi xảy ra khi tải file.";
        }
    }

    // Đóng kết nối
    $conn->close();

    // Chuyển hướng về lại trang quản lý menu
    header("Location: admin_menu.php");
    exit();
}
?>
