<?php
// Bắt đầu phiên làm việc và kiểm tra xem admin đã đăng nhập chưa
session_start();
if (!isset($_SESSION['adminloggedin']) || !$_SESSION['adminloggedin']) {
    header('Location: ../login.php');
    exit;
}
// Kết nối cơ sở dữ liệu
$host = 'localhost';
$dbname = 'restaurant';
$username = 'root';
$password = '';
$conn = new mysqli($host, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Thiết lập múi giờ
date_default_timezone_set('Asia/Colombo');

// Hàm tính tổng doanh thu giữa 2 mốc thời gian
function calculateEarnings($conn, $dateColumn, $startDate, $endDate)
{
    $query = "SELECT SUM(grand_total) AS total FROM orders WHERE $dateColumn BETWEEN ? AND ? AND payment_status = 'successful'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total'] ?? 0; // Return 0 if null
}

// Hàm tính tổng đơn hàng trong một khoảng thời gian
function calculateTotalOrders($conn, $startDate, $endDate)
{
    $query = "SELECT COUNT(*) AS total_orders FROM orders WHERE order_date BETWEEN ? AND ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total_orders'] ?? 0; // Return 0 if null
}

// Hàm tính tổng người dùng
function calculateTotalUsers($conn)
{
    $query = "SELECT COUNT(*) AS total_users FROM users";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    return $row['total_users'] ?? 0; // Return 0 if null
}

// Hàm tính tổng số lượt đặt bàn
function calculateTotalReservations($conn)
{
    $query = "SELECT COUNT(*) AS total_reservations FROM reservations";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    return $row['total_reservations'] ?? 0; // Return 0 if null
}

// Hàm tính phần trăm thay đổi giữa hai giá trị
function calculateChange($current, $previous)
{
    // Tính phần trăm thay đổi
    $change = $previous ? (($current - $previous) / $previous) * 100 : 0;

    // Rành buộc giá trị giữa -100 và 100
    if ($change < -100) {
        return -100;
    } elseif ($change > 100) {
        return 100;
    }

    // Trả về thay đổi format 
    return number_format($change, 2);
}

// Tính toán % thay đổi cho các chỉ số
$totalEarning = calculateEarnings($conn, 'order_date', '1970-01-01 00:00:00', date('Y-m-d') . ' 23:59:59');

// Tính số tiền kiếm được hôm nay
$todaysEarning = calculateEarnings($conn, 'order_date', date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59');

// Tính tổng đơn hàng hôm nay và hôm qua
$todaysOrders = calculateTotalOrders($conn, date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59');
$yesterdaysOrders = calculateTotalOrders($conn, date('Y-m-d', strtotime('-1 day')) . ' 00:00:00', date('Y-m-d', strtotime('-1 day')) . ' 23:59:59');

// Tính tổng đơn hàng giai đoạn hiện tại
$currentStartDate = '2024-01-01 00:00:00'; // Or any appropriate start date
$currentEndDate = date('Y-m-d') . ' 23:59:59';
$totalOrders = calculateTotalOrders($conn, $currentStartDate, $currentEndDate);

// Tính tổng đơn hàng giai đoạn hiện tại
$previousStartDate = date('Y-m-d', strtotime('-1 month')) . ' 00:00:00';
$previousEndDate = date('Y-m-d', strtotime('-1 day')) . ' 23:59:59';
$previousTotalOrders = calculateTotalOrders($conn, $previousStartDate, $previousEndDate);

// Đảm bảo biến đã được khởi tạo
$totalUsers = calculateTotalUsers($conn);
$previousTotalUsers = calculateTotalUsers($conn) - 100; // Example: 50 users ago; adjust as needed
$totalReservations = calculateTotalReservations($conn);
$previousTotalReservations = calculateTotalReservations($conn) - 100;




// Tính phần trăm thay đổi
$totalEarningChange = calculateChange($totalEarning, calculateEarnings($conn, 'order_date', '1970-01-01 00:00:00', date('Y-m-d', strtotime('-1 month')) . ' 23:59:59')); // Previous month earnings
$todaysEarningChange = calculateChange($todaysEarning, calculateEarnings($conn, 'order_date', date('Y-m-d', strtotime('-1 day')) . ' 00:00:00', date('Y-m-d', strtotime('-1 day')) . ' 23:59:59')); // Previous day earnings
$totalOrdersChange = calculateChange($totalOrders, $previousTotalOrders);
$totalUsersChange = calculateChange($totalUsers, $previousTotalUsers);
$totalReservationsChange = calculateChange($totalReservations, $previousTotalReservations);
$todaysOrdersChange = calculateChange($todaysOrders, $yesterdaysOrders);

// Đảm bảo các biến đã được khởi tạo
$todaysOrders = isset($todaysOrders) ? $todaysOrders : 0;
$todaysOrdersChange = isset($todaysOrdersChange) ? $todaysOrdersChange : 0;
$totalEarningChange = isset($totalEarningChange) ? $totalEarningChange : 0;
$todaysEarningChange = isset($todaysEarningChange) ? $todaysEarningChange : 0;
$totalOrdersChange = isset($totalOrdersChange) ? $totalOrdersChange : 0;
$totalUsersChange = isset($totalUsersChange) ? $totalUsersChange : 0;
$totalReservationsChange = isset($totalReservationsChange) ? $totalReservationsChange : 0;

// Hàm đếm số lượng đơn hàng theo từng trạng thái
function getOrderStatusCounts($conn)
{
    $statuses = ['Pending', 'Processing', 'On the Way', 'Completed', 'Cancelled'];
    $statusCounts = [];
    foreach ($statuses as $status) {
        $query = "SELECT COUNT(*) AS count FROM orders WHERE order_status = ?";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }
        $stmt->bind_param('s', $status);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $statusCounts[$status] = $row['count'] ?? 0; // Return 0 if null
    }
    return $statusCounts;
}

// Gọi hàm để lấy dữ liệu đếm đơn hàng theo trạng thái
$statusCounts = getOrderStatusCounts($conn);

// Close connection
$conn->close();
?>
<?php
include 'sidebar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Nhúng font và thư viện cần thiết -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="index.css">
    <style>
        .content {
            margin-bottom: 40px;
        }
    </style>
</head>

<body>

    <!-- Sidebar (thanh bên trái) -->
    <div class="sidebar">
        <button class="close-sidebar" id="closeSidebar">&times;</button>

        <!-- Phần thông tin người dùng -->
        <div class="profile-section">
            <img src="../uploads/<?php echo htmlspecialchars($admin_info['profile_image']); ?>" alt="Profile Picture">
            <div class="info">
                <h3>Welcome Back!</h3>
                <p><?php echo htmlspecialchars($admin_info['firstName']) . ' ' . htmlspecialchars($admin_info['lastName']); ?></p>
            </div>
        </div>

        <!-- Menu điều hướng -->

        <ul>
            <li><a href="index.php" class="active"><i class="fas fa-chart-line"></i>Tổng quan</a></li>
            <li><a href="admin_menu.php"><i class="fas fa-utensils"></i>Quản lý thực đơn</a></li>
            <li><a href="admin_orders.php"><i class="fas fa-shopping-cart"></i>Đơn hàng</a></li>
            <li><a href="reservations.php"><i class="fas fa-calendar-alt"></i>Đặt bàn</a></li>
            <li><a href="users.php"><i class="fas fa-users"></i>Người dùng</a></li>
            <li><a href="reviews.php"><i class="fas fa-star"></i>Đánh giá</a></li>
            <li><a href="staffs.php"><i class="fas fa-users"></i>Nhân viên</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i>Hồ sơ</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i>Đăng xuât</a></li>
        </ul>
    </div>
    <!-- Nội dung chính của trang -->
    <div class="content">
        <div class="header">
            <button id="toggleSidebar" class="toggle-button">
                <i class="fas fa-bars"></i>
            </button>
            <h2><i class="fas fa-chart-line"></i> Tổng quan</h2>
        </div>
        <div class="container">
        <!-- Các thẻ thống kê tổng quan -->
            <div class="card" data-color="blue">

                <div class="card-content">
                    <h4>Tổng doanh thu</h4>
                    <h3><?php echo number_format($totalEarning); ?></h3>
                    <p class="<?php echo $totalEarningChange > 0 ? 'positive' : 'negative'; ?>">
                        <?php echo $totalEarningChange > 0 ? '▲' : '▼'; ?> <?php echo abs($totalEarningChange); ?>%
                    </p>
                </div>
                <i class="icon-top-right icon fas fa-dollar-sign"></i>
                <canvas id="chart1"></canvas>
            </div>

            <!-- Doanh thu hôm nay -->
            <div class="card" data-color="blue">
                <div class="card-content">
                    <h4>Doanh thu hôm nay</h4>
                    <h3><?php echo number_format($todaysEarning); ?></h3>
                    <p class="<?php echo $todaysEarningChange > 0 ? 'positive' : 'negative'; ?>">
                        <?php echo $todaysEarningChange > 0 ? '▲' : '▼'; ?> <?php echo abs($todaysEarningChange); ?>%
                    </p>
                </div>
                <i class="icon-top-right icon fas fa-calendar-day"></i>
                <canvas id="chart2"></canvas>
            </div>

            <!-- Tổng đơn hàng -->
            <div class="card" data-color="blue">
                <div class="card-content">
                    <h4>Tổng đơn hàng</h4>
                    <h3><?php echo number_format($totalOrders); ?></h3> <!-- Display total orders -->
                    <p class="<?php echo $totalOrdersChange > 0 ? 'positive' : 'negative'; ?>">
                        <?php echo $totalOrdersChange > 0 ? '▲' : '▼'; ?> <?php echo abs($totalOrdersChange); ?>%
                    </p>
                </div>
                <i class="icon-top-right icon fas fa-shopping-cart"></i>
                <canvas id="chart5"></canvas>
            </div>

            <!-- Đơn hàng hôm nay -->
            <div class="card" data-color="blue">
                <div class="card-content">
                    <h4>Tổng đơn hàng hôm nay</h4>
                    <h3><?php echo number_format($todaysOrders); ?></h3> <!-- Display today's orders -->
                    <p class="<?php echo $todaysOrdersChange > 0 ? 'positive' : 'negative'; ?>">
                        <?php echo $todaysOrdersChange > 0 ? '▲' : '▼'; ?> <?php echo abs($todaysOrdersChange); ?>%
                    </p>
                </div>
                <i class="icon-top-right icon fas fa-calendar-day"></i>
                <canvas id="chart6"></canvas>
            </div>

            <!-- Tổng người dùng -->
            <div class="card" data-color="blue">
                <div class="card-content">
                    <h4>Tổng khách hàng</h4>
                    <h3><?php echo number_format($totalUsers); ?></h3> <!-- Display total users -->
                    <p class="<?php echo $totalUsersChange > 0 ? 'positive' : 'negative'; ?>">
                        <?php echo $totalUsersChange > 0 ? '▲' : '▼'; ?> <?php echo abs($totalUsersChange); ?>%
                    </p>
                </div>
                <i class="icon-top-right icon fas fa-users"></i>
                <canvas id="chart3"></canvas>
            </div>

            <!-- Tổng đặt bàn -->
            <div class="card" data-color="blue">
                <div class="card-content">
                    <h4>Tổng đặt bàn</h4>
                    <h3><?php echo number_format($totalReservations); ?></h3> <!-- Display total reservations -->
                    <p class="<?php echo $totalReservationsChange > 0 ? 'positive' : 'negative'; ?>">
                        <?php echo $totalReservationsChange > 0 ? '▲' : '▼'; ?> <?php echo abs($totalReservationsChange); ?>%
                    </p>
                </div>
                <i class="icon-top-right icon fas fa-calendar-check"></i>
                <canvas id="chart4"></canvas>
            </div>

        </div>
        <!-- Bảng đơn hàng gần nhất và biểu đồ trạng thái -->
        <div class="table-chart">
            <div class="table">
                <?php
                // Kết nối database
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "restaurant";

                $conn = new mysqli($servername, $username, $password, $dbname);

                // Kiểm tra kết nối
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Truy vấn sql lấy 5 đơn hàng gần nhất
                $sql = "SELECT order_id, firstName, lastName, order_status, grand_total 
        FROM orders 
        ORDER BY order_date DESC 
        LIMIT 6";
                $result = $conn->query($sql);

                // Kiểm tra nếu có  kết quả
                if ($result->num_rows > 0) {
                    echo '<div class="latest-orders">';
                    echo '<h2>Các đơn hàng gần đây</h2>';
                    echo '<table>';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>ID</th>';
                    echo '<th>Khách hàng</th>';
                    echo '<th>Trạng thái</th>';
                    echo '<th>Thành tiền</th>';
                    echo '<th>Chi tiết</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';

                    // Output data cho mỗi hàng
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row["order_id"]) . '</td>';
                        echo '<td>' . htmlspecialchars($row["firstName"] . " " . $row["lastName"]) . '</td>';
                        echo '<td>' . htmlspecialchars($row["order_status"]) . '</td>';
                        echo '<td>' . htmlspecialchars($row["grand_total"]) . ' đ</td>';
                        echo '<td>';
                        echo '<button onclick=\'viewDetails(' . $row['order_id'] . ')\'>Xem</button>';
                        echo '</td>';
                        echo '</tr>';
                    }

                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                } else {
                    echo "No orders found.";
                }

                $conn->close();
                ?>

            </div>
            <div class="bar-chart">

                <canvas id="orderStatusChart"></canvas>
            </div>
        </div>
        <div class="review-container">
            <div id="chartContainer">
                <h2>Doanh thu các món</h2>
                <canvas id="earningsChart"></canvas>
            </div>
            <div class="review-chart-container">
                <h2>Đánh giá</h2>
                <canvas id="ratingsLineChart"></canvas>
            </div>
        </div>

    </div>
    <?php
    include_once('footer.html');
    ?>
    <script src="sidebar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('fetch_earnings.php')
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('earningsChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['2024-08-09', '2024-08-11', '2024-08-12'],
                            datasets: [
                            {
                            label: 'Appetizer',
                            data: [5800, 10200, 5400],
                            backgroundColor: 'rgba(193, 70, 0, 0.2)',   // #C14600 soft
                            borderColor: '#C14600',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                            },
                            {
                                label: 'Burger',
                                data: [5900, 7400, 0],
                                backgroundColor: 'rgba(255, 157, 35, 0.2)', // #FF9D23
                                borderColor: '#FF9D23',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'Beverage',
                                data: [0, 10000, 1500],
                                backgroundColor: 'rgba(229, 208, 172, 0.2)', // #E5D0AC
                                borderColor: '#E5D0AC',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'Pizza',
                                data: [0, 0, 3200],
                                backgroundColor: 'rgba(142, 36, 170, 0.2)', // Tím dịu
                                borderColor: '#8E24AA',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true
                            }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                backgroundColor: '#C14600',
                                titleColor: '#fff',
                                bodyColor: '#fff'
                            },
                            legend: {
                                labels: {
                                color: '#333',
                                font: { size: 14 }
                                }
                            }
                            },
                            scales: {
                            x: {
                                ticks: { color: '#333' },
                                grid: { display: false }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: { color: '#333' },
                                grid: { color: '#f0e0d6' }
                            }
                            }
                        }
                    });

                    // const ctx = document.getElementById('earningsChart').getContext('2d');

                    // const dates = data.dates;
                    // const categories = data.categories;
                    // const earnings = data.earnings;

                    // const datasets = categories.map((category, index) => ({
                    //     label: category,
                    //     data: earnings[category],
                    //     fill: true,
                    //     borderColor: `hsl(${index * 360 / categories.length}, 70%, 50%)`,
                    //     backgroundColor: `hsla(${index * 360 / categories.length}, 70%, 70%, 0.5)`,
                    //     tension: 0.1
                    // }));

                    // new Chart(ctx, {
                    //     type: 'line',
                    //     data: {
                    //         labels: dates,
                    //         datasets: datasets
                    //     },
                    //     options: {
                    //         scales: {
                    //             x: {

                    //                 stacked: true,
                    //                 title: {
                    //                     display: true,
                    //                     text: 'Date'
                    //                 }
                    //             },
                    //             y: {
                    //                 color: '#fff',
                    //                 stacked: true,
                    //                 beginAtZero: true,
                    //                 title: {
                    //                     display: true,
                    //                     text: 'Earnings'
                    //                 }
                    //             }
                    //         },
                    //         plugins: {
                    //             legend: {
                    //                 position: 'top',
                    //             },
                    //             tooltip: {
                    //                 mode: 'index',
                    //                 intersect: false,
                    //             }
                    //         }
                    //     }
                    // });
                })
                .catch(error => console.error('Error fetching data:', error));
        });
    </script>
    <script>
        function viewDetails(orderId) {
            window.location.href = 'view_order.php?orderId=' + orderId;
        }
        const chartOptions = {
            maintainAspectRatio: false,
            legend: {
                display: false,
            },
            tooltips: {
                enabled: false,
            },
            elements: {
                point: {
                    radius: 0
                },
            },
            scales: {
                xAxes: [{
                    gridLines: false,
                    scaleLabel: false,
                    ticks: {
                        display: false
                    }
                }],
                yAxes: [{
                    gridLines: false,
                    scaleLabel: false,
                    ticks: {
                        display: false,
                        suggestedMin: 0,
                        suggestedMax: 10
                    }
                }]
            }
        };

        // Biểu đồ tổng tiền kiếm được
        var ctx1 = document.getElementById('chart1').getContext('2d');
        new Chart(ctx1, {
            type: "line",
            data: {
                labels: [1, 2, 1, 3, 5, 4, 7],
                datasets: [{
                    backgroundColor: "rgba(101, 116, 205, 0.1)",
                    borderColor: "rgba(101, 116, 205, 0.8)",
                    borderWidth: 2,
                    data: [1, 2, 1, 3, 5, 4, 7],
                }],
            },
            options: chartOptions
        });

        // Biểu đồ tiền kiếm đựơc hàng tháng
        var ctx2 = document.getElementById('chart2').getContext('2d');
        new Chart(ctx2, {
            type: "line",
            data: {
                labels: [2, 3, 2, 7, 6, 4, 5],
                datasets: [{
                    backgroundColor: "rgba(253, 108, 77, 0.1)",
                    borderColor: "rgba(253, 108, 77, 0.8)",
                    borderWidth: 2,
                    data: [2, 3, 2, 7, 6, 4, 5],
                }],
            },
            options: chartOptions
        });

        // Biểu đồ tiền kiếm được hàng tuần
        var ctx3 = document.getElementById('chart3').getContext('2d');
        new Chart(ctx3, {
            type: "line",
            data: {
                labels: [3, 5, 7, 8, 6, 3, 5],
                datasets: [{
                    backgroundColor: "rgba(60, 142, 245, 0.1)",
                    borderColor: "rgba(60, 142, 245, 0.8)",
                    borderWidth: 2,
                    data: [3, 5, 7, 8, 6, 3, 5],
                }],
            },
            options: chartOptions
        });

        // Biểu đồ tổng tiền kiếm được
        var ctx4 = document.getElementById('chart4').getContext('2d');
        new Chart(ctx4, {
            type: "line",
            data: {
                labels: [3, 5, 2, 8, 7, 3, 5],
                datasets: [{
                    backgroundColor: "rgba(80, 198, 168, 0.1)",
                    borderColor: "rgba(80, 198, 168, 0.8)",
                    borderWidth: 2,
                    data: [3, 5, 2, 8, 7, 3, 5],
                }],
            },
            options: chartOptions
        });

        // Biểu đồ tổng đơn hàng
        var ctx4 = document.getElementById('chart5').getContext('2d');
        new Chart(ctx4, {
            type: "line",
            data: {
                labels: [3, 5, 2, 8, 7, 3, 5],
                datasets: [{
                    backgroundColor: "rgba(54, 162, 235, 0.1)", // Soft teal
                    borderColor: "rgba(54, 162, 235, 0.8)", // Darker teal
                    borderWidth: 2,
                    data: [3, 5, 2, 8, 7, 3, 5],
                }],
            },
            options: chartOptions
        });

        // Biểu đồ tổng đơn hàng hôm nay
        var ctx4 = document.getElementById('chart6').getContext('2d');
        new Chart(ctx4, {
            type: "line",
            data: {
                labels: [3, 5, 2, 8, 7, 3, 5],
                datasets: [{
                    backgroundColor: "rgba(255, 99, 132, 0.1)", // Soft pink
                    borderColor: "rgba(255, 99, 132, 0.8)", // Darker pink
                    borderWidth: 2,
                    data: [3, 5, 2, 8, 7, 3, 5],
                }],
            },
            options: chartOptions
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data từ PHP
            const statusCounts = <?php echo json_encode($statusCounts); ?>;

            // Chuẩn bị data
            const labels = Object.keys(statusCounts);
            const data = Object.values(statusCounts);
            
            // Tạo biểu đồ thanh
            const ctx = document.getElementById('orderStatusChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Order Statuses',
                        data: data,
                        backgroundColor: [
                            'rgba(60, 142, 245, 0.4)', // Blue
                            'rgba(101, 116, 205, 0.4)', // Purple
                            'rgba(253, 108, 77, 0.4)', // orange
                            'rgba(80, 198, 168, 0.4)', // Green
                            'rgba(255, 0, 0, 0.4)' // Light Blue
                        ],
                        borderColor: [
                            'rgba(60, 142, 245, 0.8)',
                            'rgba(101, 116, 205, 0.8)',
                            'rgba(253, 108, 77, 0.8)',
                            'rgba(80, 198, 168, 0.8)',
                            'rgbargba(255, 0, 0, 0.8)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true
                        }
                    }
                }
            });
            
        });
    </script>
    <script>
        // Fetch review data từ  PHP script
        fetch('fetch_reviews.php')
            .then(response => response.json())
            .then(data => {
                // Xử lý data cho Chart.js
                const labels = [...new Set(data.map(item => item.review_date))]; // Extract unique dates
                const ratings = [1, 2, 3, 4, 5]; // Rating categories
                const datasets = ratings.map(rating => {
                    return {
                        label: `${rating} Star`,
                        data: labels.map(date => {
                            const entry = data.find(item => item.review_date === date && item.rating == rating);
                            return entry ? entry.count : 0;
                        }),
                        borderColor: getColorForRating(rating),
                        backgroundColor: getColorForRating(rating, 0.2),
                        fill: false,
                        tension: 0.1
                    };
                });

                // Tạo biểu đồ đường
                const ctx = document.getElementById('ratingsLineChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: datasets
                    },
                    options: {
                        scales: {
                            x: {
                                beginAtZero: true
                            },
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
                                    }
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching review data:', error));

        // Hàm lấy màu trên mỗi rating
        function getColorForRating(rating, opacity = 1) {
            const colors = {
                1: 'rgba(255, 99, 132, ',
                2: 'rgba(54, 162, 235, ',
                3: 'rgba(75, 192, 192, ',
                4: 'rgba(255, 206, 86, ',
                5: 'rgba(153, 102, 255, '
            };
            return colors[rating] + opacity + ')';
        }
    </script>

</body>

</html>
