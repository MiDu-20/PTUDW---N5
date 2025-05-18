<?php
// Bắt đầu session và kiểm tra nếu admin đã đăng nhập
session_start();
if (!isset($_SESSION['adminloggedin'])) {
  header("Location: ../login.php");
  exit();
}

// Kết nối cơ sở dữ liệu
include 'db_connection.php';
$orderId = isset($_GET['orderId']) ? $_GET['orderId'] : '';

if ($orderId) {
  // Lấy thông tin đơn hàng
  $orderQuery = "SELECT * FROM orders WHERE order_id = ?";
  $stmt = $conn->prepare($orderQuery);
  $stmt->bind_param('i', $orderId);
  $stmt->execute();
  $orderResult = $stmt->get_result();
  $order = $orderResult->fetch_assoc();

  // Lấy thông tin các món trong đơn hàng
  $itemsQuery = "SELECT itemName, quantity, price, total_price, image FROM order_items WHERE order_id = ?";
  $stmt = $conn->prepare($itemsQuery);
  $stmt->bind_param('i', $orderId);
  $stmt->execute();
  $itemsResult = $stmt->get_result();
} else {
  echo "Mã đơn hàng không hợp lệ.";
  exit();
}

$paymentMode = $order['pmode'] ?? 'takeaway';
$deliveryFee = ($paymentMode === 'takeaway') ? 0 : 130;
?>

<?php include 'sidebar.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chi tiết đơn hàng</title>
  <!-- Import các thư viện cần thiết -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" href="sidebar.css">
  <link rel="stylesheet" href="admin_orders.css">
  <link rel="stylesheet" href="view_order.css">
</head>
<body>
<!-- Sidebar điều hướng -->
<div class="sidebar">
  <button class="close-sidebar" id="closeSidebar">&times;</button>
  <div class="profile-section">
    <img src="../uploads/<?php echo htmlspecialchars($admin_info['profile_image']); ?>" alt="Ảnh đại diện">
    <div class="info">
      <h3>Chào mừng trở lại!</h3>
      <p><?php echo htmlspecialchars($admin_info['firstName']) . ' ' . htmlspecialchars($admin_info['lastName']); ?></p>
    </div>
  </div>
  <ul>
    <li><a href="index.php"><i class="fas fa-chart-line"></i> Tổng quan</a></li>
    <li><a href="admin_menu.php"><i class="fas fa-utensils"></i> Quản lý thực đơn</a></li>
    <li><a href="admin_orders.php" class="active"><i class="fas fa-shopping-cart"></i> Đơn hàng</a></li>
    <li><a href="reservations.php"><i class="fas fa-calendar-alt"></i> Đặt bàn</a></li>
    <li><a href="users.php"><i class="fas fa-users"></i> Người dùng</a></li>
    <li><a href="reviews.php"><i class="fas fa-star"></i> Đánh giá</a></li>
    <li><a href="staffs.php"><i class="fas fa-users"></i> Nhân viên</a></li>
    <li><a href="profile.php"><i class="fas fa-user"></i> Hồ sơ</a></li>
    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
  </ul>
</div>

