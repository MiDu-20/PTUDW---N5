<?php
session_start();
require 'db_connection.php';

// --Bảo vệ phiên đăng nhập / kiểm tra nếu người dùng chưa đăng nhập, chuyển hướng về trang đăng nhập
if (!isset($_SESSION['userloggedin']) || $_SESSION['userloggedin'] !== true) {
  header('location:login.php');
  exit;
}

// --Lấy email từ phiên đăng nhập hiện tại
$emailNguoiDung = $_SESSION['email'];

// --Lấy thông tin người dùng từ CSDL
$stmt = $conn->prepare('SELECT * FROM users WHERE email=?');
$stmt->bind_param('s', $emailNguoiDung);
$stmt->execute();
$result = $stmt->get_result();
$nguoiDung = $result->fetch_assoc();

// --Lấy danh sách sản phẩm đã chọn từ request POST (dạng JSON)
$matHangDaChon = json_decode($_POST['selected_items'], true);

// --Truy vấn CSDL để lấy chi tiết các mặt hàng trong giỏ hàng
$chiTietGioHang = [];
foreach ($matHangDaChon as $matHang) {
  $stmt = $conn->prepare('SELECT * FROM cart WHERE id=? AND email=?');
  $stmt->bind_param('is', $matHang['id'], $emailNguoiDung);
  $stmt->execute();
  $result = $stmt->get_result();
  $chiTietGioHang[] = $result->fetch_assoc();
}

// --Tính tổng phụ (subtotal) và phí giao hàng
$tongPhu = 0;
$phiGiaoHang = 0;
foreach ($chiTietGioHang as $matHang) {
  $gia = $matHang['price'];
  $soLuong = $matHang['quantity'];
  $tongPhu += $gia * $soLuong;
}

// --Nếu là hình thức "mang đi", không tính phí giao hàng
$phiGiaoHang = ($_POST['payment_mode'] === 'Takeaway') ? 0 : 130;
$tongCong = $tongPhu + $phiGiaoHang;

// Bảng ánh xạ tiếng Anh sang tiếng Việt
$paymentModes = [
    'Cash' => 'Tiền mặt',
    'Card' => 'Thẻ',
    'Takeaway' => 'Mang đi'
];

// Lấy phương thức người dùng chọn
$selectedMode = $_POST['payment_mode'] ?? '';

// Hiển thị với giá trị tiếng Việt nếu tồn tại
$displayMode = $paymentModes[$selectedMode] ?? 'Không xác định';

?>

<!DOCTYPE html>
<html lang="vi"> <!-- --Chuyển ngôn ngữ HTML về tiếng Việt -->

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Thư viện giao diện Bootstrap & FontAwesome -->
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css' />
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css' />
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="order_review.css">
  <title>Hoàn tất đơn hàng</title>
</head>

