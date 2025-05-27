<?php
// Bắt đầu phiên làm việc
session_start();
// Kiểm tra xem admin đã đăng nhập chưa, nếu chưa thì chuyển hướng về trang đăng nhập
if (!isset($_SESSION['adminloggedin'])) {
  header("Location: ../login.php");
  exit();
}
// Kết nối cơ sở dữ liệu
include 'db_connection.php';

// Đặt múi giờ mặc định là Colombo
date_default_timezone_set('Asia/Ho_Chi_Minh');
// Thời điểm hiện tại
$current_time = new DateTime();

// Lấy tổng số lượt đặt bàn
$sql_total = "SELECT COUNT(*) AS total FROM reservations";
$result_total = $conn->query($sql_total);
$row_total = $result_total->fetch_assoc();
$totalReservations = $row_total['total'];

// Lấy số lượt đặt bàn trong ngày hôm nay
$sql_today = "SELECT COUNT(*) AS today FROM reservations WHERE DATE(reservedDate) = CURDATE()";
$result_today = $conn->query($sql_today);
$row_today = $result_today->fetch_assoc();
$todaysReservations = $row_today['today'];

// Lấy số lượt đặt bàn sắp tới (loại trừ các trạng thái huỷ, hoàn tất hoặc đang xử lý)
$sql_upcoming = "SELECT COUNT(*) AS upcoming FROM reservations WHERE reservedDate > CURDATE() AND status != 'Cancelled' AND status != 'Completed' AND status != 'On Process'";
$result_upcoming = $conn->query($sql_upcoming);
$row_upcoming = $result_upcoming->fetch_assoc();
$upcomingReservations = $row_upcoming['upcoming'];

// Lấy số lượt đặt bàn đã bị huỷ
$sql_cancelled = "SELECT COUNT(*) AS cancelled FROM reservations WHERE status = 'Cancelled'";
$result_cancelled = $conn->query($sql_cancelled);
$row_cancelled = $result_cancelled->fetch_assoc();
$cancelledReservations = $row_cancelled['cancelled'];

// Lấy dữ liệu từ các bộ lọc (ngày và trạng thái) nếu có
$dateFilter = isset($_GET['dateFilter']) ? $_GET['dateFilter'] : '';
$statusFilter = isset($_GET['statusFilter']) ? $_GET['statusFilter'] : '';

// Tạo câu lệnh SQL chính để truy vấn danh sách đặt bàn
$query = "SELECT * FROM reservations";
$conditions = [];

// Nếu có lọc theo ngày, thêm điều kiện vào câu lệnh
if (!empty($dateFilter)) {
  $conditions[] = "reservedDate = '$dateFilter'";
}
// Nếu có lọc theo trạng thái, thêm điều kiện vào câu lệnh
if (!empty($statusFilter)) {
  $conditions[] = "status = '$statusFilter'";
}
// Nếu có điều kiện, nối vào câu truy vấn
if (!empty($conditions)) {
  $query .= " WHERE " . implode(' AND ', $conditions);
}
// Bước 1: Cập nhật trạng thái các đơn quá 30 phút
$update_sql = "SELECT reservation_id, reservedDate, reservedTime, status FROM reservations WHERE status = 'Approved'";
$update_result = $conn->query($update_sql);

while ($row = $update_result->fetch_assoc()) {
    $res_time = new DateTime($row['reservedDate'] . ' ' . $row['reservedTime']);
    $interval_minutes = ($current_time->getTimestamp() - $res_time->getTimestamp()) / 60;

    if ($interval_minutes >= 30) {
        $update_stmt = $conn->prepare("UPDATE reservations SET status = 'Cancelled' WHERE reservation_id = ?");
        $update_stmt->bind_param("i", $row['reservation_id']);
        $update_stmt->execute();
        $update_stmt->close();
    }
}
// Bước 2: Truy vấn lại để hiển thị danh sách đã cập nhật
$sql = "SELECT * FROM reservations ORDER BY CONCAT(reservedDate, ' ', reservedTime) ASC";
// Thực thi truy vấn và lưu kết quả
$result = $conn->query($sql);

