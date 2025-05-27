<?php
session_start();

// Bao gồm file kết nối cơ sở dữ liệu
include 'db_connection.php';

// Kiểm tra kết nối cơ sở dữ liệu thành công chưa
if (!$conn) {
  // Hiển thị thông báo lỗi trực tiếp
  die("Connection failed: " . mysqli_connect_error());
}

// Chuẩn bị truy vấn để lấy các món ăn phổ biến
$sql = "SELECT itemName, image, price FROM menuitem WHERE is_popular = 1";

// Kiểm tra truy vấn thành công chưa
if ($result = $conn->query($sql)) {
  // Khởi tạo mảng để lưu trữ các món ăn phổ biến
  $popularItems = [];

  // Lấy và lưu trữ kết quả truy vấn
  while ($row = $result->fetch_assoc()) {
    $popularItems[] = $row;
  }

  // Đóng kết quả truy vấn
  $result->close();
} else {
  // Hiển thị thông báo lỗi nếu truy vấn thất bại
  echo "Error: " . $sql . "<br>" . $conn->error; // Hiển thị thông báo lỗi SQL trực tiếp
}

// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Các thẻ meta và liên kết CSS -->  
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--Bootstrap CSS-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <!--poppins-->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <!--Icon-->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css' />
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css' />
  <link href="https://fonts.googleapis.com/css2?family=Allura&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/assets/owl.carousel.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Chewy Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Chewy&display=swap" rel="stylesheet">
  <!-- AOS -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="index.css">
  <title>Home</title>
</head>

