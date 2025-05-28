<?php
// Bắt đầu phiên làm việc, dùng để lưu trữ thông tin người dùng giữa các trang
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa. Nếu chưa, chuyển hướng đến trang đăng nhập
if (!isset($_SESSION['adminloggedin'])) {
    header("Location: ../login.php");  // Đưa người dùng quay lại trang đăng nhập
    exit();
}

// Kết nối đến cơ sở dữ liệu để lấy dữ liệu thực đơn
include 'db_connection.php';
?>
<?php
// Giao diện sidebar (thanh điều hướng bên trái)
include 'sidebar.php';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <!-- Thiết lập charset và viewport để hỗ trợ các thiết bị di động -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Menu Admin</title>

    <!-- Cấu hình font chữ Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="admin_menu.css">
</head>

<body>
    <!-- Sidebar bên trái chứa các mục quản lý -->
    <div class="sidebar">
        <!-- Nút đóng sidebar -->
        <button class="close-sidebar" id="closeSidebar">&times;</button>

        <!-- Phần thông tin cá nhân của admin -->
        <div class="profile-section">
            <img src="../uploads/<?php echo htmlspecialchars($admin_info['profile_image']); ?>" alt="Ảnh đại diện">
            <div class="info">
                <h3>Chào mừng bạn quay lại!</h3>
                <p><?php echo htmlspecialchars($admin_info['firstName']) . ' ' . htmlspecialchars($admin_info['lastName']); ?></p>
            </div>
        </div>

        <!-- Menu điều hướng bên trái -->
        <ul>
            <li><a href="index.php"><i class="fas fa-chart-line"></i>Tổng quan</a></li>
            <li><a href="admin_menu.php" class="active"><i class="fas fa-utensils"></i>Quản lý thực đơn</a></li>
            <li><a href="admin_orders.php"><i class="fas fa-shopping-cart"></i>Đơn hàng</a></li>
            <li><a href="reservations.php"><i class="fas fa-calendar-alt"></i>Đặt bàn</a></li>
            <li><a href="users.php"><i class="fas fa-users"></i>Người dùng</a></li>
            <li><a href="reviews.php"><i class="fas fa-star"></i>Đánh giá</a></li>
            <li><a href="staffs.php"><i class="fas fa-users"></i>Nhân viên</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i>Hồ sơ</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i>Đăng xuất</a></li>
        </ul>
    </div>

    <!-- Nội dung chính của trang -->
    <div class="content">
        <div class="header">
            <button id="toggleSidebar" class="toggle-button">
                <i class="fas fa-bars"></i>
            </button>
            <h2><i class="fas fa-utensils"></i> Quản lý Menu</h2>
        </div>
        <div class="modal-row">
            <!-- Các nút thao tác chính -->
            <div>
                <button onclick="openModal()"><i class="fas fa-plus"></i> &nbsp;Thêm Danh Mục Mới</button>
                <button onclick="openItemModal()"> <i class="fas fa-plus"></i> &nbsp;Thêm Món Mới</button>
                <button onclick="openViewCategoryModal()"> <i class="fas fa-eye"></i> &nbsp;Xem Danh Mục</button>
            </div>
            <!-- Combo box lọc theo danh mục -->
            <div class="search-bar ">
                <select id="categoryFilter" onchange="filterCategories()">
                    <option value="">Tất cả danh mục</option>
                    <?php
                    $sql = "SELECT catName FROM menucategory";  // Lấy danh sách các danh mục từ bảng menucategory
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='{$row['catName']}'>{$row['catName']}</option>";  // Hiển thị danh mục trong thẻ <option>
                    }
                    ?>
                </select>

            </div>

        </div>
        <!-- Bảng hiển thị danh sách món ăn -->
        <table id="menuTable">
            <thead>
                <tr>
                    <th>Tên Món</th>
                    <th>Hình Ảnh</th>
                    <th>Mô Tả</th>
                    <th>Giá</th>
                    <th>Danh Mục</th>
                    <th>Trạng Thái</th>
                    <th>Phổ Biến</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Truy vấn lấy thông tin món ăn từ cơ sở dữ liệu
                $sql = "SELECT * FROM menuitem";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    // Lặp qua kết quả để hiển thị món ăn
                    while ($row = mysqli_fetch_assoc($result)) {
                        $isPopularChecked = $row['is_popular'] ? 'checked' : '';  // Kiểm tra nếu món ăn này là phổ biến
                        echo "<tr data-category='{$row['catName']}'>
                <td>{$row['itemName']}</td>
                <td><img src='../uploads/{$row['image']}' alt='{$row['itemName']}' width='50'></td>
                <td>{$row['description']}</td>
                <td>Rs {$row['price']}</td>
                <td>{$row['catName']}</td>
                <td>{$row['status']}</td>
                <td>
                    <div class='toggler'>
                        <input id='toggler-{$row['itemId']}' name='toggler-{$row['itemId']}' type='checkbox' value='1' $isPopularChecked onchange='togglePopular({$row['itemId']}, this)' />
                        <label for='toggler-{$row['itemId']}'>
                            <svg class='toggler-on' version='1.1' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 130.2 130.2'>
                                <polyline class='path check' points='100.2,40.2 51.5,88.8 29.8,67.5'></polyline>
                            </svg>
                            <svg class='toggler-off' version='1.1' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 130.2 130.2'>
                                <line class='path line' x1='34.4' y1='34.4' x2='95.8' y2='95.8'></line>
                                <line class='path line' x1='95.8' y1='34.4' x2='34.4' y2='95.8'></line>
                            </svg>
                        </label>
                    </div>
                </td>
                <td>
                    <button id='editbtn' onclick='openEditItemModal(this)' data-itemid='{$row['itemId']}' data-itemname='{$row['itemName']}' data-description='{$row['description']}' data-price='{$row['price']}' data-image='{$row['image']}' data-category='{$row['catName']}' data-status='{$row['status']}'><i class='fas fa-edit'></i></button>  
                    <button id='deletebtn'  onclick=\"deleteItem('" . $row["itemId"] . "')\"><i class='fas fa-trash'></i></button>
                </td>
              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' style='text-align: center;'>Chưa có món ăn nào</td></tr>";
                }
                ?>

            </tbody>
        </table>

    </div>

    <!-- Modal thêm danh mục -->
    <div class="modal" id="categoryModal">
        <div class="modal-overlay"></div>
        <div class="modal-container">
            <form class="form" method="POST" action="add_category.php">
                <div class="modal-header">
                    <h2>Thêm Danh Mục Mới</h2>
                    <span class="close-icon" onclick="closeModal()">&times;</span>
                </div>
                <div class="modal-content">
                    <div class="input-group">
                        <input type="text" name="catName" id="catName" class="input" required>
                        <label for="catName" class="label">Tên Danh Mục</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button" onclick="closeModal()">Hủy</button>
                    <button type="submit" class="button">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal thêm món ăn mới -->
    <div class="modal" id="itemModal">
        <div class="modal-overlay"></div>
        <div class="modal-container">
            <form class="form" method="POST" action="add_item.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h2>Thêm Món Mới</h2>
                    <span class="close-icon" onclick="closeItemModal()">&times;</span>
                </div>
                <div class="modal-content">
                    <div class="input-group">
                        <input type="text" name="itemName" id="itemName" class="input" required>
                        <label for="itemName" class="label">Tên Món</label>
                    </div>
                    <div class="input-group">
                        <input type="text" name="description" id="description" class="input" required>
                        <label for="description" class="label">Mô Tả</label>
                    </div>
                    <div class="input-group">
                        <select name="status" id="status" class="input" required>
                            <option value="">Trạng Thái</option>
                            <option value="Available">Còn Hàng</option>
                            <option value="Unavailable">Hết Hàng</option>
                        </select>
                        <label for="status" class="label">Trạng Thái</label>
                    </div>
                    <div class="input-group">
                        <input type="number" name="price" id="price" class="input" required>
                        <label for="price" class="label">Giá</label>
                    </div>
                    <div class="input-group">
                        <select name="catName" id="catName" class="input" required>
                            <option value="">Chọn Danh Mục</option>
                            <?php
                            $sql = "SELECT catName FROM menucategory";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='{$row['catName']}'>{$row['catName']}</option>";
                            }
                            ?>
                        </select>
                        <label for="catName" class="label">Danh Mục</label>
                    </div>
                    <div class="input-group">
                        <input type="file" name="image" id="image" class="input" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button" onclick="closeItemModal()">Hủy</button>
                    <button type="submit" class="button">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('categoryModal').classList.add('open');
        }
        function closeModal() {
            document.getElementById('categoryModal').classList.remove('open');
        }
        function openItemModal() {
            document.getElementById('itemModal').classList.add('open');
        }
        function closeItemModal() {
            document.getElementById('itemModal').classList.remove('open');
        }

        // Hàm xóa món ăn
        function deleteItem(itemId) {
            if (confirm("Bạn có chắc chắn muốn xóa món này không?")) {
                window.location.href = `delete_item.php?id=${itemId}`;
            }
        }

        function togglePopular(itemId, checkbox) {
            var isPopular = checkbox.checked ? 1 : 0;

            // Tạo AJAX request để cập nhật trạng thái phổ biến
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "update_popular_status.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        console.log("Trạng thái đã được cập nhật.");
                    } else {
                        console.error("Lỗi khi cập nhật trạng thái.");
                    }
                }
            };

            xhr.send("itemId=" + itemId + "&is_popular=" + isPopular);
        }
    </script>

    <?php include_once('footer.html'); ?>

</body>
</html>
