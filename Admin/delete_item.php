<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $itemId = $_GET['id'];

    $sql = "DELETE FROM menuitem WHERE itemId='$itemId'";
    if ($conn->query($sql) !== TRUE) {
        // Nếu lỗi, ghi log (tùy chọn)
        error_log("Lỗi xóa món: " . $conn->error);
    }

    $conn->close();
    // Phải gọi header trước khi có bất kỳ echo hay output nào
    header("Location: admin_menu.php");
    exit();
}
?>
