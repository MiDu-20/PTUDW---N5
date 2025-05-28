<?php
session_start(); // Bắt đầu phiên làm việc (session)

// Kiểm tra xem admin đã đăng nhập chưa
if (!isset($_SESSION['adminloggedin']) || !$_SESSION['adminloggedin']) {
  header('Location: login.php'); // Nếu chưa thì chuyển hướng về trang đăng nhập
  exit;
}

// Lấy email của admin đang đăng nhập từ session
$admin_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

// Nếu không tìm thấy email trong session thì dừng chương trình
if (empty($admin_email)) {
  die("Admin email not found in session.");
}

// Kết nối đến CSDL
include 'db_connection.php';

// Hàm lấy thông tin admin từ CSDL dựa trên email
function getAdminInfo($email)
{
  global $conn;
  $stmt = $conn->prepare("SELECT firstName, lastName, email, contact, password, profile_image FROM staff WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->bind_result($firstName, $lastName, $email, $contact, $password, $profile_image);
  $stmt->fetch();
  $stmt->close();

  return [
    'firstName' => $firstName ?: '',
    'lastName' => $lastName ?: '',
    'email' => $email ?: '',
    'contact' => $contact ?: '',
    'password' => $password ?: '',
    'profile_image' => $profile_image ?: 'default.jpg' // Ảnh mặc định nếu không có
  ];
}

// Hàm cập nhật thông tin admin vào CSDL
function updateAdminInfo($email, $firstName, $lastName, $contact, $password, $profile_image)
{
  global $conn;
  $stmt = $conn->prepare("UPDATE staff SET firstName = ?, lastName = ?, contact = ?, password = ?, profile_image = ? WHERE email = ?");
  $stmt->bind_param("ssssss", $firstName, $lastName, $contact, $password, $profile_image, $email);
  $stmt->execute();
  $stmt->close();
}

// Xử lý khi admin gửi form cập nhật
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Lấy dữ liệu từ form gửi lên
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $contact = $_POST['contact'];
  $password = $_POST['password'];

  // Lấy ảnh cũ từ DB
  $profile_image = getAdminInfo($admin_email)['profile_image'];

  // Nếu admin có tải lên ảnh mới
  if (!empty($_FILES['profile_image']['name'])) {
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
    move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file);
    $profile_image = basename($_FILES["profile_image"]["name"]); // Lưu tên file ảnh
  }

  // Gọi hàm cập nhật thông tin admin
  updateAdminInfo($admin_email, $firstName, $lastName, $contact, $password, $profile_image);

  // Gán thông báo thành công vào session
  $_SESSION['success_message'] = "Thay đổi thành công";

  // Chuyển về lại trang hồ sơ sau khi cập nhật
  header('Location: profile.php');
  exit;
}

// Lấy thông tin admin để hiển thị trên giao diện
$admin_info = getAdminInfo($admin_email);
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Settings</title>

  <!-- Font Poppins từ Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Icon Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

  <!-- CSS tùy chỉnh giao diện sidebar và profile -->
  <link rel="stylesheet" href="sidebar.css">
  <link rel="stylesheet" href="profile.css">
</head>

<body>
  <!-- Thanh bên trái (Sidebar) -->
  <div class="sidebar">
    <!-- Nút đóng sidebar -->
    <button class="close-sidebar" id="closeSidebar">&times;</button>

    <!-- Khu vực hiển thị ảnh đại diện admin -->
    <div class="profile-section">
      <img src="../uploads/<?php echo htmlspecialchars($admin_info['profile_image']); ?>" alt="Profile Picture">
      <div class="info">
        <h3>Welcome Back!</h3>
        <p><?php echo htmlspecialchars($admin_info['firstName']) . ' ' . htmlspecialchars($admin_info['lastName']); ?></p>
      </div>
    </div>

    <!-- Danh sách điều hướng (menu admin) -->
    <ul>
      <li><a href="index.php"><i class="fas fa-chart-line"></i>Tổng quan</a></li>
      <li><a href="admin_menu.php"><i class="fas fa-utensils"></i>Quản lý thực đơn</a></li>
      <li><a href="admin_orders.php"><i class="fas fa-shopping-cart"></i>Đơn hàng</a></li>
      <li><a href="reservations.php"><i class="fas fa-calendar-alt"></i>Đặt bàn</a></li>
      <li><a href="users.php"><i class="fas fa-users"></i>Người dùng</a></li>
      <li><a href="reviews.php"><i class="fas fa-star"></i>Đánh giá</a></li>
      <li><a href="staffs.php"><i class="fas fa-users"></i>Nhân viên</a></li>
      <li><a href="profile.php" class="active"><i class="fas fa-user"></i>Hồ sơ</a></li>
      <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i>Đăng xuất</a></li>
    </ul>
  </div>

  <!-- Phần nội dung chính bên phải -->
  <div class="content">
    <!-- Header của phần content -->
    <div class="header">
      <button id="toggleSidebar" class="toggle-button">
        <i class="fas fa-bars"></i> <!-- Nút mở sidebar -->
      </button>
      <h2><i class="fas fa-user"></i>Hồ sơ</h2>
    </div>

    <!-- Nội dung chỉnh sửa hồ sơ -->
    <div class="wrapper">
      <div class="container">
        <!-- Hiển thị ảnh đại diện admin -->
        <img src="../uploads/<?php echo htmlspecialchars($admin_info['profile_image']); ?>" alt="Profile Image" class="profile-image">

        <!-- Hiển thị thông báo -->
        <?php if (isset($_SESSION['success_message'])): ?>
          <div style="background-color: #d4edda; color: #155724; padding: 10px 20px; margin-bottom: 20px; border-radius: 5px; border: 1px solid #c3e6cb;">
            <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success_message']; ?>
          </div>
          <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <!-- Form cập nhật thông tin -->
        <form action="profile.php" method="post" enctype="multipart/form-data">
          <div class="form-row">
            <!-- Họ -->
            <div class="form-group">
              <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($admin_info['lastName']); ?>" placeholder=" ">
              <label for="lastName">Họ:</label>
            </div>

            <!-- Tên -->
            <div class="form-group">
              <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($admin_info['firstName']); ?>" placeholder=" ">
              <label for="firstName">Tên:</label>
            </div>
          </div>

          <div class="form-row">
            <!-- Email (không thể chỉnh sửa) -->
            <div class="form-group">
              <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin_info['email']); ?>" readonly placeholder=" ">
              <label for="email">Email:</label>
            </div>

            <!-- Số điện thoại -->
            <div class="form-group">
              <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($admin_info['contact']); ?>" placeholder=" ">
              <label for="contact">Số liên lạc:</label>
            </div>
          </div>

          <div class="form-row">
            <!-- Mật khẩu (dạng text, cần cải thiện bảo mật) -->
            <div class="form-group">
              <input type="text" id="password" name="password" value="<?php echo htmlspecialchars($admin_info['password']); ?>" placeholder=" ">
              <label for="password">Mật khẩu:</label>
            </div>

            <!-- Tải ảnh đại diện mới -->
            <div class="form-group">
              <input type="file" id="profile_image" name="profile_image" placeholder=" ">
            </div>
          </div>

          <!-- Nút gửi form -->
          <button type="submit">Lưu thay đổi</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Nhúng footer nếu có -->
  <?php include_once ('footer.html'); ?>

  <!-- Script xử lý sidebar -->
  <script src="sidebar.js"></script>
</body>

</html>

<!-- Đóng kết nối CSDL -->
<?php $conn->close(); ?>
