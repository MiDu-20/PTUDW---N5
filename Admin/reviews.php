<?php
// Bắt đầu phiên làm việc (session) để kiểm tra đăng nhập admin
session_start();
// Kiểm tra nếu admin chưa đăng nhập thì chuyển hướng về trang đăng nhập
if (!isset($_SESSION['adminloggedin'])) {
  header("Location: ../login.php");
  exit();
}
// Kết nối đến cơ sở dữ liệu
include 'db_connection.php';
?>
<?php
include 'sidebar.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Review Management</title>

  <!--poppins-->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="admin_reservation.css">
  <link rel="stylesheet" href="sidebar.css">
  <link rel="stylesheet" href="admin_review.css">
</head>

<body>
  <div class="sidebar">
    <button class="close-sidebar" id="closeSidebar">&times;</button>

    <!-- Profile Section -->
    <div class="profile-section">
      <img src="../uploads/<?php echo htmlspecialchars($admin_info['profile_image']); ?>" alt="Profile Picture">
      <div class="info">
        <h3>Welcome Back!</h3>
        <p><?php echo htmlspecialchars($admin_info['firstName']) . ' ' . htmlspecialchars($admin_info['lastName']); ?></p>
      </div>
    </div>

    <!-- Navigation Items -->

    <ul>
      <li><a href="index.php"><i class="fas fa-chart-line"></i>Tổng quan</a></li>
      <li><a href="admin_menu.php"><i class="fas fa-utensils"></i>Quản lý thực đơn</a></li>
      <li><a href="admin_orders.php"><i class="fas fa-shopping-cart"></i>Đơn hàng</a></li>
      <li><a href="reservations.php"><i class="fas fa-calendar-alt"></i>Đặt bàn</a></li>
      <li><a href="users.php"><i class="fas fa-users"></i>Người dùng</a></li>
      <li><a href="reviews.php" class="active"><i class="fas fa-star"></i>Đánh giá</a></li>
      <li><a href="staffs.php"><i class="fas fa-users"></i>Nhân viên</a></li>
      <li><a href="profile.php"><i class="fas fa-user"></i>Hồ sơ</a></li>
      <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i>Đăng xuất</a></li>
    </ul>
  </div>
  <div class="content">
    <div class="header">
      <button id="toggleSidebar" class="toggle-button">
        <i class="fas fa-bars"></i>
      </button>
      <h2><i class="fas fa-star"></i>Đánh giá</h2>
    </div>

    <div class="actions">
      <select id="statusFilter" name="statusFilter" onchange="filterByStatus()">
        <option value="">Tất cả</option>
        <option value="pending">Đang chờ</option>
        <option value="approved">Xác nhận</option>
        <option value="rejected">Từ chối</option>
      </select>
    </div>

    <div class="table">
      <table id="reviewTable">
        <thead>
          <tr>
            <th>Mã đơn hàng</th>
            <th>Email</th>
            <th>Nhận xét</th>
            <th>Đánh giá</th>
            <th>Trạng thái</th>
            <th>Phản hồi</th>
            <th>Chỉnh sửa</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Kết nối đến cơ sở dữ liệu
          include 'db_connection.php';

          // Truy vấn lấy toàn bộ review
          $sql = "SELECT * FROM reviews";
          $result = mysqli_query($conn, $sql);

          if (mysqli_num_rows($result) > 0) {
            //Nếu có review thì hiển thị
            while ($row = mysqli_fetch_assoc($result)) {
              // Chuyển rating thành biểu tượng sao
              $ratingStars = str_repeat('&#9733;', $row['rating']) . str_repeat('&#9734;', 5 - $row['rating']);

              echo "<tr>
                        <td>{$row['order_id']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['review_text']}</td>
                        <td class='rating-stars'>{$ratingStars}</td>
                        <td>
                         <select id='status-{$row['order_id']}' onchange='updateStatus({$row['order_id']}, this.value)' class='status-select'>
                         <option value='pending' " . ($row['status'] == 'pending' ? 'selected' : '') . ">Đang chờ</option>
                         <option value='approved' " . ($row['status'] == 'approved' ? 'selected' : '') . ">Xác nhận</option>
                         <option value='rejected' " . ($row['status'] == 'rejected' ? 'selected' : '') . ">Từ chối</option>
                         </select>
                        </td>

                        <td>{$row['response']}</td>
                        <td>
                            <button id='editbtn' onclick='openEditReviewModal(this)' data-id='{$row['order_id']}' data-email='{$row['email']}' data-review_text='{$row['review_text']}' data-rating='{$row['rating']}' data-response='{$row['response']}'><i class='fas fa-edit'></i></button>
                            <button id='deletebtn' onclick=\"deleteReview('{$row['order_id']}', '{$row['email']}')\"><i class='fas fa-trash'></i></button>
                        </td>
                      </tr>";
            }
          } else {
            //Nếu không có review, hiển thị thông báo 
            echo "<tr><td colspan='6' style='text-align: center;'>Không có đánh giá nào.</td></tr>";
          }

          // Đóng kết nối với cơ sở dữ liệu
          mysqli_close($conn);
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal for editing review -->
  <div id="editReviewModal" class="modal">
    <div class="modal-overlay"></div>
    <div class="modal-container">
      <form id="editReviewForm" method="POST" action="edit_review.php">
        <div class="modal-header">
          <h2>Chỉnh sửa đánh giá</h2>
          <span class="close-icon" onclick="closeEditReviewModal()">&times;</span>
        </div>
        <div class="modal-content">
          <div class="input-group">
            <input type="number" name="order_id" id="editOrder_id" class="input" readonly>
            <label for="editOrder_id" class="label">Mã đơn hàng</label>
          </div>
        </div>
        <div class="modal-content">
          <div class="input-group">
            <input type="email" name="email" id="editEmail" class="input" readonly>
            <label for="editEmail" class="label">Email</label>
          </div>
        </div>
        <div class="modal-content">
          <div class="input-group">
            <input type="text" name="review_text" id="editReview_text" class="input" readonly>
            <label for="editReview_text" class="label">Nhận xét</label>
          </div>
        </div>
        <div class="modal-content">
          <div class="input-group">
            <input type="text" name="rating" id="editRating" class="input" readonly>
            <label for="editRating" class="label">Đánh giá</label>
          </div>
        </div>
        <div class="modal-content">
          <div class="input-group">
            <input type="text" name="response" id="editResponse" class="input" required>
            <label for="editResponse" class="label">Phản hồi</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="button" onclick="closeEditReviewModal()">Hủy</button>
          <button type="submit" class="button">Lưu</button>
        </div>
      </form>
    </div>
  </div>

  <?php
    include_once ('footer.html');
    ?>
  <script>
   function updateStatus(order_id, status) {
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "update_review_status.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4 && xhr.status === 200) {
      // Kiểm tra phản hồi thành công
      if (xhr.responseText.trim() === "Status updated successfully") {
        // Hiển thị thông báo thành công
        alert("Cập nhật trạng thái đánh giá thành công.");
      } else {
        // Hiển thị thông báo lỗi
        alert("Lỗi khi cập nhật trạng thái đánh giá: " + xhr.responseText);
      }
    }
  };
  xhr.send("order_id=" + encodeURIComponent(order_id) + "&status=" + encodeURIComponent(status));
}



    function deleteReview(orderId, email) {
      // Xác nhận xoá review
      if (confirm('Bạn có chắc chắn muốn xóa đánh giá này không?')) {
        // Gửi yêu cầu xoá lên server
        fetch('delete_review.php', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({
              orderId: orderId,
              email: email
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert('Xóa đánh giá thành công.');
              location.reload(); // Tải lại trang để cập nhật danh sách
            } else {
              alert('Lỗi khi xóa đánh giá.');
            }
          });
      }
    }

    function openEditReviewModal(button) {
      // Lấy dữ liệu review từ thuộc tính data
      const order_id = button.getAttribute('data-id');
      const email = button.getAttribute('data-email');
      const review_text = button.getAttribute('data-review_text');
      const rating = button.getAttribute('data-rating');
      const response = button.getAttribute('data-response');

      // Đưa dữ liệu vào form chỉnh sửa
      document.getElementById('editOrder_id').value = order_id;
      document.getElementById('editEmail').value = email;
      document.getElementById('editReview_text').value = review_text;
      document.getElementById('editRating').value = rating;
      document.getElementById('editResponse').value = response;

      // Mở modal chỉnh sửa review
      document.getElementById('editReviewModal').classList.add('open');
    }

    function closeEditReviewModal() {
      // Đóng modal chỉnh sửa review
      document.getElementById('editReviewModal').classList.remove('open');
    }

    function filterByStatus() {
    // Lấy trạng thái được chọn trong dropdown
    const status = document.getElementById('statusFilter').value;

    // Tạo đối tượng XMLHttpRequest
    var xhr = new XMLHttpRequest();

    // Thiết lập yêu cầu POST tới file xử lý lọc trạng thái
    xhr.open('POST', 'fetch_review_status.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Xử lý khi nhận được phản hồi
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Cập nhật nội dung bảng với dữ liệu trả về
            document.querySelector('#reviewTable tbody').innerHTML = xhr.responseText;
        }
    };

    // Gửi dữ liệu trạng thái lên server
    xhr.send('status=' + encodeURIComponent(status));
}

  </script>
</body>

</html>
