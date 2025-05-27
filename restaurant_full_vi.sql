

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restaurant`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `itemName` varchar(255) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `image` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `catName` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `total_price` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `itemName`, `price`, `image`, `quantity`, `catName`, `email`, `total_price`) VALUES
(1, 'Khoai tây chiên', 760, 'fries.jpg', 1, 'Món khai vị', 'asna@gmail.com', '760'),
(2, 'Pizza Gà Nướng BBQ', 1000, 'bbq-pizza.jpg', 1, 'Pizza', 'zidnan@gmail.com', '1000'),
(3, 'Mocktail Dâu Tây', 550, 'strawberry-drink.png', 2, 'Đồ uống', 'zidnan@gmail.com', '1100');

-- --------------------------------------------------------

--
-- Table structure for table `menucategory`
--

CREATE TABLE `menucategory` (
  `catId` int(11) NOT NULL,
  `catName` varchar(255) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menucategory`
--

INSERT INTO `menucategory` (`catId`, `catName`, `dateCreated`) VALUES
(1, 'Món khai vị', '2024-07-26 12:31:55'),
(2, 'Burger', '2024-07-26 12:31:55'),
(3, 'Pizza', '2024-07-26 12:33:18'),
(4, 'Đồ uống', '2024-07-26 12:33:18');

-- --------------------------------------------------------

--
-- Table structure for table `menuitem`
--

