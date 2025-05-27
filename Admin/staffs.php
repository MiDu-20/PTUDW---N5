<?php
// Bắt đầu phiên làm việc (session)
session_start();

// Kiểm tra xem admin đã đăng nhập hay chưa, nếu chưa thì chuyển hướng đến trang đăng nhập
if (!isset($_SESSION['adminloggedin'])) {
  header("Location: ../login.php");
  exit();
}

// Kết nối cơ sở dữ liệu
include 'db_connection.php';

// Khởi tạo biến tìm kiếm rỗng
$search = '';
// Nếu người dùng gửi biểu mẫu tìm kiếm thì xử lý dữ liệu tìm kiếm
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
  $search = $conn->real_escape_string($_POST['search']); // Bảo vệ SQL injection
}
?>

<!-- Bao gồm thanh điều hướng bên trái -->
<?php include 'sidebar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Staff Management</title>

  <!-- Font chữ Poppins -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

  <!-- Font Awesome cho icon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

  <!-- CSS giao diện -->
  <link rel="stylesheet" href="sidebar.css">
  <link rel="stylesheet" href="admin_user.css">

  <style>
    /* Điều chỉnh icon trong thanh điều hướng */
    .sidebar ul li a i {
      margin-right: 10px;
    }

    /* Làm nổi bật menu đang được chọn */
    .sidebar ul li a.active {
      font-weight: bold;
    }

    /* Chiều cao của hộp chỉnh sửa và thêm người dùng */
    #editUserModal .modal-container {
      height: 39rem;
    }

    #addUserModel .modal-container {
      height: 40rem;
    }
  </style>
