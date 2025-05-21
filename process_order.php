<?php
session_start();
require 'db_connection.php';

// --vấn đề: Kiểm tra xem người dùng đã đăng nhập chưa
// Nếu chưa đăng nhập thì chuyển hướng về trang login để bắt buộc đăng nhập mới đặt hàng được
if (!isset($_SESSION['userloggedin']) || $_SESSION['userloggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// --vấn đề: Lấy dữ liệu gửi lên từ form đặt hàng bằng phương thức POST
// Sử dụng toán tử null coalescing để tránh lỗi khi biến không tồn tại
$hoTenDem = $_POST['firstName'] ?? '';
$hoTen = $_POST['lastName'] ?? '';
$email = $_POST['email'] ?? '';
$diaChi = $_POST['address'] ?? '';
$sdt = $_POST['contact'] ?? '';
$ghiChuDonHang = $_POST['order_note'] ?? '';
$hinhThucThanhToan = $_POST['payment_mode'] ?? '';
$tongTien = $_POST['total'] ?? 0;
$tongTienTamTinh = $_POST['subtotal'] ?? 0;
// Mảng các mặt hàng đã chọn, giải mã JSON thành mảng PHP, nếu không có thì trả về mảng rỗng
$danhSachMatHang = json_decode($_POST['selected_items'], true) ?? [];

// --vấn đề: Kiểm tra hình thức thanh toán
// Nếu người dùng chọn thanh toán bằng thẻ (card) thì chuyển về trang xem lại đơn hàng (order_review.php)
if ($hinhThucThanhToan === 'card') {
    header('Location: order_review.php');
    exit;
}

// --vấn đề: Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu khi lưu đơn hàng và chi tiết đơn hàng
$conn->begin_transaction();

try {
    // --vấn đề: Chuẩn bị câu lệnh SQL chèn thông tin đơn hàng vào bảng orders
    $stmt = $conn->prepare('INSERT INTO orders (firstName, lastName, email, phone, address, sub_total, grand_total, pmode, note) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
    if ($stmt === false) {
        throw new Exception('Không chuẩn bị được câu lệnh chèn đơn hàng: ' . $conn->error);
    }
    // Gán tham số cho câu lệnh chèn đơn hàng
    $stmt->bind_param('sssssddss', $hoTenDem, $hoTen, $email, $sdt, $diaChi, $tongTienTamTinh, $tongTien, $hinhThucThanhToan, $ghiChuDonHang);
    $stmt->execute();

    // Lấy ID đơn hàng vừa được tạo để dùng cho bảng chi tiết đơn hàng
    $maDonHang = $stmt->insert_id;

    // --vấn đề: Chuẩn bị câu lệnh SQL chèn từng mặt hàng trong đơn vào bảng order_items
    $stmt = $conn->prepare('INSERT INTO order_items (order_id, itemName, quantity, price, total_price, image) VALUES (?, ?, ?, ?, ?, ?)');
    if ($stmt === false) {
        throw new Exception('Không chuẩn bị được câu lệnh chèn chi tiết đơn hàng: ' . $conn->error);
    }
    
    // --vấn đề: Duyệt qua từng mặt hàng trong danh sách chọn của khách
    foreach ($danhSachMatHang as $matHang) {
        $maMatHang = $matHang['id'] ?? 0;
        $soLuong = $matHang['quantity'] ?? 0;

        // --vấn đề: Lấy chi tiết mặt hàng từ bảng giỏ hàng theo ID và email người dùng
        $itemStmt = $conn->prepare('SELECT * FROM cart WHERE id=? AND email=?');
        $itemStmt->bind_param('is', $maMatHang, $email);
        $itemStmt->execute();
        $itemResult = $itemStmt->get_result();
        $chiTietMatHang = $itemResult->fetch_assoc();

        if ($chiTietMatHang === null) {
            throw new Exception('Không tìm thấy mặt hàng trong giỏ.');
        }

        // Lấy thông tin chi tiết mặt hàng để chèn vào bảng order_items
        $tenMatHang = $chiTietMatHang['itemName'];
        $giaMatHang = $chiTietMatHang['price'];
        $tongGia = $giaMatHang * $soLuong;
        $anhMatHang = $chiTietMatHang['image'];

        // Gán tham số và chèn chi tiết đơn hàng
        $stmt->bind_param('issdds', $maDonHang, $tenMatHang, $soLuong, $giaMatHang, $tongGia, $anhMatHang);
        $stmt->execute();

        // --vấn đề: Xóa mặt hàng đã đặt ra khỏi giỏ hàng để tránh đặt lại
        $deleteStmt = $conn->prepare('DELETE FROM cart WHERE id=? AND email=?');
        $deleteStmt->bind_param('is', $maMatHang, $email);
        $deleteStmt->execute();
    }

    // --vấn đề: Nếu tất cả bước trên thành công, commit transaction để lưu vào database
    $conn->commit();

    // --vấn đề: Chuyển hướng đến trang xác nhận đơn hàng kèm theo ID đơn hàng mới tạo
    header('Location: order_confirm.php?order_id=' . $maDonHang);
    exit;

} catch (Exception $e) {
    // --vấn đề: Nếu có lỗi xảy ra trong quá trình lưu đơn hàng, rollback để không lưu dữ liệu lỗi
    $conn->rollback();
    // Hiển thị lỗi để debug (có thể thay bằng logging khi deploy)
    echo 'Lỗi: ' . $e->getMessage();
}
?>