?>
<?php
include 'sidebar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reservations</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

  <link rel="stylesheet" href="sidebar.css">
  <link rel="stylesheet" href="admin_reservation.css">
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

    <!-- Navigation Items -->
    <ul>
            <li><a href="index.php" ><i class="fas fa-chart-line"></i> Thống kê </a></li>
            <li><a href="admin_menu.php" ><i class="fas fa-utensils"></i> Quản lý thực đơn </a></li>
            <li><a href="admin_orders.php"><i class="fas fa-shopping-cart"></i> Đơn hàng </a></li>
            <li><a href="reservations.php" class="active"><i class="fas fa-calendar-alt"></i> Đặt bàn </a></li>
            <li><a href="users.php"><i class="fas fa-users"></i> Người dùng </a></li>
            <li><a href="reviews.php"><i class="fas fa-star"></i> Đánh giá </a></li>
            <li><a href="staffs.php" ><i class="fas fa-users"></i> Nhân viên </a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i> Hồ sơ </a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
        </ul>
  </div>
  <div class="content">
    <div class="header">
      <button id="toggleSidebar" class="toggle-button">
        <i class="fas fa-bars"></i>
      </button>
      <h2><i class="fas fa-calendar-alt"></i> Đặt bàn </h2>
    </div>
    <div>

      <!-- Reservation Statistics -->
      <div class="stats">
        <div class="stat-item" id="total">
          <div class="stat-icon" id="total-icon">
            <i class="fas fa-calendar-check"></i>
          </div>
          <div class="stat-text">
            <p>Toàn bộ</p>
            <p><?php echo $totalReservations; ?></p>
          </div>
        </div>
        <div class="stat-item" id="today">
          <div class="stat-icon" id="today-icon">
            <i class="fas fa-calendar-day"></i>
          </div>
          <div class="stat-text">
            <p>Hôm nay</p>
            <p><?php echo $todaysReservations; ?></p>
          </div>
        </div>
        <div class="stat-item" id="upcoming">
          <div class="stat-icon" id="upcoming-icon">
            <i class="fas fa-calendar-alt"></i>
          </div>
          <div class="stat-text">
            <p> Sắp tới </p>
            <p><?php echo $upcomingReservations; ?></p>
          </div>
        </div>
        <div class="stat-item" id="cancelled">
          <div class="stat-icon" id="cancelled-icon">
            <i class="fas fa-calendar-times"></i>
          </div>
          <div class="stat-text">
            <p> Đã hủy</p>
            <p><?php echo $cancelledReservations; ?></p>
          </div>
        </div>
      </div>
      <div class="buttons-container">
        <button onclick="openaddReservationModal()"><i class="fas fa-calendar-plus"></i> &nbsp; Thêm thông tin đặt bàn </button>
        <div class="actions">
          <select id="statusFilter" name="statusFilter" onchange="filterByStatus()">
            <option value="">Tất cả</option>
            <option value="Pending">Hoãn</option>
            <option value="Approved">Xác nhận</option>
            <option value="On Process">Đang xử lý</option>
            <option value="Completed">Hoàn thành</option>
            <option value="Cancelled">Hủy bỏ</option>
          </select>
          <input type="date" id="dateFilter" name="dateFilter" value="<?php echo htmlspecialchars($dateFilter); ?>" onchange="filterByDate()">
          <button type="button" onclick="clearFilter()">Xóa</button>
        </div>
      </div>
      <table id="userTable">
        <thead>
          <tr>
            <th>STT</th>
            <th>Đặt bàn lúc</th>
            <th>Email</th>
            <th>Tên</th>
            <th>Liên hệ</th>
            <th>Số lượng khách</th>
            <th>Ngày đặt bàn</th>
            <th>Giờ đặt bàn</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
            <th>Ghi chú</th>
          </tr>
        </thead>
        <tbody>
          <?php
          
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
             $res_time = new DateTime($row['reservedDate'] . ' ' . $row['reservedTime']);
        $interval_minutes = ($current_time->getTimestamp() - $res_time->getTimestamp()) / 60;
        $highlight = "";
        $note = "";

  // Nếu đã đến giờ và chưa xử lý
   if ($interval_minutes >= 30 && $row['status'] == 'Cancelled') {
            $highlight = 'style="background-color: #f8d7da;"';
            $note = 'Tự động hủy sau 30 phút trễ';
        } elseif ($interval_minutes > 0 && $row['status'] == 'Approved') {
            $highlight = 'style="background-color: #fff3cd;"';
            $note = 'Khách chưa đến, vui lòng kiểm tra';
        } elseif ($interval_minutes < 0 && $row['status'] == 'Approved') {
            $highlight = 'style="background-color: #d1e7dd;"';
            $note = 'Sắp đến giờ đặt bàn';
        }

              echo "<tr $highlight>
      <td>{$row['reservation_id']}</td>
      <td>{$row['reservedAt']}</td>
      <td>{$row['email']}</td>
      <td>{$row['name']}</td>
      <td>{$row['contact']}</td>
      <td>{$row['noOfGuests']}</td>
      <td>{$row['reservedDate']}</td>
      <td>{$row['reservedTime']}</td>
      <td>
        <select id='status-{$row['reservation_id']}' onchange=\"updateStatus('{$row['reservation_id']}', this.value)\" class='status-select'>
          <option value='Pending' " . ($row['status'] == 'Pending' ? 'selected' : '') . ">Hoãn</option>
          <option value='Approved' " . ($row['status'] == 'Approved' ? 'selected' : '') . ">Xác nhận</option>
          <option value='On Process' " . ($row['status'] == 'On Process' ? 'selected' : '') . ">Đang xử lý</option>
          <option value='Completed' " . ($row['status'] == 'Completed' ? 'selected' : '') . ">Hoàn thành</option>
          <option value='Cancelled' " . ($row['status'] == 'Cancelled' ? 'selected' : '') . ">Hủy bỏ</option>
        </select>
      </td>
      <td>
        <button id='editbtn' onclick='openEditReservationModal(this)' data-id='{$row['reservation_id']}' data-email='{$row['email']}' data-name='{$row['name']}' data-contact='{$row['contact']}' data-reservedDate='{$row['reservedDate']}' data-reservedTime='{$row['reservedTime']}' data-noOfGuests='{$row['noOfGuests']}' data-status='{$row['status']}'><i class='fas fa-edit'></i></button>
        <button id='deletebtn' onclick=\"deleteItem('{$row['reservation_id']}')\"><i class='fas fa-trash'></i></button>
      </td>
      <td>{$note}</td>
    </tr>";
            }
          } else {
            echo "<tr><td colspan='10' style='text-align: center;'>Không có đơn đặt bàn nào được tìm thấy.</td></tr>";
          }
          ?>
        </tbody>
      </table>


    </div>

    <!-- Modal for adding reservation -->
    <div id="addReservationModal" class="modal">
      <div class="modal-overlay"></div>
      <div class="modal-container">
        <form id="addReservationForm" method="POST" action="add_reservation.php">
          <div class="modal-header">
            <h2>Thêm thông tin đặt bàn</h2>
            <span class="close-icon" onclick="closeModal()">&times;</span>
          </div>
          <div class="modal-content">
            <div class="input-group">
              <input type="email" name="email" id="email" class="input" required>
              <label for="email" class="label">Email</label>
            </div>
          </div>

          <div class="modal-content">
            <div class="input-group">
              <input type="text" name="name" id="name" class="input" required>
              <label for="name" class="label">Tên</label>
            </div>
          </div>

          <div class="modal-content">
            <div class="input-group">
              <input type="text" name="contact" id="contact" class="input" required>
              <label for="contact" class="label">Liên hệ</label>
            </div>
          </div>

          <div class="modal-content">
            <div class="input-group">
              <input type="number" name="noOfGuests" id="noOfGuests" class="input" required>
              <label for="noOfGuests" class="label">Số lượng khách</label>
            </div>
          </div>

          <div class="modal-content">
            <div class="input-group">
              <input type="date" name="reservedDate" id="reservedDate" class="input" required>
              <label for="reservedDate" class="label">Ngày đặt bàn</label>
            </div>
          </div>

          <div class="modal-content">
            <div class="input-group">
              <input type="time" name="reservedTime" id="reservedTime" class="input" required>
              <label for="reservedTime" class="label">Giờ đặt bàn</label>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="button" onclick="closeaddReservationModal()">Hủy bỏ</button>
            <button type="submit" class="button"> Lưu </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal for editing reservation -->
