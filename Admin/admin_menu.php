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

    <!-- DataTables CSS + JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <!-- ### FIX/ADD: CSS cho Toast Notification -->
    <style>
        .toast {
          visibility: hidden;
          min-width: 280px;
          max-width: 320px;
          background-color: #4BB543; /* Màu xanh thành công */
          color: white;
          text-align: center;
          border-radius: 5px;
          padding: 15px 20px;
          position: fixed;
          top: 20px;
          right: 20px;
          font-size: 1.1rem;
          z-index: 10000;
          opacity: 0;
          transition: opacity 0.4s ease-in-out;
          box-shadow: 0 4px 10px rgba(0,0,0,0.25);
        }
        .toast.show {
          visibility: visible;
          opacity: 1;
        }
        /* Thông báo vàng trong modal xem danh mục */
        .notice {
          margin-bottom: 10px;
          background: #fff3cd;
          padding: 10px;
          border-left: 5px solid #ffc107;
          font-size: 14px;
          color: #856404;
          border-radius: 3px;
        }
    </style>
</head>

<body>
<div class="sidebar">
    <!-- Nội dung sidebar nguyên bản không đổi -->
    <button class="close-sidebar" id="closeSidebar">&times;</button>
    <div class="profile-section">
        <img src="../uploads/<?php echo htmlspecialchars($admin_info['profile_image']); ?>" alt="Ảnh đại diện" />
        <div class="info">
            <h3>Chào mừng bạn quay lại!</h3>
            <p><?php echo htmlspecialchars($admin_info['firstName'] . ' ' . $admin_info['lastName']); ?></p>
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
  <!-- Lọc theo danh mục -->
  <select id="categoryFilter">
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
                            <button id='editbtn' onclick='openEditItemModal(this)' data-itemid='" . htmlspecialchars($row['itemId']) . "' data-itemname='" . htmlspecialchars($row['itemName']) . "' data-description='" . htmlspecialchars($row['description']) . "' data-price='" . htmlspecialchars($row['price']) . "' data-image='" . htmlspecialchars($row['image']) . "' data-category='" . htmlspecialchars($row['catName']) . "' data-status='" . htmlspecialchars($row['status']) . "'><i class='fas fa-edit'></i></button>
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

<!-- ### FIX/ADD: Toast Notification HTML -->
<div id="toast" class="toast"></div>

<!-- Modal xem danh mục -->
<div class="modal" id="viewCategoryModal">
    <div class="modal-overlay" onclick="closeViewCategoryModal()"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h2>Danh mục món ăn</h2>
            <span class="close-icon" onclick="closeViewCategoryModal()">&times;</span>
        </div>
        <div class="modal-content">
            <!-- ### FIX/ADD: Thông báo cứng không thể xóa danh mục -->
            <div class="notice">
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
                    $sql = "SELECT mc.catName, GROUP_CONCAT(mi.itemName SEPARATOR '|') AS items FROM menucategory mc LEFT JOIN menuitem mi ON mc.catName = mi.catName GROUP BY mc.catName";
                    $result = mysqli_query($conn, $sql);
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $catName = htmlspecialchars($row['catName']);
                        $items = array_filter(explode('|', $row['items'])); // loại bỏ giá trị rỗng
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

<!-- Modal thêm danh mục -->
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

<!-- Modal thêm món -->
<div class="modal" id="itemModal">
    <div class="modal-overlay" onclick="closeItemModal()"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h2>Thêm Món Ăn Mới</h2>
            <span class="close-icon" onclick="closeItemModal()">&times;</span>
        </div>
        <form action="add_item.php" method="POST" enctype="multipart/form-data" class="form" id="addItemForm">
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

