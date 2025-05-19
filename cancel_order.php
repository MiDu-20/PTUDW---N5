<?php
// --Kết nối cơ sở dữ liệu
include 'db_connection.php';

// --Chỉ xử lý nếu là phương thức POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // --Lấy ID đơn hàng và lý do hủy từ POST
    $ma_don = isset($_POST['orderId']) ? intval($_POST['orderId']) : 0;
    $ly_do = isset($_POST['reason']) ? trim($_POST['reason']) : '';

    error_log("Nhận được mã đơn: $ma_don, lý do: $ly_do"); // --Ghi log phục vụ debug

    // --Kiểm tra dữ liệu đầu vào hợp lệ
    if ($ma_don > 0 && !empty($ly_do)) {
        // --Cập nhật trạng thái đơn hàng thành 'Đã hủy' và lưu lý do
        $stmt = $conn->prepare("UPDATE orders SET order_status = 'Cancelled', cancel_reason = ? WHERE order_id = ?");
        $stmt->bind_param("si", $ly_do, $ma_don);

        if ($stmt->execute()) {
            echo "Đơn hàng đã được hủy.";
        } else {
            error_log("Lỗi cơ sở dữ liệu: " . $stmt->error); // --Ghi log lỗi nếu có
            echo "Không thể hủy đơn hàng.";
        }

        $stmt->close();
    } else {
        echo "Mã đơn hàng hoặc lý do không hợp lệ.";
    }
} else {
    echo "Yêu cầu không hợp lệ.";
}

$conn->close();
?>
