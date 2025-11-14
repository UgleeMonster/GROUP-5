-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2025 at 03:14 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `thrift`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `username`, `product_id`, `quantity`, `added_at`) VALUES
(54, 'admin', 4, 1, '2025-11-10 10:54:39'),
(95, 'lacalaca', 3, 2, '2025-11-14 01:10:16'),
(96, 'lacalaca', 9, 2, '2025-11-14 01:10:20'),
(97, 'lacalaca', 10, 2, '2025-11-14 01:10:22'),
(100, 'lacalaca', 1, 1, '2025-11-14 02:10:35'),
(101, 'lacalaca', 4, 1, '2025-11-14 02:10:39');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `date_sent` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `subject`, `message`, `date_sent`) VALUES
(2, 'Rence', 'rence@gmail.com', 'Thrift', 'SKIBIDI TOILET', '2025-11-12 15:01:03'),
(3, 'Rence', 'rence@gmail.com', 'Thrift', 'TETOOOO', '2025-11-12 15:01:18'),
(4, 'Simon', 'lacalaca@gmail.com', 'Thrift', 'TETOPEAR!!!', '2025-11-12 15:39:12'),
(5, 'dada', 'williejaytomascalacala@gmail.com', 'dada', 'dadadad', '2025-11-14 01:43:43'),
(6, 'Pogi', 'lacalaca@gmail.com', 'teto', 'HAHAHAH YUNG IBA JAN SUMAGOT KAYO NG MAAYOS MAGPROVIE NA KAYO AT MAGISIP NA', '2025-11-14 03:09:25');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category` varchar(50) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `image` varchar(50) NOT NULL,
  `stock` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category`, `name`, `image`, `stock`, `price`) VALUES
(1, 'Mens', 'Mens Shirt 1', 'mens1.jpg', 2, 499.00),
(2, 'Mens', 'Mens Jacket 2', 'mens2.jpg', 0, 799.00),
(3, 'Mens', 'Mens Pants 3', 'mens3.jpg', 4, 599.00),
(4, 'Mens', 'Mens Hoodie 4', 'mens4.jpg', 4, 699.00),
(5, 'Mens', 'Mens T-Shirt 5', 'mens5.jpg', 0, 399.00),
(6, 'Mens', 'Mens Jeans 6', 'mens6.jpg', 0, 649.00),
(7, 'Womens', 'Womens Dress 1', 'womens1.jpg', 0, 599.00),
(8, 'Womens', 'Womens Top 2', 'womens2.jpg', 2, 499.00),
(9, 'Womens', 'Womens Skirt 3', 'womens3.jpg', 4, 549.00),
(10, 'Womens', 'Womens Jacket 4', 'womens4.jpg', 4, 799.00),
(11, 'Womens', 'Womens Pants 5', 'womens5.jpg', 0, 649.00),
(12, 'Womens', 'Womens Hoodie 6', 'womens6.jpg', 0, 699.00),
(13, 'Kids', 'Kids T-Shirt 1', 'kids1.jpg', 1, 299.00),
(14, 'Kids', 'Kids Shorts 2', 'kids2.jpg', 3, 259.00),
(15, 'Kids', 'Kids Hoodie 3', 'kids3.jpg', 0, 349.00),
(16, 'Kids', 'Kids Dress 4', 'kids4.jpg', 5, 399.00),
(17, 'Kids', 'Kids Jacket 5', 'kids5.jpg', 5, 449.00),
(18, 'Kids', 'Kids Pants 6', 'kids6.jpg', 5, 329.00),
(19, 'Unisex', 'lol', 'unisex1.jpg', 0, 544.00),
(20, 'Unisex', 'Unisex T-Shirt 2', 'unisex2.jpg', 5, 499.00),
(21, 'Unisex', 'Unisex Jacket 3', 'unisex3.jpg', 4, 799.00),
(22, 'Unisex', 'Unisex Pants 4', 'unisex4.jpg', 2, 599.00),
(23, 'Unisex', 'Unisex Sweater 5', 'unisex5.jpg', 5, 649.00),
(24, 'Unisex', 'Unisex Shorts 6', 'unisex6.jpg', 5, 399.00);

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `receipt_code` varchar(20) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `purchase_date` datetime DEFAULT NULL,
  `payment_type` varchar(50) NOT NULL DEFAULT 'COD',
  `payment_number` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `receipt_code`, `customer_name`, `product_name`, `quantity`, `price`, `total`, `purchase_date`, `payment_type`, `payment_number`) VALUES
