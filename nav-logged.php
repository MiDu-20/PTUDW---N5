<?php

// KẾT NỐI DATABASE VÀ KIỂM TRA PHIÊN ĐĂNG NHẬP
include 'db_connection.php';

// Kiểm tra xem người dùng đã đăng nhập chưa
// Thuật toán bảo mật: Redirect về trang login nếu chưa đăng nhập
if (!isset($_SESSION['userloggedin']) || !$_SESSION['userloggedin']) {
  header('Location: login.php');
  exit;
}

// Lấy ảnh đại diện của người dùng từ database
$useremail = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$sql = "SELECT profile_image FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);

// Kiểm tra email có tồn tại trong session không
if (empty($useremail)) {
  die("User email not found in session."); //--Hiển thị thông báo lỗi trực tiếp
}

// Hàm lấy thông tin người dùng từ database
// Thuật toán: Sử dụng Prepared Statement để tránh SQL Injection
function get_UserInfo($email)
{
  global $conn;
  $stmt = $conn->prepare("SELECT  profile_image FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->bind_result($profile_image);
  $stmt->fetch();
  $stmt->close();
  // Trả về thông tin với ảnh mặc định nếu không có
  return [
    'profile_image' => $profile_image ?: 'default.jpg'
  ];
}

$userinfo = get_UserInfo($useremail);
// Đóng kết nối database
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <!--Bootstrap CSS-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  
  <!-- Font Lexend cho navbar -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  
  <!-- Font Awesome cho icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
  
  <!-- Font Chewy cho logo -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Chewy&display=swap" rel="stylesheet">
  
  <title>Navbar</title>
  <style>
    * {
      margin: 0;
      padding: 0;
    }

    body {
      background-color: #feead4;
      font-family: "Poppins", sans-serif;
      font-weight: 300;
      font-style: normal;
    }

    a {
      color: white;
      text-decoration: none;
    }

    .navbar-brand,
    .offcanvas-header {
      color: #fb4a36;
      font-family: "Chewy", system-ui;
      font-optical-sizing: auto;
      font-weight: 500;
      font-style: normal;
      font-size: 28px;
    }

    .nav-container {
      margin-right: 30px;
      margin-left: 30px;
      padding-bottom: 15px;
      padding-right: 13px;
      padding-left: 13px;
    }

    .Btn {
      display: flex;
      align-items: center;
      justify-content: flex-start;
      width: 40px;
      height: 40px;
      border: none;
      border-radius: 50%;
      cursor: pointer;
      position: relative;
      overflow: hidden;
      transition-duration: 0.3s;
      box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.199);
      background-color: #fb4a36;
    }

    /* THIẾT LẬP NÚT (Giữ nguyên cho tương thích) */
    .sign {
      width: 100%;
      transition-duration: 0.3s;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .sign svg {
      width: 22px;
    }

    .sign svg path {
      fill: #1d1818;
    }

    /* TEXT TRONG NÚT */
    .text {
      position: absolute;
      right: 0%;
      width: 0%;
      opacity: 0;
      color: #1d1818;
      font-size: 1.2em;
      font-weight: 500;
      transition-duration: 0.3s;
    }

    /* HIỆU ỨNG HOVER CHO NÚT */
    .Btn:hover {
      width: 125px;
      border-radius: 40px;
      transition-duration: 0.3s;
    }

    .Btn:hover .sign {
      width: 33%;
      transition-duration: 0.3s;
      padding-left: 10px;
      padding-right: 8px;
    }

    /* HIỆU ỨNG CLICK */
    .Btn:hover .text {
      opacity: 1;
      width: 70%;
      transition-duration: 0.3s;
      padding-right: 10px;
    }

    /* THIẾT LẬP NAVBAR TOGGLER */
    .Btn:active {
      transform: translate(2px, 2px);
    }

    .navbar-toggler {
      border: none;
      font-size: 1.25rem;
    }

    .navbar-toggler:focus,
    .btn-close:focus {
      box-shadow: none;
      outline: none;
    }

    /* THIẾT LẬP NAV LINK */
    .nav-link {
      color: black;
      font-weight: 500;
      transition: 0.3s color ease;
      font-family: "Lexend", sans-serif;
      font-optical-sizing: auto;
      font-weight: 480;
      font-style: light;

    }

    .dropdown-menu {
      border: none;
      margin-left: -50px !important;
      text-align: center;
      background-color: #ffbda1;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .dropdown-toggle {
      color: #1d1818;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .dropdown-toggle:hover {
      color: #fb4a36;
    }

    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='gray' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
      padding-left: 22px;
    }

    .nav-link:hover,
    .nav-link.active {
      color: #fb4a36;
    }

    .navbar-nav .nav-link.active,
    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link:focus {
      color: #fb4a36;
    }

    .offcanvas-header {
      background-color: #ffbda1;
    }


    .nav-item.dropdown:hover .dropdown-menu {
      display: block;
    }


    .logo:hover {
      color: white;
    }

    .navbar {
      border-bottom-left-radius: 80px;
      border-bottom-right-radius: 80px;
      padding: 5px 0px 0px 0px;
      background-color: #ffbda1;
    }

    .cart {
      color: green;
      font-size: 25px;
      cursor: pointer;
    }

    /* HIỆU ỨNG HOVER CHO PROFILE */
    .nav-item.dropdown .nav-link:hover {
      color: #fb4a36;
    }

    .dropdown-item {
      color: #212529;
    }

    .dropdown-item:hover {
      background-color: #fb4a36;
      color: white;
    }

    .dropdown-menu .dropdown-item i {
      margin-right: 8px;
      /* Khoảng cách giữa icon và text */
      color: #212529;
      /* Icon color */
    }

    .dropdown-menu .dropdown-item {
      display: flex;
      align-items: center;
      /* Căn giữa theo chiều dọc */
      justify-content: center;
      /* Căn giữa theo chiều ngang */
    }

    .nav-profile {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      object-fit: cover;
    }

    .offcanvas-body {
      background: #ffbda1;
    }

    .offcanvas-header {
      border-bottom: 1px solid #fb4a36;
    }

    .btn-close {
      color: red !important;
    }

    .navbar .active {

      color: #fb4a36;
      font-weight: 700;
    }
  </style>
