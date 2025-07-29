-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 29, 2025 at 10:45 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `food_ordering`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(2, 'yashasvi', 'kowshik'),
(1005, 'yashasvi159', 'kowshikch');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Biryani'),
(2, 'Pizza'),
(3, 'Burger'),
(4, 'Shawarma'),
(5, 'Fried Rice'),
(6, 'Seafood'),
(7, 'Grill/BBQ'),
(8, 'Cakes'),
(9, 'Shakes'),
(10, 'Parotta'),
(11, 'Pulka'),
(12, 'Meals');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `discount_type` enum('Flat','Percentage') DEFAULT NULL,
  `discount_value` decimal(10,2) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `expiry_date` date NOT NULL DEFAULT curdate(),
  `is_first_time_only` tinyint(1) DEFAULT 0,
  `is_biryani_only` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `discount_type`, `discount_value`, `is_active`, `expiry_date`, `is_first_time_only`, `is_biryani_only`) VALUES
(1, 'ABC12345', 'Percentage', 8.50, 1, '2025-07-15', 0, 1),
(2, 'E42C295F', '', 9.00, 1, '2025-07-15', 0, 0),
(3, 'BAD7B29C', '', 7.00, 1, '2025-07-19', 0, 0),
(4, '28A77D5E', '', 6.00, 1, '2025-07-28', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `delivery_rating` int(11) NOT NULL,
  `taste_rating` int(11) NOT NULL,
  `feedback_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `delivery_rating`, `taste_rating`, `feedback_text`, `created_at`) VALUES
(1, 1, 5, 4, 'good serivicing and graet website', '2025-07-15 10:31:49');

-- --------------------------------------------------------

--
-- Table structure for table `food_items`
--

CREATE TABLE `food_items` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_items`
--

INSERT INTO `food_items` (`id`, `name`, `description`, `price`, `image`, `category_id`) VALUES
(1, 'Chicken Biryani', NULL, 180.00, 'chicken_biryani.jpg', 1),
(2, 'Mutton Biryani', NULL, 220.00, 'mutton_biryani.jpg', 1),
(3, 'Veg Biryani', NULL, 150.00, 'veg_biryani.avif', 1),
(4, 'Prawns Biryani', NULL, 230.00, 'prawns_biryani.jpg', 1),
(5, 'Hyderabadi Biryani', NULL, 200.00, 'hyd_biryani.jpg', 1),
(6, 'Margherita Pizza', NULL, 160.00, 'margherita.jpg', 2),
(7, 'Cheese Burst Pizza', NULL, 200.00, 'cheese_burst.webp', 2),
(8, 'Farmhouse Pizza', NULL, 220.00, 'farmhouse.avif', 2),
(9, 'Paneer Pizza', NULL, 230.00, 'paneer_pizza.png', 2),
(10, 'Tandoori Chicken Pizza', NULL, 250.00, 'tandoori_chicken.webp', 2),
(11, 'Veg Burger', NULL, 120.00, 'veg_burger.jpg', 3),
(12, 'Cheese Burger', NULL, 150.00, 'cheese_burger.jpg', 3),
(13, 'Chicken Burger', NULL, 180.00, 'chicken_burger.webp', 3),
(14, 'Double Patty Burger', NULL, 220.00, 'double_patty.jpg', 3),
(15, 'Grilled Burger', NULL, 200.00, 'grilled_burger.jpg', 3),
(31, 'Classic Chicken Shawarma', NULL, 130.00, 'classic_shawarma.jpg', 4),
(32, 'Spicy Shawarma', NULL, 150.00, 'spicy_shawarma.webp', 4),
(33, 'Paneer Shawarma', NULL, 140.00, 'paneer_shawarma.jpeg', 4),
(34, 'Cheese Shawarma', NULL, 160.00, 'cheese_shawarma.jpg', 4),
(35, 'Egg Shawarma', NULL, 120.00, 'egg_shawarma.jpg', 4),
(36, 'BBQ Shawarma', NULL, 170.00, 'bbq_shawarma.jpg', 4),
(37, 'Garlic Mayo Shawarma', NULL, 135.00, 'garlic_mayo.jpeg', 4),
(38, 'Loaded Shawarma', NULL, 180.00, 'loaded_shawarma.jpg', 4),
(39, 'Roll Shawarma', NULL, 145.00, 'roll_shawarma.webp', 4),
(40, 'Combo Shawarma', NULL, 200.00, 'combo_shawarma.jpg', 4),
(41, 'Veg Fried Rice', NULL, 120.00, 'veg_friedrice.jpg', 5),
(42, 'Egg Fried Rice', NULL, 130.00, 'egg_friedrice.jpg', 5),
(43, 'Chicken Fried Rice', NULL, 150.00, 'chicken_friedrice.jpg', 5),
(44, 'Paneer Fried Rice', NULL, 140.00, 'paneer_friedrice.webp', 5),
(45, 'Mixed Fried Rice', NULL, 170.00, 'mixed_friedrice.jpg', 5),
(46, 'Garlic Fried Rice', NULL, 135.00, 'garlic_friedrice.jpg', 5),
(47, 'Schezwan Fried Rice', NULL, 160.00, 'schezwan.webp', 5),
(48, 'Mushroom Fried Rice', NULL, 140.00, 'mushroom_friedrice.jpg', 5),
(49, 'Tandoori Fried Rice', NULL, 150.00, 'tandoori_rice.jpg', 5),
(50, 'Chinese Combo', NULL, 190.00, 'combo_rice.webp', 5),
(51, 'Fish Fry', NULL, 200.00, 'fish_fry.jpg', 6),
(52, 'Prawn Curry', NULL, 250.00, 'prawn_curry.jpg', 6),
(53, 'Crab Masala', NULL, 280.00, 'crab.avif', 6),
(54, 'Fish Biryani', NULL, 230.00, 'fish_biryani.jpg', 6),
(55, 'Seafood Platter', NULL, 300.00, 'seafood_platter.jpg', 6),
(56, 'Tandoori Fish', NULL, 240.00, 'tandoori_fish.jpg', 6),
(57, 'Prawn Fry', NULL, 220.00, 'prawn_fry.jpg', 6),
(58, 'Fish Curry', NULL, 210.00, 'fish_curry.jpg', 6),
(59, 'Crab Fry', NULL, 270.00, 'crab_fry.webp', 6),
(60, 'Spicy Prawn Rice', NULL, 230.00, 'spicy_prawn.jpg', 6),
(61, 'Grilled Chicken', NULL, 250.00, 'grilled_chicken.jpg', 7),
(62, 'BBQ Wings', NULL, 200.00, 'bbq_wings.jpg', 7),
(63, 'Paneer Tikka', NULL, 180.00, 'paneer_tikka.jpg', 7),
(64, 'Grilled Fish', NULL, 260.00, 'grilled_fish.jpg', 7),
(65, 'Tandoori Chicken', NULL, 230.00, 'tandoori_chicken.jpg', 7),
(66, 'BBQ Paneer', NULL, 200.00, 'bbq_paneer.jpg', 7),
(67, 'Chicken Seekh Kebab', NULL, 220.00, 'seekh_kebab.jpg', 7),
(68, 'Mutton Kebab', NULL, 280.00, 'mutton_kebab.jpg', 7),
(69, 'Stuffed Mushrooms', NULL, 190.00, 'stuffed_mushrooms.webp', 7),
(70, 'Mixed Grill Platter', NULL, 320.00, 'grill_platter.jpg', 7),
(71, 'Chocolate Cake', NULL, 150.00, 'chocolate_cake.jpg', 8),
(72, 'Black Forest', NULL, 170.00, 'black_forest.webp', 8),
(73, 'Red Velvet', NULL, 180.00, 'red_velvet.jpg', 8),
(74, 'Pineapple Cake', NULL, 160.00, 'pineapple.avif', 8),
(75, 'Butterscotch', NULL, 175.00, 'butterscotch.webp', 8),
(76, 'Vanilla Cake', NULL, 140.00, 'vanilla.avif', 8),
(77, 'Fruit Cake', NULL, 190.00, 'fruit.jpg', 8),
(78, 'Rainbow Cake', NULL, 210.00, 'rainbow.webp', 8),
(79, 'Cup Cakes', NULL, 120.00, 'cupcakes.webp', 8),
(80, 'Cheesecake', NULL, 220.00, 'cheesecake.jpg', 8),
(81, 'Chocolate Shake', NULL, 120.00, 'choco_shake.jpg', 9),
(82, 'Strawberry Shake', NULL, 130.00, 'strawberry_shake.webp', 9),
(83, 'Mango Shake', NULL, 125.00, 'mango_shake.jpg', 9),
(84, 'Vanilla Shake', NULL, 110.00, 'vanilla_shake.jpg', 9),
(85, 'Oreo Shake', NULL, 140.00, 'oreo_shake.webp', 9),
(86, 'Banana Shake', NULL, 115.00, 'banana_shake.avif', 9),
(87, 'Cold Coffee', NULL, 100.00, 'cold_coffee.jpg', 9),
(88, 'KitKat Shake', NULL, 150.00, 'kitkat.jpg', 9),
(89, 'Butterscotch Shake', NULL, 130.00, 'butterscotch_shake.webp', 9),
(90, 'Dry Fruit Shake', NULL, 160.00, 'dryfruit.png', 9),
(91, 'Veg Parotta', NULL, 40.00, 'veg_parotta.jpg', 10),
(92, 'Chicken Kothu Parotta', NULL, 100.00, 'kothu_chicken.jpg', 10),
(93, 'Egg Kothu Parotta', NULL, 90.00, 'egg_kothu.jpg', 10),
(94, 'Parotta with Curry', NULL, 80.00, 'parotta_curry.avif', 10),
(95, 'Layered Parotta', NULL, 50.00, 'layered.jpeg', 10),
(96, 'Mutton Kothu', NULL, 120.00, 'mutton_kothu.jpg', 10),
(97, 'Cheese Parotta', NULL, 70.00, 'cheese_parotta.jpg', 10),
(98, 'Garlic Parotta', NULL, 60.00, 'garlic_parotta.jpg', 10),
(99, 'Double Egg Parotta', NULL, 95.00, 'double_egg.jpg', 10),
(100, 'Paneer Parotta', NULL, 85.00, 'paneer_parotta.jpg', 10),
(101, 'Plain Pulka', NULL, 30.00, 'plain_pulka.jpg', 11),
(102, 'Pulka with Dal', NULL, 50.00, 'pulka_dal.jpg', 11),
(103, 'Pulka with Chicken Curry', NULL, 90.00, 'pulka_chicken.webp', 11),
(104, 'Pulka with Paneer', NULL, 80.00, 'pulka_paneer.webp', 11),
(105, 'Stuffed Pulka', NULL, 70.00, 'stuffed_pulka.jpg', 11),
(106, 'Butter Pulka', NULL, 60.00, 'butter_pulka.avif', 11),
(107, 'Garlic Pulka', NULL, 65.00, 'garlic_pulka.jpg', 11),
(108, 'Tandoori Pulka', NULL, 75.00, 'tandoori_pulka.avif', 11),
(109, 'Mix Veg Pulka', NULL, 85.00, 'mixveg_pulka.jpg', 11),
(110, 'Ragi Pulka', NULL, 100.00, 'ragi_pulka.webp', 11),
(111, 'Veg Meals', NULL, 100.00, 'veg_meals.avif', 12),
(112, 'Chicken Meals', NULL, 150.00, 'chicken_meals.jpg', 12),
(113, 'Mutton Meals', NULL, 200.00, 'mutton_meals.webp', 12),
(114, 'Fish Meals', NULL, 180.00, 'fish_meals.jpg', 12),
(115, 'Egg Meals', NULL, 130.00, 'egg_meals.avif', 12),
(116, 'Prawn Meals', NULL, 220.00, 'prawn_meals.png', 12),
(117, 'Combo Meals', NULL, 250.00, 'combo_meals.jpg', 12),
(118, 'Tandoori Chicken Meals', NULL, 240.00, 'tandoori_meals.png', 12),
(119, 'Biryani Meals', NULL, 230.00, 'biryani_meals.jpg', 12),
(120, 'Special Meals', NULL, 300.00, 'special_meals.jpg', 12);

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

CREATE TABLE `job_applications` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `reason` text NOT NULL,
  `status` enum('Pending','Reviewed','Rejected') DEFAULT 'Pending',
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_applications`
--

INSERT INTO `job_applications` (`id`, `name`, `mobile`, `email`, `address`, `reason`, `status`, `applied_at`) VALUES
(1, 'yashasvi kowshik ch', '7995233706', 'yashasvi159@gmail.com', 'yashasvi,k/o subbarao ,ramnagar addanki,bapatla,', 'i want a part time job', '', '2025-07-15 10:33:03'),
(2, 'yashasvi kowshik ch', '7995233706', 'yashasvi159@gmail.com', 'yashasvi,k/o subbarao ,ramnagar addanki,bapatla,', 'jhnuby', '', '2025-07-19 07:48:01');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `coupon_applied` varchar(50) DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Processing','Delivered') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `coupon_applied`, `delivery_address`, `order_date`, `status`) VALUES
(1, 1, 0.00, '', 'k/o subbarao,behind ss lodge,ramnager,addanki,andhra pradash', '2025-06-21 11:19:57', 'Delivered'),
(2, 1, 900.00, '', 'k/o subbarao,behind ss lodge,ramnager,addanki,andhra pradash', '2025-07-15 10:16:46', 'Delivered'),
(3, 1, 163.80, 'E42C295F', 'k/o subbarao,behind ss lodge,ramnager,addanki,andhra pradash', '2025-07-15 10:30:30', 'Delivered'),
(4, 1, 480.00, '', 'k/o subbarao,behind ss lodge,ramnager,addanki,andhra pradash', '2025-07-19 07:46:35', 'Delivered'),
(5, 1, 818.40, 'BAD7B29C', 'k/o subbarao,behind ss lodge,ramnager,addanki,andhra pradash', '2025-07-19 07:47:10', 'Delivered'),
(6, 2, 330.00, '', 'htsrdhtdshtds', '2025-07-19 07:50:55', 'Delivered'),
(7, 1, 390.00, '', 'k/o subbarao,behind ss lodge,ramnager,addanki,andhra pradash', '2025-07-28 05:42:37', 'Delivered');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `food_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `food_id`, `quantity`, `price`) VALUES
(1, 2, 37, 2, 135.00),
(2, 2, 32, 1, 150.00),
(3, 2, 12, 2, 150.00),
(4, 2, 1, 1, 180.00),
(5, 3, 1, 1, 180.00),
(6, 4, 12, 2, 150.00),
(7, 4, 1, 1, 180.00),
(8, 5, 2, 4, 220.00),
(9, 6, 1, 1, 180.00),
(10, 6, 3, 1, 150.00),
(11, 7, 31, 3, 130.00);

