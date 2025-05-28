CREATE TABLE cart (
  id int(11) NOT NULL,
  itemName varchar(255) NOT NULL,
  price decimal(10,0) NOT NULL,
  image varchar(255) NOT NULL,
  quantity int(11) NOT NULL,
  catName varchar(255) NOT NULL,
  email varchar(255) NOT NULL,
  total_price varchar(255) NOT NULL
); 

INSERT INTO cart (id, itemName, price, image, quantity, catName, email, total_price) VALUES
(1, 'Khoai tây chiên', 760, 'fries.jpg', 1, 'Món khai vị', 'asna@gmail.com', '760'),
(2, 'Pizza Gà Nướng BBQ', 1000, 'bbq-pizza.jpg', 1, 'Pizza', 'zidnan@gmail.com', '1000'),
(3, 'Mocktail Dâu Tây', 550, 'strawberry-drink.png', 2, 'Đồ uống', 'zidnan@gmail.com', '1100');



CREATE TABLE menucategory (
  catId int(11) NOT NULL,
  catName varchar(255) NOT NULL,
  dateCreated timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO menucategory (catId, catName, dateCreated) VALUES
(1, 'Món khai vị', '2024-07-26 12:31:55'),
(2, 'Burger', '2024-07-26 12:31:55'),
(3, 'Pizza', '2024-07-26 12:33:18'),
(4, 'Đồ uống', '2024-07-26 12:33:18');



CREATE TABLE menuitem (
  itemId int(11) NOT NULL,
  itemName varchar(255) NOT NULL,
  catName varchar(255) NOT NULL,
  price varchar(255) NOT NULL,
  status enum('Available','Unavailable') NOT NULL DEFAULT 'Available',
  description varchar(255) NOT NULL,
  image varchar(255) NOT NULL,
  dateCreated timestamp NOT NULL DEFAULT current_timestamp(),
  updatedDate datetime NOT NULL,
  is_popular tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO menuitem (itemId, itemName, catName, price, status, description, image, dateCreated, updatedDate, is_popular) VALUES
(3, 'Khoai tây chiên', 'Món khai vị', '760', 'Unavailable', 'Khoai tây chiên vàng giòn rụm, ăn kèm với nước chấm bạn chọn.', 'fries.jpg', '2024-07-26 09:09:35', '2024-07-26 14:39:35', 0),
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


CREATE TABLE orders (
  order_id int(11) NOT NULL,
  email varchar(255) NOT NULL,
  firstName varchar(255) NOT NULL,
  lastName varchar(255) NOT NULL,
  phone varchar(10) NOT NULL,
  address varchar(200) NOT NULL,
  pmode enum('Cash','Card','Takeaway','') NOT NULL DEFAULT 'Cash',
  payment_status enum('Pending','Successful','Rejected','') NOT NULL DEFAULT 'Pending',
  sub_total decimal(10,2) NOT NULL,
  grand_total decimal(10,2) NOT NULL,
  order_date timestamp NOT NULL DEFAULT current_timestamp(),
  order_status enum('Pending','Completed','Cancelled','Processing','On the way') NOT NULL DEFAULT 'Pending',
  cancel_reason varchar(255) DEFAULT NULL,
  note varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO orders (order_id, email, firstName, lastName, phone, address, pmode, payment_status, sub_total, grand_total, order_date, order_status, cancel_reason, note) VALUES
(54, 'hien.nguyen@example.com', 'Hiền', 'Nguyễn', '0911222333', '12 Trần Phú, Q.5, TP.HCM', 'Cash', 'Pending', 191000.00, 204000.00, '2024-08-11 18:00:04', 'Processing', NULL, 'Thêm phô mai'),
(55, 'hoang.vo@example.com', 'Hoàng', 'Võ', '0944555666', '123 Nguyễn Oanh, Gò Vấp, TP.HCM', 'Cash', 'Pending', 742000.00, 755000.00, '2024-08-10 18:02:26', 'On the way', NULL, 'Làm burger cay hơn'),
(56, 'hoang.vo@example.com', 'Hoàng', 'Võ', '0944555666', '123 Nguyễn Oanh, Gò Vấp, TP.HCM', 'Takeaway', 'Successful', 115000.00, 115000.00, '2024-08-11 18:04:16', 'Completed', NULL, 'Đã thanh toán tại quầy'),
(57, 'bao.tran@example.com', 'Bảo', 'Trần', '0922333444', '88 Lê Lợi, Q.1, TP.HCM', 'Takeaway', 'Successful', 572000.00, 572000.00, '2024-08-08 18:05:26', 'Completed', NULL, 'Khách đến lấy trực tiếp'),
(58, 'hoang.vo@example.com', 'Hoàng', 'Võ', '0944555666', '123 Nguyễn Oanh, Gò Vấp, TP.HCM', 'Takeaway', 'Pending', 270000.00, 270000.00, '2024-08-10 20:12:14', 'Cancelled', 'Chờ quá lâu', 'Thêm phô mai');



CREATE TABLE order_items (
  id int(11) NOT NULL,
  order_id int(11) NOT NULL,
  itemName varchar(255) NOT NULL,
  image varchar(255) NOT NULL,
  quantity int(11) NOT NULL,
  price decimal(10,0) NOT NULL,
  total_price decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO order_items (id, order_id, itemName, image, quantity, price, total_price) VALUES
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

CREATE TABLE reservations (
  email varchar(255) NOT NULL,
  name varchar(255) NOT NULL,
  contact varchar(10) NOT NULL,
  noOfGuests int(50) NOT NULL,
  reservedTime time NOT NULL,
  reservedDate date NOT NULL,
  reservedAt timestamp NOT NULL DEFAULT current_timestamp(),
  status enum('Pending','On Process','Completed','Cancelled') NOT NULL DEFAULT 'Pending',
  reservation_id int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO reservations (email, name, contact, noOfGuests, reservedTime, reservedDate, reservedAt, status, reservation_id) VALUES
('hien.nguyen@example.com', 'Nguyễn Hiền', '0911222333', 6, '12:00:00', '2024-07-31', '2024-07-29 15:35:05', 'Completed', 1),
('hoang.vo@example.com', 'Võ Hoàng', '0944555666', 5, '10:00:07', '2024-08-11', '2024-08-10 18:14:55', 'Pending', 2),
('ngoc.pham@example.com', 'Phạm Ngọc', '0933444555', 2, '06:30:59', '2024-08-10', '2024-08-03 18:15:54', 'On Process', 3),
('bao.tran@example.com', 'Trần Bảo', '0922333444', 9, '20:45:59', '2024-08-09', '2024-08-05 18:16:38', 'Cancelled', 4);



CREATE TABLE reviews (
  review_id int(11) NOT NULL,
  email varchar(255) NOT NULL,
  order_id int(11) DEFAULT NULL,
  rating int(11) NOT NULL,
  review_text text DEFAULT NULL,
  review_date date DEFAULT current_timestamp(),
  status enum('approved','pending','rejected') DEFAULT 'pending',
  response text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO reviews (review_id, email, order_id, rating, review_text, review_date, status, response) VALUES
(1, 'hoang.vo@example.com', 56, 5, 'Món ăn rất ngon! Tôi chắc chắn sẽ đặt lại lần nữa!', '2024-08-10', 'approved', 'Cảm ơn bạn đã phản hồi.'),
(2, 'bao.tran@example.com', 57, 3, 'Burger ngon, nhưng đến nơi hơi nguội. Khoai tây cũng bị mềm. Hy vọng lần sau sẽ tốt hơn.', '2024-08-11', 'pending', NULL),
(3, 'hien.nguyen@example.com', 54, 4, 'Dịch vụ nhanh, món ăn ổn định. Giao hàng đúng giờ.', '2024-08-12', 'approved', 'Cảm ơn bạn, rất mong phục vụ bạn lần sau!'),
(4, 'ngoc.pham@example.com', 55, 2, 'Phục vụ tốt nhưng món ăn chưa được nóng lắm.', '2024-08-12', 'pending', NULL),
(5, 'hoang.vo@example.com', 58, 1, 'Tôi đã hủy đơn vì chờ quá lâu. Mong cải thiện thời gian giao hàng.', '2024-08-13', 'approved', 'Chúng tôi xin lỗi về trải nghiệm không tốt này.');



CREATE TABLE staff (
  id int(11) NOT NULL,
  firstName varchar(50) NOT NULL,
  lastName varchar(50) NOT NULL,
  email varchar(100) NOT NULL,
  contact varchar(15) DEFAULT NULL,
  role enum('superadmin','admin','delivery boy','waiter') NOT NULL,
  password varchar(255) NOT NULL,
  createdAt timestamp NOT NULL DEFAULT current_timestamp(),
  updatedAt timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  profile_image varchar(255) NOT NULL DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO staff (id, firstName, lastName, email, contact, role, password, createdAt, updatedAt, profile_image) VALUES
(2, 'Mỹ Linh', 'Đỗ Trần', 'my.linh@example.com', '0901002001', 'superadmin', 'matkhau1', '2024-08-02 19:45:36', '2024-08-10 15:30:48', 'user-girl.png'),
(3, 'Phương An', 'Lê', 'phuong.an@example.com', '0902003002', 'admin', 'matkhau2','2024-08-02 19:46:10', '2024-08-02 19:46:10', 'default.jpg'),
(4, 'Minh Dũng', 'Trần', 'minh.dung@example.com', '0903004003', 'delivery boy', 'matkhau3', '2024-08-02 19:46:10', '2024-08-02 19:46:10', 'default.jpg'),
(5, 'Gia Phúc', 'Đào', 'gia.phuc@example.com', '0904005004', 'waiter', 'matkhau4', '2024-08-04 06:51:20', '2024-08-04 06:51:38', 'default.jpg'),
(6, 'Khách Huyền', 'Trịnh', 'khach.huyen@example.com', '0905006005', 'admin', 'matkhau5', '2024-08-04 06:51:20', '2024-08-04 06:51:38', 'default.jpg');



CREATE TABLE users (
  email varchar(255) NOT NULL,
  firstName varchar(255) NOT NULL,
  lastName varchar(255) NOT NULL,
  contact varchar(10) NOT NULL,
  password varchar(20) NOT NULL,
  dateCreated timestamp NOT NULL DEFAULT current_timestamp(),
  profile_image varchar(255) NOT NULL DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO users (email, firstName, lastName, contact, password, dateCreated, profile_image) VALUES
('hien.nguyen@example.com', 'Hiền', 'Nguyễn', '0911222333', 'matkhau1', '2024-07-26 12:50:46', 'user-girl.png'),
('bao.tran@example.com', 'Bảo', 'Trần', '0922333444', 'matkhau2', '2024-08-10 15:37:56', 'default.jpg'),
('ngoc.pham@example.com', 'Ngọc', 'Phạm', '0933444555', 'matkhau3', '2024-08-10 15:36:50', 'default.jpg'),
('hoang.vo@example.com', 'Hoàng', 'Võ', '0944555666', 'matkhau4', '2024-07-30 12:45:21', 'user-boy.jpg');


ALTER TABLE cart
  ADD PRIMARY KEY (id);


ALTER TABLE menucategory
  ADD PRIMARY KEY (catId);


ALTER TABLE menuitem
  ADD PRIMARY KEY (itemId);


ALTER TABLE orders
  ADD PRIMARY KEY (order_id),
  ADD KEY email (email);


ALTER TABLE order_items
  ADD PRIMARY KEY (id),
  ADD KEY order_id (order_id),
  ADD KEY itemId (itemName) USING BTREE;


ALTER TABLE reservations
  ADD PRIMARY KEY (reservation_id);


ALTER TABLE reviews
  ADD PRIMARY KEY (review_id),
  ADD KEY email (email),
  ADD KEY order_id (order_id);


ALTER TABLE staff
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY email (email);


ALTER TABLE users
  ADD PRIMARY KEY (email);



ALTER TABLE cart
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;


ALTER TABLE menucategory
  MODIFY catId int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;


ALTER TABLE menuitem
  MODIFY itemId int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;


ALTER TABLE orders
  MODIFY order_id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;


ALTER TABLE order_items
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;


ALTER TABLE reservations
  MODIFY reservation_id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;


ALTER TABLE reviews
  MODIFY review_id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;


ALTER TABLE staff
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;



ALTER TABLE orders
  ADD CONSTRAINT orders_ibfk_1 FOREIGN KEY (email) REFERENCES users (email);


ALTER TABLE order_items
  ADD CONSTRAINT order_items_ibfk_1 FOREIGN KEY (order_id) REFERENCES orders (order_id);


ALTER TABLE reviews
  ADD CONSTRAINT reviews_ibfk_1 FOREIGN KEY (email) REFERENCES users (email),
  ADD CONSTRAINT reviews_ibfk_2 FOREIGN KEY (order_id) REFERENCES orders (order_id);
COMMIT;




