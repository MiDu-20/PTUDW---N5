<?php
session_start();
include 'db_connection.php'; // --Kết nối cơ sở dữ liệu

// --Lấy email người dùng từ session
$email = $_SESSION['email'];

// --Lấy trạng thái đơn hàng từ tham số GET, mặc định là 'All' (tất cả)
$trang_thai_don = isset($_GET['status']) ? $_GET['status'] : 'All';

// --Truy vấn đơn hàng và thông tin đánh giá (nếu có) bằng LEFT JOIN
$query = "SELECT orders.*, reviews.review_text, reviews.response 
          FROM orders 
          LEFT JOIN reviews ON orders.order_id = reviews.order_id 
          WHERE orders.email = ?";
if ($trang_thai_don !== 'All') {
    $query .= " AND orders.order_status = ?";
}

// --Chuẩn bị truy vấn với các điều kiện phù hợp
$stmt = $conn->prepare($query);
if ($trang_thai_don === 'All') {
    $stmt->bind_param('s', $email);
} else {
    $stmt->bind_param('ss', $email, $trang_thai_don);
}

$stmt->execute();
$result = $stmt->get_result();
$don_hang = [];

while ($don = $result->fetch_assoc()) {
    $ma_don = $don['order_id'];
    
    // --Lấy danh sách sản phẩm trong đơn hàng
    $truyvan_sp = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
    $truyvan_sp->bind_param('i', $ma_don);
    $truyvan_sp->execute();
    $ketqua_sp = $truyvan_sp->get_result();
    $don['items'] = $ketqua_sp->fetch_all(MYSQLI_ASSOC);
    $truyvan_sp->close();

    // --Nếu đơn hàng bị hủy thì lấy lý do hủy từ bảng orders
    if ($don['order_status'] === 'Cancelled') {
        $truyvan_huy = $conn->prepare("SELECT cancel_reason FROM orders WHERE order_id = ?");
        $truyvan_huy->bind_param('i', $ma_don);
        $truyvan_huy->execute();
        $ketqua_huy = $truyvan_huy->get_result();
        $dulieu_huy = $ketqua_huy->fetch_assoc();
        $don['cancel_reason'] = $dulieu_huy['cancel_reason'];
        $truyvan_huy->close();
    }

    // --Thông tin đánh giá đã có sẵn trong truy vấn chính, không cần lấy lại
    $don_hang[] = $don;
}

// --Trả về danh sách đơn hàng ở dạng JSON
echo json_encode($don_hang);

$stmt->close();
$conn->close();
?>