<div id="editReservationModal" class="modal">
  <div class="modal-overlay"></div>
  <div class="modal-container">
    <form id="editReservationForm" method="POST" action="edit_reservation.php">
      <div class="modal-header">
        <h2>Chỉnh sửa thông tin đặt bàn</h2>
        <span class="close-icon" onclick="closeEditReservationModal()">&times;</span>
      </div>
      <div class="modal-content">
        <div class="input-group">
          <input type="email" name="email" id="editEmail" class="input" required>
          <label for="editEmail" class="label">Email</label>
        </div>
      </div>
      <div class="modal-content">
        <div class="input-group">
          <input type="text" name="name" id="editName" class="input" required>
          <label for="editName" class="label">Tên</label>
        </div>
      </div>
      <div class="modal-content">
        <div class="input-group">
          <input type="text" name="contact" id="editContact" class="input" required>
          <label for="editContact" class="label">Liên hệ</label>
        </div>
      </div>
      <div class="modal-content">
        <div class="input-group">
          <input type="number" name="noOfGuests" id="editNoOfGuests" class="input" required>
          <label for="editNoOfGuests" class="label">Số lượng khách</label>
        </div>
      </div>
      <div class="modal-content">
        <div class="input-group">
          <input type="date" name="reservedDate" id="editReservedDate" class="input" required>
          <label for="editReservedDate" class="label">Ngày đặt bàn</label>
        </div>
      </div>
      <div class="modal-content">
        <div class="input-group">
          <input type="time" name="reservedTime" id="editReservedTime" class="input" required>
          <label for="editReservedTime" class="label">Giờ đặt bàn</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="button" onclick="closeEditReservationModal()">Hủy bỏ</button>
        <button type="submit" class="button">Lưu</button>
      </div>
    </form>
  </div>
