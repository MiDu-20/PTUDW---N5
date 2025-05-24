
<?php
// -- Bắt đầu session để sử dụng thông tin người dùng
session_start();

// -- Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['userloggedin']) || !$_SESSION['userloggedin']) {
  header('Location: login.php'); // Chuyển hướng về trang đăng nhập nếu chưa đăng nhập
  exit;
}

// -- Lấy email người dùng đang đăng nhập từ session
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

if (empty($user_email)) {
  die("Không tìm thấy email người dùng.");
}

// -- Kết nối cơ sở dữ liệu
include 'db_connection.php';

// -- Hàm lấy thông tin người dùng từ CSDL theo email
function getUserInfo($email)
{
  global $conn;
  $stmt = $conn->prepare("SELECT firstName, lastName, email, contact, password, profile_image FROM users WHERE email = ?");
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
    'profile_image' => $profile_image ?: 'default.jpg'
  ];
}

// -- Hàm cập nhật thông tin người dùng trong CSDL
function updateUserInfo($email, $firstName, $lastName, $contact, $password, $profile_image)
{
  global $conn;
  $stmt = $conn->prepare("UPDATE users SET firstName = ?, lastName = ?, contact = ?, password = ?, profile_image = ? WHERE email = ?");
  $stmt->bind_param("ssssss", $firstName, $lastName, $contact, $password, $profile_image, $email);
  $stmt->execute();
  $stmt->close();
}

// -- Nếu form được gửi đi (POST method)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $contact = $_POST['contact'];
  $password = $_POST['password'];
  $profile_image = getUserInfo($user_email)['profile_image'];

  // -- Nếu có upload ảnh mới, lưu lại file ảnh và cập nhật tên
  if (!empty($_FILES['profile_image']['name'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
    move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file);
    $profile_image = basename($_FILES["profile_image"]["name"]);
  }

  // -- Cập nhật thông tin người dùng
  updateUserInfo($user_email, $firstName, $lastName, $contact, $password, $profile_image);

  // -- Chuyển hướng sau khi cập nhật
  header('Location: profile.php');
  exit;
}

// -- Lấy thông tin người dùng để hiển thị ra form
$user_info = getUserInfo($user_email);
?>