</head>

<body>
  <?php
  // Lấy tên trang hiện tại
  $current_page = basename($_SERVER['PHP_SELF']);
  ?>

  <!-- Navbar -->
  <div>
    <nav class="navbar navbar-expand-md fixed-top">
      <div class="container-fluid nav-container">
        <a class="navbar-brand me-auto logo" href="index.php">Grill 'N' Chill</a>

        <!-- OFFCANVAS MENU -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
          <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasNavbarLabel">
              Flavour Fiesta
            </h5>
            <button type="button" class="btn-close btn-close-red" aria-label="Close" id="closeOffcanvas"></button>
          </div>

          <div class="offcanvas-body text-center">
            <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
              <!-- MENU TRANG CHỦ -->
              <li class="nav-item">
                <a class="nav-link mx-lg-2 <?php echo $current_page == 'index.php' ? 'active' : ''; ?>" aria-current="page" href="index.php">Home</a>
              </li>

              <!-- MENU THỰC ĐƠN -->
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle mx-lg-2 <?php echo $current_page == 'menu.php' ? 'active' : ''; ?>" href="menu.php" role="button" aria-haspopup="true" aria-expanded="false">
                  Menu
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                  <li><a class="dropdown-item" href="menu.php#appetizer">Appetizers</a></li>
                  <li><a class="dropdown-item" href="menu.php#pizza">Pizza</a></li>
                  <li><a class="dropdown-item" href="menu.php#burger">Burger</a></li>
                  <li><a class="dropdown-item" href="menu.php#beverage">Beverages</a></li>
                </ul>
              </li>

              <!-- MENU ĐẶT BÀN -->
              <li class="nav-item">
                <a class="nav-link mx-lg-2 <?php echo $current_page == 'index.php#Reservation' ? 'active' : ''; ?>" href="index.php#Reservation">Reservation</a>
              </li>

              <!-- MENU VỀ CHÚNG TÔI -->
              <li class="nav-item">
                <a class="nav-link mx-lg-2 <?php echo $current_page == 'index.php#About-Us' ? 'active' : ''; ?>" href="index.php#About-Us">About Us</a>
              </li>

              <!-- MENU ĐÁNH GIÁ -->
              <li class="nav-item">
                <a class="nav-link mx-lg-2 <?php echo $current_page == '#review' ? 'active' : ''; ?>" href="#review">Review</a>
              </li>
            </ul>
          </div>
        </div>

        <!-- ICON GIỎ HÀNG -->
        <a class="nav-link cart <?php echo $current_page == 'cart.php' ? 'active' : ''; ?>" href="cart.php"><i class="fas fa-shopping-cart"></i>
          <span id="cart-item" class="badge badge-danger"></span></a>
        
        <!-- DROPDOWN PROFILE NGƯỜI DÙNG - TÍNH NĂNG MỚI -->
        <!-- Thuật toán: Thay thế nút LOGIN bằng ảnh profile và menu dropdown -->
        <li class="nav-item dropdown ms-3" style="list-style: none; ">
          <a href="#" class="dropdown-toggle" id="profileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <!-- Hiển thị ảnh profile từ database với xử lý bảo mật -->
            <img src="uploads/<?php echo htmlspecialchars($userinfo['profile_image']); ?>" alt="Profile Picture" class="nav-profile">
          </a>

          <!-- MENU DROPDOWN PROFILE -->
          <ul class="dropdown-menu" aria-labelledby="profileDropdown" style="margin-left: -50px;">
            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-circle dropdown-icon"></i> Profile</a></li>
            <li><a class="dropdown-item" href="orders.php"><i class="fas fa-box dropdown-icon"></i> Orders</a></li>
            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt dropdown-icon"></i> Logout</a></li>
          </ul>
        </li>

        <!-- NÚT TOGGLE CHO MOBILE -->
        <button class="navbar-toggler" type="button" aria-label="Toggle navigation" id="toggleOffcanvas">
          <span class="navbar-toggler-icon" style="color: #f9f6e8"></span>
        </button>
      </div>
    </nav>
  </div>

  <!-- BOOTSTRAP JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

  <script>
    // XỬ LÝ OFFCANVAS MENU
    const closeOffcanvasBtn = document.getElementById("closeOffcanvas");
    const toggleOffcanvasBtn = document.getElementById("toggleOffcanvas");
    const offcanvasNavbar = new bootstrap.Offcanvas(
      document.getElementById("offcanvasNavbar")
    );

    closeOffcanvasBtn.addEventListener("click", function() {
      offcanvasNavbar.hide();
    });

    toggleOffcanvasBtn.addEventListener("click", function() {
      offcanvasNavbar.show();
    });
  </script>
  
  <script>
    // XỬ LÝ HIGHLIGHT MENU DỰA TRÊN SCROLL
    document.addEventListener("DOMContentLoaded", function() {
      const sections = document.querySelectorAll("section");
      const navLinks = document.querySelectorAll(".navbar-nav .nav-link");
      const currentPage = window.location.pathname.split("/").pop(); // Get the current page name

      function removeActiveClasses() {
        navLinks.forEach(link => {
          link.classList.remove("active");
        });
      }

      function addActiveClassOnScroll() {
        let currentSection = "Home"; // Default to Home if on index.php

        // Check if the current page is index.php
        if (currentPage === "index.php") {
          sections.forEach(section => {
            const sectionTop = section.offsetTop;
            if (pageYOffset >= sectionTop - 60) {
              currentSection = section.getAttribute("id");
            }
          });

          removeActiveClasses();

          if (currentSection === "Reservation" || currentSection === "About-Us" || currentSection === "review") {
            const activeLink = document.querySelector(`.navbar-nav a[href*="${currentSection}"]`);
            if (activeLink) {
              activeLink.classList.add("active");
            }
          } else {
            // Default to highlighting Home when on index.php
            const homeLink = document.querySelector(`.navbar-nav a[href="index.php"]`);
            if (homeLink) {
              homeLink.classList.add("active");
            }
          }
        } else {
          // Highlight the current page if it's not index.php
          const activeLink = document.querySelector(`.navbar-nav a[href="${currentPage}"]`);
          if (activeLink) {
            removeActiveClasses();
            activeLink.classList.add("active");
          }
        }
      }

      window.addEventListener("scroll", addActiveClassOnScroll);
      addActiveClassOnScroll(); // Call it initially to set the correct tab on page load
    });
  </script>
</body>
</html>
