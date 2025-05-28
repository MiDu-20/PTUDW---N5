<?php
session_start();

// Include database connection file
include 'db_connection.php';

// Check if database connection was successful
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Prepare query to fetch popular items
$sql = "SELECT itemName, image, price FROM menuitem WHERE is_popular = 1";

// Check if query was successful
if ($result = $conn->query($sql)) {
  // Initialize array to store popular items
  $popularItems = [];

  // Fetch and store query results
  while ($row = $result->fetch_assoc()) {
    $popularItems[] = $row;
  }

  // Close query result
  $result->close();
} else {
  // Display error message if query fails
  echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!--Bootstrap CSS-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <!-- Baloo 2 font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Baloo+2:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">
  <!-- Áp dụng Baloo 2 làm font mặc định -->
  <style>
    body {
      font-family: 'Baloo 2', cursive !important;
    }
  </style>
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
  <title>Trang chủ</title>
</head>

<body>
  <?php
  if (isset($_SESSION['userloggedin']) && $_SESSION['userloggedin']) {
    include 'nav-logged.php';
  } else {
    include 'navbar.php';
  }
  ?>

  <div class="main">
    <section>
      <div class="container mt-3">
        <div class="row d-flex justify-content-start align-items-start main-container">
          <div class="col-md-5 col-sm-12 col-lg-5 reveal main-text mb-4 text-align-justify mt-5" data-aos="fade-up">
            <h1>Welcome to <span> Chomp Chomp Fast Food,</span></h1>
            <h4 style="color: gray; font-weight: 450;">"Ngon là Nhai!"</h4>
            <p style="font-size: 24px; text-align: justify;">
            Thiên đường thức ăn nhanh với burger mềm thơm, khoai tây chiên giòn rụm và salad mọng nước, được chế biến từ nguyên liệu tươi ngon. Không gian trẻ trung, sôi động sẽ mang đến cho bạn trải nghiệm ẩm thực nhanh gọn nhưng tràn đầy hứng khởi.
            </p>
            <div class="buttondiv">
              <div>
                <a href="login.php">
                  <button class="button">
                    Bắt đầu đặt mua
                    <svg class="cartIcon" viewBox="0 0 576 512">
                      <path d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"></path>
                    </svg>
                  </button>
                </a>
              </div>
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
                  Khám phá menu
                </a>
              </div>
            </div>
          </div>
          <div class="col-md-7 col-sm-12 col-lg-7 d-flex justify-content-center align-items-start slide-in-right main-image">
            <img src="images/Pizza.png" class="img" style=" width: 85%; height: 80%;">
          </div>
        </div>
        <div class="row">
          <!-- Top picks section -->
          <section>
            <div class="popular reveal" data-aos="fade-up">
              <h1 class="text-center mt-3"><span>TOP TUYỂN CHỌN</span> DÀNH CHO BẠN</h1>
              <P class="text-center" style="font-size: 1.3rem;">Những bữa ăn được lựa chọn kỹ lưỡng và chinh phục mọi thực khách.</P>

              <div id="cardCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000" data-aos="fade-up">
                <div class="carousel-inner">

                  <div id="toast" class="toast">
                    <button class="toast-btn toast-close">&times;</button>
                    <span class="pt-3"><strong>Bạn phải đăng nhập để thêm vào giỏ hàng.</strong></span>
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
                        echo '<a class="button-cart" onclick="addToCart()">Thêm vào giỏ hàng</a>';
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
                  <span class="visually-hidden">Trước</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#cardCarousel" data-bs-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="visually-hidden">Sau</span>
                </button>
              </div>
            </div>
          </section>  
        </div>
      </div>
    </section>
  </div>
  
  <!-- Why Choose Us Section  -->
  <section class="why-choose-us" id="why-choose-us">
    <div class="container">
      <div class="row why-us-content">
        <div class="col-md-12 col-lg-6 col-sm-12 col-xs-12 mt-5 reveal d-flex justify-content-start align-items-start" data-aos="fade-up">
          <img src="images/Why-Us.png" width="100%" height="auto" loading="lazy" alt="delivery boy" class="w-100 delivery-img" data-delivery-boy>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 d-flex flex-column justify-content-center reveal" data-aos="fade-up">
          <h1>TẠI SAO <span>NÊN NHAI Ở CHOMP CHOMP?</span></h1>
          <p class="content">Nhà hàng chúng tôi mang đến dịch vụ giao đồ ăn hàng đầu, sử dụng nguyên liệu tươi ngon và chất lượng thượng hạng.</p>
          <ul class="why-choose-us-list">
            <li data-aos="fade-up">
              <div class="image-wrapper mt-1">
                <img src="icons/delivery-man.png" alt="Giao hàng nhanh">
              </div>
              <div class="feature-content">
                <h4>Giao hàng nhanh</h4>
                <p>Trải nghiệm dịch vụ giao hàng nhanh chóng, đáng tin cậy đến tận cửa nhà bạn.</p>
              </div>
            </li>
            <li data-aos="fade-up">
              <div class="image-wrapper">
                <img src="icons/vegetables.png" alt="Nguyên liệu tươi ngon">
              </div>
              <div class="feature-content">
                <h4>Nguyên liệu tươi ngon</h4>
                <p>Chúng tôi chỉ sử dụng nguyên liệu tươi ngon và chất lượng cao.</p>
              </div>
            </li>
            <li data-aos="fade-up">
              <div class="image-wrapper">
                <img src="icons/waiter (1).png" alt="Phục vụ thân thiện" class="why-us-image">
              </div>
              <div class="feature-content">
                <h4>Phục vụ thân thiện</h4>
                <p>Trải nghiệm dịch vụ ấm áp, tận tâm và luôn niềm nở chào đón bạn.</p>
              </div>
            </li>
            <li data-aos="fade-up">
              <div class="image-wrapper">
                <img src="icons/tasty.png" alt="Exceptional Taste">
              </div>
              <div class="feature-content">
                <h4>Hương vị tuyệt hảo</h4>
                <p>Đắm chìm trong những hương vị thật sự tuyệt hảo.</p>
              </div>
            </li>
          </ul>
        </div>
      </div>
  </section>

  <!-- Menu Section -->
  <section class="menu" id="menu">
            <div class="menu-section">
              <div class="container-fluid">
                <div class="row">
                  <div class="row d-flex justify-content-center align-items-center mt-4 mb-4 font-weight-bold" id="text">
                    <h1><span>MENU</span> CỦA CHÚNG TÔI</h1>
                  </div>
                  <div class="col-lg-3 col-md-6 mb-4">
                    <div class="category-card" style="background-image: url('images/appe-index.avif');" data-aos="fade-up">
                      <div class="card-overlay">
                        <div class="overlay-content">
                          <h3>Khai vị</h3>
                          <p>Khởi đầu bữa ăn với những món khai vị thơm ngon, tạo tiền đề cho hành trình ẩm thực trọn vẹn.</p>
                          <a href="menu.php#appetizer">
                            <button class="explore-btn">Xem ngay</button></a>
                        </div>
                      </div>
                      <div class="card-bottom">
                        <h3>Khai vị</h3>
                        <a href="menu.php#appetizer">
                          <button class="explore-btn">Xem ngay</button></a>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3 col-md-6 mb-4">
                    <div class="category-card" style="background-image: url('images/index-pizza.jpg');" data-aos="fade-up">
                      <div class="card-overlay">
                        <div class="overlay-content">
                          <h3>Pizza</h3>
                          <p>Hãy tận hưởng đa dạng các loại pizza của chúng tôi, mỗi chiếc được chế biến từ nguyên liệu thượng hạng và nướng đến độ hoàn hảo.</p>
                          <a href="menu.php#pizza">
                            <button class="explore-btn">Xem ngay</button></a>
                        </div>
                      </div>
                      <div class="card-bottom">
                        <h3>Pizza</h3>
                        <a href="menu.php#pizza">
                          <button class="explore-btn">Xem ngay</button></a>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3 col-md-6 mb-4">
                    <div class="category-card" style="background-image: url('images/index-burger.avif');" data-aos="fade-up">
                      <div class="card-overlay">
                        <div class="overlay-content">
                          <h3>Burger</h3>
                          <p>Thưởng thức những chiếc burger mọng nước, phủ đầy topping tươi ngon và bùng nổ hương vị ở mỗi miếng cắn.</p>
                          <a href="menu.php#burger">
                            <button class="explore-btn">Xem ngay</button></a>
                        </div>
                      </div>
                      <div class="card-bottom">
                        <h3>Burger</h3>
                        <a href="menu.php#burger">
                          <button class="explore-btn">Xem ngay</button></a>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-3 col-md-6 mb-4">
                    <div class="category-card" style="background-image: url('images/bev-index.jpeg');" data-aos="fade-up">
                      <div class="card-overlay">
                        <div class="overlay-content">
                          <h3>Đồ uống</h3>
                          <p>Giải khát với bộ sưu tập đồ uống tươi mát, lý tưởng cho mọi bữa ăn.</p>
                          <a href="menu.php#beverage">
                            <button class="explore-btn">Xem ngay</button></a>
                        </div>
                      </div>
                      <div class="card-bottom">
                        <h3>Đồ uống</h3>
                        <a href="menu.php#beverage">
                          <button class="explore-btn">Xem ngay</button></a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
  </section>

  <!-- Table Reservation -->
  <section class="table-reservation" id="Reservation">
    <div class="row text-center ms-4" data-aos="fade-up">
      <h1 class="mb-2">ĐẶT <span style="color: #ff9d23;">BÀN</span></h1>
      <h5 class="mb-5">Hãy đặt trải nghiệm ẩm thực cùng chúng tôi để tận hưởng bữa ăn ngon miệng.</h5>
    </div>
    <div class="table ms-4 me-5" data-aos="fade-up">
      <div class="reservation row reveal">
        <div class="reservation-image col-lg-7 col-md-6 col-sm-12" style="background: none !important; padding: 0 !important;">
          <img src="images/table.jpg" alt="Reservation" style="background: none ; width: 100%; height: 100%; padding: 0 !important;" class=" w-100 h-100">
        </div>
        <div class="reservation-section col-lg-5 col-md-6 col-sm-12">
          <h2 style="background-color: #feead4;">Đặt bàn ngay!</h2>
          <form id="reservation-form" action="reservations.php" method="POST">
            <div class="form-row">
              <div class="form-group">
                <label for="name">Họ tên:</label>
                <input type="text" id="name" name="name" required>
              </div>
              <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" placeholder="example@gmail.com" 
                  pattern="[a-zA-Z0-9._%+-]+@gmail\.com" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="phone">Số điện thoại:</label>
                <input type="tel" id="phone" name="contact" required>
              </div>
              <div class="form-group">
                <label for="date">Ngày đặt:</label>
                <input type="date" id="date" name="reservedDate" required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group">
                <label for="reservedTime">Thời gian đặt:</label>
                <input type="time" id="time" name="reservedTime" required>
              </div>
              <div class="form-group">
                <label for="guests">Số thực khách:</label>
                <input type="number" id="guests" name="noOfGuests" required min="1">
              </div>
            </div>
            <button type="submit" value="submit">Đặt bàn ngay</button>
          </form>
        </div>
      </div>
    </div>
  </section>


  <!-- About Us section -->
  <div class="aboutus" id="About-Us" style="background-image: url(images/about-bg.png); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <section class="our-story-section p-5">
      <div class="container ">
        <div class="row" data-aos="fade-up">
          <h1 style="text-align: center;">VỀ <span style="color: #ff9d23;">CHÚNG TÔI</span></h1>
          <h4 style="text-align: center;" class="mb-5">Tạo dựng những bữa ăn khó quên!</h4>
        </div>
        <div class="row d-flex align-items-center">
          <div class="story-text col-lg-6 col-md-6 col-sm-12 my-auto text-lg-start text-center" data-aos="fade-up" data-os-interval="300">
            <p>Tại <strong>Chomp Chomp Fast Food</strong>, chúng tôi đam mê tôn vinh nghệ thuật ẩm thực. Đội ngũ đầu bếp sáng tạo của chúng tôi thổi hồn vào từng món ăn, mang đến một bữa tiệc đầy xúc cảm cho các giác quan. Hãy cùng chúng tôi trải nghiệm hành trình ẩm thực độc đáo, nơi hương vị và niềm vui được nâng niu từng khoảnh khắc.</p>
            <p>Được thành lập vào năm 2021, Chomp Chomp luôn tiên phong trong đổi mới ẩm thực. Cam kết sử dụng nguyên liệu tươi ngon nhất cùng chuyên môn tài năng của đội ngũ đầu bếp đã mang lại cho chúng tôi danh tiếng xuất sắc. Chúng tôi tin rằng thưởng thức không chỉ là ăn; mà là trải nghiệm nghệ thuật ẩm thực.</p>
            <p>Cho dù bạn đang tìm kiếm một bữa tối lãng mạn, buổi sum họp gia đình hay không gian lý tưởng để kỷ niệm những dịp đặc biệt, Grill ’N’ Chill luôn mang đến không gian hoàn hảo cùng ẩm thực tinh tế, giúp mỗi khoảnh khắc trở nên khó quên. Hãy đến và cảm nhận niềm vui của hương vị cùng chúng tôi!</p>
            <div class="text-center mt-4">
              <a href="menu.php" class="about_btn">
                <i class="fa-solid fa-burger"></i>Đặt ngay
              </a>
            </div>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12" data-aos="fade-up">
            <img src="images/Burger.png" class="img-fluid" alt="Tạo nên bữa ăn đáng nhớ" style="width: 100%; height: auto;">
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Review  -->
  <section class="testimonial" id="review">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
          <div class="text-center mb-5" data-aos="fade-up">
            <h1 style="font-family: 'Baloo 2', cursive;"><span>Những khách hàng hài lòng</span> nói gì</h1>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="clients-carousel owl-carousel" data-aos="fade-up">
          <div class="single-box">
            <div class="img-area"><img alt="" class="img-fluid" src="uploads/user-girl.png"></div>
            <div class="content">
              <p>"Thức ăn tươi ngon, hương vị tuyệt hảo. Tôi rất ấn tượng với sự đa dạng trên thực đơn. Đây thực sự là điểm đến tuyệt vời cho những bữa tối gia đình."</p>
              <h4>-Thu Phương</h4>
            </div>
          </div>
          <div class="single-box">
            <div class="img-area"><img alt="" class="img-fluid" src="uploads/user-boy.jpg"></div>
            <div class="content">
              <p>"Quy trình đặt hàng trực tuyến diễn ra suôn sẻ, dễ thao tác. Đồ ăn đến nóng hổi và đúng giờ. Dịch vụ giao hàng rất chuyên nghiệp."</p>
              <h4>-Phương Tuấn</h4>
            </div>
          </div>
          <div class="single-box">
            <div class="img-area"><img alt="" class="img-fluid" src="uploads/default.jpg"></div>
            <div class="content">
              <p>"Thật tuyệt vời! Burger mọng nước và pizza đầy ắp topping. Nhân viên cực kỳ thân thiện, phục vụ nhanh chóng. Đã trở thành địa điểm yêu thích mới!"</p>
              <h4>-Tuấn Anh</h4>
            </div>
          </div>
          <div class="single-box">
            <div class="img-area"><img alt="" class="img-fluid" src="uploads/default.jpg"></div>
            <div class="content">
              <span class="rating-star"><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i><i class="icofont-star"></i></span>
              <p>"Hệ thống đặt hàng trực tuyến thật tuyệt vời. Việc tùy chỉnh đơn hàng dễ dàng và giao hàng luôn nhanh chóng. Đồ ăn đến nóng hổi và thơm ngon mỗi lần."</p>
              <h4>-Anh Phan</h4>
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
          <h4>Liên hệ với chúng tôi</h4>
          <p>2799 Nguyễn Tri Phương, Phường 8, Quận 10</p>
          <p>Email: abc@chompchomp.com</p>
          <p>Phone: +84 12 345 4567</p>
        </div>
        <div class="footer-col">
          <h4>Follow Us</h4>
          <div class="social-icons">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
          </div>
        </div>
        <div class="footer-col">
          <h4>Đăng ký thành viên</h4>
          <form action="#">
            <input type="email" placeholder="Nhập email của bạn" required style="background-color: #f9f9f9; color: #333; margin-top: 12px;">
            <button type="submit">Đăng ký</button>
          </form>
        </div>
      </div>
      <div class="footer-bottom">
        <h4>&copy; 2024 Authored by Asna Assalam. All Rights Reserved.</h4>
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
