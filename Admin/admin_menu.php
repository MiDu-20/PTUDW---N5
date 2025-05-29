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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Menu Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="admin_menu.css">
</head>

<body>
<div class="sidebar">
    <button class="close-sidebar" id="closeSidebar">&times;</button>
    <div class="profile-section">
        <img src="../uploads/<?php echo htmlspecialchars($admin_info['profile_image']); ?>" alt="Ảnh đại diện">
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
            <button onclick="openModal()"><i class="fas fa-plus"></i> &nbsp;Thêm Danh Mục Mới</button>
            <button onclick="openItemModal()"> <i class="fas fa-plus"></i> &nbsp;Thêm Món Mới</button>
            <button id="viewCategoryBtn"><i class="fas fa-eye"></i> &nbsp;Xem Danh Mục</button>
        </div>
        <div class="search-bar">
            <select id="categoryFilter" onchange="filterCategories()">
                <option value="">Tất cả danh mục</option>
                <?php
                $sql = "SELECT catName FROM menucategory";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='{$row['catName']}'>{$row['catName']}</option>";
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
                                    <svg class='toggler-on' viewBox='0 0 130.2 130.2'><polyline class='path check' points='100.2,40.2 51.5,88.8 29.8,67.5'></polyline></svg>
                                    <svg class='toggler-off' viewBox='0 0 130.2 130.2'><line class='path line' x1='34.4' y1='34.4' x2='95.8' y2='95.8'></line><line class='path line' x1='95.8' y1='34.4' x2='34.4' y2='95.8'></line></svg>
                                </label>
                            </div>
                        </td>
                        <td>
                            <button id='editbtn' onclick='openEditItemModal(this)' data-itemid='{$row['itemId']}' data-itemname='{$row['itemName']}' data-description='{$row['description']}' data-price='{$row['price']}' data-image='{$row['image']}' data-category='{$row['catName']}' data-status='{$row['status']}'><i class='fas fa-edit'></i></button>
                            <button id='deletebtn' onclick=\"deleteItem('{$row['itemId']}')\"><i class='fas fa-trash'></i></button>
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

<!-- ✅ Modal: XEM DANH MỤC ĐÃ CẬP NHẬT -->
<div class="modal" id="viewCategoryModal">
    <div class="modal-overlay" onclick="closeViewCategoryModal()"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h2>Danh mục món ăn</h2>
            <span class="close-icon" onclick="closeViewCategoryModal()">&times;</span>
        </div>
        <div class="modal-content">
            <div class="notice" style="margin-bottom: 10px; background: #fff3cd; padding: 10px; border-left: 5px solid #ffc107; font-size: 14px;">
                ⚠️ Danh mục đã tạo và đi vào sử dụng sẽ không thể xóa
            </div>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên danh mục</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT mc.catName, GROUP_CONCAT(mi.itemName SEPARATOR '|') AS items 
                            FROM menucategory mc 
                            LEFT JOIN menuitem mi ON mc.catName = mi.catName 
                            GROUP BY mc.catName";
                    $result = mysqli_query($conn, $sql);
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $catName = htmlspecialchars($row['catName']);
                        $items = array_filter(explode('|', $row['items'])); // remove empty
                        echo "<tr class='category-row' data-toggle='category-$i'>
                                <td>{$i}</td><td>{$catName}</td>
                              </tr>";
                        echo "<tr class='item-list' id='category-$i' style='display: none; background: #f9f9f9;'>
                                <td colspan='2'><ul style='margin: 0; padding-left: 20px;'>";
                        if (count($items)) {
                            foreach ($items as $item) {
                                echo "<li>" . htmlspecialchars($item) . "</li>";
                            }
                        } else {
                            echo "<li><i>Không có món nào</i></li>";
                        }
                        echo "</ul></td></tr>";
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button class="button" onclick="closeViewCategoryModal()">Thoát</button>
        </div>
    </div>
</div>

<!-- Các modal khác vẫn giữ nguyên như cũ -->

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

// ✅ Xử lý modal danh mục
document.getElementById("viewCategoryBtn").addEventListener("click", function () {
    document.getElementById("viewCategoryModal").classList.add("open");
});
function closeViewCategoryModal() {
    document.getElementById("viewCategoryModal").classList.remove("open");
}
function confirmDeleteCategory() {
    if (confirm("Bạn có chắc chắn muốn xóa tất cả danh mục không?")) {
        alert("⚠️ Chức năng xóa chưa được kết nối CSDL!");
    }
}
// ✅ Toggle hiển thị danh sách món ăn trong từng danh mục
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
<div class="modal" id="categoryModal">
    <div class="modal-overlay" onclick="closeModal()"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h2>Thêm Danh Mục Mới</h2>
            <span class="close-icon" onclick="closeModal()">&times;</span>
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
                <button type="button" onclick="closeModal()">Hủy</button>
            </div>
        </form>
    </div>
</div>
<div class="modal" id="itemModal">
    <div class="modal-overlay" onclick="closeItemModal()"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h2>Thêm Món Ăn Mới</h2>
            <span class="close-icon" onclick="closeItemModal()">&times;</span>
        </div>
        <form action="add_item.php" method="POST" enctype="multipart/form-data" class="form">
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
                <button type="button" onclick="closeItemModal()">Hủy</button>
            </div>
        </form>
    </div>
</div>

<!--Lọc bảng theo danh mục -->
<script>
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
</script>

</body>
</html>
