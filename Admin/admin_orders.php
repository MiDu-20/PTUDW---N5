<?php
session_start();
// Kiểm tra nếu admin chưa đăng nhập thì chuyển hướng về trang login
if (!isset($_SESSION['adminloggedin'])) {
    header("Location: ../login.php");
    exit();
}

// Kết nối tới CSDL
include 'db_connection.php'; 
//khgjhg

// Lấy giá trị lọc trạng thái đơn hàng và mã đơn hàng từ URL
$statusFilter = isset($_GET['statusFilter']) ? $_GET['statusFilter'] : '';
$searchOrderId = isset($_GET['searchOrderId']) ? $_GET['searchOrderId'] : '';

// Tạo truy vấn SQL lấy thông tin đơn hàng
$query = "SELECT order_id, order_date, firstName, lastName, phone, grand_total, order_status, pmode, cancel_reason FROM orders";
$conditions = [];

// Nếu có lọc theo trạng thái thì thêm điều kiện vào mảng $conditions
if (!empty($statusFilter)) {
    $conditions[] = "order_status = '" . $conn->real_escape_string($statusFilter) . "'";
}

// Nếu có tìm kiếm theo mã đơn hàng thì thêm điều kiện
if (!empty($searchOrderId)) {
    $conditions[] = "order_id LIKE '%" . $conn->real_escape_string($searchOrderId) . "%'";
}

// Ghép các điều kiện lại và nối vào truy vấn
if (!empty($conditions)) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}

// Sắp xếp kết quả theo thứ tự giảm dần của order_id
$query .= " ORDER BY order_id DESC";

// Thực thi truy vấn
$result = $conn->query($query);

?>
<?php
include 'sidebar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Thông tin chung của trang -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders</title>
    <!--Load font và CSS-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="admin_orders.css">
    <style>
  .content{
    margin-bottom: 40px;
  }
</style>
</head>