</head>
<body>
  <!-- Giao diện thanh điều hướng -->
  <div class="sidebar">
    <button class="close-sidebar" id="closeSidebar">&times;</button>

    <!-- Thông tin người quản trị -->
    <div class="profile-section">
      <img src="../uploads/<?php echo htmlspecialchars($admin_info['profile_image']); ?>" alt="Ảnh đại diện">
      <div class="info">
        <h3>Welcome Back!</h3>
        <p><?php echo htmlspecialchars($admin_info['firstName']) . ' ' . htmlspecialchars($admin_info['lastName']); ?></p>
      </div>
    </div>

    <!-- Menu điều hướng -->
    <ul>
      <li><a href="index.php"><i class="fas fa-chart-line"></i> Overview</a></li>
      <li><a href="admin_menu.php"><i class="fas fa-utensils"></i> Menu Management</a></li>
      <li><a href="admin_orders.php"><i class="fas fa-shopping-cart"></i> Orders</a></li>
      <li><a href="reservations.php"><i class="fas fa-calendar-alt"></i> Reservations</a></li>
      <li><a href="users.php"><i class="fas fa-users"></i> Users</a></li>
      <li><a href="reviews.php"><i class="fas fa-star"></i> Reviews</a></li>
      <li><a href="staffs.php" class="active"><i class="fas fa-users"></i> Staffs</a></li>
      <li><a href="profile.php"><i class="fas fa-user"></i> Profile Setting</a></li>
      <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </div>

  <!-- Nội dung chính -->
  <div class="content">
    <!-- Tiêu đề và nút mở sidebar -->
    <div class="header">
      <button id="toggleSidebar" class="toggle-button"><i class="fas fa-bars"></i></button>
      <h2><i class="fas fa-users"></i> Staff List</h2>
    </div>

    <!-- Hành động: Thêm và Tìm kiếm -->
    <div class="actions">
      <button onclick="openaddUserModal()"><i class="fas fa-user-plus"></i> Add Staff</button>
      <form method="POST" id="searchForm" class="search-bar">
        <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
      </form>
    </div>

    <!-- Bảng danh sách nhân viên -->
    <table id="userTable">
      <thead>
        <tr>
          <th>ID</th>
          <th>Date Created</th>
          <th>Email</th>
          <th>Name</th>
          <th>Contact</th>
          <th>Role</th>
          <th>Password</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Câu truy vấn hiển thị danh sách nhân viên, có tìm kiếm nếu người dùng nhập
        $sql = "SELECT * FROM staff";
        if (!empty($search)) {
          $sql .= " WHERE email LIKE '%$search%' OR firstName LIKE '%$search%' OR lastName LIKE '%$search%'";
        }

        // Thực thi truy vấn
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
          // Hiển thị từng dòng dữ liệu
          while ($row = $result->fetch_assoc()) {
            $passwordMasked = str_repeat('*', strlen($row['password'])); // Ẩn mật khẩu bằng dấu *
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['createdAt']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['firstName']} {$row['lastName']}</td>
                    <td>{$row['contact']}</td>
                    <td>{$row['role']}</td>
                    <td>
                      <span class='password-masked'>{$passwordMasked}</span>
                      <span class='password-visible' style='display: none;'>{$row['password']}</span>
                      <i class='fas fa-eye-slash toggle-password' onclick='togglePassword(this)'></i>
                    </td>
                    <td>
                      <button onclick='openEditUserModal(this)' data-email='{$row['email']}' data-firstname='{$row['firstName']}' data-lastname='{$row['lastName']}' data-contact='{$row['contact']}' data-role='{$row['role']}' data-password='{$row['password']}'><i class='fas fa-edit'></i></button>
                      <button onclick=\"deleteItem('{$row['email']}')\"><i class='fas fa-trash'></i></button>
                    </td>
                  </tr>";
          }
        } else {
          echo "<tr><td colspan='8' style='text-align:center;'>No Users Found</td></tr>";
        }

        // Đóng kết nối
        $conn->close();
        ?>
      </tbody>
    </table>
  </div>

  <!-- Modal thêm nhân viên -->
  <div id="addUserModal" class="modal">
    <div class="modal-overlay"></div>
    <div class="modal-container">
      <form id="addUserForm" method="POST" action="add_staffs.php">
        <div class="modal-header">
          <h2>Add Staff</h2>
          <span class="close-icon" onclick="closeaddUserModal()">&times;</span>
        </div>
        <!-- Các trường nhập liệu: email, họ tên, số điện thoại, vai trò, mật khẩu -->
        <div class="modal-content">
          <div class="input-group">
            <input type="email" name="email" required>
            <label>Email</label>
          </div>
          <div class="input-group">
            <input type="text" name="firstName" required>
            <label>First Name</label>
          </div>
          <div class="input-group">
            <input type="text" name="lastName" required>
            <label>Last Name</label>
          </div>
          <div class="input-group">
            <input type="text" name="contact" required>
            <label>Contact</label>
          </div>
          <div class="input-group">
            <select name="role" required>
              <option value="">Role</option>
              <option value="admin">Admin</option>
              <option value="superadmin">Super Admin</option>
              <option value="delivery boy">Delivery Boy</option>
              <option value="waiter">Waiter</option>
            </select>
            <label>Role</label>
          </div>
          <div class="input-group">
            <input type="password" name="password" id="Password" required>
            <span class="toggle-password" onclick="togglePasswordVisibility()"><i class="fas fa-eye-slash" id="passwordIcon"></i></span>
            <label>Password</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" onclick="closeaddUserModal()">Cancel</button>
          <button type="submit">Save</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal chỉnh sửa nhân viên -->
  <div id="editUserModal" class="modal">
    <div class="modal-overlay"></div>
    <div class="modal-container">
      <form id="editUserForm" method="POST" action="edit_staffs.php">
        <div class="modal-header">
          <h2>Edit User</h2>
          <span class="close-icon" onclick="closeEditUserModal()">&times;</span>
        </div>
        <div class="modal-content">
          <div class="input-group">
            <input type="email" name="email" id="editEmail" readonly required>
            <label>Email</label>
          </div>
          <div class="input-group">
            <input type="text" name="firstName" id="editFirstName" required>
            <label>First Name</label>
          </div>
          <div class="input-group">
            <input type="text" name="lastName" id="editLastName" required>
            <label>Last Name</label>
          </div>
          <div class="input-group">
            <input type="text" name="contact" id="editContact" required>
            <label>Contact</label>
          </div>
          <div class="input-group">
            <select name="role" id="editRole" required>
              <option value="">Role</option>
              <option value="admin">Admin</option>
              <option value="superadmin">Super Admin</option>
              <option value="delivery boy">Delivery Boy</option>
            </select>
            <label>Role</label>
          </div>
          <div class="input-group">
            <input type="password" name="password" id="editPassword" required>
            <span class="toggle-password" onclick="toggleEditPasswordVisibility()"><i class="fas fa-eye-slash" id="editPasswordIcon"></i></span>
            <label>Password</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" onclick="closeEditUserModal()">Cancel</button>
          <button type="submit">Save</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Gồm phần footer -->
  <?php include_once ('footer.html'); ?>

  <!-- Script điều khiển giao diện -->
  <script src="sidebar.js"></script>
  <script>
    // Chuyển đổi hiển thị mật khẩu khi nhấn icon
    function togglePassword(el) {
      const masked = el.previousElementSibling.previousElementSibling;
      const visible = el.previousElementSibling;
      if (masked.style.display === 'none') {
        masked.style.display = 'inline';
        visible.style.display = 'none';
        el.classList.remove('fa-eye');
        el.classList.add('fa-eye-slash');
      } else {
        masked.style.display = 'none';
        visible.style.display = 'inline';
        el.classList.remove('fa-eye-slash');
        el.classList.add('fa-eye');
      }
    }

    function togglePasswordVisibility() {
      const input = document.getElementById('Password');
      const icon = document.getElementById('passwordIcon');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      }
    }

    function toggleEditPasswordVisibility() {
      const input = document.getElementById('editPassword');
      const icon = document.getElementById('editPasswordIcon');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      }
    }
  </script>
</body>
</html>
