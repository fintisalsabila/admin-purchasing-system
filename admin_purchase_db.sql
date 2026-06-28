-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2026 at 03:20 PM
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
-- Database: `admin_purchase_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_code`, `name`, `category`, `price`, `description`, `created_at`, `updated_at`) VALUES
(1, 'PRD001', 'Laptop Asus ROG', 'Elektronik', 15000000.00, 'Laptop gaming dengan performa tinggi', '2026-06-28 13:17:21', NULL),
(2, 'PRD002', 'Smartphone Samsung Galaxy', 'Elektronik', 8000000.00, 'Smartphone flagship terbaru', '2026-06-28 13:17:21', NULL),
(3, 'PRD003', 'Headphone Sony WH-1000XM5', 'Audio', 4500000.00, 'Headphone noise cancelling premium', '2026-06-28 13:17:21', NULL),
(4, 'PRD004', 'Mouse Logitech MX Master 3', 'Aksesoris', 1500000.00, 'Mouse wireless ergonomis', '2026-06-28 13:17:21', NULL),
(5, 'PRD005', 'Keyboard Mechanical Keychron', 'Aksesoris', 1200000.00, 'Keyboard mechanical dengan switch hot-swap', '2026-06-28 13:17:21', NULL),
(6, 'PRD006', 'Monitor Samsung 27\"', 'Elektronik', 3500000.00, 'Monitor 4K UHD untuk produktivitas', '2026-06-28 13:17:21', NULL),
(7, 'PRD007', 'Printer Epson L3210', 'Elektronik', 2800000.00, 'Printer ink tank all-in-one', '2026-06-28 13:17:21', NULL),
(8, 'PRD008', 'Speaker JBL Flip 6', 'Audio', 1200000.00, 'Speaker portable tahan air', '2026-06-28 13:17:21', NULL),
(9, 'PRD009', 'Power Bank Anker 20000mAh', 'Aksesoris', 800000.00, 'Power bank fast charging', '2026-06-28 13:17:21', NULL),
(10, 'PRD010', 'Smartwatch Apple Watch SE', 'Elektronik', 5000000.00, 'Smartwatch dengan fitur kesehatan', '2026-06-28 13:17:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) UNSIGNED NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','cancelled') DEFAULT 'active',
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `invoice_number`, `product_id`, `quantity`, `total_price`, `customer_name`, `customer_phone`, `purchase_date`, `status`, `cancelled_at`, `created_at`) VALUES
(1, 'INV-2026001', 1, 2, 30000000.00, 'Budi Santoso', '081234567890', '2026-06-28 13:17:21', 'active', NULL, '2026-06-28 13:17:21'),
(2, 'INV-2026002', 2, 1, 8000000.00, 'Siti Rahayu', '081234567891', '2026-06-28 13:17:21', 'active', NULL, '2026-06-28 13:17:21'),
(3, 'INV-2026003', 3, 3, 13500000.00, 'Andi Wijaya', '081234567892', '2026-06-28 13:17:21', 'active', NULL, '2026-06-28 13:17:21'),
(4, 'INV-2026004', 4, 5, 7500000.00, 'Dewi Lestari', '081234567893', '2026-06-28 13:17:21', 'active', NULL, '2026-06-28 13:17:21'),
(5, 'INV-2026005', 5, 2, 2400000.00, 'Rudi Hartono', '081234567894', '2026-06-28 13:17:21', 'active', NULL, '2026-06-28 13:17:21'),
(6, 'INV-2026060006', 9, 1, 800000.00, 'Udin', '081234567890', '2026-06-28 13:19:21', 'cancelled', '2026-06-28 13:19:28', '2026-06-28 13:19:21');

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `min_stock` int(11) NOT NULL DEFAULT 5,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `product_id`, `quantity`, `min_stock`, `last_updated`) VALUES
(1, 1, 15, 5, '2026-06-28 13:17:21'),
(2, 2, 20, 5, '2026-06-28 13:17:21'),
(3, 3, 10, 5, '2026-06-28 13:17:21'),
(4, 4, 25, 5, '2026-06-28 13:17:21'),
(5, 5, 12, 5, '2026-06-28 13:17:21'),
(6, 6, 8, 5, '2026-06-28 13:17:21'),
(7, 7, 6, 5, '2026-06-28 13:17:21'),
(8, 8, 18, 5, '2026-06-28 13:17:21'),
(9, 9, 30, 5, '2026-06-28 13:19:28'),
(10, 10, 7, 5, '2026-06-28 13:17:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_code` (`product_code`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`),
  ADD UNIQUE KEY `unique_invoice` (`invoice_number`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_product` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