<body>
  <?php include('nav-logged.php'); ?>
  <div class="title mt-2">
    <h3>Xin chào <span class="username-highlight"><?php echo $nguoiDung['firstName'] . " " . $nguoiDung['lastName']; ?></span>, vui lòng hoàn tất đơn hàng!</h3>
  </div>


  <div class="main mt-4">
    <div class="order-fee">
      <h4>Chi tiết đơn hàng</h4>
      <hr>

      <form action="process_order.php" method="post">
        <!-- --Dữ liệu ẩn gửi đi xử lý -->
        <input type="hidden" name="total" value="<?= $tongCong ?>">
        <input type="hidden" name="subtotal" value="<?= $tongPhu ?>">
        <input type="hidden" name="order_id" value="<?= $orderId ?>">
        <input type="hidden" name="selected_items" value='<?= json_encode($matHangDaChon) ?>'>
        <input type="hidden" name="payment_mode" value="<?= htmlspecialchars($_POST['payment_mode']) ?>">

        <!-- --Form nhập thông tin khách hàng -->
        <div class="form-group row">
          <div class="col">
            <label for="firstName">Họ:</label>
            <input type="text" class="form-control" id="firstName" name="firstName" required>
          </div>
          <div class="col">
            <label for="lastName">Tên:</label>
            <input type="text" class="form-control" id="lastName" name="lastName" required>
          </div>
        </div>
        <div class="form-group row">
          <div class="col">
            <label for="contact">Liên hệ:</label>
            <input type="text" class="form-control" id="contact" name="contact" required>
          </div>
          <div class="col">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($emailNguoiDung) ?>" readonly>
          </div>
        </div>
        <div class="form-group">
          <label for="order_note">Ghi chú đơn hàng:</label>
          <textarea class="form-control" id="order_note" name="order_note" rows="3"></textarea>
        </div>
        <div class="form-group">
          <label for="address">Địa chỉ giao hàng:</label>
          <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
        </div>
    </div>

    <!-- --Phần hiển thị tóm tắt đơn hàng -->
    <div class="order-summary">
      <h4>Tóm tắt đơn hàng</h4>
      <hr>
      <div class="order-items mb-2">
        <?php foreach ($chiTietGioHang as $matHang) : ?>
          <div class="order-item d-flex align-items-center">
            <?php if (!empty($matHang['image'])) : ?>
              <img src="uploads/<?= htmlspecialchars($matHang['image']) ?>" alt="Ảnh món hàng" class="ms-1">
            <?php else : ?>
              <span>Không có hình ảnh</span>
            <?php endif; ?>
            <div class="ms-1 row d-flex justify-content-between w-100">
              <div class="col d-flex flex-column justify-content-center">
                <div class="mb-1"><strong><?= htmlspecialchars($matHang['itemName']) ?></strong></div>
                <div>Số lượng: <?= htmlspecialchars($matHang['quantity']) ?></div>
              </div>
              <div class="col d-flex flex-column justify-content-center">
                <div class="d-flex justify-content-end mt-2">VNĐ <?= htmlspecialchars($matHang['price'], 0) ?> x <?= htmlspecialchars($matHang['quantity']) ?></div>
                <div class="d-flex justify-content-end mb-2">
                  <span class="badge rounded-pill text-light p-2 mt-2 item-total-price" style="background-color: #fb4a36;">
                    VNĐ <?= $matHang['total_price'] ?>
                  </span>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <h4 class="mt-1">Chi phí đơn hàng</h4>
      <hr>
      <div class="summary-details">
        <div class="fee-details">
          <div><strong>Tạm tính:</strong></div>
          <div>VNĐ <?= number_format($tongPhu) ?></div>
        </div>
        <div class="fee-details">
          <div><strong>Hình thức thanh toán:</strong></div>
          <div><?= htmlspecialchars($displayMode) ?></div>
        </div>
        <div class="fee-details">
          <div><strong>Phí giao hàng:</strong></div>
          <div>VNĐ <?= number_format($phiGiaoHang) ?></div>
        </div>
        <div class="fee-details">
          <div><strong>Tổng cộng:</strong></div>
          <div>VNĐ <?= number_format($tongCong) ?></div>
        </div>
      </div>
      <hr>

      <!-- --Xử lý nút xác nhận thanh toán tùy theo hình thức -->
      <?php
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $phuongThuc = $_POST['payment_mode'] ?? '';

        if ($phuongThuc == 'Card') {
          echo '<button type="submit" class="Button">Thanh toán <svg viewBox="0 0 576 512" class="svgIcon"><path d="..."></path></svg></button>';
        } else {
          echo '<button type="submit" class="order-btn">Đặt hàng</button>';
        }
      }
      ?>
      </form>
    </div>
  </div>

  <?php include_once('footer.html'); ?>

  <!-- --JavaScript xử lý hiển thị số lượng sản phẩm trong giỏ -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js'></script>

  <script>
    $(document).ready(function () {
      console.log('Trang đã tải, gọi load_cart_item_number');
      load_cart_item_number();

      function load_cart_item_number() {
        $.ajax({
          url: 'action.php',
          method: 'get',
          data: { cartItem: "cart_item" },
          success: function (response) {
            $("#cart-item").html(response);
          }
        });
      }
    });
  </script>
</body>

</html>
