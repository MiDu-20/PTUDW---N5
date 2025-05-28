<?php
session_start();
if (!isset($_SESSION['userloggedin'])) {
    header("Location: login.php");
    exit();
}
include 'db_connection.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css' />
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css' />
    <!--Bootstrap CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>My Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding-top: 100px;
            background: #fef0e8;
        }

        .tabs {
            display: flex;
            cursor: pointer;
            justify-content: space-evenly;
            background-color: #faa79d;
            color: black;
            padding: 10px 0 15px 0;
        }

        .tab {
            padding: 10px 20px;
            border-bottom: 2px solid transparent;
            transition: all 0.3s;
            font-size: 1.2rem;
        }

        .tab:hover {
            background-color: rgba(255, 99, 132, 0.4);
        }

        .tab.active {
            border-bottom: 2px solid rgba(255, 99, 132, 5);
        }

        .tab-content {
            display: none;
            padding: 40px 60px;
            background-color: #fdd9c9;
            margin-bottom: 50px;
        }

        .tab-content.active {
            display: block;
        }

        .order {
            background-color: #fcbbb3;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(255, 99, 132, 0.2);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            border-bottom: 1px solid rgba(255, 99, 132, 0.8);
            padding-bottom: 10px;
        }

        .order-header div {
            font-weight: bold;
        }

        .order-details {
            margin-bottom: 10px;
        }

        .order-items {
            border-top: 1px solid rgba(255, 99, 132, 0.8);
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 99, 132, 0.8);
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-total {
            text-align: right;
            font-weight: bold;
            margin-top: 10px;
        }

        .cancel-btn {
            background-color: red;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .main-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container-div {
            width: 85%;
        }

        .customer-details {
            display: flex;
            justify-content: space-between;
            font-size: 1.1rem;
        }

        .customer-details strong,
        .order-items strong {
            font-weight: 600;
        }

        .order-items strong {
            padding-right: 5px;
        }

        .status-pending .status-text {
            color: #fb4a36;
            /* Orange color for pending */
        }

        .status-processing .status-text {
            color: #f39c12;
            /* Yellow color for processing */
        }

        .status-on-the-way .status-text {
            color: #3498db;
            /* Blue color for on the way */
        }

        .status-completed .status-text {
            color: #27ae60;
            /* Green color for completed */
        }

        .status-cancelled .status-text {
            color: #e74c3c;
            /* Red color for cancelled */
        }

        /* Modal Background */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        /* Modal Content */
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 5px;
            position: relative;

        }

        /* Close Button */
        .modal-close {
            position: absolute;
            color: red;
            font-size: 30px;
            font-weight: bold;
            top: 0px;
            right: 10px;
        }

        .modal-close:hover,
        .modal-close:focus {
            color: orangered;
            text-decoration: none;
            cursor: pointer;
        }

        /* Cancel Reason Textarea */
        textarea {
            width: 100%;
            height: 100px;
            margin-bottom: 20px !important;
            padding: 10px;
            border-radius: 5px;
            border: 2px solid #ccc;
            box-sizing: border-box;
        }

        /* Cancel Order Button */
        button {
            background-color: #f44336;
            /* Red */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px !important;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #c62828;
            /* Darker Red */
        }

        .star-rating {
            direction: rtl;
            display: inline-block;
            font-size: 2rem;
            unicode-bidi: bidi-override;
        }

        .review-btn {
            background: #27ae60 !important;
            transition: background 0.3s ease;
        }

        .review-btn:hover {
            background: green !important;
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            color: #ccc;
            /* Gray color for unselected stars */
            cursor: pointer;
        }

        .star-rating label:hover,
        .star-rating label:hover~label {
            color: #ffcc00;
            /* Yellow color for hovered stars */
        }

        .star-rating input[type="radio"]:checked~label {
            color: #ffcc00;
            /* Yellow color for selected stars */
        }

        .review-section strong {
            font-weight: 600;
            font-size: 1.1rem;
            text-align: left;
        }

        .review-section span {
            font-size: 1.1rem;
        }

        .review {
            display: flex;
            justify-content: space-between;

        }

        @media screen and (max-width: 900px) {
            .tabs {
                display: none;
            }

            .tab-content {
                display: none;
                padding: 0px;
                background-color: none !important;
                background: none !important;
                margin-bottom: 50px;
            }

            .customer-details {
                display: flex;
                justify-content: flex-start !important;
                gap: 20px;
                font-size: 1rem;
            }

            .order-header {
                font-size: 1rem !important;
            }

            .review {
                display: flex;
                justify-content: flex-start !important;
                gap: 20px;
                font-size: 1rem;
            }
        }

        #reviewModal .review-btn {
            margin-top: 0px !important;
            width: 100%;
        }
    </style>