(40, '', 'lacalaca', 'Mens Shirt 1', 1, 499.00, 499.00, '2025-11-12 19:10:27', 'COD', ''),
(41, '', 'lacalaca', 'Mens Jacket 2', 1, 799.00, 799.00, '2025-11-12 20:48:31', 'GCash', '09777912709'),
(42, '', 'lacalaca', 'Mens Pants 3', 1, 599.00, 599.00, '2025-11-12 20:53:51', 'COD', ''),
(43, '', 'rence', 'Mens Jacket 2', 1, 799.00, 799.00, '2025-11-12 21:20:24', 'COD', ''),
(44, '', 'rence', 'Mens Shirt 1', 1, 499.00, 499.00, '2025-11-12 21:20:27', 'COD', ''),
(45, '', 'rence', 'Womens Top 2', 1, 499.00, 499.00, '2025-11-12 21:20:27', 'COD', ''),
(46, '', 'rence', 'Womens Skirt 3', 1, 549.00, 549.00, '2025-11-12 21:20:27', 'COD', ''),
(47, '', 'rence', 'Womens Hoodie 6', 1, 699.00, 699.00, '2025-11-12 21:20:27', 'COD', ''),
(48, '', 'rence', 'Womens Pants 5', 1, 649.00, 649.00, '2025-11-12 21:20:27', 'COD', ''),
(49, '', 'rence', 'Womens Jacket 4', 1, 799.00, 799.00, '2025-11-12 21:20:27', 'COD', ''),
(50, 'GKWGW2AG0P', 'rence', 'Mens Jacket 2', 1, 799.00, 799.00, '2025-11-12 21:29:16', '', ''),
(51, 'VW2Q8TX9UB', 'rence', 'Mens Jacket 2', 1, 799.00, 799.00, '2025-11-12 21:31:34', 'COD', ''),
(52, 'O23KPRQSH0', 'rence', 'Mens Pants 3', 1, 599.00, 599.00, '2025-11-12 21:33:31', 'COD', ''),
(53, 'AJ0WFKGRPH', 'rence', 'Unisex Jacket 3', 1, 799.00, 799.00, '2025-11-12 21:34:06', 'COD', ''),
(54, 'FUBF1IY3KC', 'rence', 'Mens Pants 3', 1, 599.00, 599.00, '2025-11-12 21:34:39', 'COD', ''),
(55, 'P5TN3NJ1BF', 'rence', 'Mens Hoodie 4', 1, 699.00, 699.00, '2025-11-12 21:34:57', 'COD', ''),
(56, '2J4XKEMBXF', 'rence', 'Mens Hoodie 4', 1, 699.00, 699.00, '2025-11-12 21:35:31', 'COD', ''),
(57, 'YLI43LHC9P', 'rence', 'Mens Hoodie 4', 1, 699.00, 699.00, '2025-11-12 21:36:10', 'COD', ''),
(58, '9H3WEDAA26', 'rence', 'Mens Jacket 2', 1, 799.00, 799.00, '2025-11-12 21:37:08', 'COD', ''),
(59, '6QBFZK3C1I', 'rence', 'Mens Shirt 1', 1, 499.00, 499.00, '2025-11-12 21:37:31', 'COD', ''),
(60, 'MRIS0WUGNH', 'rence', 'Mens Shirt 1', 1, 499.00, 499.00, '2025-11-12 21:38:16', 'COD', ''),
(61, 'IIL8DR9H5D', 'rence', 'Mens Shirt 1', 1, 499.00, 499.00, '2025-11-12 21:38:27', 'COD', ''),
(62, 'O24Z0HN7H9', 'rence', 'Mens Shirt 1', 1, 499.00, 499.00, '2025-11-12 21:38:43', 'COD', ''),
(63, 'SJ26NRDEVK', 'rence', 'Mens Jacket 2', 1, 799.00, 799.00, '2025-11-12 21:38:54', 'COD', ''),
(64, 'GH81NF746E', 'rence', 'Mens Shirt 1', 1, 499.00, 499.00, '2025-11-12 21:39:06', 'COD', ''),
(65, 'JA6SAV4LHH', 'rence', 'Mens Shirt 1', 1, 499.00, 499.00, '2025-11-12 21:39:30', 'COD', ''),
(66, '73R9G6K3FS', 'rence', 'Mens Jacket 2', 1, 799.00, 799.00, '2025-11-12 21:39:42', 'COD', ''),
(67, '2YKK6XHU5B', 'rence', 'Mens Shirt 1', 1, 499.00, 499.00, '2025-11-12 21:39:56', 'COD', ''),
(68, '5WVUXH97Q5', 'rence', 'Mens Jacket 2', 1, 799.00, 799.00, '2025-11-12 21:40:50', 'COD', ''),
(69, '5WVUXH97Q5', 'rence', 'Mens Shirt 1', 1, 499.00, 499.00, '2025-11-12 21:40:50', 'COD', ''),
(70, 'AUC2T5N9RK', 'rence', 'Mens Jacket 2', 1, 799.00, 799.00, '2025-11-12 21:43:35', 'COD', ''),
(71, '1E6QTRXQ8U', 'rence', 'Mens Jacket 2', 1, 799.00, 799.00, '2025-11-12 21:43:59', 'COD', ''),
(72, 'NQFI6JWN12', 'rence', 'Mens Jacket 2', 1, 799.00, 799.00, '2025-11-12 21:44:43', 'COD', ''),
(73, '6OLH7U1E0V', 'rence', 'Mens Shirt 1', 1, 499.00, 499.00, '2025-11-12 21:45:00', 'COD', ''),
(74, 'AZX6ELU0HK', 'rence', 'Mens Shirt 1', 1, 499.00, 499.00, '2025-11-12 21:45:29', 'COD', ''),
(75, 'TG5Q89B7UB', 'rence', 'Mens Jacket 2', 1, 799.00, 799.00, '2025-11-12 21:45:44', 'COD', ''),
(76, '7ETKJH96CL', 'rence', 'Mens Shirt 1', 1, 499.00, 499.00, '2025-11-12 21:45:59', 'COD', ''),
(77, 'K6HN3AJL4O', 'rence', 'Mens Shirt 1', 1, 499.00, 499.00, '2025-11-12 21:46:10', 'COD', ''),
(78, 'SLEW09HCHS', 'rence', 'Mens Jacket 2', 1, 799.00, 799.00, '2025-11-12 21:51:50', 'COD', ''),
(79, 'WCZ5UL84KK', 'rence', 'Mens Shirt 1', 1, 499.00, 499.00, '2025-11-12 21:52:07', 'COD', ''),
(80, 'ZP39AR7NUC', 'rence', 'Mens Jacket 2', 1, 799.00, 799.00, '2025-11-12 21:52:18', 'COD', ''),
(81, 'Q6RPWUH3VC', 'rence', 'Mens Jacket 2', 1, 799.00, 799.00, '2025-11-12 21:52:34', 'COD', ''),
(82, '4297EFHZ9V', 'rence', 'Mens Jacket 2', 1, 799.00, 799.00, '2025-11-12 21:53:56', 'COD', ''),
(83, '8GGU250VA4', 'rence', 'Mens Jacket 2', 1, 799.00, 799.00, '2025-11-12 21:54:29', 'COD', ''),
(84, '1UF9OBI2A4', 'rence', 'Mens Shirt 1', 1, 499.00, 499.00, '2025-11-12 21:59:48', 'COD', ''),
(85, 'CLG4T23G9T', 'rence', 'Mens Jacket 2', 1, 799.00, 799.00, '2025-11-12 22:00:27', 'COD', ''),
(86, 'CLG4T23G9T', 'rence', 'Mens Shirt 1', 1, 499.00, 499.00, '2025-11-12 22:00:27', 'COD', ''),
(87, 'CLG4T23G9T', 'rence', 'Unisex Hoodie 1', 1, 699.00, 699.00, '2025-11-12 22:00:27', 'COD', ''),
(88, 'CLG4T23G9T', 'rence', 'Unisex T-Shirt 2', 1, 499.00, 499.00, '2025-11-12 22:00:27', 'COD', ''),
(89, 'LQPD6X3U6I', 'lacalaca', 'Mens Jacket 2', 1, 799.00, 799.00, '2025-11-12 22:38:32', 'COD', ''),
(90, 'HVNF7HSH8V', 'lacalaca', 'Mens Shirt 1', 2, 499.00, 998.00, '2025-11-12 22:38:37', 'COD', ''),
(91, 'HVNF7HSH8V', 'lacalaca', 'Mens Jacket 2', 1, 799.00, 799.00, '2025-11-12 22:38:37', 'COD', ''),
(92, 'HVNF7HSH8V', 'lacalaca', 'Mens Pants 3', 1, 599.00, 599.00, '2025-11-12 22:38:37', 'COD', ''),
(93, '43FZZB7P8U', 'lacalaca', 'Mens Shirt 1', 1, 499.00, 499.00, '2025-11-12 22:38:49', 'COD', ''),
(94, 'MDDJ4O93BK', 'lacalaca', 'Mens Shirt 1', 12, 499.00, 4790.40, '2025-11-12 22:40:21', 'COD', ''),
(95, 'MDDJ4O93BK', 'lacalaca', 'Mens Jacket 2', 28, 799.00, 17897.60, '2025-11-12 22:40:21', 'COD', ''),
(96, 'MDDJ4O93BK', 'lacalaca', 'Womens Pants 5', 4, 649.00, 2596.00, '2025-11-12 22:40:21', 'COD', ''),
(97, 'MDDJ4O93BK', 'lacalaca', 'Womens Hoodie 6', 4, 699.00, 2796.00, '2025-11-12 22:40:21', 'COD', ''),
(98, '8YQ7T3WTDX', 'lacalaca', 'Mens Jacket 2', 1, 799.00, 799.00, '2025-11-14 09:00:06', 'COD', ''),
(99, '081ZUFZ9GX', 'lacalaca', 'Unisex Hoodie 1', 4, 699.00, 2796.00, '2025-11-14 09:00:22', 'COD', ''),
(100, '081ZUFZ9GX', 'lacalaca', 'Unisex T-Shirt 2', 4, 499.00, 1996.00, '2025-11-14 09:00:22', 'COD', ''),
(101, 'GE0NLN22A3', 'lacalaca', 'Mens Jacket 2', 1, 799.00, 799.00, '2025-11-14 09:01:03', 'COD', ''),
(102, 'K8EIERM2D9', 'drinn', 'Unisex Pants 4', 5, 599.00, 2995.00, '2025-11-14 09:03:42', 'COD', ''),
(103, 'K8EIERM2D9', 'drinn', 'Unisex Sweater 5', 5, 649.00, 3245.00, '2025-11-14 09:03:42', 'COD', ''),
(104, 'FK71K47PQT', 'lacalaca', 'Unisex Hoodie 1', 20, 699.00, 13980.00, '2025-11-14 09:18:24', 'COD', ''),
(105, 'NHXW8PEZBW', 'lacalaca', 'Kids Hoodie 3', 5, 349.00, 1745.00, '2025-11-14 10:04:24', 'COD', ''),
(106, 'NHXW8PEZBW', 'lacalaca', 'Mens Shirt 1', 2, 499.00, 998.00, '2025-11-14 10:04:24', 'COD', ''),
(107, '44BPXH2H1H', 'drinn', 'Mens Jacket 2', 2, 799.00, 1598.00, '2025-11-14 10:06:32', 'COD', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `remember_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `address`, `email`, `password`, `role`, `remember_token`) VALUES
(1, 'admin', 'jan lang', 'admin@gmail.com\r\n', '123', 'admin', NULL),
(2, 'lacalaca', 'mabalacat\r\n', 'lacalaca@gmail.com', '0000', 'customer', '5900e2964c015444acdac86e310cc09c'),
(3, 'rence', 'mars', 'rence@gmail.com', 'rence123', 'customer', NULL),
(4, 'jay', 'mantle', 'jay123@gmail.com', 'jay123', 'customer', NULL),
(5, 'drinn', 'ploto', 'drinnnnnn@gmail.com', 'drinn123', 'customer', NULL),
(6, 'jereek', 'uranus', 'jerek@gmail.com', 'jereek123', 'customer', NULL),
(7, 'vincent', 'nether', 'vicente@gmail.com', 'vincent123', 'customer', NULL),
(8, 'Alyssa', 'angeles', 'alyssa@gmail.com', '123', 'customer', NULL),
(9, 'junjun', 'dadad', 'dada@gmail.com', '55', 'customer', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