<!-- Nội dung chính -->
<div class="content">
  <div class="header d-flex justify-content-between">
    <div class="col">
      <button id="toggleSidebar" class="toggle-button"><i class="fas fa-bars"></i></button>
      <h2><i class="fas fa-shopping-cart"></i> Đơn hàng #<?php echo $order['order_id']; ?></h2>
    </div>
    <div class="col d-flex justify-content-end">
      <a href="admin_orders.php" class="button"><i class="fas fa-arrow-left"></i>&nbsp; Trở về</a>
    </div>
  </div>

  <div class="details">
    <!-- Thông tin các món đã đặt -->
    <div class="order-details">
      <div class="order-items">
        <h4 class="mt-2">Danh sách món</h4><hr>
        <ul class="list-group">
          <?php while ($item = $itemsResult->fetch_assoc()) : ?>
          <li class="d-flex justify-content-between mb-3">
            <div class="d-flex align-items-start">
              <?php
              if (!empty($item['image'])) {
                echo '<img src="../uploads/' . htmlspecialchars($item['image']) . '" alt="Ảnh món" style="width: 70px; height: 70px; object-fit: cover;">';
              } else {
                echo '<span>Không có ảnh</span>';
              }
              ?>
              <?php echo $item['itemName']; ?>
            </div>
            <div>
              <div class="d-flex flex-row justify-content-between align-items-start quantity-price">
                <div>Giá: <?php echo $item['price']; ?> x <?php echo $item['quantity']; ?></div>
              </div>
              <div class="d-flex flex-row justify-content-end align-items-end">
                <span class="badge rounded-pill text-light p-2 mt-2" style="background-color: #fb4a36;">Tổng: <?php echo $item['total_price']; ?></span>
              </div>
            </div>
          </li>
          <?php endwhile; ?>
        </ul>
      </div>

      <!-- Tóm tắt đơn hàng -->
      <div class="order-summary">
        <h4 class="mt-2">Chi phí đơn hàng</h4><hr>
        <div class="summary-details">
          <p><strong>Tạm tính:</strong></p>
          <p><?php echo $order['sub_total']; ?> đ</p>
        </div>
        <div class="summary-details">
          <p><strong>Phí vận chuyển:</strong></p>
          <p><?php echo number_format($deliveryFee, 2); ?> đ</p>
        </div>
        <div class="summary-details">
          <p><strong>Tổng cộng:</strong></p>
          <p><?php echo $order['grand_total']; ?> đ</p>
        </div>
        <div class="summary-details">
          <p><strong>Phương thức thanh toán:</strong></p>
          <p><?php echo $order['pmode']; ?></p>
        </div>
        <div class="summary-details">
          <p style="width: 60%;"><strong>Trạng thái thanh toán:</strong></p>
          <select class="form-select" id="paymentStatus" name="payment_status">
            <option value="Pending" <?php if ($order['payment_status'] == 'Pending') echo 'selected'; ?>>Chờ thanh toán</option>
            <option value="Successful" <?php if ($order['payment_status'] == 'Successful') echo 'selected'; ?>>Thành công</option>
            <option value="Rejected" <?php if ($order['payment_status'] == 'Rejected') echo 'selected'; ?>>Từ chối</option>
          </select>
        </div>
        <div class="summary-details">
          <p><strong>Lý do huỷ:</strong></p>
          <p><?php echo $order['cancel_reason']; ?></p>
        </div>
        <hr>
        <!-- Cập nhật trạng thái đơn hàng -->
        <form method="post" action="update_order_status.php" onsubmit="return validateForm()">
          <div class="status-container">
            <label for="orderStatus" class="form-label"><strong>Trạng thái đơn</strong></label>
            <select class="form-select" id="orderStatus" name="order_status">
              <option value="Pending" <?php if ($order['order_status'] == 'Pending') echo 'selected'; ?>>Chờ xử lý</option>
              <option value="Processing" <?php if ($order['order_status'] == 'Processing') echo 'selected'; ?>>Đang xử lý</option>
              <option value="Completed" <?php if ($order['order_status'] == 'Completed') echo 'selected'; ?>>Hoàn thành</option>
              <option value="Cancelled" <?php if ($order['order_status'] == 'Cancelled') echo 'selected'; ?>>Đã huỷ</option>
              <option value="On the way" <?php if ($order['order_status'] == 'On the way') echo 'selected'; ?>>Đang giao</option>
            </select>
          </div>
          <div class="mb-3" id="cancelReasonContainer" style="display: none;">
            <label for="cancelReason" class="form-label">Lý do huỷ</label>
            <textarea class="form-control" id="cancelReason" name="cancel_reason"></textarea>
          </div>
          <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
          <button type="submit" id="statusbtn">Cập nhật trạng thái</button>
        </form>
      </div>
    </div>

    <!-- Thông tin khách hàng -->
    <div class="customer mb-4">
      <h4 class="mt-2">Thông tin khách hàng</h4><hr>
      <div class="customer-details">
        <div class="summary-details">
          <p><strong>Họ tên:</strong></p>
          <p><?php echo $order['firstName'] . ' ' . $order['lastName']; ?></p>
        </div>
        <div class="summary-details">
          <p><strong>Email:</strong></p>
          <p><?php echo $order['email']; ?></p>
        </div>
        <div class="summary-details">
          <p><strong>Liên hệ:</strong></p>
          <p><?php echo $order['phone']; ?></p>
        </div>
        <div class="summary-details">
          <p><strong>Địa chỉ:</strong></p>
          <p><?php echo $order['address']; ?></p>
        </div>
        <div class="summary-details">
          <p><strong>Ghi chú:</strong></p>
          <p><?php echo $order['note']; ?></p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include_once('footer.html'); ?>
<script src="sidebar.js"></script>
<script>
  // Gửi AJAX khi thay đổi trạng thái thanh toán
  document.getElementById('paymentStatus').addEventListener('change', function() {
    var paymentStatus = this.value;
    var orderId = <?php echo $order['order_id']; ?>;
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_payment_status.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
      if (xhr.status === 200) {
        alert('Cập nhật trạng thái thanh toán thành công');
      } else {
        console.error('Lỗi khi cập nhật');
      }
    };
    xhr.send('order_id=' + encodeURIComponent(orderId) + '&payment_status=' + encodeURIComponent(paymentStatus));
  });

  // Hiển thị khung lý do hủy nếu chọn huỷ
  document.getElementById('orderStatus').addEventListener('change', function() {
    const cancelReasonContainer = document.getElementById('cancelReasonContainer');
    cancelReasonContainer.style.display = this.value === 'Cancelled' ? 'block' : 'none';
  });

  // Kiểm tra hợp lệ trước khi gửi form
  function validateForm() {
    const orderStatus = document.getElementById('orderStatus').value;
    if (orderStatus === 'Cancelled') {
      const cancelReason = document.getElementById('cancelReason').value;
      if (cancelReason.trim() === '') {
        alert('Vui lòng nhập lý do huỷ.');
        return false;
      }
    }
    return true;
  }
</script>
</body>
</html>