-- --------------------------------------------------------

--
-- Table structure for table `used_coupons`
--

CREATE TABLE `used_coupons` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `coupon_id` int(11) DEFAULT NULL,
  `used_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `mobile`, `email`, `address`, `password`, `created_at`) VALUES
(1, 'YASHASVI KOWSHIK CHALLAGUNDLA', '7995233706', 'yashasvi@srmap.edu.in', 'k/o subbarao,behind ss lodge,ramnager,addanki,andhra pradash', '$2y$10$/4ztsKNA1UjyzgnSOWBMAeZaJMAAgjEEyXr9bY1y8hSWle4QuA.Ki', '2025-06-21 11:13:38'),
(2, 'raghavi ch', '9677868906', 'fesgtg@gmail.com', 'htsrdhtdshtds', '$2y$10$sfq2VqeLBBAYTNeQZYXQ0.xZce3oWFJXaxIo1sSbNaVorysyc11Km', '2025-07-19 07:49:38'),
(3, 'smartdine', '7866565764', 'fesgtfg@gmail.com', 'cffcfc', '$2y$10$pv26EVrNf0uvUYuWBqQGeOiUhBElYmAyiPrGeVBg4LnpFGFaWwsoW', '2025-07-28 05:40:50');

-- --------------------------------------------------------

--
-- Table structure for table `user_coupons`
--

CREATE TABLE `user_coupons` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_coupons`
--