</head>

<body>
    <?php
    include_once("nav-logged.php"); 
    // --vấn đề: include thanh điều hướng khi người dùng đã đăng nhập
    ?>
    <div class="main-container">
        <div class="container-div">
            <div class="tabs">
                <div class="tab active" data-status="All">Tất cả</div> 
                <!-- --vấn đề: Các tab để lọc trạng thái đơn hàng -->
                <div class="tab" data-status="Pending">Đang chờ</div>
                <div class="tab" data-status="Processing">Đang xử lý</div>
                <div class="tab" data-status="On the way">Đang giao</div>
                <div class="tab" data-status="Completed">Hoàn thành</div>
                <div class="tab" data-status="Cancelled">Đã hủy</div>
            </div>
            <div id="orders">
                <!-- --vấn đề: Các vùng nội dung tương ứng từng tab, mặc định chỉ tab 'Tất cả' hiển thị -->
                <div class="tab-content active" id="all-orders"></div>
                <div class="tab-content" id="pending-orders"></div>
                <div class="tab-content" id="processing-orders"></div>
                <div class="tab-content" id="on-the-way-orders"></div>
                <div class="tab-content" id="completed-orders"></div>
                <div class="tab-content" id="cancelled-orders"></div>
            </div>
        </div>
    </div>

    <!-- Modal lý do hủy đơn -->
    <div id="cancelModal" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <h2>Hủy đơn hàng</h2>
            <textarea id="cancelReason" placeholder="Nhập lý do hủy đơn..."></textarea>
            <button id="cancelOrderBtn">Hủy đơn</button>
        </div>
    </div>

    <!-- Modal đánh giá sản phẩm -->
    <div id="reviewModal" class="modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <h2>Gửi đánh giá của bạn</h2>
            <form id="reviewForm" action="submit_reviews.php" method="POST">

                <input type="hidden" name="email" value="<?php echo $userEmail; ?>"> 
                <!-- --vấn đề: trường ẩn chứa email người dùng, dùng để gửi thông tin đánh giá -->
                <input type="hidden" id="reviewOrderId" name="orderId"> 
                <!-- --vấn đề: trường ẩn chứa mã đơn hàng đang đánh giá -->

                <!-- Hiển thị sao đánh giá -->
                <div class="star-rating">
                    <input type="radio" id="star5" name="rating" value="5" />
                    <label for="star5" title="5 sao">&#9733;</label>
                    <input type="radio" id="star4" name="rating" value="4" />
                    <label for="star4" title="4 sao">&#9733;</label>
                    <input type="radio" id="star3" name="rating" value="3" />
                    <label for="star3" title="3 sao">&#9733;</label>
                    <input type="radio" id="star2" name="rating" value="2" />
                    <label for="star2" title="2 sao">&#9733;</label>
                    <input type="radio" id="star1" name="rating" value="1" />
                    <label for="star1" title="1 sao">&#9733;</label>
                </div>
                <br>
                <label for="reviewText">Nội dung đánh giá:</label>
                <textarea id="reviewText" name="reviewText" rows="4" cols="50"></textarea>
                <br>

                <br>
                <button type="submit" id="submitReviewBtn" class="review-btn">Gửi đánh giá</button>
            </form>
        </div>
    </div>
   
    <?php
    include_once ('footer.html');
    // --vấn đề: thêm footer trang
    ?>

    <!-- Thư viện Bootstrap JS và jQuery -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/js/bootstrap.min.js'></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        // --vấn đề: Khi trang tải xong, gọi hàm load_cart_item_number để cập nhật số lượng sản phẩm trong giỏ hàng trên giao diện
        $(document).ready(function() {
            console.log('Trang đã sẵn sàng, gọi hàm load_cart_item_number.');
            load_cart_item_number();

            function load_cart_item_number() {
                $.ajax({
                    url: 'action.php', // file xử lý lấy số lượng giỏ hàng
                    method: 'get',
                    data: {
                        cartItem: "cart_item" // gửi tham số yêu cầu lấy số lượng sản phẩm giỏ hàng
                    },
                    success: function(response) {
                        $("#cart-item").html(response); // hiển thị số lượng sản phẩm trong giỏ hàng lên thẻ có id="cart-item"
                    }
                });
            }
        });
    </script>

    <script>
        // --vấn đề: lắng nghe sự kiện chọn sao đánh giá
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-rating input[type="radio"]');

            stars.forEach(star => {
                star.addEventListener('change', function() {
                    const rating = this.value;
                    console.log('Đánh giá được chọn:', rating);
                    // Có thể gửi rating này lên server qua AJAX hoặc form submit
                });
            });
        });
    </script>

    <script>

        // --ánh xạ phương thức thanh toán sang tiếng Việt
        const paymentModeMap = {
            'Cash': 'Tiền mặt',
            'Card': 'Thẻ',
            'Takeaway': 'Mang đi'
        };


        // --vấn đề: xử lý sự kiện click chuyển tab trạng thái đơn hàng
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Xóa lớp active trên tab hiện tại
                document.querySelector('.tab.active').classList.remove('active');
                this.classList.add('active'); // Thêm active vào tab vừa click

                const status = this.getAttribute('data-status'); // lấy trạng thái đơn hàng của tab
                // Ẩn nội dung tab đang active
                document.querySelector('.tab-content.active').classList.remove('active');
                // Hiển thị nội dung tab theo trạng thái đã chọn, đổi chuỗi sang dạng id
                document.getElementById(`${status.toLowerCase().replace(/ /g, '-')}-orders`).classList.add('active');

                fetchOrders(status); // gọi hàm lấy dữ liệu đơn hàng theo trạng thái
            });
        });

        // --vấn đề: hàm lấy dữ liệu đơn hàng từ server theo trạng thái
        function fetchOrders(status) {
            fetch(`fetch_orders.php?status=${status}`) // gửi trạng thái lấy đơn hàng
                .then(response => response.json())
                .then(data => {
                    // Sắp xếp đơn hàng theo ngày mới nhất trước
                    data.sort((a, b) => new Date(b.order_date) - new Date(a.order_date)); 

                    // Lấy thẻ chứa danh sách đơn hàng theo trạng thái
                    const ordersContainer = document.getElementById(`${status.toLowerCase().replace(/ /g, '-')}-orders`);
                    // Hiển thị các đơn hàng lên giao diện
                    ordersContainer.innerHTML = data.map(order => `
                        <div class="order container" style="padding: 20px 30px;">
                            <div class="order-header" style="font-size: 1.3rem;">
                                <div>Mã đơn hàng: #${order.order_id}</div>
                                <div class="status ${getStatusClass(order.order_status)}">Trạng thái: <span class="status-text">${order.order_status}</span></div>
                            </div>
                            <div class="order-details">
                                <div class="customer-details">
                                    <div><p><strong>Họ tên: </strong></p></div>
                                    <div><p>${order.firstName} ${order.lastName}</p></div>
                                </div>
                                <div class="customer-details">
                                    <div><p><strong>Địa chỉ: </strong></p></div>
                                    <div><p>${order.address}</p></div>
                                </div>
                                <div class="customer-details">
                                    <div><p><strong>Liên hệ: </strong></p></div>
                                    <div><p>${order.phone}</p></div>
                                </div>
                                <div class="customer-details">
                                    <div><p><strong>Phương thức thanh toán: </strong></p></div>
                                    <div><p>${paymentModeMap[order.pmode] || order.pmode}</p></div>
                                </div>
                                <div class="customer-details">
                                    <div><p><strong>Ngày đặt hàng: </strong></p></div>
                                    <div><p>${new Date(order.order_date).toLocaleString()}</p></div>
                                </div>
                                <div class="customer-details">
                                    <div><p><strong>Ghi chú đơn hàng: </strong></p></div>
                                    <div><p>${order.note || 'Không có'}</p></div>
                                </div>
                            </div>
                            <div class="order-items" style="font-size: 1.1rem;">
                                ${order.items.map(item => `
                                    <div class="order-item">
                                        <div>${item.itemName} (x${item.quantity})</div>
                                        <div>${item.total_price}</div>
                                    </div>
                                `).join('')}
                                 <div class="order-total">Tổng tiền: ${order.grand_total}</div>
                        ${order.order_status === 'Cancelled' ? `
                        <div class="review mt-3">
                        <div><p><strong>Lý do hủy: </strong></p></div>
                        <div><p>${order.cancel_reason}</p></div>
                        </div>` : ''}
                    </div>
                   ${order.order_status !== 'Completed' && order.order_status !== 'Cancelled' ? `<button class="cancel-btn" onclick="openCancelModal(${order.order_id})">Hủy đơn</button>` : ''}
                    ${(order.order_status === 'Completed' || order.order_status === 'Cancelled') && !order.review_text ? `
                        <button class="review-btn" onclick="openReviewModal(${order.order_id})">Viết đánh giá</button>
                    ` : ''}
                    ${(order.order_status === 'Completed' || order.order_status === 'Cancelled') && order.review_text ? `
                        <div class="review-section">
                         <div class="review">
                            <div><p><strong>Đánh giá của bạn: </strong></p></div>
                            <div><p><span>${order.review_text}</span></p></div>
                         </div>
                            ${order.response ? `
                            <div class="review">
                              <div><p><strong>Phản hồi: </strong></p></div>
                              <div><p><span>${order.response}</span></p></div>
                            </div>` : ''}
                        </div>
                    ` : ''}
                </div>
            `).join('');
                })
                .catch(error => console.error('Lỗi khi lấy dữ liệu đơn hàng:', error));
        }

        

        // --vấn đề: hàm trả về tên lớp CSS tương ứng với trạng thái đơn hàng để style màu sắc phù hợp
        function getStatusClass(status) {
            switch (status) {
                case 'Pending':
                    return 'status-pending'; // màu cho đơn chờ
                case 'Processing':
                    return 'status-processing'; // màu cho đơn đang xử lý
                case 'On the way':
                    return 'status-on-the-way'; // màu cho đơn đang giao
                case 'Completed':
                    return 'status-completed'; // màu cho đơn hoàn thành
                case 'Cancelled':
                    return 'status-cancelled'; // màu cho đơn đã hủy
                default:
                    return '';
            }
        }

        // --vấn đề: tự động load danh sách đơn hàng trạng thái "Tất cả" khi vào trang
        fetchOrders('All');
    </script>
    


    <script>
        // --vấn đề: mở modal hủy đơn khi nhấn nút Hủy
        function openCancelModal(orderId) {
            document.getElementById("cancelModal").setAttribute("data-order-id", orderId);
            document.getElementById("cancelModal").style.display = "block"; // hiển thị modal hủy đơn
        }

        // --vấn đề: đóng modal khi nhấn nút đóng (dấu x)
        document.querySelector(".modal-close").onclick = function() {
            document.getElementById("cancelModal").style.display = "none";
        };

        // --vấn đề: xử lý nút Hủy đơn trong modal
        document.getElementById("cancelOrderBtn").onclick = function() {
            var cancelReason = document.getElementById("cancelReason").value;
            var orderId = document.getElementById("cancelModal").getAttribute("data-order-id");

            if (cancelReason.trim() === "") {
                alert("Vui lòng nhập lý do hủy đơn.");
                return;
            }

            // Gửi yêu cầu hủy đơn lên server bằng AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "cancel_order.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert("Đơn hàng đã được hủy thành công.");
                    location.reload(); // tải lại trang để cập nhật trạng thái đơn hàng
                } else {
                    alert("Có lỗi xảy ra. Vui lòng thử lại.");
                }
            };
            xhr.send("orderId=" + encodeURIComponent(orderId) + "&cancelReason=" + encodeURIComponent(cancelReason));
        };

        // --vấn đề: mở modal đánh giá khi nhấn nút Viết đánh giá
        function openReviewModal(orderId) {
            document.getElementById("reviewModal").style.display = "block";
            document.getElementById("reviewOrderId").value = orderId; // gán mã đơn hàng vào form đánh giá
        }

        // --vấn đề: đóng modal đánh giá khi nhấn dấu x
        document.querySelectorAll(".modal-close")[1].onclick = function() {
            document.getElementById("reviewModal").style.display = "none";
        };

        // --vấn đề: đóng modal khi click ngoài vùng modal
        window.onclick = function(event) {
            var cancelModal = document.getElementById("cancelModal");
            var reviewModal = document.getElementById("reviewModal");
            if (event.target == cancelModal) {
                cancelModal.style.display = "none";
            }
            if (event.target == reviewModal) {
                reviewModal.style.display = "none";
            }
        };
    </script>

</body>