CREATE TABLE `menuitem` (
  `itemId` int(11) NOT NULL,
  `itemName` varchar(255) NOT NULL,
  `catName` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `status` enum('Available','Unavailable','','') NOT NULL DEFAULT 'Available',
  `description` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedDate` datetime NOT NULL,
  `is_popular` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menuitem`
--

INSERT INTO `menuitem` (`itemId`, `itemName`, `catName`, `price`, `status`, `description`, `image`, `dateCreated`, `updatedDate`, `is_popular`) VALUES
(3, 'Khoai tây chiên', 'Món khai vị', '760', 'Unavailable', ' Crispy, golden-brown fries seasoned to perfection, served with your choice of dipping sauces.', 'fries.jpg', '2024-07-26 09:09:35', '2024-07-26 14:39:35', 0),
(5, 'Pizza Rau Củ Thập Cẩm', 'Pizza', '800', 'Available', 'Pizza rau củ với nhiều loại rau theo mùa, sốt cà chua và phô mai béo ngậy.', 'veggie-pizza.jpg', '2024-07-26 09:10:36', '2024-07-26 14:40:36', 1),
(6, 'Pizza Tôm', 'Pizza', '1200', 'Available', 'Pizza phủ tôm tươi tẩm gia vị, sốt cà chua và hỗn hợp phô mai tan chảy.', 'prawn-piza.jpg', '2024-07-26 09:12:03', '2024-07-26 14:42:03', 0),
(7, 'Pizza Phô Mai', 'Pizza', '800', 'Unavailable', 'Pizza phô mai cổ điển với lớp phô mai mozzarella dày và sốt cà chua đậm vị.', 'cheese-pizza.jpg', '2024-07-26 09:13:09', '2024-07-26 14:43:09', 1),
(8, 'Pizza Gà Nướng BBQ', 'Pizza', '1000', 'Available', 'Pizza gà nướng sốt BBQ thơm lừng với miếng gà mềm và đậm đà.', 'bbq-pizza.jpg', '2024-07-26 09:13:45', '2024-07-26 14:43:45', 0),
(9, 'Burger Gà Cay Lửa', 'Burger', '2100', 'Available', 'Ức gà chiên giòn, xà lách và phô mai trắng, rưới sốt cay đặc trưng.', 'firebird-burger.jpeg', '2024-08-03 14:37:51', '2024-08-03 16:37:09', 0),
(10, 'Burger Lai Đặc Biệt', 'Burger', '1800', 'Available', 'Gà chiên giòn, phô mai, thịt bò nướng và thịt xông khói cùng sốt đặc trưng.', 'hybrid-burger.jpeg', '2024-08-03 15:07:32', '2024-08-03 17:07:01', 1),
(11, 'Burger Gà Nướng BBQ', 'Burger', '1900', 'Available', 'Thịt bò nướng, xà lách, hành tím, phô mai trắng và sốt BBQ.', 'bbq-burger.jpeg', '2024-08-03 15:09:50', '2024-08-03 17:07:34', 1),
(12, 'Burger Gà Giòn', 'Burger', '1900', 'Unavailable', 'Gà chiên giòn, phô mai cheddar, xà lách và sốt đặc trưng.', 'crispy-burger.jpeg', '2024-08-03 15:21:27', '2024-08-03 17:20:42', 0),
(13, 'Mocktail Dâu Tây', 'Đồ uống', '550', 'Available', 'Dâu tây chín kết hợp với chanh và vị ngọt thanh mát.', 'strawberry-drink.png', '2024-08-03 14:18:11', '2024-08-03 16:09:51', 0),
(14, 'Cam Sủi Bọt', 'Đồ uống', '350', 'Available', 'Nước cam tươi pha với soda nhẹ tạo cảm giác sảng khoái.', 'orange-drink.png', '2024-08-03 14:24:49', '2024-08-03 16:24:05', 1),
(15, 'Mojito Thanh Long', 'Đồ uống', '760', 'Available', 'Thanh long, bạc hà và chanh hòa quyện tạo vị nhiệt đới tươi mát.', 'Dragon-fruit-drink.png', '2024-08-03 14:25:57', '2024-08-03 16:24:54', 0),
(16, 'Sinh Tố Dưa Hấu', 'Đồ uống', '400', 'Available', 'Dưa hấu tươi xay nhuyễn cùng một chút chanh mát lạnh.', 'watermelon-drink.png', '2024-08-03 14:26:56', '2024-08-03 16:26:00', 0),
(33, 'Bánh Mì Bơ Tỏi', 'Món khai vị', '350', 'Available', 'Bánh mì nướng giòn với bơ tỏi và rau thơm.', 'garlic-bread.avif', '2024-08-08 16:37:43', '2024-08-08 22:07:43', 1),
(34, 'Cánh Gà Chiên', 'Món khai vị', '480', 'Available', 'Cánh gà giòn rụm, tẩm sốt đậm đà, mềm mọng bên trong.', 'chicken-wing.avif', '2024-08-08 16:43:59', '2024-08-08 22:13:59', 0),
(35, 'Bánh Samosa', 'Món khai vị', '120', 'Available', 'Bánh samosa giòn nhân khoai tây và đậu Hà Lan tẩm gia vị.', 'samosa.avif', '2024-08-08 16:45:44', '2024-08-08 22:15:44', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `address` varchar(200) NOT NULL,
  `pmode` enum('Tiền mặt','Thẻ','Mang đi','') NOT NULL DEFAULT 'Tiền mặt',
  `payment_status` enum('Đang chờ xử lý','Thành công','Từ chối','') NOT NULL DEFAULT 'Đang chờ xử lý',
  `sub_total` decimal(10,2) NOT NULL,
  `grand_total` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_status` enum('Đang chờ xử lý','Đã hoàn tất','Đã hủy','Processing','Đang giao hàng') NOT NULL DEFAULT 'Đang chờ xử lý',
  `cancel_reason` varchar(255) DEFAULT NULL,
  `note` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `email`, `firstName`, `lastName`, `phone`, `address`, `pmode`, `payment_status`, `sub_total`, `grand_total`, `order_date`, `order_status`, `cancel_reason`, `note`) VALUES
(54, 'preethi@gmail.com', 'Preethi', 'Suresh', '9999999999', 'Galle Road', 'Tiền mặt', 'Đang chờ xử lý', 1910.00, 2040.00, '2024-08-11 18:00:04', 'Processing', '', 'Thêm phô mai'),
(55, 'zidnan@gmail.com', 'Zidnan', 'Ahamad', '2222222222', 'Kolonnawa', 'Tiền mặt', 'Đang chờ xử lý', 7420.00, 7550.00, '2024-08-10 18:02:26', 'Đang giao hàng', '', 'Làm burger cay hơn'),
(56, 'zidnan@gmail.com', 'Mohamed', 'Muhadh', '0000000000', 'Kolonnawa', 'Mang đi', 'Thành công', 1150.00, 1150.00, '2024-08-11 18:04:16', 'Đã hoàn tất', '', ''),
(57, 'jhon@gmail.com', 'Jhon', 'Paul', '7777777777', 'Colombo 15', 'Mang đi', 'Thành công', 5720.00, 5720.00, '2024-08-08 18:05:26', 'Đã hoàn tất', '', ''),
(58, 'zidnan@gmail.com', 'Zidnan', 'Ahamad', '4444444444', 'Colombo 12', 'Mang đi', 'Đang chờ xử lý', 2700.00, 2700.00, '2024-08-10 20:12:14', 'Đã hủy', 'Thời gian chờ quá lâu.', '');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `itemName` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `itemName`, `image`, `quantity`, `price`, `total_price`) VALUES
(122, 54, 'Bánh Mì Bơ Tỏi', 'garlic-bread.avif', 1, 350, 350.00),
(123, 54, 'Khoai tây chiên', 'fries.jpg', 1, 760, 760.00),
(124, 54, 'Pizza Phô Mai', 'cheese-pizza.jpg', 1, 800, 800.00),
(125, 55, 'Mojito Thanh Long', 'Dragon-fruit-drink.png', 1, 760, 760.00),
(126, 55, 'Burger Gà Nướng BBQ', 'bbq-burger.jpeg', 3, 1900, 5700.00),
(127, 55, 'Cánh Gà Chiên', 'chicken-wing.avif', 2, 480, 960.00),
(128, 56, 'Bánh Mì Bơ Tỏi', 'garlic-bread.avif', 1, 350, 350.00),
(129, 56, 'Pizza Phô Mai', 'cheese-pizza.jpg', 1, 800, 800.00),
(130, 57, 'Khoai tây chiên', 'fries.jpg', 2, 760, 1520.00),
(131, 57, 'Burger Gà Cay Lửa', 'firebird-burger.jpeg', 2, 2100, 4200.00),
(132, 58, 'Bánh Mì Bơ Tỏi', 'garlic-bread.avif', 3, 350, 1050.00),
(133, 58, 'Mocktail Dâu Tây', 'strawberry-drink.png', 3, 550, 1650.00);

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `email` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact` varchar(10) NOT NULL,
  `noOfGuests` int(50) NOT NULL,
  `reservedTime` time NOT NULL,
  `reservedDate` date NOT NULL,
  `reservedAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Đang chờ xử lý','Đang xử lý','Đã hoàn tất','Đã hủy') NOT NULL DEFAULT 'Đang chờ xử lý',
  `reservation_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`email`, `name`, `contact`, `noOfGuests`, `reservedTime`, `reservedDate`, `reservedAt`, `status`, `reservation_id`) VALUES
('asna@gmail.com', 'Asna Assalam', '0000000000', 6, '12:00:00', '2024-07-31', '2024-07-29 15:35:05', 'Đã hoàn tất', 1),
('zidnan@gmail.com', 'Zidnan', '1111111111', 5, '10:00:07', '2024-08-11', '2024-08-10 18:14:55', 'Đang chờ xử lý', 2),
('preethi@gmail.com', 'Preethi Suresh', '5555555', 2, '06:30:59', '2024-08-10', '2024-08-03 18:15:54', 'Đang xử lý', 3),
('jhon@gmail.com', 'Jhon Paul', '334455', 9, '20:45:59', '2024-08-09', '2024-08-05 18:16:38', 'Đã hủy', 4);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `rating` int(11) NOT NULL,
  `review_text` text DEFAULT NULL,
  `review_date` date DEFAULT current_timestamp(),
  `status` enum('approved','pending','rejected') DEFAULT 'pending',
  `response` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `email`, `order_id`, `rating`, `review_text`, `review_date`, `status`, `response`) VALUES
