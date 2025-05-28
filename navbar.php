<!DOCTYPE html>
<html lang="en">
  
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <!-- Bootstrap CSS - Framework CSS để tạo giao diện responsive -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  
  <!-- Font Lexend cho navbar - Font chữ đẹp và dễ đọc -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Baloo+2:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">

  <!-- Font Awesome - Thư viện icon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Material Icons - Thư viện icon Google -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

  <!-- Font Chewy - Font vui nhộn cho logo -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Chewy&display=swap" rel="stylesheet">
  
  <title>Navbar</title>
  <style>
    /* RESET CSS - Xóa margin và padding mặc định */
    * {
      margin: 0;
      padding: 0;
    }

    body {
      background-color: #feead4;
      font-family: "Baloo 2", sans-serif;
      font-weight: 300;
      font-style: normal;
      font-size: large;
    }

    a {
      color: white;
      text-decoration: none; /* Bỏ gạch chân */
    }

    .navbar-brand,
    .offcanvas-header {
      color: #ff9d23;
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

    /* THIẾT LẬP NÚT ĐĂNG NHẬP - Hiệu ứng mở rộng khi hover */
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
      background-color: #ff9d23;
    }

    /* ICON TRONG NÚT */
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

    /* TEXT TRONG NÚT - Ban đầu ẩn */
    .text {
      position: absolute;
      right: 0%;
      width: 0%;
      opacity: 0;
      color: #1d1818;
      font-size: 18px;
      font-weight: 500;
      transition-duration: 0.3s;
    }

    /* HIỆU ỨNG HOVER - Mở rộng nút */
    .Btn:hover {
      width: 170px;
      border-radius: 40px;
      transition-duration: 0.3s;
    }

    .Btn:hover .sign {
      width: 33%;
      transition-duration: 0.3s;
      padding-left: 10px;
      padding-right: 8px;
    }

    /* HIỆU ỨNG HOVER - Hiện text */
    .Btn:hover .text {
      opacity: 1;
      width: 100%;
      transition-duration: 0.3s;
      padding-left: 30px;
      align: center;
    }

    /* HIỆU ỨNG CLICK - Tạo cảm giác nhấn */
    .Btn:active {
      transform: translate(2px, 2px);
    }

    .navbar-toggler {
      border: none;
      font-size: 1.25rem;
    }

    /* THIẾT LẬP NÚT TOGGLE MENU MOBILE */
    .navbar-toggler:focus,
    .btn-close:focus {
      box-shadow: none;
      outline: none;
    }

    .nav-link {
      color: black;
      font-weight: 500;
      transition: 0.3s color ease;
      font-family: "Baloo 2", sans-serif;
      font-optical-sizing: auto;
      font-weight: 480;
      font-style: light;
    }

    .dropdown-menu {
      border: none;
      margin-left: -30px;
      text-align: center;
      background-color: #e5d0ac;
      margin-top: -5px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .dropdown-item {
      color: #212529;
    }

    .dropdown-item:hover {
      background-color: #ff9d23;
      color: white;
    }

    .nav-item.dropdown .nav-link:hover {
      color: #ff9d23;
    }

    /* THIẾT LẬP ICON HAMBURGER MENU */
    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='gray' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
      padding-left: 22px;
    }
    
    /* HIỆU ỨNG HOVER VÀ ACTIVE CHO NAV LINK */
    .nav-link:hover,
    .nav-link.active {
      color: #ff9d23;
    }

    .navbar-nav .nav-link.active,
    .navbar-nav .nav-link:hover,
    .navbar-nav .nav-link:focus {
      color: #ff9d23;
    }

    .offcanvas-header {
      background-color: #c14600;
    }

    /* HIỆU ỨNG HOVER CHO DROPDOWN - Hiện menu khi hover */
    .nav-item.dropdown:hover .dropdown-menu {
      display: block;
    }

    .logo:hover {
      color: white;
    }

    /* THIẾT LẬP NAVBAR CHÍNH */
    .navbar {
      border-bottom-left-radius: 80px;
      border-bottom-right-radius: 80px;
      padding: 5px 0px 0px 0px;
      background-color: #c14600; /* Màu nền navbar */
    }

    .cart {
      color: green;
      font-size: 25px;
      cursor: pointer;
    }

    .offcanvas-body {
      background: #c14600;
    }

    .offcanvas-header {
      border-bottom: 1px solid #ff9d23;
    }

    .navbar .active {
      color: #ff9d23;
      font-weight: 700;
    }
  </style>
</head>

