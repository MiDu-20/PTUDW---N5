<?php
session_start();
require 'db_connection.php';

//-- Kiểm tra xem biến phiên email đã được thiết lập chưa
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    //-- Thêm sản phẩm vào bảng giỏ hàng
    if (isset($_POST['pid']) && isset($_POST['pname']) && isset($_POST['pprice'])) {
        $pid = $_POST['pid']; //--Không xác thực đầu vào 
        $pname = $_POST['pname']; //--Không xác thực đầu vào 
        $pprice = $_POST['pprice']; //--Không xác thực đầu vào 
        $pimage = $_POST['pimage']; //--Không xác thực đầu vào 
        $pcode = $_POST['pcode']; //--Không xác thực đầu vào 
        $pqty = 1; //--Số lượng cố định là 1, không cho phép người dùng chọn số lượng

        $total_price = $pprice * $pqty;

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $stmt = $conn->prepare('SELECT itemName FROM cart WHERE itemName=? AND email=?');
        $stmt->bind_param('ss', $pname, $email);
        $stmt->execute();
        $res = $stmt->get_result();
        $r = $res->fetch_assoc();
        $code = $r['itemName'] ?? '';

        if (!$code) {
            // Nếu sản phẩm chưa có trong giỏ hàng, thêm vào
            $query = $conn->prepare('INSERT INTO cart (itemName, price, image, quantity, total_price, catName, email) VALUES (?, ?, ?, ?, ?, ?, ?)');
            $query->bind_param('sdsisss', $pname, $pprice, $pimage, $pqty, $total_price, $pcode, $email);
            $query->execute();

            //-- Sử dụng CSS nội tuyến trực tiếp trong echo
            //-- Không có bảo vệ XSS khi hiển thị thông báo
            // Hiển thị thông báo thành công
            echo '<div class="alert alert-success alert-dismissible mt-2" style="width: 300px; position: fixed; top: 50%; right: 50%; transform: translate(50%, -50%); z-index: 9999; padding-top: 40px; padding-bottom: 40px; font-size: 17px; text-align: center;">
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                      <strong>Món đã được thêm vào Giỏ hàng!</strong>
                    </div>';
        } else {
            //-- Không cập nhật số lượng nếu sản phẩm đã tồn tại, chỉ hiển thị thông báo lỗi
            //-- Sử dụng CSS nội tuyến trực tiếp trong echo
            // Nếu sản phẩm đã có trong giỏ hàng, hiển thị thông báo
            echo '<div class="alert alert-danger alert-dismissible mt-2" style="width: 300px; position: fixed; top: 50%; right: 50%; transform: translate(50%, -50%); z-index: 9999; padding-top: 40px; padding-bottom: 40px; font-size: 17px; text-align: center;">
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                      <strong>Món đã tồn tại trong Giỏ hàng!</strong>
                    </div>';
        }
    }

    // Lấy số lượng sản phẩm trong giỏ hàng
    if (isset($_GET['cartItem']) && $_GET['cartItem'] == 'cart_item') {
        $stmt = $conn->prepare('SELECT SUM(quantity) AS qty FROM cart WHERE email=?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        // Kiểm tra nếu 'qty' là null thì gán giá trị 0
        $quantity = $row['qty'] !== null ? $row['qty'] : 0;

        echo $quantity;
    }
} 
?>
