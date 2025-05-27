<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Kết nối đến cơ sở dữ liệu
  include 'db_connection.php';

  $reservation_id = $_POST['reservation_id'];
  $status = $_POST['status'];

  //Chuẩn bị và thực thi câu truy vấn cập nhật
  $stmt = $conn->prepare("UPDATE reservations SET status = ? WHERE reservation_id = ?");
  $stmt->bind_param("si", $status, $reservation_id);

  if ($stmt->execute()) {
    echo "Status updated successfully";
    // GỬI EMAIL nếu trạng thái được duyệt
        if ($status === 'Approved') {
            // Lấy thông tin người đặt bàn để gửi email
            $info_stmt = $conn->prepare("SELECT name, email, reservedDate, reservedTime FROM reservations WHERE reservation_id = ?");
            $info_stmt->bind_param("i", $reservation_id);
            $info_stmt->execute();
            $result = $info_stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $to = $row['email'];
                $subject = "Xác nhận đặt bàn tại nhà hàng Chomp Chomp";
                $message = "Chào " . $row['name'] . ",\n\n";
                $message .= "Đơn đặt bàn của bạn vào lúc " . $row['reservedTime'] . " ngày " . $row['reservedDate'] . " đã được *xác nhận*.\n\n";
                $message .= "Chúng tôi rất mong được chào đón bạn tại nhà hàng.\n";
                $message .= "Trân trọng,\nChomp Chomp Restaurant";

                // Gửi email (sử dụng hàm mail cơ bản)
                $headers = "From: chompchomp@example.com"; // có thể dùng email thật
                mail($to, $subject, $message, $headers);
            }

            $info_stmt->close();
  } else {
    echo "Error updating status: " . $conn->error;
  }

  $stmt->close();
  $conn->close();
}




?>

