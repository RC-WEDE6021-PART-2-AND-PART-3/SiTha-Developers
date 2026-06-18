-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 18, 2026 at 03:54 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clothingstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblaorder`
--

CREATE TABLE `tblaorder` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `payment_status` varchar(30) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblaorder`
--

INSERT INTO `tblaorder` (`order_id`, `user_id`, `total_amount`, `order_date`, `payment_status`) VALUES
(1, 1, 1799.00, '2026-06-18 11:56:31', 'completed'),
(2, 1, 180.00, '2026-06-18 11:59:29', 'completed'),
(3, 2, 770.00, '2026-06-18 14:11:23', 'completed'),
(4, 7, 820.00, '2026-06-18 15:37:59', 'completed'),
(5, 3, 180.00, '2026-06-18 15:42:35', 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `tblcart`
--

CREATE TABLE `tblcart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblclothes`
--

CREATE TABLE `tblclothes` (
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `size` varchar(10) DEFAULT NULL,
  `brand` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `condition_type` varchar(50) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblclothes`
--

INSERT INTO `tblclothes` (`product_id`, `user_id`, `title`, `description`, `price`, `size`, `brand`, `category`, `condition_type`, `image_url`, `created_at`) VALUES
(1, 3, 'Vintage Floral Dress', 'Beautiful vintage dress in excellent condition', 350.00, 'M', 'Zara', 'Dresses', 'Like New', 'images/item1.jpeg', '2026-05-03 23:29:58'),
(2, 3, 'Chunky Sneakers', 'White platform sneakers barely worn', 650.00, '38', 'Nike', 'Shoes', 'Like New', 'images/item2.jpeg', '2026-05-03 23:29:58'),
(3, 3, 'High Waisted Jeans', 'Classic blue denim jeans', 220.00, '32', 'Cotton On', 'Jeans', 'Good', 'images/item3.jpeg', '2026-05-03 23:29:58'),
(4, 3, 'Knit Sweater', 'Cozy oversized knit sweater', 180.00, 'L', 'Woolworths', 'Tops', 'Good', 'images/item4.jpeg', '2026-05-03 23:29:58'),
(5, 3, 'Leather Jacket', 'Black faux leather biker jacket', 499.00, 'S', 'H&M', 'Jackets', 'Like New', 'images/item5.jpeg', '2026-05-03 23:29:58'),
(6, 3, 'Floral Mini Skirt', 'Cute floral print mini skirt', 180.00, 'S', 'Zara', 'Dresses', 'Like New', 'images/item6.jpeg', '2026-06-18 12:28:14'),
(7, 3, 'Oversized Hoodie', 'Comfy grey oversized hoodie', 220.00, 'L', 'H&M', 'Tops', 'Good', 'images/item7.jpeg', '2026-06-18 12:28:14'),
(8, 3, 'Straight Leg Jeans', 'Classic straight leg blue jeans', 299.00, '30', 'Levi\'s', 'Jeans', 'Good', 'images/item8.jpeg', '2026-06-18 12:28:14'),
(9, 3, 'Puffer Jacket', 'Warm black puffer jacket', 450.00, 'M', 'Nike', 'Jackets', 'Like New', 'images/item9.jpeg', '2026-06-18 12:28:14'),
(10, 3, 'White Sneakers', 'Clean white lace up sneakers', 380.00, '37', 'Adidas', 'Shoes', 'Like New', 'images/item10.jpeg', '2026-06-18 12:28:14'),
(11, 3, 'Wrap Dress', 'Elegant wrap dress for any occasion', 320.00, 'M', 'Woolworths', 'Dresses', 'Good', 'images/item11.jpeg', '2026-06-18 12:28:14'),
(12, 3, 'Crop Top', 'Black ribbed crop top', 120.00, 'XS', 'Cotton On', 'Tops', 'Like New', 'images/item12.jpeg', '2026-06-18 12:28:14'),
(13, 3, 'Denim Shorts', 'High waisted denim shorts', 160.00, '28', 'Zara', 'Jeans', 'Good', 'images/item13.jpeg', '2026-06-18 12:28:14'),
(14, 3, 'Leather Boots', 'Ankle leather boots in brown', 550.00, '38', 'Woolworths', 'Shoes', 'Good', 'images/item14.jpeg', '2026-06-18 12:28:14'),
(15, 3, 'Bucket Hat', 'Trendy beige bucket hat', 90.00, 'S', 'H&M', 'Accessories', 'Like New', 'images/item15.jpeg', '2026-06-18 12:28:14');

-- --------------------------------------------------------

--
-- Table structure for table `tblmessages`
--

CREATE TABLE `tblmessages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message_text` text DEFAULT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblmessages`
--

INSERT INTO `tblmessages` (`message_id`, `sender_id`, `receiver_id`, `message_text`, `timestamp`) VALUES
(1, 5, 6, 'Update you account details', '2026-06-18 14:13:43'),
(2, 5, 4, 'The dress you wanted is now available', '2026-06-18 14:14:04');

-- --------------------------------------------------------

--
-- Table structure for table `tblorderitems`
--

CREATE TABLE `tblorderitems` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblorderitems`
--

INSERT INTO `tblorderitems` (`order_item_id`, `order_id`, `product_id`, `quantity`) VALUES
(1, 1, 5, 1),
(2, 1, 2, 2),
(3, 2, 4, 1),
(4, 3, 6, 1),
(5, 3, 15, 1),
(6, 3, 12, 1),
(7, 3, 10, 1),
(8, 4, 7, 1),
(9, 4, 3, 1),
(10, 4, 10, 1),
(11, 5, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'buyer',
  `seller_status` tinyint(1) DEFAULT 0,
  `verification_status` varchar(20) DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`user_id`, `first_name`, `last_name`, `email`, `username`, `password`, `role`, `seller_status`, `verification_status`, `created_at`) VALUES
(1, 'John', 'Doe', 'j.doe@abc.co.za', 'johndoe', '482c811da5d5b4bc6d497ffa98491e38', 'buyer', 0, 'verified', '2026-06-18 11:43:27'),
(2, 'Jane', 'Smith', 'j.smith@abc.co.za', 'janesmith', '482c811da5d5b4bc6d497ffa98491e38', 'buyer', 0, 'verified', '2026-06-18 11:43:27'),
(3, 'Thabo', 'Nkosi', 't.nkosi@abc.co.za', 'thabonkosi', '482c811da5d5b4bc6d497ffa98491e38', 'seller', 1, 'verified', '2026-06-18 11:43:27'),
(4, 'Lerato', 'Dlamini', 'l.dlamini@abc.co.za', 'leratod', '482c811da5d5b4bc6d497ffa98491e38', 'buyer', 0, 'verified', '2026-06-18 11:43:27'),
(5, 'Admin', 'User', 'admin@pastimes.co.za', 'admin', '482c811da5d5b4bc6d497ffa98491e38', 'admin', 0, 'verified', '2026-06-18 11:43:27'),
(6, 'James', 'King', 'James@24', 'James', '482c811da5d5b4bc6d497ffa98491e38', 'seller', 0, 'verified', '2026-06-18 14:13:08'),
(7, 'Zee', 'Molo', 'Molo@co.za', 'Zee', '0f1f8e92a90f745c7b31f1c11b9d0a56', 'buyer', 0, 'verified', '2026-06-18 15:22:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `tblaorder`
--
ALTER TABLE `tblaorder`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tblcart`
--
ALTER TABLE `tblcart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `tblclothes`
--
ALTER TABLE `tblclothes`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tblmessages`
--
ALTER TABLE `tblmessages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `tblorderitems`
--
ALTER TABLE `tblorderitems`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblaorder`
--
ALTER TABLE `tblaorder`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblcart`
--
ALTER TABLE `tblcart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tblclothes`
--
ALTER TABLE `tblclothes`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tblmessages`
--
ALTER TABLE `tblmessages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblorderitems`
--
ALTER TABLE `tblorderitems`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblaorder`
--
ALTER TABLE `tblaorder`
  ADD CONSTRAINT `tblaorder_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tblcart`
--
ALTER TABLE `tblcart`
  ADD CONSTRAINT `tblcart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tblcart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `tblclothes` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `tblclothes`
--
ALTER TABLE `tblclothes`
  ADD CONSTRAINT `tblclothes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tblmessages`
--
ALTER TABLE `tblmessages`
  ADD CONSTRAINT `tblmessages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `tbluser` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tblmessages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `tbluser` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tblorderitems`
--
ALTER TABLE `tblorderitems`
  ADD CONSTRAINT `tblorderitems_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `tblaorder` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tblorderitems_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `tblclothes` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
