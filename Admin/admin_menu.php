<?php
session_start();
if (!isset($_SESSION['adminloggedin'])) {
    header("Location: ../login.php");
    exit();
}
include 'db_connection.php';
include 'sidebar.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Quản lý Menu Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="sidebar.css" />
    <link rel="stylesheet" href="admin_menu.css" />
</head>

<body>
<div class="sidebar">
    <button class="close-sidebar" id="closeSidebar">&times;</button>
    <div class="profile-section">
        <img src="../uploads/<?php echo htmlspecialchars($admin_info['profile_image']); ?>" alt="Ảnh đại diện" />
        <div class="info">
            <h3>Chào mừng bạn quay lại!</h3>
            <p><?php echo htmlspecialchars($admin_info['firstName']) . ' ' . htmlspecialchars($admin_info['lastName']); ?></p>
        </div>
    </div>
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

<div class="content">
    <div class="header">
        <button id="toggleSidebar" class="toggle-button"><i class="fas fa-bars"></i></button>
        <h2><i class="fas fa-utensils"></i> Quản lý Menu</h2>
    </div>

    <div class="modal-row">
        <div>
            <button onclick="openCategoryModal()"><i class="fas fa-plus"></i> &nbsp;Thêm Danh Mục Mới</button>
            <button onclick="openAddItemModal()"><i class="fas fa-plus"></i> &nbsp;Thêm Món Mới</button>
            <button id="viewCategoryBtn"><i class="fas fa-eye"></i> &nbsp;Xem Danh Mục</button>
        </div>
        <div class="search-bar">
            <select id="categoryFilter" onchange="filterCategories()">
                <option value="">Tất cả danh mục</option>
                <?php
                $sql = "SELECT catName FROM menucategory";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . htmlspecialchars($row['catName']) . "'>" . htmlspecialchars($row['catName']) . "</option>";
                }
                ?>
            </select>
        </div>
    </div>

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
        $sql = "SELECT * FROM menuitem";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $isPopularChecked = $row['is_popular'] ? 'checked' : '';
                echo "<tr data-category='" . htmlspecialchars($row['catName']) . "'>
                    <td>" . htmlspecialchars($row['itemName']) . "</td>
                    <td><img src='../uploads/" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['itemName']) . "' width='50'></td>
                    <td>" . htmlspecialchars($row['description']) . "</td>
                    <td>Rs " . htmlspecialchars($row['price']) . "</td>
                    <td>" . htmlspecialchars($row['catName']) . "</td>
                    <td>" . htmlspecialchars($row['status']) . "</td>
                    <td>
                        <div class='toggler'>
                            <input id='toggler-" . $row['itemId'] . "' name='toggler-" . $row['itemId'] . "' type='checkbox' value='1' $isPopularChecked onchange='togglePopular(" . $row['itemId'] . ", this)' />
                            <label for='toggler-" . $row['itemId'] . "'>
                                <svg class='toggler-on' viewBox='0 0 130.2 130.2'><polyline class='path check' points='100.2,40.2 51.5,88.8 29.8,67.5'></polyline></svg>
                                <svg class='toggler-off' viewBox='0 0 130.2 130.2'><line class='path line' x1='34.4' y1='34.4' x2='95.8' y2='95.8'></line><line class='path line' x1='95.8' y1='34.4' x2='34.4' y2='95.8'></line></svg>
                            </label>
                        </div>
                    </td>
                    <td>
                        <button id='editbtn' onclick='openEditItemModal(this)' 
                            data-itemid='" . htmlspecialchars($row['itemId']) . "'
                            data-itemname='" . htmlspecialchars($row['itemName']) . "'
                            data-description='" . htmlspecialchars($row['description']) . "'
                            data-price='" . htmlspecialchars($row['price']) . "'
                            data-image='" . htmlspecialchars($row['image']) . "'
                            data-category='" . htmlspecialchars($row['catName']) . "'
                            data-status='" . htmlspecialchars($row['status']) . "'><i class='fas fa-edit'></i></button>
                        <button id='deletebtn' onclick=\"deleteItem('" . htmlspecialchars($row['itemId']) . "')\"><i class='fas fa-trash'></i></button>
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

<!-- Modal Thêm Danh Mục -->
<div class="modal" id="categoryModal">
    <div class="modal-overlay" onclick="closeCategoryModal()"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h2>Thêm Danh Mục Mới</h2>
            <span class="close-icon" onclick="closeCategoryModal()">&times;</span>
        </div>
        <form action="add_category.php" method="POST" class="form">
            <div class="modal-content">
                <div class="input-group">
                    <input type="text" name="catName" class="input" required />
                    <label class="label">Tên danh mục</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit">Thêm</button>
                <button type="button" onclick="closeCategoryModal()">Hủy</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Thêm Món Mới -->
