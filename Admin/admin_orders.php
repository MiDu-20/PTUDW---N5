<?php
session_start();
if (!isset($_SESSION['adminloggedin'])) {
    header("Location: ../login.php");
    exit();
}

include 'db_connection.php';

// Nhận dữ liệu từ URL
$statusFilter = $_GET['statusFilter'] ?? '';
$search = $_GET['search'] ?? '';

// Tạo truy vấn
$query = "SELECT order_id, order_date, firstName, lastName, phone, grand_total, order_status, pmode, cancel_reason FROM orders";
$conditions = [];

if (!empty($statusFilter)) {
    $conditions[] = "order_status = '" . $conn->real_escape_string($statusFilter) . "'";
}

if (!empty($search)) {
    $searchEscaped = $conn->real_escape_string($search);
    $conditions[] = "(order_id LIKE '%$searchEscaped%' OR phone LIKE '%$searchEscaped%')";
}

if ($conditions) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$query .= " ORDER BY order_id DESC";
$result = $conn->query($query);
?>

<?php include 'sidebar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="admin_orders.css">
    <style>.content{margin-bottom: 40px;}</style>
</head>

<body>
<div class="sidebar">
    <button class="close-sidebar" id="closeSidebar">&times;</button>
    <div class="profile-section">
        <img src="../uploads/<?php echo htmlspecialchars($admin_info['profile_image']); ?>" alt="Profile Picture">
        <div class="info">
            <h3>Chào mừng bạn quay trở lại!</h3>
            <p><?php echo htmlspecialchars($admin_info['firstName'] . ' ' . $admin_info['lastName']); ?></p>
        </div>
    </div>
    <ul>
        <li><a href="index.php"><i class="fas fa-chart-line"></i>Tổng quan</a></li>
        <li><a href="admin_menu.php"><i class="fas fa-utensils"></i>Quản lý thực đơn</a></li>
        <li><a href="admin_orders.php" class="active"><i class="fas fa-shopping-cart"></i>Đơn hàng</a></li>
        <li><a href="reservations.php"><i class="fas fa-calendar-alt"></i>Đặt bàn</a></li>
        <li><a href="users.php"><i class="fas fa-users"></i>Nhân viên</a></li>
        <li><a href="reviews.php"><i class="fas fa-star"></i>Đánh giá</a></li>
        <li><a href="staffs.php"><i class="fas fa-users"></i>Người dùng</a></li>
        <li><a href="profile.php"><i class="fas fa-user"></i>Hồ sơ</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i>Đăng xuất</a></li>
    </ul>
</div>

<div class="content">
    <div class="header">
        <button id="toggleSidebar" class="toggle-button"><i class="fas fa-bars"></i></button>
        <h2><i class="fas fa-shopping-cart"></i>Đơn hàng</h2>
    </div>

    <div class="actions">
        <button id="refreshButton" title="Refresh"><i class="fas fa-sync-alt"></i></button>
        <div class="filter-orders">
            <select id="statusFilter" name="statusFilter">
                <option value="">Tất cả đơn hàng</option>
                <option value="Pending">Pending</option>
                <option value="On Process">On Process</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
            </select>
            <input type="text" id="searchInput" placeholder="Tìm theo ID hoặc SĐT">
        </div>
    </div>

    <table>
        <tr>
            <th>ID đơn hàng</th>
            <th>Tên khách hàng</th>
            <th>Liên hệ</th>
            <th>Tổng đơn</th>
            <th>Trạng thái</th>
            <th>Cách thanh toán</th>
            <th>Lý do hủy</th>
            <th>Chi tiết</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): 
                $statusClass = '';
                switch ($row['order_status']) {
                    case 'Pending': $statusClass = 'status-pending'; break;
                    case 'Processing': $statusClass = 'status-processing'; break;
                    case 'Completed': $statusClass = 'status-completed'; break;
                    case 'Cancelled': $statusClass = 'status-cancelled'; break;
                    case 'On the way': $statusClass = 'status-ontheway'; break;
                }
            ?>
                <tr>
                    <td><?= $row['order_id'] ?></td>
                    <td><?= htmlspecialchars($row['firstName'] . ' ' . $row['lastName']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?=  $row['grand_total'].' đ ' ?></td>
                    <td><span class="status <?= $statusClass ?>"><?= $row['order_status'] ?></span></td>
                    <td><?= $row['pmode'] ?></td>
                    <td><?= $row['order_status'] === 'Cancelled' ? $row['cancel_reason'] : '-' ?></td>
                    <td><button onclick="viewDetails(<?= $row['order_id'] ?>)">Xem</button></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8" style="text-align: center;">Không tìm thấy thông tin</td></tr>
        <?php endif; ?>
    </table>
</div>

<?php include_once('footer.html'); ?>

<script src="sidebar.js"></script>
<script>
    function viewDetails(orderId) {
        window.location.href = 'view_order.php?orderId=' + orderId;
    }

    function filterByStatus() {
        const status = document.getElementById('statusFilter').value;
        const search = document.getElementById('searchInput').value.trim();
        window.location.href = `admin_orders.php?statusFilter=${encodeURIComponent(status)}&search=${encodeURIComponent(search)}`;
    }

    function refreshPage() {
        window.location.href = 'admin_orders.php';
    }

    // Khôi phục giá trị đã chọn
    document.getElementById('statusFilter').value = "<?= $_GET['statusFilter'] ?? '' ?>";
    document.getElementById('searchInput').value = "<?= $_GET['search'] ?? '' ?>";

    // Gắn sự kiện lọc
    document.getElementById('statusFilter').addEventListener('change', filterByStatus);
    document.getElementById('searchInput').addEventListener('input', filterByStatus);
    document.getElementById('refreshButton').addEventListener('click', refreshPage);
</script>
</body>
</html>
