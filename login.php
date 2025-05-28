
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Import Font Awesome icons -->
  <!-- Nhúng thư viện Font Awesome (icon) -->
  <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Baloo+2&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="login.css" />
  
  <title>Đăng nhập/Đăng ký</title>
  <style>
    /* Icon ẩn/hiện mật khẩu */
    .input-field .fa-eye,
    .input-field .fa-eye-slash {
      position: absolute;
      right: 10px;
      cursor: pointer;
      color: #007bff;
      transition: color 0.3s ease;
    }

    .input-field .fa-eye:hover,
    .input-field .fa-eye-slash:hover {
      color: #0056b3;
    }
  </style>
</head>

<body>

  <?php
  // Gọi thanh điều hướng (navbar) vào trang login
  include_once("navbar.php");
  ?>

  <!-- Container toàn trang -->
  <div class="container">
    <div class="forms-container">
      <div class="signin-signup">
        <!-- Form đăng nhập -->
        <form action="dblogin.php" class="sign-in-form" method="POST">
          <h2 class="title">Đăng nhập</h2>
          <div class="input-field">
            <i class="fas fa-envelope"></i>
            <input type="email" placeholder="Email" name="email" required onkeyup="hideAlertBox()" />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" id="loginPassword" placeholder="Mật khẩu" name="password" required onkeyup="hideAlertBox()" />
            <i class="fas fa-eye-slash" id="toggleLoginPassword" style="cursor: pointer;"></i>
          </div>
          <input type="submit" value="Đăng nhập" class="submit solid" id="loginButton" />

          <?php
           // Nếu có lỗi từ URL, hiển thị thông báo sai tài khoản/mật khẩu
          if (isset($_GET['error'])) {
            echo ('<div class="alert alert-danger" id="alertbox" role="alert">
              Email hoặc mật khẩu không đúng.
            </div>');
          }
          ?>
        </form>

        <!-- Form đăng ký -->
        <form action="dbregister.php" class="sign-up-form" method="POST" id="registerForm">
          <h2 class="title">Đăng ký</h2>
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="Họ" name="firstName" onkeyup="hideAlertBox()" required />
          </div>
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="Tên" name="lastName" onkeyup="hideAlertBox()" required />
          </div>
          <div class="input-field">
            <i class="fas fa-envelope"></i>
            <input type="email" placeholder="Email" name="email" onkeyup="hideAlertBox()" required />
          </div>
          <div class="input-field">
            <i class="fas fa-phone" style="transform: rotate(90deg);"></i>
            <input type="text" placeholder="Số điện thoại" name="contact" onkeyup="hideAlertBox()" required />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" id="registerPassword" placeholder="Mật khẩu" name="password" required onkeyup="hideAlertBox()" />
            <i class="fas fa-eye-slash" id="toggleRegisterPassword" style="cursor: pointer;"></i>
          </div>
          <input type="submit" class="submit" value="Đăng ký" id="registerButton" />
        </form>
      </div>
    </div>

    <!-- Khu vực panel giới thiệu -->
    <div class="panels-container">
      <!-- Panel bên trái: dành cho người mới -->
      <div class="panel left-panel">
        <div class="content">
         <h2 style="font-family: 'Baloo 2', sans-serif;">Lần đầu đến với nhà hàng?</h2>
          <p style="text-align: center; font-family: 'Baloo 2', sans-serif; width: 100%; margin: 0 auto; font-size: 1.1rem;">
            Hãy đăng ký ngay hôm nay để trải nghiệm đặt món trực tuyến tiện lợi, nhận ưu đãi độc quyền và theo dõi đơn hàng dễ dàng.
          </p>
          <button class="btn transparent" id="sign-up-btn">Đăng ký</button>
        </div>
        <img src="images/form-pic3.png" class="image" alt="" style="margin-bottom: 400px" />
      </div>

      <!-- Panel bên phải: dành cho khách hàng cũ -->
      <div class="panel right-panel">
        <div class="content">
          <h2 style="font-family: 'Baloo 2', sans-serif;">Quý khách đã từng đặt món?</h2>
          <p style="font-family: 'Baloo 2', sans-serif; font-size: 1.1rem;">
            Hãy đăng nhập để tiếp tục thưởng thức các món ăn hấp dẫn và quản lý đơn hàng của bạn một cách tiện lợi.
          </p>
          <button class="btn transparent" id="sign-in-btn" style="font-family: 'Baloo 2', sans-serif;">Đăng nhập</button>
        </div>
        <img src="images/form-pic2.png" class="image" alt="" style="margin-bottom: 400px" />
      </div>
    </div>
  </div>

  <!-- JS xử lý hiện/ẩn mật khẩu -->
  <script>
    // Toggle mật khẩu form đăng nhập
    const toggleLoginPassword = document.querySelector('#toggleLoginPassword');
    const loginPassword = document.querySelector('#loginPassword');

    toggleLoginPassword.addEventListener('click', function () {
      const type = loginPassword.getAttribute('type') === 'password' ? 'text' : 'password';
      loginPassword.setAttribute('type', type);
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });

    // Toggle mật khẩu form đăng ký
    const toggleRegisterPassword = document.querySelector('#toggleRegisterPassword');
    const registerPassword = document.querySelector('#registerPassword');

    toggleRegisterPassword.addEventListener('click', function () {
      const type = registerPassword.getAttribute('type') === 'password' ? 'text' : 'password';
      registerPassword.setAttribute('type', type);
      this.classList.toggle('fa-eye');
      this.classList.toggle('fa-eye-slash');
    });
  </script>

  <!-- JS: Chuyển đổi giữa form đăng nhập và đăng ký -->
  <script>
    const sign_in_btn = document.querySelector("#sign-in-btn");
    const sign_up_btn = document.querySelector("#sign-up-btn");
    const container = document.querySelector(".container");

    sign_up_btn.addEventListener("click", () => {
      container.classList.add("sign-up-mode");
    });

    sign_in_btn.addEventListener("click", () => {
      container.classList.remove("sign-up-mode");
    });
  </script>

  <!-- JS: Ẩn thông báo lỗi khi người dùng nhập lại -->
  <script>
    function hideAlertBox() {
      const alertBox = document.getElementById('alertbox');
      if (alertBox) {
        alertBox.style.display = 'none';
      }
    }
  </script>

</body>
</html>