INSERT INTO `user_coupons` (`id`, `user_id`, `coupon_id`, `assigned_at`) VALUES
(1, 1, 2, '2025-07-15 10:27:13'),
(2, 1, 3, '2025-07-19 07:46:20'),
(3, 2, 4, '2025-07-28 05:35:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_feedback_user` (`user_id`),
  ADD KEY `idx_feedback_date` (`created_at`);

--
-- Indexes for table `food_items`
--
ALTER TABLE `food_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_job_applications_status` (`status`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `food_id` (`food_id`);

--
-- Indexes for table `used_coupons`
--
ALTER TABLE `used_coupons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `coupon_id` (`coupon_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mobile` (`mobile`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_coupons`
--
ALTER TABLE `user_coupons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `coupon_id` (`coupon_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1006;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `food_items`
--
ALTER TABLE `food_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `job_applications`
--
ALTER TABLE `job_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `used_coupons`
--
ALTER TABLE `used_coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_coupons`
--
ALTER TABLE `user_coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `food_items`
--
ALTER TABLE `food_items`
  ADD CONSTRAINT `food_items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`food_id`) REFERENCES `food_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `used_coupons`
--
ALTER TABLE `used_coupons`
  ADD CONSTRAINT `used_coupons_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `used_coupons_ibfk_2` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`);

--
-- Constraints for table `user_coupons`
--
ALTER TABLE `user_coupons`
  ADD CONSTRAINT `user_coupons_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_coupons_ibfk_2` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