<body>
    <div class="sidebar">
        <button class="close-sidebar" id="closeSidebar">&times;</button>
       
        <!-- Profile Section -->
    <div class="profile-section">
      <img src="../uploads/<?php echo htmlspecialchars($admin_info['profile_image']); ?>" alt="Profile Picture">
      <div class="info">
        <h3>Welcome Back!</h3>
        <p><?php echo htmlspecialchars($admin_info['firstName']) . ' ' . htmlspecialchars($admin_info['lastName']); ?></p>
      </div>
    </div>

    <!-- Menu điều hướng -->

    <ul>
            <li><a href="index.php" ><i class="fas fa-chart-line"></i> Overview</a></li>
            <li><a href="admin_menu.php"><i class="fas fa-utensils"></i> Menu Management</a></li>
            <li><a href="admin_orders.php" class="active"><i class="fas fa-shopping-cart"></i> Orders</a></li>
            <li><a href="reservations.php"><i class="fas fa-calendar-alt"></i> Reservations</a></li>
            <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
            <li><a href="reviews.php"><i class="fas fa-star"></i> Reviews</a></li>
            <li><a href="staffs.php" ><i class="fas fa-users"></i> Staffs</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i> Profile Setting</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    <div class="content">
        <div class="header">
            <button id="toggleSidebar" class="toggle-button">
                <i class="fas fa-bars"></i>
            </button>
            <h2><i class="fas fa-shopping-cart"></i> Orders</h2>
        </div>

        <!-- Bộ lọc đơn hàng -->
        <div class="actions">
            <div>
            <button id="refreshButton" onclick="refreshPage()" title="Refresh">
                <i class="fas fa-sync-alt"></i>
            </button>
           
            </div>
            
            <div class="filter-orders">
                <!-- Dropdown lọc theo trạng thái -->
                <select id="statusFilter" name="statusFilter" onchange="filterByStatus()">
                    <option value="">All Orders</option>
                    <option value="Pending">Pending</option>
                    <option value="On Process">Process</option>
                    <option value="On Process">On the way </option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
                <!-- Tìm kiếm theo mã đơn -->
                <input type="text" id="searchOrderId" placeholder="Search by Order ID" oninput="searchByOrderId()">
            </div>
        </div>
        <?php
        /// Hiển thị dữ liệu đơn hàng dưới dạng bảng
        echo "<table>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Contact</th>
                    <th>Total</th>
                    <th>Order Status</th>
                    <th>Payment Mode</th>
                    <th>Cancel Reason</th>
                    <th>Action</th>
                </tr>";
        // Nếu có kết quả thì hiển thị từng dòng
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $statusClass = '';
                switch ($row['order_status']) {
                    case 'Pending':
                        $statusClass = 'status-pending';
                        break;
                    case 'Processing':
                        $statusClass = 'status-processing';
                        break;
                    case 'Completed':
                        $statusClass = 'status-completed';
                        break;
                    case 'Cancelled':
                        $statusClass = 'status-cancelled';
                        break;
                    case 'On the way':
                        $statusClass = 'status-ontheway';
                        break;
                }
                echo "<tr>
                    <td>" . $row['order_id'] . "</td>
                    <td>" . $row['firstName'] . " " . $row['lastName'] . "</td>
                    <td>" . $row['phone'] . "</td>
                    <td>" . 'Rs ' . $row['grand_total'] . "</td>
                    <td><span class='status $statusClass'>" . $row['order_status'] . "</span></td>
                    <td>" . $row['pmode'] . "</td>
                    <td>" . ($row['order_status'] == 'Cancelled' ? $row['cancel_reason'] : '-') . "</td>
                    <td><button id='viewbtn' onclick=\"viewDetails(" . $row['order_id'] . ")\">View Details</button></td>
                </tr>";
            }
        } else {
            // Nếu không có đơn hàng
            echo "<tr><td colspan='8' style='text-align: center;'>No Orders Found</td></tr>";
        }

        echo "</table>";
        // Đóng kết nối
        $conn->close();
        ?>
    </div>

    <?php
    include_once ('footer.html');
    ?>
    <script src="sidebar.js"></script>
    <script>
                // Chuyển tới trang chi tiết đơn hàng
                function viewDetails(orderId) {
            window.location.href = 'view_order.php?orderId=' + orderId;
        }
    const modal = document.querySelector('.modal');
    const buttons = document.querySelectorAll('.toggle-button');

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            modal.classList.toggle('open');
        });
    });
    // Lọc đơn hàng theo trạng thái và mã
    function filterByStatus() {
        var statusFilter = document.getElementById('statusFilter').value;
        var dateFilter = document.getElementById('dateFilter') ? document.getElementById('dateFilter').value : ''; // Optional date filter
        var searchOrderId = document.getElementById('searchOrderId').value.trim();
        window.location.href = 'admin_orders.php?statusFilter=' + encodeURIComponent(statusFilter) + '&dateFilter=' + encodeURIComponent(dateFilter) + '&searchOrderId=' + encodeURIComponent(searchOrderId);
    }
    // Tìm kiếm theo ID cũng sẽ gọi filterByStatus để áp dụng ngay
    function searchByOrderId() {
        filterByStatus(); 
    }

    // Nút làm mới trang
    function refreshPage() {
        window.location.href = 'admin_orders.php'; // Reload the page
    }

    // Gán giá trị đã chọn lại vào ô input/select khi reload
    document.getElementById('statusFilter').value = "<?= isset($_GET['statusFilter']) ? $_GET['statusFilter'] : '' ?>";

    if (document.getElementById('dateFilter')) {
        document.getElementById('dateFilter').value = "<?= isset($_GET['dateFilter']) ? $_GET['dateFilter'] : '' ?>";
    }

    document.getElementById('searchOrderId').value = "<?= isset($_GET['searchOrderId']) ? $_GET['searchOrderId'] : '' ?>";

    // Gắn lại sự kiện onchange/input cho các ô lọc
    document.getElementById('statusFilter').addEventListener('change', filterByStatus);
    if (document.getElementById('dateFilter')) {
        document.getElementById('dateFilter').addEventListener('change', filterByStatus);
    }
    document.getElementById('searchOrderId').addEventListener('input', searchByOrderId);
    document.getElementById('refreshButton').addEventListener('click', refreshPage);
</script>



</body>

</html>