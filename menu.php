<?php
session_start();
include 'db_connection.php';

$categoryQuery = 'SELECT DISTINCT catName FROM menuitem';
$categoryResult = $conn->query($categoryQuery);
$categories = [];
while ($row = $categoryResult->fetch_assoc()) {
    $categories[] = $row['catName'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css' />
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css' />
  <link href="https://fonts.googleapis.com/css2?family=Baloo+2&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="menu.css" />
  <title>Thực đơn</title>
</head>
<body>
<?php include isset($_SESSION['userloggedin']) && $_SESSION['userloggedin'] ? 'nav-logged.php' : 'navbar.php'; ?>

<div class="heading">
  <div class="row heading-title">Thực đơn</div>
  <div class="row heading-description">Thưởng thức món ăn ngon</div>
</div>

<?php foreach ($categories as $category): ?>
<section id="<?= strtolower($category) ?>">
  <div id="message"></div>
  <div class="container-fluid">
    <h1 class="mt-1"> <?= $category ?> </h1>
    <div class="row">
      <?php
      $stmt = $conn->prepare('SELECT * FROM menuitem WHERE catName = ?');
      $stmt->bind_param('s', $category);
      $stmt->execute();
      $result = $stmt->get_result();
      while ($row = $result->fetch_assoc()):
        $buttonClass = $row['status'] == 'Unavailable' ? 'disabled-button' : '';
      ?>
        <div class="col-md-6 col-lg-3 col-sm-12 menu-item col-xs-12 d-flex">
          <div class="w-100 h-100 d-flex">
            <div class="card w-100">
              <img src="uploads/<?= $row['image'] ?>" alt="image" class="card-img-top">
              <div class="card-body modern-card">
                <h5 class="card-title text-center mt-3"> <?= $row['itemName'] ?> </h5>
                <p class="description text-center"> <?= $row['description'] ?> </p>

                <?php if ($row['status'] == 'Unavailable'): ?>
                  <div class="card-status">Hết hàng</div>
                <?php endif; ?>

                <form action="" class="form-submit">
                  <input type="hidden" class="pid" value='<?= $row['id'] ?>'>
                  <input type="hidden" class="pname" value="<?= $row['itemName'] ?>">
                  <input type="hidden" class="pprice" value="<?= $row['price'] ?>">
                  <input type="hidden" class="pimage" value="<?= $row['image'] ?>">
                  <input type="hidden" class="pcode" value="<?= $row['catName'] ?>">

                  <div class="button-container mt-2">
                    <p><?= number_format($row['price']) ?> VNĐ</p>
                    <button class="addItemBtn <?= $buttonClass ?>" type="button">
                      <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</section>
<?php endforeach; ?>

<div id="toast" class="toast">
  <button class="toast-btn toast-close">&times;</button>
  <span><strong>Bạn cần đăng nhập để thêm món vào giỏ hàng.</strong></span><br>
  <button class="toast-btn toast-ok">Đồng ý</button>
</div>

<?php include_once('footer.html'); ?>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js'></script>
<script>
$(document).ready(function() {
  function userIsLoggedIn() {
    return <?php echo isset($_SESSION['userloggedin']) && $_SESSION['userloggedin'] === true ? 'true' : 'false'; ?>;
  }

  function showToast() {
    $('#toast').addClass('show');
    setTimeout(function() { $('#toast').removeClass('show'); }, 5000);
  }

  function getUserEmail() {
    return "<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>";
  }

  $(".addItemBtn").click(function(e) {
    e.preventDefault();
    if (!userIsLoggedIn()) { showToast(); return; }
    if ($(this).hasClass('disabled-button')) return;
    var email = getUserEmail();
    var $form = $(this).closest(".form-submit");
    var pid = $form.find(".pid").val();
    var pname = $form.find(".pname").val();
    var pprice = $form.find(".pprice").val();
    var pimage = $form.find(".pimage").val();
    var pcode = $form.find(".pcode").val();
    $.ajax({
      url: 'action.php', method: 'post',
      data: { pid, pname, pprice, pqty: 1, pimage, pcode, email },
      success: function(response) {
        $("#message").html(response);
        window.scrollTo(0, 0);
        load_cart_item_number();
      }
    });
  });

  $('.toast-close').click(() => $('#toast').removeClass('show'));
  $('.toast-ok').click(() => window.location.href = 'login.php');

  function load_cart_item_number() {
    $.ajax({
      url: 'action.php', method: 'get',
      data: { cartItem: "cart_item" },
      success: response => $("#cart-item").html(response)
    });
  }
  load_cart_item_number();
});
$(document).ready(function () {
    function load_cart_item_number() {
      $.ajax({
        url: 'action.php', method: 'get',
        data: { cartItem: "cart_item" },
        success: response => $("#cart-item").html(response)
      });
    }
    load_cart_item_number();

    // Hiệu ứng xuất hiện từng món
    const items = document.querySelectorAll(".menu-item");

    const observer = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add("visible");
          observer.unobserve(entry.target); // Chỉ hiệu ứng 1 lần
        }
      });
    }, {
      threshold: 0.1
    });

    items.forEach(item => {
      observer.observe(item);
    });
  });
</script>
</body>
</html>
    