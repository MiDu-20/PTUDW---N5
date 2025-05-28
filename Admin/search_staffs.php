<?php
session_start();

// Kiểm tra xem người dùng đã đăng nhập với quyền admin chưa
if (!isset($_SESSION['adminloggedin'])) {
    // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
    header("Location: ../login.php");
    exit();
}

include 'db_connection.php'; // Kết nối tới cơ sở dữ liệu

$search = '';
// Nếu có dữ liệu tìm kiếm gửi lên qua phương thức POST
if (isset($_POST['search'])) {
    // Lấy giá trị tìm kiếm và lọc để tránh lỗi SQL Injection
    $search = $conn->real_escape_string($_POST['search']);
}

// Câu truy vấn SQL lấy tất cả dữ liệu từ bảng staff
$sql = "SELECT * FROM staff";

// Nếu có giá trị tìm kiếm thì thêm điều kiện WHERE để lọc kết quả
if (!empty($search)) {
    $sql .= " WHERE id LIKE '%$search%' OR email LIKE '%$search%' OR firstName LIKE '%$search%' OR lastName LIKE '%$search%' OR role LIKE '%$search%'";
}

// Thực thi câu truy vấn
$result = $conn->query($sql);

// Kiểm tra nếu có kết quả trả về
if ($result->num_rows > 0) {
    // Duyệt từng bản ghi
    while ($row = $result->fetch_assoc()) {
        // Mã hóa mật khẩu thành dấu * để che dấu mật khẩu khi hiển thị
        $passwordMasked = str_repeat('*', strlen($row['password']));
        
        // Hiển thị từng dòng trong bảng HTML
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['createdAt']}</td>
                <td>{$row['email']}</td>
                <td>{$row['firstName']}</td>
                <td>{$row['lastName']}</td>
                <td>{$row['contact']}</td>
                <td>{$row['role']}</td>
                <td>
                    <span class='password-masked'>{$passwordMasked}</span>
                    <span class='password-visible' style='display: none;'>{$row['password']}</span>
                    <i class='fas fa-eye toggle-password' onclick='togglePassword(this)'></i>
                </td>
                <td>
                    <button id='editbtn' onclick='openEditUserModal(this)' 
                        data-email='{$row['email']}' 
                        data-firstname='{$row['firstName']}' 
                        data-lastname='{$row['lastName']}' 
                        data-contact='{$row['contact']}' 
                        data-role='{$row['role']}' 
                        data-password='{$row['password']}'>
                        <i class='fas fa-edit'></i>
                    </button>
                    <button id='deletebtn' onclick='deleteItem(\"{$row['email']}\")'>
                        <i class='fas fa-trash'></i>
                    </button>
                </td>
              </tr>";
    }
} else {
    // Nếu không có bản ghi nào, hiển thị dòng thông báo
    echo "<tr><td colspan='9' style='text-align: center;'>Không tìm thấy nhân viên nào</td></tr>";
}

// Đóng kết nối database
$conn->close();
?>