<script>
document.addEventListener('DOMContentLoaded', () => {
  // Hàm đóng tất cả modal đang mở
  function closeAllModals() {
    document.querySelectorAll('.modal.open').forEach(modal => modal.classList.remove('open'));
  }

  // Mở modal thêm danh mục
  window.openModal = function() {
    closeAllModals();
    document.getElementById('categoryModal').classList.add('open');
  }

  // Đóng modal thêm danh mục
  window.closeModal = function() {
    document.getElementById('categoryModal').classList.remove('open');
  }

  // Mở modal thêm món
  window.openItemModal = function() {
    closeAllModals();
    // Reset tiêu đề modal về "Thêm Món Ăn Mới" khi mở modal thêm mới
    const modal = document.getElementById('itemModal');
    modal.querySelector('.modal-header h2').textContent = 'Thêm Món Ăn Mới';
    // Reset form các trường
    const form = modal.querySelector('form');
    form.reset();
    form.querySelector('input[name="itemId"]').value = '';
    modal.classList.add('open');
  }

  // Đóng modal thêm món
  window.closeItemModal = function() {
    document.getElementById('itemModal').classList.remove('open');
  }

  // Mở modal chỉnh sửa món
  window.openEditItemModal = function(button) {
    closeAllModals();
    const modal = document.getElementById('itemModal');
    modal.classList.add('open');

    // Đổi tiêu đề modal
    modal.querySelector('.modal-header h2').textContent = 'Chỉnh sửa thông tin món ăn';

    const form = modal.querySelector('form');
    form.querySelector('input[name="itemId"]').value = button.getAttribute('data-itemid') || '';
    form.querySelector('input[name="itemName"]').value = button.getAttribute('data-itemname') || '';
    form.querySelector('textarea[name="description"]').value = button.getAttribute('data-description') || '';
    form.querySelector('input[name="price"]').value = button.getAttribute('data-price') || '';
    form.querySelector('select[name="catName"]').value = button.getAttribute('data-category') || '';

    // Thêm class 'filled' nếu dùng floating label
    form.querySelectorAll('.input').forEach(input => {
      if(input.value) input.classList.add('filled');
      else input.classList.remove('filled');
    });
  }

  // Mở modal xem danh mục
  const viewCategoryBtn = document.getElementById("viewCategoryBtn");
  if (viewCategoryBtn) {
    viewCategoryBtn.addEventListener("click", () => {
      closeAllModals();
      document.getElementById("viewCategoryModal").classList.add("open");
    });
  }

  // Đóng modal xem danh mục khi click overlay hoặc nút đóng
  const viewCategoryModal = document.getElementById("viewCategoryModal");
  if (viewCategoryModal) {
    viewCategoryModal.querySelector(".modal-overlay").addEventListener("click", () => {
      viewCategoryModal.classList.remove("open");
    });
    viewCategoryModal.querySelector(".close-icon").addEventListener("click", () => {
      viewCategoryModal.classList.remove("open");
    });
  }

  // Toggle danh sách món trong modal xem danh mục
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
});

</script>
<?php include_once('footer.html'); ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const categoryFilter = document.getElementById('categoryFilter');

  // Hàm lọc món theo danh mục
  function filterCategories() {
    const selectedCategory = categoryFilter.value.trim().toLowerCase();
    const rows = document.querySelectorAll('#menuTable tbody tr');

    rows.forEach(row => {
      const rowCategory = row.getAttribute('data-category');
      if (!rowCategory) return; // Bỏ qua dòng không có data-category

      if (selectedCategory === '' || rowCategory.toLowerCase() === selectedCategory) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });
  }

  // Gán sự kiện onchange cho select
  if (categoryFilter) {
    categoryFilter.addEventListener('change', filterCategories);
  }
});
</script>
<script> // Script phân trang và lọc món ăn
document.getElementById('toggleSidebar').addEventListener('click', () => {
  document.querySelector('.sidebar').classList.toggle('open');
});
document.getElementById('closeSidebar').addEventListener('click', () => {
  document.querySelector('.sidebar').classList.remove('open');
});
</script>
<script> // Kích hoạt DataTables
$(document).ready(function () {
  $('#menuTable').DataTable({
  ordering: false,
  pageLength: 10,
  lengthMenu: [5, 10, 20, 50],
  dom: '<"top-row d-flex justify-between mb-3"lf>rt<"bottom-row d-flex justify-between mt-3"ip>',
  language: {
    search: "Tìm kiếm:",
    lengthMenu: "Số hàng/trang: _MENU_",
    info: "Trang _PAGE_ / _PAGES_",
    paginate: {
      first: "<<",
      last: ">>",
      next: ">",
      previous: "<"
    },
    emptyTable: "Không có dữ liệu"
  },
  columnDefs: [
    {
      targets: 2,
      render: function (data, type, row) {
        let words = data.split(/\s+/);
        let shortText = words.length > 4 ? words.slice(0, 4).join(" ") + " ..." : data;
        return `<span title="${data}">${shortText}</span>`;
      }
    },
    { targets: [1, 6, 7], orderable: false }
  ]
});
});
</script>
<script> // Thêm hàm XÓA ITEM 
function deleteItem(itemId) {
    if (confirm("Bạn có chắc chắn muốn xóa món này không?")) {
        window.location.href = `delete_item.php?id=${itemId}`;
    }
}
</script>
</body>
</html>