<body>
  <?php
  // Bao gồm thanh điều hướng phù hợp dựa trên trạng thái đăng nhập
  if (isset($_SESSION['userloggedin']) && $_SESSION['userloggedin']) {
    include 'nav-logged.php';
  } else {
    include 'navbar.php';
  }
  ?>

  <!-- Phần chính của trang -->
  <div class="main">
    <section>
      <!-- Banner chính -->
      <div class="container mt-3">
        <div class="row d-flex justify-content-start align-items-start main-container">
          <!-- Nội dung chào mừng -->
          <div class="col-md-5 col-sm-12 col-lg-5 reveal main-text mb-4 text-align-justify mt-5" data-aos="fade-up">
            <h2>Chào mừng đến với <span style="color: #fb4a36;"> Chomp Chomp Fast Food,</span></h2> 
            <h4 style="color: gray; font-weight: 450;">"Nơi hương vị cay nồng hòa quyện cùng cảm giác mát dịu dễ chịu."</h4>
            <p style="font-size: 18px; text-align: justify;">
              Hãy đắm chìm trong bữa tiệc ẩm thực nơi mỗi món ăn 
              đều bùng nổ hương vị. Tại Chomp Chomp Fast Food, chúng tôi tin rằng mỗi 
              bữa ăn đều xứng đáng là một trải nghiệm khó quên. Dù bạn đến để dùng 
              bữa thường ngày hay kỷ niệm dịp đặc biệt, những món ăn đầy sắc màu 
              của chúng tôi chắc chắn sẽ để lại ấn tượng sâu sắc.
            </p>
            <!-- Các nút hành động -->
            <div class="buttondiv">
              <!-- Nút đặt hàng -->
              <div>
                <a href="login.php">
                  <button class="button">
                    Start Order
                    <svg class="cartIcon" viewBox="0 0 576 512">
                      <path d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"></path>
                    </svg>
                  </button>
                </a>
              </div>
              <!-- Nút khám phá menu -->
              <div>
                <a class="button1" href="menu.php">
                  <span class="button__icon-wrapper">
                    <svg width="10" class="button__icon-svg" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 15">
                      <path fill="currentColor" d="M13.376 11.552l-.264-10.44-10.44-.24.024 2.28 6.96-.048L.2 12.56l1.488 1.488 9.432-9.432-.048 6.912 2.304.024z"></path>
                    </svg>
                    <svg class="button__icon-svg button__icon-svg--copy" xmlns="http://www.w3.org/2000/svg" width="10" fill="none" viewBox="0 0 14 15">
                      <path fill="currentColor" d="M13.376 11.552l-.264-10.44-10.44-.24.024 2.28 6.96-.048L.2 12.56l1.488 1.488 9.432-9.432-.048 6.912 2.304.024z"></path>
                    </svg>
                  </span>
                  Explore Menu
                </a>
              </div>
            </div>
          </div>
          <!-- Hình ảnh chính -->
          <div class="col-md-7 col-sm-12 col-lg-7 d-flex justify-content-center align-items-start slide-in-right main-image">
            <img src="images/Pizza.png" class="img" style=" width: 85%; height: 80%;">
          </div>
        </div>
        <div class="row">
      <!-- Phần menu -->
      <section>
        <div class="menu-section">
          <div class="container-fluid">
            <div class="row">
              <h1>THỰC <span>ĐƠN</span></h1>
            </div>
            <!-- Các danh mục menu -->
            <div class="col-lg-3 col-md-6 mb-4">
              <!-- Danh mục Món Khai Vị -->
              <div class="category-card" style="background-image: url('images/appe-index.avif');" data-aos="fade-up">
                <!-- Nội dung overlay -->
                <div class="card-overlay">
                  <div class="overlay-content">
                    <h3>Món Khai Vị</h3>
                    <p>Bắt đầu bữa ăn với các món khai vị thơm ngon, tạo nên khởi đầu tuyệt vời cho trải nghiệm ẩm thực của bạn.</p>
                    <a href="menu.php#appetizer">
                      <button class="explore-btn">Khám Phá Thêm</button></a>
                  </div>
                </div>
                <div class="card-bottom">
                  <h3>Món Khai Vị</h3>
                  <a href="menu.php#appetizer">
                    <button class="explore-btn">Khám Phá Thêm</button></a>
                </div>
              </div>
            </div>
            <!-- Các danh mục khác: Pizza, Burger, Đồ Uống -->
            <div class="col-lg-3 col-md-6 mb-4">
              <div class="category-card" style="background-image: url('images/index-pizza.jpg');" data-aos="fade-up">
                <div class="card-overlay">
                  <div class="overlay-content">
                    <h3>Pizza</h3>
                    <p>Thưởng thức đa dạng các loại pizza được chế biến từ nguyên liệu tươi ngon và nướng hoàn hảo.</p>
                    <a href="menu.php#pizza">
                      <button class="explore-btn">Khám Phá Thêm</button></a>
                  </div>
                </div>
                <div class="card-bottom">
                  <h3>Pizza</h3>
                  <a href="menu.php#pizza">
                    <button class="explore-btn">Khám Phá Thêm</button></a>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
              <div class="category-card" style="background-image: url('images/index-burger.avif');" data-aos="fade-up">
                <div class="card-overlay">
                  <div class="overlay-content">
                    <h3>Burger</h3>
                    <p>Thưởng thức những chiếc burger mọng nước, đầy ắp topping tươi ngon và hương vị đậm đà.</p>
                    <a href="menu.php#burger">
                      <button class="explore-btn">Khám Phá Thêm</button></a>
                  </div>
                </div>
                <div class="card-bottom">
                  <h3>Burger</h3>
                  <a href="menu.php#burger">
                    <button class="explore-btn">Khám Phá Thêm</button></a>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
              <div class="category-card" style="background-image: url('images/bev-index.jpeg');" data-aos="fade-up">
                <div class="card-overlay">
                  <div class="overlay-content">
                    <h3>Đồ Uống</h3>
                    <p>Giải khát với lựa chọn đồ uống tươi mát, hoàn hảo cho mọi bữa ăn.</p>
                    <a href="menu.php#beverage">
                      <button class="explore-btn">Khám Phá Thêm</button></a>
                  </div>
                </div>
                <div class="card-bottom">
                  <h3>Đồ Uống</h3>
                  <a href="menu.php#beverage">
                    <button class="explore-btn">Khám Phá Thêm</button></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>


  <!-- Phần "Why Choose Us" -->
  <section class="why-choose-us" id="why-choose-us">
    <!-- Nội dung phần "Why Choose Us" -->
    <div class="container">
      <div class="row why-us-content">
        <div class="col-md-12 col-lg-6 col-sm-12 col-xs-12 mt-5 reveal d-flex justify-content-start align-items-start" data-aos="fade-up">
          <img src="images/Why-Us.png" width="100%" height="auto" loading="lazy" alt="delivery boy" class="w-100 delivery-img" data-delivery-boy>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 d-flex flex-column justify-content-center reveal" data-aos="fade-up">
          <h1>WHY <span>CHOOSE US?</span></h1>
          <p class="content">Nhà hàng của chúng tôi cung cấp dịch vụ giao đồ ăn tốt nhất với nguyên liệu tươi ngon và chất lượng cao.</p>
          <ul class="why-choose-us-list">
            <li data-aos="fade-up">
              <div class="image-wrapper mt-1">
                <img src="icons/delivery-man.png" alt="Fast Delivery">
              </div>
              <div class="feature-content">
                <h4>Giao Hàng Nhanh Chóng</h4>
                <p>Tận hưởng dịch vụ giao hàng nhanh chóng và đáng tin cậy đến tận cửa nhà bạn.</p>
              </div>
            </li>
            <li data-aos="fade-up">
              <div class="image-wrapper">
                <img src="icons/vegetables.png" alt="Fresh Ingredients">
              </div>
              <div class="feature-content">
                <h4>Nguyên Liệu Tươi Ngon</h4>
                <p>Chúng tôi chỉ sử dụng những nguyên liệu tươi ngon và chất lượng cao nhất.</p>
              </div>
            </li>
            <li data-aos="fade-up">
              <div class="image-wrapper">
                <img src="icons/waiter (1).png" alt="Friendly Service" class="why-us-image">
              </div>
              <div class="feature-content">
                <h4>Dịch Vụ Thân Thiện</h4>
                <p>Trải nghiệm dịch vụ khách hàng thân thiện và nồng hậu.</p>
              </div>
            </li>
            <li data-aos="fade-up">
              <div class="image-wrapper">
                <img src="icons/tasty.png" alt="Exceptional Taste">
              </div>
              <div class="feature-content">
                <h4>Hương Vị Tuyệt Hảo</h4>
                <p>Thưởng thức những hương vị thật sự tuyệt hảo.</p>
              </div>
            </li>
          </ul>
        </div>
      </div>

      <!-- Top picks section -->
      <div class="popular reveal" data-aos="fade-up">
        <h1 class="text-center mt-3">OUR <span>TOP PICKS</span></h1>
        <P class="text-center" style="font-size: 1.3rem;">Những món ăn được chọn lọc kỹ lưỡng, khiến ai cũng mê.</P>

        <div id="cardCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="8000" data-aos="fade-up">
          <div class="carousel-inner">

            <div id="toast" class="toast">
              <button class="toast-btn toast-close">&times;</button>
              <span class="pt-3"><strong>Bạn phải đăng nhập để thêm món ăn vào giỏ hàng..</strong></span>
              <button class="toast-btn toast-ok">Okay</button>
            </div>
            <?php
            $chunkedItems = array_chunk($popularItems, 3); // Group items into chunks of 3
            $isActive = true; // To set the first carousel item as active

            foreach ($chunkedItems as $items) {
              echo '<div class="carousel-item' . ($isActive ? ' active' : '') . '" >';
              echo '<div class="d-flex justify-content-center">';

              foreach ($items as $item) {
                echo '<div class="card" >';
                echo '<img src="uploads/' . $item['image'] . '" class="card-img-top" alt="' . $item['itemName'] . '">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title text-center">' . $item['itemName'] . '</h5>';
                echo '<p class="card-text text-center">Rs ' . $item['price'] . '</p>';
                echo '<a class="button-cart" onclick="addToCart()">Add to Cart</a>';
                echo '</div>';
                echo '</div>';
              }

              echo '</div>';
              echo '</div>';
              $isActive = false; // Only the first item should be active
            }
            ?>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#cardCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#cardCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>
    </div>
  </section>

  <!-- About Us section -->
  <div class="aboutus" id="About-Us" style="background-image: url(images/about-bg.png); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <section class="our-story-section p-5">
      <div class="container ">
        <div class="row" data-aos="fade-up">
          <h1 style="text-align: center;"><span style="color: #fb4a36;">ABOUT </span>US</h1>
          <h4 style="text-align: center;" class="mb-5">Tạo Nên Những Bữa Ăn Khó Quên!</h4>
        </div>
        <div class="story-content row mb-2">
          <div class="story-text col-lg-6 col-md-6 col-sm-12 reveal mt-2" data-aos="fade-up" data-os-interval="300">
            <p>Tại <strong>Chomp Chomp Fast Food</strong>, Chúng tôi đam mê tôn vinh ẩm thực. Các đầu bếp của chúng tôi luôn mang đến sự sáng tạo trong từng món ăn, tạo nên một bữa tiệc thỏa mãn mọi giác quan. Hãy đến và cùng trải nghiệm một hành trình ẩm thực tuyệt vời, nơi hương vị và niềm vui được tôn vinh.</p>
            <p>Được thành lập vào năm [2020], Grill 'N' Chill đã tiên phong trong việc đổi mới ẩm thực. Cam kết sử dụng những nguyên liệu tươi ngon nhất cùng với tay nghề của các đầu bếp đã giúp chúng tôi xây dựng được danh tiếng về chất lượng vượt trội. Chúng tôi tin rằng việc dùng bữa không chỉ là ăn uống, mà là trải nghiệm nghệ thuật ẩm thực.</p>
            <p>Dù bạn đang tìm kiếm một bữa tối lãng mạn, một buổi họp mặt gia đình hay một nơi để kỷ niệm những dịp đặc biệt, Chomp Chomp Fast Food luôn mang đến không gian lý tưởng cùng ẩm thực tinh tế để biến chuyến ghé thăm của bạn trở nên khó quên. Hãy đến và cùng chúng tôi trải nghiệm niềm vui từ hương vị!</p>
            <a href="menu.php" class="about_btn">
              <i class="fa-solid fa-burger"></i>Đặt ngay
            </a>
          </div>
          <div class="story-image col-lg-6 col-md-6 col-sm-12 d-flex justify-content-end align-items-start slide-in-right" data-aos="fade-up">
            <img src="images/Burger.png" alt="Crafting Memorable Meals" style="width: 100%; height: auto;">
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Table Reservation -->
  <section class="table-reservation" id="Reservation">
    <div class="row text-center ms-4" data-aos="fade-up">
      <h1 class="mb-2">ĐẶT <span style="color: #fb4a36;">BÀN</span></h1>
      <h5 class="mb-5">Đặt chỗ ngay để tận hưởng bữa ăn ngon miệng cùng chúng tôi.</h5>
    </div>
    <div class="table ms-4 me-5" data-aos="fade-up">
      <div class="reservation">
  <div class="reservation-image">
    <img src="images/table.jpg" alt="Reservation">
  </div>
       <div class="reservation-section">
    <h2>Đặt ngay!</h2>
    <form id="reservation-form" action="reservations.php" method="POST">
            <div class="form-row">
              <div class="form-group">
                <label for="name">Họ và tên:</label>
                <input type="text" id="name" name="name" required>
              </div>
              <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" placeholder="example@gmail.com" pattern=".+@gmail\.com" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="phone">Số điện thoại:</label>
                <input type="tel" id="phone" name="contact" required>
              </div>
              <div class="form-group">
                <label for="date">Ngày:</label>
                <input type="date" id="date" name="reservedDate" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="reservedTime">Thời gian:</label>
                <input type="time" id="time" name="reservedTime" required>
              </div>
              <div class="form-group">
                <label for="guests">Số lượng người:</label>
                <input type="number" id="guests" name="noOfGuests" required min="1">
              </div>
            </div>
            <button type="submit" value="submit">Đặt bàn ngay</button>
          </form>
        </div>
      </div>
    </div>
  </section>
  <!-- Review  -->
  <section class="testimonial" id="review">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
          <div class="text-center mb-5" data-aos="fade-up">
            <h1>Lắng nghe cảm nhận từ <span>khách hàng!</span></h1>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="clients-carousel owl-carousel" data-aos="fade-up">
          <div class="single-box">
            <div class="img-area"><img alt="" class="img-fluid" src="uploads/user-girl.png"></div>
            <div class="content">
              <p>"Món ăn rất tươi ngon, hương vị thì tuyệt vời. Tôi rất thích sự đa dạng trong thực đơn. Một địa điểm lý tưởng cho những bữa tối cùng gia đình."</p>
              <h4>-Phương An</h4>
            </div>
          </div>
          <div class="single-box">
            <div class="img-area"><img alt="" class="img-fluid" src="uploads/user-boy.jpg"></div>
            <div class="content">
              <p>"Quy trình đặt món trực tuyến rất mượt mà và dễ sử dụng. Món ăn được giao đến còn nóng hổi và đúng giờ. Dịch vụ giao hàng rất chuyên nghiệp."</p>
              <h4>-Mỹ Linh</h4>
            </div>
          </div>
          <div class="single-box">
            <div class="img-area"><img alt="" class="img-fluid" src="uploads/default.jpg"></div>
            <div class="content">
              <p>"Một nơi tuyệt vời! Bánh burger thì mọng nước, còn pizza thì ngập tràn topping. Nhân viên cực kỳ thân thiện và phục vụ nhanh chóng. Đây sẽ là địa điểm yêu thích mới của tôi!"</p>
              <h4>-Khánh Huyền</h4>
            </div>
          </div>
          <div class="single-box">
            <div class="img-area"><img alt="" class="img-fluid" src="uploads/default.jpg"></div>
            <div class="content">
              <span class="rating-star"><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i></span>
              <p>"Hệ thống đặt món trực tuyến thật tuyệt vời. Tôi có thể dễ dàng tùy chỉnh đơn hàng của mình, và món ăn luôn được giao nhanh chóng. Mỗi lần nhận hàng, đồ ăn đều nóng hổi và thơm ngon."</p>
              <h4>-Minh Thư</h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- footer -->
  <footer>
    <div class="footer-container">
      <div class="footer-row">
        <div class="footer-col" id="contact">
          <h4>Thông tin liên lạc</h4>
          <p>279 Nguyễn Tri Phương, P.8, Q.10</p>
          <p>Email: ChompChompFastFood@gmail.com</p>
          <p>Phone: +0353 551 657</p>
        </div>
        <div class="footer-col">
          <h4>Hãy theo dõi chúng tôi</h4>
          <div class="social-icons">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
          </div>
        </div>
        <div class="footer-col">
          <h4>Đăng ký</h4>
          <form action="#">
            <input type="email" placeholder="Your email address" required style="background-color: #f9f9f9; color: #333; margin-top: 12px;">
            <button type="submit">Subscribe</button>
          </form>
        </div>
      </div>
      <div class="footer-bottom">
        <h4>&copy; Chomp Chomp Fast Food </h4>
      </div>
    </div>
  </footer>

    <!-- Kiểm tra ngày đặt bàn -->
  <script>
  document.getElementById('reservation-form').addEventListener('submit', function(event) {
    const now = new Date();

    const inputDate = document.getElementById('date').value;
    const inputTime = document.getElementById('time').value;

    if (!inputDate || !inputTime) return;

    const selectedDateTime = new Date(`${inputDate}T${inputTime}`);

    // 1. Không cho đặt trong quá khứ
    if (selectedDateTime < now) {
      alert("Không thể đặt bàn trong quá khứ.");
      event.preventDefault();
      return;
    }

    // 2. Giới hạn giờ đặt trong khoảng mở cửa (10:00 - 21:00)
    const hour = parseInt(inputTime.split(":")[0]);
    if (hour < 9 || hour >= 21) {
      alert("Chỉ nhận đặt bàn từ 9:00 đến 21:00.");
      event.preventDefault();
      return;
    }

    // 3. Nếu đặt trong hôm nay thì giờ phải lớn hơn giờ hiện tại
    const today = now.toISOString().slice(0, 10); // YYYY-MM-DD
    if (inputDate === today && inputTime <= now.toTimeString().slice(0, 5)) {
      alert("Giờ đặt phải lớn hơn thời điểm hiện tại.");
      event.preventDefault();
      return;
    }
  });
