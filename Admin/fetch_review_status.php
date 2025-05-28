<?php
// Kết nối đến cơ sở dữ liệu
include 'db_connection.php';

// Lấy giá trị bộ lọc 'status' từ request POST, nếu không có thì để trống
$statusFilter = isset($_POST['status']) ? $_POST['status'] : '';

// Tạo câu lệnh SQL tùy theo việc có bộ lọc hay không
if ($statusFilter) {
    // Nếu có bộ lọc, chỉ lấy các review có status tương ứng
    $sql = "SELECT * FROM reviews WHERE status = ?";
    $stmt = $conn->prepare($sql);
    // Gắn giá trị của $statusFilter vào câu SQL để tránh SQL injection
    $stmt->bind_param("s", $statusFilter);
} else {
    // Nếu không có bộ lọc, lấy tất cả các review
    $sql = "SELECT * FROM reviews";
    $stmt = $conn->prepare($sql);
}
// Thực thi truy vấn
$stmt->execute();
$result = $stmt->get_result();

// Nếu có dữ liệu thì lặp qua từng dòng để hiển thị ra bảng HTML
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Chuyển số rating (1–5) thành biểu tượng sao HTML
        $ratingStars = str_repeat('&#9733;', $row['rating']) . str_repeat('&#9734;', 5 - $row['rating']);
// Hiển thị từng dòng dữ liệu trong bảng
        echo "<tr>
                <td>{$row['order_id']}</td>
                <td>{$row['email']}</td>
                <td>{$row['review_text']}</td>
                <!-- Cột hiển thị sao đánh giá -->
                <td class='rating-stars'>{$ratingStars}</td>
                <!-- Cột chọn trạng thái với dropdown -->
                <td>
                    <select id='status-{$row['order_id']}' onchange='updateStatus({$row['order_id']}, this.value)' class='status-select'>
                        <option value='pending' " . ($row['status'] == 'pending' ? 'selected' : '') . ">Đang chờ</option>
                        <option value='approved' " . ($row['status'] == 'approved' ? 'selected' : '') . ">Xác nhận</option>
                        <option value='rejected' " . ($row['status'] == 'rejected' ? 'selected' : '') . ">Từ chối</option>
                    </select>
                </td>
                <!-- Cột hiển thị phản hồi từ admin -->
                <td>{$row['response']}</td>
                <!-- Cột nút chỉnh sửa và xóa -->
                <td>
                    <button id='editbtn' onclick='openEditReviewModal(this)' data-id='{$row['order_id']}' data-email='{$row['email']}' data-review_text='{$row['review_text']}' data-rating='{$row['rating']}' data-response='{$row['response']}'><i class='fas fa-edit'></i></button>
                    <button id='deletebtn' onclick=\"deleteReview('{$row['order_id']}', '{$row['email']}')\"><i class='fas fa-trash'></i></button>
                </td>
              </tr>";
    }
} else {
    // Nếu không có bản ghi nào, hiển thị dòng thông báo
    echo "<tr><td colspan='7' style='text-align: center;'>Không có đánh giá nào.</td></tr>";
}

// Đóng kết nối với cơ sở dữ liệu
$stmt->close();
$conn->close();
?>
