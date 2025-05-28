<?php
// Bắt đầu session để kiểm tra trạng thái đăng nhập của admin
session_start();
// Kiểm tra nếu admin chưa đăng nhập thì chuyển hướng đến trang đăng nhập
if (!isset($_SESSION['adminloggedin'])) {
    header("Location: ../login.php");
    exit();
}
// Kết nối đến cơ sở dữ liệu
include 'db_connection.php';

// Biến để lưu từ khóa tìm kiếm
$search = '';
if (isset($_POST['search'])) {
    // Lấy và lọc dữ liệu tìm kiếm (chống SQL injection cơ bản)
    $search = $conn->real_escape_string($_POST['search']);
}

// Câu lệnh SQL để lấy danh sách người dùng
$sql = "SELECT * FROM users";
// Nếu có nhập từ khóa tìm kiếm thì thêm điều kiện lọc
if (!empty($search)) {
    $sql .= " WHERE email LIKE '%$search%' OR firstName LIKE '%$search%' OR lastName LIKE '%$search%'";
}
// Thực thi truy vấn
$result = $conn->query($sql);

// Kiểm tra nếu có bản ghi trả về
if ($result->num_rows > 0) {
    $counter = 1;
    while ($row = $result->fetch_assoc()) {
        // Ẩn mật khẩu bằng cách chuyển đổi thành chuỗi dấu *
        $passwordMasked = str_repeat('*', strlen($row['password']));
        echo "<tr>
                <td>{$counter}</td>
                <td>{$row['dateCreated']}</td>
                <td>{$row['email']}</td>
                <td>{$row['firstName']}</td>
                <td>{$row['lastName']}</td>
                <td>{$row['contact']}</td>
                <td>
                <!-- Hiển thị mật khẩu bị ẩn và có thể bật tắt -->
                    <span class='password-masked'>{$passwordMasked}</span>
                    <span class='password-visible' style='display: none;'>{$row['password']}</span>
                    <i class='fas fa-eye toggle-password' onclick='togglePassword(this)'></i>
                </td>
                <td>
                <!-- Nút sửa: mở modal và truyền dữ liệu người dùng -->
                    <button id='editbtn' onclick='openEditUserModal(this)' data-email='{$row['email']}' data-firstname='{$row['firstName']}' data-lastname='{$row['lastName']}' data-contact='{$row['contact']}' data-password='{$row['password']}'><i class='fas fa-edit'></i></button>
                    <!-- Nút xoá: gọi hàm xóa với email làm tham số -->
                    <button id='deletebtn' onclick=\"deleteItem('{$row['email']}')\"><i class='fas fa-trash'></i></button>
                </td>
              </tr>";
        $counter++; // Tăng số thứ tự
    }
} else {
    // Nếu không có người dùng nào được tìm thấy
    echo "<tr><td colspan='8' style='text-align: center;'>Không tìm thấy người dùng nào</td></tr>";
}
// Đóng kết nối đến cơ sở dữ liệu
$conn->close();
?>