</script>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js">
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js">
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js">
  </script>
  <!-- AOS -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init();
  </script>
  <script>
    $(document).ready(function() {
      console.log('Page is ready. Calling load_cart_item_number.');
      load_cart_item_number();

      function load_cart_item_number() {
        $.ajax({
          url: 'action.php',
          method: 'get',
          data: {
            cartItem: "cart_item"
          },
          success: function(response) {
            $("#cart-item").html(response);
          }
        });
      }
    });
  </script>
  <script>
    $('.clients-carousel').owlCarousel({
      loop: true,
      nav: false,
      autoplay: true,
      autoplayTimeout: 5000,
      animateOut: 'fadeOut',
      animateIn: 'fadeIn',
      smartSpeed: 450,
      margin: 30,
      responsive: {
        0: {
          items: 1
        },
        768: {
          items: 2
        },
        991: {
          items: 2
        },
        1200: {
          items: 2
        },
        1920: {
          items: 2
        }
      }
    });
  </script>
  <script>
    function addToCart() {
      var userLoggedIn = <?php echo isset($_SESSION['userloggedin']) ? 'true' : 'false'; ?>;

      if (!userLoggedIn) {
        showToast();
      } else {
        // Add to cart logic goes here
      }
    }

    function showToast() {
      var toast = document.getElementById("toast");
      toast.className = "toast show";

      // Handle "Okay" button click
      document.querySelector('.toast-ok').onclick = function() {
        window.location.href = 'login.php'; // Redirect to login page
      };

      // Handle "Close (X)" button click
      document.querySelector('.toast-close').onclick = function() {
        toast.className = toast.className.replace("show", "hide");
      };
    }
  </script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <!-- Bootstrap JS and dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const elements = document.querySelectorAll('.animate-on-scroll');
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('reveal');
          }
        });
      }, {
        threshold: 0.1
      });

      elements.forEach(element => {
        observer.observe(element);
      });
    });
  </script>

          


</body>
</html>