</div>


<?php
    include_once ('footer.html');
    ?>
    <script>
      function openModal() {
        document.getElementById('addReservationModal').classList.add('open');
      }

      function closeModal() {
        document.getElementById('addReservationModal').classList.remove('open');
      }

      function openaddReservationModal() {
        document.getElementById('addReservationModal').classList.add('open');
      }

      function closeaddReservationModal() {
        document.getElementById('addReservationModal').classList.remove('open');
      }

      function updateStatus(reservation_id, status) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "update_status.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
          if (xhr.readyState == 4 && xhr.status == 200) {
            // Tải lại trang sau khi cập nhật
            location.reload();
          }
        };
        xhr.send("reservation_id=" + encodeURIComponent(reservation_id) + "&status=" + encodeURIComponent(status));
      }



      // Xóa đặt bàn
      function deleteItem(reservation_id) {
        if (confirm('Bạn có chắc chắn bạn muốn xóa thông tin đặt bàn này không?')) {
          var xhr = new XMLHttpRequest();
          xhr.open("POST", "delete_reservation.php", true);
          xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
          xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
              location.reload();
            }
          };
          xhr.send("reservation_id=" + encodeURIComponent(reservation_id));
        }
      }

      // Bật/tắt modal
      const modal = document.querySelector('.modal');
      const buttons = document.querySelectorAll('.toggleButton');

      buttons.forEach(button => {
        button.addEventListener('click', () => {
          modal.classList.toggle('open');
        });
      });


      function filterByDate() {
        var dateFilter = document.getElementById('dateFilter').value;
        var statusFilter = document.getElementById('statusFilter').value;
        window.location.href = 'reservations.php?dateFilter=' + encodeURIComponent(dateFilter) + '&statusFilter=' + encodeURIComponent(statusFilter);
      }

      function filterByStatus() {
        var statusFilter = document.getElementById('statusFilter').value;
        var dateFilter = document.getElementById('dateFilter').value;
        window.location.href = 'reservations.php?statusFilter=' + encodeURIComponent(statusFilter) + '&dateFilter=' + encodeURIComponent(dateFilter);
      }

      // Gán lại giá trị bộ lọc ngày
      document.getElementById('dateFilter').value = "<?= isset($_GET['dateFilter']) ? $_GET['dateFilter'] : '' ?>";

     // Gán lại giá trị bộ lọc trạng thái
      document.getElementById('statusFilter').value = "<?= isset($_GET['statusFilter']) ? $_GET['statusFilter'] : '' ?>";

      function clearFilter() {
        window.location.href = 'reservations.php';
      }


    
      function openEditReservationModal(button) {
  // Lấy thông tin từ thuộc tính data-
  const reservation_id = button.getAttribute('data-id');
  const email = button.getAttribute('data-email');
  const name = button.getAttribute('data-name');
  const contact = button.getAttribute('data-contact');
  const noOfGuests = button.getAttribute('data-noOfGuests');
  const reservedDate = button.getAttribute('data-reservedDate');
  const reservedTime = button.getAttribute('data-reservedTime');
  const status = button.getAttribute('data-status');

  // Gán giá trị vào form chỉnh sửa
  document.getElementById('editEmail').value = email;
  document.getElementById('editName').value = name;
  document.getElementById('editContact').value = contact;
  document.getElementById('editNoOfGuests').value = noOfGuests;
  document.getElementById('editReservedDate').value = reservedDate;
  document.getElementById('editReservedTime').value = reservedTime;

  // Gán hoặc tạo input ẩn chứa reservation_id
  const form = document.getElementById('editReservationForm');
  const hiddenIdInput = form.querySelector('input[name="reservation_id"]');
  if (!hiddenIdInput) {
    // Create a hidden input field if it does not exist
    const newHiddenInput = document.createElement('input');
    newHiddenInput.type = 'hidden';
    newHiddenInput.name = 'reservation_id';
    newHiddenInput.value = reservation_id;
    form.appendChild(newHiddenInput);
  } else {
    hiddenIdInput.value = reservation_id;
  }

  // Mở modal chỉnh sửa
  document.getElementById('editReservationModal').classList.add('open');
}

function closeEditReservationModal() {
  document.getElementById('editReservationModal').classList.remove('open');
}

   
     
      

    
   

    </script>
    <script src="sidebar.js"></script>
</body>

</html>
