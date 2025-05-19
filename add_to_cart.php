<?php
session_start();
require 'db_connection.php';

// --Khởi tạo mảng phản hồi (trạng thái + thông báo)
$response = array('status' => '', 'message' => '');

// --Kiểm tra xem người dùng đã đăng nhập hay chưa thông qua session email
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // --Kiểm tra dữ liệu sản phẩm được gửi qua phương thức POST
    if (isset($_POST['pid']) && isset($_POST['pname']) && isset($_POST['pprice'])) {
        // --Lấy thông tin sản phẩm từ POST
        $ma_san_pham = $_POST['pid'];
        $ten_san_pham = $_POST['pname'];
        $gia_san_pham = $_POST['pprice'];
        $hinh_anh = $_POST['pimage'];
        $ma_code = $_POST['pcode'];
        $so_luong = 1;

        // --Tính tổng giá sản phẩm
        $tong_gia = $gia_san_pham * $so_luong;

        // --Kiểm tra xem sản phẩm đã có trong giỏ hàng của người dùng chưa
        $stmt = $conn->prepare('SELECT itemName FROM cart WHERE itemName=? AND email=?');
        $stmt->bind_param('ss', $ten_san_pham, $email);
        $stmt->execute();
        $res = $stmt->get_result();
        $r = $res->fetch_assoc();
        $da_co = $r['itemName'] ?? '';

        if (!$da_co) {
            // --Chưa có sản phẩm -> thêm vào giỏ hàng
            $query = $conn->prepare('INSERT INTO cart (itemName, price, image, quantity, total_price, email, itemCode) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $query->bind_param('sssids', $ten_san_pham, $gia_san_pham, $hinh_anh, $so_luong, $tong_gia, $email, $ma_code);
            if ($query->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Sản phẩm đã được thêm vào giỏ hàng!';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Lỗi khi thêm sản phẩm vào giỏ hàng!';
            }
        } else {
            // --Sản phẩm đã tồn tại trong giỏ hàng
            $response['status'] = 'exists';
            $response['message'] = 'Sản phẩm đã tồn tại trong giỏ hàng!';
        }
    } else {
        // --Thiếu dữ liệu sản phẩm
        $response['status'] = 'invalid';
        $response['message'] = 'Dữ liệu sản phẩm không hợp lệ!';
    }
} else {
    // --Người dùng chưa đăng nhập
    $response['status'] = 'unauthorized';
    $response['message'] = 'Bạn cần đăng nhập để thêm vào giỏ hàng!';
}

// --Trả kết quả về phía client dạng JSON
echo json_encode($response);
?>
