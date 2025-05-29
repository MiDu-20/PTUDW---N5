<?php
// Bắt đầu phiên làm việc
session_start();

// Kết nối tới cơ sở dữ liệu
require 'db_connection.php';

// Kiểm tra nếu người dùng chưa đăng nhập thì chuyển hướng sang trang đăng nhập
if (!isset($_SESSION['userloggedin']) || $_SESSION['userloggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Lấy order_id từ tham số URL nếu có, ngược lại gán = 0
$orderId = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

// Truy vấn thông tin đơn hàng dựa trên order_id
$stmt = $conn->prepare('SELECT * FROM orders WHERE order_id=?');
if ($stmt === false) {
    // Hiển thị lỗi nếu không thể chuẩn bị truy vấn
    die('Failed to prepare order details statement: ' . $conn->error);
}
$stmt->bind_param('i', $orderId);  // Gán tham số vào câu truy vấn
$stmt->execute();
$orderResult = $stmt->get_result();
$order = $orderResult->fetch_assoc();  // Lấy dòng dữ liệu đơn hàng

// Nếu không tìm thấy đơn hàng thì dừng chương trình
if ($order === null) {
    die('Order not found.');
}

// Truy vấn các mặt hàng trong đơn hàng
$stmt = $conn->prepare('SELECT * FROM order_items WHERE order_id=?');
if ($stmt === false) {
    die('Failed to prepare order items statement: ' . $conn->error);
}
$stmt->bind_param('i', $orderId);
$stmt->execute();
$orderItemsResult = $stmt->get_result();

$paymentModes = [
    'Cash' => 'Tiền mặt',
    'Card' => 'Thẻ',
    'Takeaway' => 'Mang đi'
];

$pmodeRaw = $order['pmode'] ?? '';
$pmodeDisplay = $paymentModes[$pmodeRaw];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Các file CSS dùng Bootstrap & FontAwesome -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css' />
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css' />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
     <link
        href="https://fonts.googleapis.com/css2?family=Baloo+2:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <title>Order Confirmation</title>

    <!-- Phần CSS tùy chỉnh cho hiệu ứng và bố cục -->
    <style>
        body {
            font-family: "Baloo 2", cursive;
            padding-top: 120px;
            padding-bottom: 50px;
        }

        /* Các hiệu ứng hoạt hình cho phần thông báo đơn hàng */
        .card h1,
        .card p {
            animation: translate-y-100 300ms cubic-bezier(0.165, 0.84, 0.44, 1.2) forwards;
            opacity: 0;
        }

        .card h3 {
            margin-top: 0;
            margin-bottom: 0.5rem;
            animation-delay: 1100ms;
        }

        .card p {
            margin-top: 0;
            font-size: 17px;
            color: #60655f;
            animation-delay: 1150ms;
        }

        /* Nút điều hướng */
        .card .cta-row {
            display: flex;
            justify-content: center;
            gap: 12px;
            width: 100%;
            animation: translate-y-100 600ms 2200ms cubic-bezier(0.19, 1, 0.22, 1) forwards;
            opacity: 0;
        }

        .card a button {
            height: 36px;
            width: 200px;
            border: 0;
            border-radius: 5px;
            background: #46a758;
            color: #ffffff;
            font-size: 15px;
            font-weight: 500;
            padding: 0.5rem 1rem;
            cursor: pointer;
            transition: transform 200ms ease, background-color 100ms ease;
        }

        .card a button:hover {
            background: #2a7e3b;
        }

        .card a button.secondary {
            background: #fb4a36;
            border: 1px solid #fb4a36;
        }

        .card a button.secondary:hover {
            background: none;
            color: #fb4a36;
        }

        /* Phong cách của thẻ hiển thị đơn hàng */
        .card {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 448px;
            padding: 32px;
            border-radius: 5px;
            position: relative;
        }

        .card .icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(to top, #f2f2f280, #e0e0e080);
            border-radius: 9999px;
            display: grid;
            place-content: center;
            margin-bottom: 1rem;
        }

        .card .icon:before {
            content: "\2713"; /* Biểu tượng dấu tích */
            display: grid;
            place-items: center;
            width: 56px;
            height: 56px;
            border-radius: 9999px;
            background-color: #ffffff;
            font-size: 32px;
            color: #46a758;
        }

        .card ul {
            list-style: none;
            padding: 0;
            width: 100%;
        }

        .card ul > li {
            display: flex;
            justify-content: space-between;
        }

        .card ul > li span {
            font-size: 16px;
            font-weight: 500;
        }

        /* Hiệu ứng animation */
        @keyframes translate-y-100 {
            0% { transform: scale(0.9) translateY(0.5rem); }
            100% { opacity: 1; }
        }

        @media screen and (max-width: 500px) {
            .card {
                width: 350px !important;
                padding: 32px 20px !important;
                margin: 0 20px;
            }

            .card ul>li span {
                font-size: 14px !important;
            }

            .card a button {
                width: 140px;
            }
        }
    </style>
</head>


<body>
    <!-- Thanh điều hướng -->
    <?php include('nav-logged.php'); ?>

    <!-- Phần nội dung xác nhận đơn hàng -->
    <div class="title d-flex justify-content-center align-items-center">
        <div id="wrapper">
            <!-- Thẻ cảm ơn -->
            <div class="card" style="background: rgba(255, 99, 132, 0.3);">
                <div class="icon"></div>
                <h3>Cảm ơn bạn đã đặt hàng!</h3>
                <p>Bạn đã đặt hàng thành công</p>
            </div>

            <!-- Thẻ hiển thị chi tiết đơn hàng -->
            <div class="card" style="background: rgba(255, 99, 132, 0.3);">
                <ul>
                    <li>
                        <span><strong>ID Đơn hàng:</strong></span>
                        <span>#<?= htmlspecialchars($order['order_id'] ?? 'N/A') ?></span>
                    </li>
                    <li>
                        <span><strong>Email:</strong></span>
                        <span><?= htmlspecialchars($order['email'] ?? 'N/A') ?></span>
                    </li>
                    <li>
                        <span><strong>Phương thức thanh toán:</strong></span>
                        <span><?= htmlspecialchars($pmodeDisplay ?? 'N/A') ?></span>
                    </li>
                    <li>
                        <span><strong>Danh sách món ăn:</strong></span>
                        <span>
                            <ul>
                                <!-- Hiển thị từng mặt hàng trong đơn hàng -->
                                <?php while ($item = $orderItemsResult->fetch_assoc()) : ?>
                                    <li><?= htmlspecialchars($item['itemName']) ?> - <?= htmlspecialchars($item['quantity']) ?></li>
                                <?php endwhile; ?>
                            </ul>
                        </span>
                    </li>
                    <li>
                        <span><strong>Tổng cộng:</strong></span>
                        <span>VNĐ <?= number_format($order['grand_total']) ?></span>
                    </li>
                    <li>
                        <span><strong>Địa chỉ:</strong></span>
                        <span><?= htmlspecialchars($order['address'] ?? 'N/A') ?></span>
                    </li>
                </ul>
            </div>

            <!-- Thẻ chứa các nút điều hướng -->
            <div class="card" style="background: rgba(255, 99, 132, 0.3);">
                <div class="cta-row">
                    <a href="menu.php">
                        <button class="secondary">Quay lại Thực đơn</button>
                    </a>
                    <a href="orders.php">
                        <button>Trạng thái Đơn hàng</button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include_once ('footer.html'); ?>

    <!-- Script để tải lại số lượng sản phẩm trong giỏ hàng -->
    <script>
        $(document).ready(function () {
            console.log('Page is ready. Calling load_cart_item_number.');
            load_cart_item_number();

            function load_cart_item_number() {
                $.ajax({
                    url: 'action.php',
                    method: 'get',
                    data: {
                        cartItem: "cart_item"
                    },
                    success: function (response) {
                        $("#cart-item").html(response);
                    }
                });
            }
        });
    </script>
</body>
</html>