(1, 'zidnan@gmail.com', 56, 5, 'Món ăn rất ngon! Tôi chắc chắn sẽ đặt lại lần nữa!', '2024-08-10', 'approved', 'Cảm ơn bạn đã phản hồi.'),
(2, 'jhon@gmail.com', 57, 3, '\"Burger ngon, nhưng đến nơi hơi nguội. Khoai tây cũng bị mềm. Hy vọng lần sau sẽ tốt hơn.\"', '2024-08-11', 'pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(15) DEFAULT NULL,
  `role` enum('quản trị cao cấp','quản trị viên','nhân viên giao hàng','phục vụ') NOT NULL,
  `password` varchar(255) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `profile_image` varchar(255) NOT NULL DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `firstName`, `lastName`, `email`, `contact`, `role`, `password`, `createdAt`, `updatedAt`, `profile_image`) VALUES
(2, 'Akshaya', 'Rohit', 'ak@gmail.com', '8877669955', 'quản trị cao cấp', 'AkRohit', '2024-08-02 19:45:36', '2024-08-10 15:30:48', 'user-girl.png'),
(3, 'Ravi', 'Kumar', 'ravi@gmail.com', '9876543210', 'nhân viên giao hàng', 'ravi123', '2024-08-02 19:46:10', '2024-08-02 19:46:10', 'default.jpg'),
(5, 'Demo', 'Admin', 'admin@gmail.com', '0000000000', 'quản trị viên', 'admin2024', '2024-08-04 06:51:20', '2024-08-04 06:51:38', 'default.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `email` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `contact` varchar(10) NOT NULL,
  `password` varchar(20) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT current_timestamp(),
  `profile_image` varchar(255) NOT NULL DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`email`, `firstName`, `lastName`, `contact`, `password`, `dateCreated`, `profile_image`) VALUES
('asna@gmail.com', 'Asna', 'Assalam', '3333333333', 'AsnaA', '2024-07-26 12:50:46', 'user-girl.png'),
('jhon@gmail.com', 'Jhon', 'Paul', '4444444444', 'JhonP', '2024-08-10 15:37:56', 'default.jpg'),
('preethi@gmail.com', 'Preethi', 'Suresh', '2222222222', 'Preethi123', '2024-08-10 15:36:50', 'default.jpg'),
('zidnan@gmail.com', 'Zidnan', 'Ahamad', '1111111111', 'Zidnan123', '2024-07-30 12:45:21', 'user-boy.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menucategory`
--
ALTER TABLE `menucategory`
  ADD PRIMARY KEY (`catId`);

--
-- Indexes for table `menuitem`
--
ALTER TABLE `menuitem`
  ADD PRIMARY KEY (`itemId`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `itemId` (`itemName`) USING BTREE;

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `email` (`email`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `menucategory`
--
ALTER TABLE `menucategory`
  MODIFY `catId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `menuitem`
--
ALTER TABLE `menuitem`
  MODIFY `itemId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
