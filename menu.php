<?php
// -- Khởi động phiên làm việc để lưu trữ thông tin người dùng giữa các trang
session_start();

// -- Import file kết nối cơ sở dữ liệu để thực hiện truy vấn MySQL
include 'db_connection.php';

// -- Tạo câu truy vấn SQL để lấy danh sách tất cả các loại món ăn (catName) không trùng nhau trong bảng menuitem
$categoryQuery = 'SELECT DISTINCT catName FROM menuitem';

// -- Thực thi truy vấn SQL và lưu kết quả vào biến $categoryResult
$categoryResult = $conn->query($categoryQuery);

// -- Khởi tạo mảng rỗng để lưu trữ tên các danh mục món ăn
$categories = [];

// -- Lặp qua từng dòng kết quả trả về từ truy vấn
// -- Mỗi dòng là một danh mục món ăn, được thêm vào mảng $categories
while ($row = $categoryResult->fetch_assoc()) {
    $categories[] = $row['catName'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Import các thư viện CSS cho Bootstrap và Font Awesome để thiết kế giao diện -->
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css' />
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css' />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
    
    <!-- Import font chữ Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Import file CSS tùy chỉnh -->
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="menu.css" />
    <title>Thực đơn</title>

    <!-- Một số CSS tùy chỉnh trực tiếp -->
    <style>
        .disabled-button {
            background-color: gray;
            color: white;
            cursor: not-allowed;
            pointer-events: none;
        }
        .disabled-button i {
            color: white;
        }
        section:nth-child(odd) {
            background-color: #ffe4c2;
        }
        section:nth-child(even) {
            background-color: #feead4;
        }
    </style>
</head>
<body>
<?php
// -- Kiểm tra người dùng đã đăng nhập chưa để hiển thị thanh điều hướng phù hợp
if (isset($_SESSION['userloggedin']) && $_SESSION['userloggedin']) {
    include 'nav-logged.php'; // menu sau khi đăng nhập
} else {
    include 'navbar.php'; // menu cho khách
}
?>
<!-- Tiêu đề và mô tả thực đơn -->
<div class="heading">
    <div class="row heading-title">Thực đơn</div>
    <div class="row heading-description">Thưởng thức món ăn ngon</div>
</div>

<?php foreach ($categories as $category): ?>
    <!-- Mỗi section ứng với một danh mục món ăn -->
    <section id="<?= strtolower($category) ?>">
        <div id="message"></div>
        <div class="container-fluid">
            <!-- Hiển thị tên danh mục -->
            <h1 class="mt-1"> <?= $category ?> </h1>
            <div class="row">
                <?php
                // -- Truy vấn danh sách món ăn thuộc danh mục hiện tại
                $stmt = $conn->prepare('SELECT * FROM menuitem WHERE catName = ?');
                $stmt->bind_param('s', $category);
                $stmt->execute();
                $result = $stmt->get_result();

                // -- Lặp qua từng món ăn và hiển thị thông tin chi tiết
                while ($row = $result->fetch_assoc()) :
                    $buttonClass = $row['status'] == 'Unavailable' ? 'disabled-button' : '';
                ?>
                    <div class="col-md-6 col-lg-3 col-sm-12 menu-item col-xs-12">
                        <div class="mt-4" style="background-color: #fdd9c9; border-radius: 5px;">
                            <!-- Hiển thị ảnh món ăn -->
                            <img src="uploads/<?= $row['image'] ?>" alt="image" class="card-img-top" height="250">
                            <div class="card-body">
                                <!-- Tên món ăn -->
                                <h4 class="card-title text-center mt-3"><?= $row['itemName'] ?></h4>
                                <!-- Mô tả món ăn -->
                                <p class="card-title text-center description"><?= $row['description'] ?></p>

                                <!-- Nếu món ăn không khả dụng thì hiển thị trạng thái màu đỏ -->
                                <?php if ($row['status'] == 'Unavailable') : ?>
                                    <p class="card-status" style="color: red; text-align: center; font-size: 1.3em;">
                                        <?= $row['status']; ?>
                                    </p>
                                <?php endif; ?>

                                <div style="text-align: center;">
                                    <!-- Form để chứa thông tin món ăn khi bấm nút thêm vào giỏ -->
                                    <form action="" class="form-submit">
                                        <input type="hidden" class="pid" value='<?= $row['id'] ?>'>
                                        <input type="hidden" class="pname" value="<?= $row['itemName'] ?>">
                                        <input type="hidden" class="pprice" value="<?= $row['price'] ?>">
                                        <input type="hidden" class="pimage" value="<?= $row['image'] ?>">
                                        <input type="hidden" class="pcode" value="<?= $row['catName'] ?>">

                                        <div class="button-container mt-2">
                                            <p class="card-text text-center "><?= number_format($row['price']) ?> VNĐ</p>
                                            <button class="addItemBtn <?= $buttonClass ?>" type="button">
                                                <i class="fas fa-cart-plus"></i> &nbsp;&nbsp;Thêm vào giỏ hàng
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

<!-- Thông báo dạng Toast nếu người dùng chưa đăng nhập -->
<div id="toast" class="toast" style="background: rgba(255, 182, 182, 0.9); border: 1px solid rgba(255, 182, 182, 1); font-size: 16px;">
    <button class="toast-btn toast-close">&times;</button>
    <span class="pt-3"><strong>Bạn cần đăng nhập để thêm món vào giỏ hàng.</strong></span><br>
    <button class="toast-btn toast-ok">Đồng ý</button>
</div>

<!-- Chân trang -->
<?php include_once('footer.html'); ?>

<!-- Import jQuery và Bootstrap JS -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js'></script>

<script type="text/javascript">
$(document).ready(function() {
    // -- Hàm kiểm tra trạng thái đăng nhập
    function userIsLoggedIn() {
        return <?php echo isset($_SESSION['userloggedin']) && $_SESSION['userloggedin'] === true ? 'true' : 'false'; ?>;
    }

    // -- Hiển thị thông báo nếu chưa đăng nhập
    function showToast() {
        var toast = $('#toast');
        toast.addClass('show');
        setTimeout(function() {
            toast.removeClass('show');
        }, 5000);
    }

    // -- Lấy email người dùng từ session
    function getUserEmail() {
        return "<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>";
    }

    // -- Xử lý khi người dùng nhấn nút “Thêm vào giỏ hàng”
    $(".addItemBtn").click(function(e) {
        e.preventDefault();

        if (!userIsLoggedIn()) {
            showToast();
            return;
        }

        // Nếu nút đang bị disabled thì không làm gì
        if ($(this).hasClass('disabled-button')) {
            return;
        }

        var email = getUserEmail();
        var $form = $(this).closest(".form-submit");
        var pid = $form.find(".pid").val();
        var pname = $form.find(".pname").val();
        var pprice = $form.find(".pprice").val();
        var pimage = $form.find(".pimage").val();
        var pcode = $form.find(".pcode").val();
        var pqty = 1;

        // Gửi dữ liệu món ăn qua AJAX tới file action.php
        $.ajax({
            url: 'action.php',
            method: 'post',
            data: {
                pid: pid,
                pname: pname,
                pprice: pprice,
                pqty: pqty,
                pimage: pimage,
                pcode: pcode,
                email: email
            },
            success: function(response) {
                $("#message").html(response);
                window.scrollTo(0, 0);
                load_cart_item_number();
            }
        });
    });

    // -- Đóng thông báo khi nhấn nút X
    $('.toast-close').click(function() {
        $('#toast').removeClass('show');
    });

    // -- Chuyển hướng về trang đăng nhập
    $('.toast-ok').click(function() {
        window.location.href = 'login.php';
    });

    // -- Tải lại số lượng món trong giỏ hàng (dùng cho biểu tượng giỏ)
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
</body>
</html>