<div class="modal" id="addItemModal">
    <div class="modal-overlay" onclick="closeAddItemModal()"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h2>Thêm Món Ăn Mới</h2>
            <span class="close-icon" onclick="closeAddItemModal()">&times;</span>
        </div>
        <form action="add_item.php" method="POST" enctype="multipart/form-data" class="form" id="addItemForm">
            <div class="modal-content">
                <div class="input-group">
                    <input type="text" name="itemName" class="input" required />
                    <label class="label">Tên món</label>
                </div>
                <div class="input-group">
                    <input type="number" name="price" class="input" required />
                    <label class="label">Giá</label>
                </div>
                <div class="input-group">
                    <textarea name="description" class="input" required></textarea>
                    <label class="label">Mô tả</label>
                </div>
                <div class="input-group">
                    <select name="catName" class="input" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php
                        $sql = "SELECT catName FROM menucategory";
                        $result = mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . htmlspecialchars($row['catName']) . "'>" . htmlspecialchars($row['catName']) . "</option>";
                        }
                        ?>
                    </select>
                    <label class="label">Danh mục</label>
                </div>
                <div class="input-group">
                    <input type="file" name="image" class="input" accept="image/*" required />
                    <label class="label"></label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit">Thêm</button>
                <button type="button" onclick="closeAddItemModal()">Hủy</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Chỉnh sửa món ăn -->
<div class="modal" id="editItemModal">
    <div class="modal-overlay" onclick="closeEditItemModal()"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h2>Chỉnh sửa thông tin món ăn</h2>
            <span class="close-icon" onclick="closeEditItemModal()">&times;</span>
        </div>
        <form action="update_item.php" method="POST" enctype="multipart/form-data" class="form" id="editItemForm">
            <input type="hidden" name="itemId" value="" />
            <div class="modal-content">
                <div class="input-group">
                    <input type="text" name="itemName" class="input" required />
                    <label class="label">Tên món</label>
                </div>
                <div class="input-group">
                    <input type="number" name="price" class="input" required />
                    <label class="label">Giá</label>
                </div>
                <div class="input-group">
                    <textarea name="description" class="input" required></textarea>
                    <label class="label">Mô tả</label>
                </div>
                <div class="input-group">
                    <select name="catName" class="input" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php
                        $sql = "SELECT catName FROM menucategory";
                        $result = mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . htmlspecialchars($row['catName']) . "'>" . htmlspecialchars($row['catName']) . "</option>";
                        }
                        ?>
                    </select>
                    <label class="label">Danh mục</label>
                </div>
                <div class="input-group">
                    <input type="file" name="image" class="input" accept="image/*" />
                    <label class="label"></label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit">Lưu</button>
                <button type="button" onclick="closeEditItemModal()">Hủy</button>
            </div>
        </form>
    </div>
</div>

<script>
// Các hàm mở/đóng modal riêng biệt
function openCategoryModal() {
    document.getElementById('categoryModal').classList.add('open');
}
function closeCategoryModal() {
    document.getElementById('categoryModal').classList.remove('open');
}

function openAddItemModal() {
    document.getElementById('addItemModal').classList.add('open');
    document.getElementById('addItemForm').reset();
}
function closeAddItemModal() {
    document.getElementById('addItemModal').classList.remove('open');
}

function openEditItemModal(button) {
    const modal = document.getElementById('editItemModal');
    modal.classList.add('open');

    const form = document.getElementById('editItemForm');

    // Lấy dữ liệu từ nút
    form.querySelector('input[name="itemId"]').value = button.getAttribute('data-itemid') || '';
    form.querySelector('input[name="itemName"]').value = button.getAttribute('data-itemname') || '';
    form.querySelector('textarea[name="description"]').value = button.getAttribute('data-description') || '';
    form.querySelector('input[name="price"]').value = button.getAttribute('data-price') || '';
    form.querySelector('select[name="catName"]').value = button.getAttribute('data-category') || '';

    // Làm nổi label nếu cần
    form.querySelectorAll('.input').forEach(input => {
        if(input.value) {
            input.classList.add('filled');
        } else {
            input.classList.remove('filled');
        }
    });
}
function closeEditItemModal() {
    document.getElementById('editItemModal').classList.remove('open');
}

function deleteItem(itemId) {
    if (confirm("Bạn có chắc chắn muốn xóa món này không?")) {
        window.location.href = `delete_item.php?id=${itemId}`;
    }
}

function togglePopular(itemId, checkbox) {
    var isPopular = checkbox.checked ? 1 : 0;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_popular_status.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) console.log("Trạng thái đã được cập nhật.");
            else console.error("Lỗi khi cập nhật trạng thái.");
        }
    };
    xhr.send("itemId=" + itemId + "&is_popular=" + isPopular);
}

function filterCategories() {
    const selectedCategory = document.getElementById('categoryFilter').value.trim().toLowerCase();
    const rows = document.querySelectorAll('#menuTable tbody tr');

    rows.forEach(row => {
        const rowCategory = row.getAttribute('data-category')?.trim().toLowerCase();
        
        if (!selectedCategory || selectedCategory === '') {
            row.style.display = '';
        } else {
            if (rowCategory === selectedCategory) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
}

// Xử lý modal xem danh mục
document.getElementById("viewCategoryBtn").addEventListener("click", function () {
    document.getElementById("viewCategoryModal").classList.add("open");
});
function closeViewCategoryModal() {
    document.getElementById("viewCategoryModal").classList.remove("open");
}

// Toggle hiển thị danh sách món ăn theo danh mục
document.querySelectorAll('.category-row').forEach(row => {
    row.addEventListener('click', () => {
        const targetId = row.getAttribute('data-toggle');
        const itemListRow = document.getElementById(targetId);
        if (itemListRow.style.display === 'none') {
            itemListRow.style.display = '';
        } else {
            itemListRow.style.display = 'none';
        }
    });
});
</script>

<?php include_once('footer.html'); ?>
</body>
</html> 