<body>
  <?php
  // Lấy tên trang hiện tại
  // Thuật toán: Sử dụng basename() để lấy tên file từ đường dẫn hiện tại
  // $_SERVER['PHP_SELF'] chứa đường dẫn đầy đủ, basename() chỉ lấy tên file
  $current_page = basename($_SERVER['PHP_SELF']);
  ?>

  <!-- Thanh điều hướng -->
  <div>
    <nav class="navbar navbar-expand-md fixed-top">
      <div class="container-fluid nav-container">
        <a class="navbar-brand me-auto logo" href="index.php">Chomp Chomp Fast Food</a>

        <!-- OFFCANVAS MENU (Menu trượt từ bên phải trên mobile) -->
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
                <!-- Thuật toán: So sánh trang hiện tại với 'index.php' để thêm class 'active' -->
                <a class="nav-link mx-lg-2 <?php echo $current_page == 'index.php' ? 'active' : ''; ?>" aria-current="page" href="index.php">Home</a>
              </li>

              <!-- MENU DROPDOWN -->
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle mx-lg-2 <?php echo $current_page == 'menu.php' ? 'active' : ''; ?>" href="menu.php" role="button" aria-haspopup="true" aria-expanded="false">
                  Thực đơn
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                  <li style="font-size: large;"><a class="dropdown-item" href="menu.php#appetizer">Khai vị</a></li>
                  <li style="font-size: large;"><a class="dropdown-item" href="menu.php#pizza">Pizza</a></li>
                  <li style="font-size: large;"><a class="dropdown-item" href="menu.php#burger">Burger</a></li>
                  <li style="font-size: large;"><a class="dropdown-item" href="menu.php#beverage">Thức uống</a></li>
                </ul>
              </li>

              <!-- MENU ĐẶT BÀN -->
              <li class="nav-item">
                <a class="nav-link mx-lg-2 <?php echo $current_page == 'index.php#Reservation' ? 'active' : ''; ?>" href="index.php#Reservation">Đặt bàn</a>
              </li>

              <!-- MENU VỀ CHÚNG TÔI -->
              <li class="nav-item">
                <a class="nav-link mx-lg-2 <?php echo $current_page == 'index.php#About-Us' ? 'active' : ''; ?>" href="index.php#About-Us">Về chúng tôi</a>
              </li>

              <!-- MENU ĐÁNH GIÁ -->
              <li class="nav-item">
                <a class="nav-link mx-lg-2 <?php echo $current_page == '#review' ? 'active' : ''; ?>" href="#review">Đánh giá</a>
              </li>
            </ul>
          </div>
        </div>

        <!-- ICON GIỎ HÀNG -->
        <a class="nav-link cart <?php echo $current_page == 'cart.php' ? 'active' : ''; ?>" href="cart.php"><i class="fas fa-shopping-cart"></i> <span id="cart-item" class="badge badge-danger"></span></a>

        <!-- NÚT ĐĂNG NHẬP VỚI HIỆU ỨNG ĐẶC BIỆT -->
        <a href="login.php">
          <button class="Btn ms-3">
            <div class="text">ĐĂNG NHẬP</div>
            <div class="sign">
              <!-- SVG ICON MŨI TÊN -->
              <svg viewBox="0 0 512 512">
                <path d="M217.9 105.9L340.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L217.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1L32 320c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM352 416l64 0c17.7 0 32-14.3 32-32l0-256c0-17.7-14.3-32-32-32l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32l64 0c53 0 96 43 96 96l0 256c0 53-43 96-96 96l-64 0c-17.7 0-32-14.3-32-32s14.3-32 32-32z"></path>
              </svg>
            </div>
          </button>
        </a>
        
        <!-- NÚT TOGGLE CHO MOBILE MENU -->
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
    // Thuật toán: Sử dụng Bootstrap Offcanvas API để điều khiển menu trượt
    const closeOffcanvasBtn = document.getElementById("closeOffcanvas");
    const toggleOffcanvasBtn = document.getElementById("toggleOffcanvas");
    const offcanvasNavbar = new bootstrap.Offcanvas(
      document.getElementById("offcanvasNavbar")
    );

    // Sự kiện đóng menu
    closeOffcanvasBtn.addEventListener("click", function() {
      offcanvasNavbar.hide();
    });

    // Sự kiện mở menu
    toggleOffcanvasBtn.addEventListener("click", function() {
      offcanvasNavbar.show();
    });
  </script>
  
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    const OFFSET = 70; // chiều cao navbar cố định, điều chỉnh nếu cần
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

    function activateOnScroll() {
      const scrollPos = window.scrollY + OFFSET;
      let found = false;

      // 1. Kiểm tra các link có hash (các section trên index.php)
      navLinks.forEach(link => {
        const hash = link.hash; 
        if (!hash) return;
        const section = document.querySelector(hash);
        if (section &&
            section.offsetTop <= scrollPos &&
            section.offsetTop + section.offsetHeight > scrollPos) {
          navLinks.forEach(l => l.classList.remove('active'));
          link.classList.add('active');
          found = true;
        }
      });

      if (!found) {
        // 2. Nếu không phải index.php hoặc không cuộn vào section nào,
        //    lấy tên file hiện tại và tìm link tương ứng
        const fileName = window.location.pathname.split('/').pop() || 'index.php';
        const pageLink = document.querySelector(`.navbar-nav .nav-link[href$="${fileName}"]`);
        if (pageLink) {
          navLinks.forEach(l => l.classList.remove('active'));
          pageLink.classList.add('active');
        } else {
          // 3. Cuối cùng nếu vẫn không tìm được, highlight Home
          const homeLink = document.querySelector('.navbar-nav .nav-link[href="index.php"]');
          if (homeLink) {
            navLinks.forEach(l => l.classList.remove('active'));
            homeLink.classList.add('active');
          }
        }
      }
    }

    window.addEventListener('scroll', activateOnScroll);
    activateOnScroll(); // chạy ngay khi load trang để set đúng link
  });
</script>

</body>
</html>
