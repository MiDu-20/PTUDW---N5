<?php
// Thiết lập header để định dạng dữ liệu trả về là JSON
header('Content-Type: application/json');

include 'db_connection.php';

// Câu truy vấn: đếm số lượng đánh giá theo từng ngày và theo từng mức rating
$sql = "SELECT review_date, rating, COUNT(*) as count FROM reviews GROUP BY review_date, rating ORDER BY review_date";
$result = $conn->query($sql);
// Mảng để lưu trữ dữ liệu kết quả
$data = [];
if ($result->num_rows > 0) {
    // Duyệt từng dòng kết quả và thêm vào mảng dữ liệu
    while ($row = $result->fetch_assoc()) {
        $data[] = $row; // Mỗi phần tử sẽ có review_date, rating, và count
    }
}

$conn->close();

// Trả về kết quả dạng JSON để client-side (JS) sử dụng, ví dụ vẽ biểu đồ
echo json_encode($data);
?>
