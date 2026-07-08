-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2026 at 06:36 PM
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
-- Database: `progressivecrm_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bodyshop_records`
--

CREATE TABLE `bodyshop_records` (
  `id` int(11) NOT NULL,
  `customer_registration_no` varchar(50) NOT NULL,
  `bodyshop_invoice_no` varchar(100) NOT NULL,
  `bodyshop_bill_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `extended_warranty` enum('Yes','No') NOT NULL DEFAULT 'No',
  `amc` enum('Yes','No') NOT NULL DEFAULT 'No',
  `insurance_renewal` enum('Yes','No') NOT NULL DEFAULT 'No',
  `earned_points` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bodyshop_records`
--

INSERT INTO `bodyshop_records` (`id`, `customer_registration_no`, `bodyshop_invoice_no`, `bodyshop_bill_amount`, `extended_warranty`, `amc`, `insurance_renewal`, `earned_points`, `created_by`, `created_at`) VALUES
(1, 'NC-2026-6847-2205', 'Inv0123155', 100000.00, 'No', 'No', 'No', 200, 2, '2026-07-08 17:50:43');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `customer_registration_no` varchar(50) NOT NULL,
  `customer_type` varchar(30) DEFAULT 'New_Customer',
  `customer_name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `pin_code` varchar(10) NOT NULL,
  `vehicle_model` varchar(50) NOT NULL,
  `reg_no` varchar(30) NOT NULL,
  `vin_number` varchar(50) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `vehicle_purchase_date` date NOT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `extended_warranty` enum('YES','NO') DEFAULT 'NO',
  `warranty_start_date` date DEFAULT NULL,
  `warranty_end_date` date DEFAULT NULL,
  `preferred_location` varchar(100) NOT NULL,
  `privilege_points_earned` decimal(10,2) DEFAULT 0.00,
  `extended_warranty_points` decimal(10,2) DEFAULT 0.00,
  `total_privilege_points` decimal(10,2) DEFAULT 0.00,
  `privilege_category` varchar(20) NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `created_on` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `customer_registration_no`, `customer_type`, `customer_name`, `address`, `pin_code`, `vehicle_model`, `reg_no`, `vin_number`, `mobile_number`, `vehicle_purchase_date`, `invoice_number`, `extended_warranty`, `warranty_start_date`, `warranty_end_date`, `preferred_location`, `privilege_points_earned`, `extended_warranty_points`, `total_privilege_points`, `privilege_category`, `created_by`, `created_on`, `created_at`) VALUES
(1, 'NC-2026-6846-4205', 'New_Customer', 'Test Customer name ', 'In an MVC (Model-View-Controller) architecture, the URL doesn\'t look directly for the file name (like customer_register.php). Instead, the URL talks to your Router (index.php), and the router tells the Controller which view file to load.', '363001', 'TIAGO', 'GJ13P3707', 'VINNO1234546846', '6355122735', '2026-02-27', 'INV 1234546846', 'YES', NULL, NULL, 'SG highway', 200.00, 200.00, 900.00, 'GREEN', 'User02', '2026-07-05', '2026-07-05 16:48:09'),
(2, 'NC-2026-6847-2205', 'Existing_Customer', 'Darshan G. Solanki', 'shyam nagar society , Memnagar , Ahemdabad', '380052', 'HARRIER', 'GJ13P3706', 'VINNO1234546847', '06355122735', '2026-06-12', 'INV 123454684678', 'NO', NULL, NULL, 'SG highway', 500.00, 0.00, 2760.00, 'GREEN', 'User02', '2026-07-05', '2026-07-05 16:50:43'),
(3, 'NC-2026-6846-8994', 'New_Customer', 'Darshan G. Solanki', 'shyam nagar society , Memnagar , Ahemdabad', '380052', 'SAFARI', 'GJ13P3706', 'VINNO1234546846', '06355122735', '2026-07-07', 'INV 1234546846', 'YES', '2026-07-12', '2026-07-31', 'SG highway', 500.00, 200.00, 700.00, 'GREEN', 'User02', '2026-07-06', '2026-07-06 17:26:53');

-- --------------------------------------------------------

--
-- Table structure for table `points_master`
--

CREATE TABLE `points_master` (
  `id` int(11) NOT NULL,
  `category` enum('CAR_MODEL','SERVICE') NOT NULL,
  `item_code` varchar(50) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `points` decimal(10,2) NOT NULL,
  `point_type` enum('FIXED','PER_AMOUNT') DEFAULT 'FIXED',
  `amount_unit` decimal(10,2) DEFAULT NULL,
  `status` enum('ACTIVE','INACTIVE') DEFAULT 'ACTIVE',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `points_master`
--

INSERT INTO `points_master` (`id`, `category`, `item_code`, `item_name`, `points`, `point_type`, `amount_unit`, `status`, `created_at`, `updated_at`) VALUES
(1, 'CAR_MODEL', 'TIAGO', 'TIAGO', 200.00, 'FIXED', NULL, 'ACTIVE', '2026-07-05 15:42:17', '2026-07-05 15:42:17'),
(2, 'CAR_MODEL', 'TIGOR', 'TIGOR', 200.00, 'FIXED', NULL, 'ACTIVE', '2026-07-05 15:42:17', '2026-07-05 15:42:17'),
(3, 'CAR_MODEL', 'PUNCH', 'PUNCH', 200.00, 'FIXED', NULL, 'ACTIVE', '2026-07-05 15:42:17', '2026-07-05 15:42:17'),
(4, 'CAR_MODEL', 'ALTROZ', 'ALTROZ', 300.00, 'FIXED', NULL, 'ACTIVE', '2026-07-05 15:42:17', '2026-07-05 15:42:17'),
(5, 'CAR_MODEL', 'NEXON', 'NEXON', 300.00, 'FIXED', NULL, 'ACTIVE', '2026-07-05 15:42:17', '2026-07-05 15:42:17'),
(6, 'CAR_MODEL', 'CURVV', 'CURVV', 400.00, 'FIXED', NULL, 'ACTIVE', '2026-07-05 15:42:17', '2026-07-05 15:42:17'),
(7, 'CAR_MODEL', 'HARRIER', 'HARRIER', 500.00, 'FIXED', NULL, 'ACTIVE', '2026-07-05 15:42:17', '2026-07-05 15:42:17'),
(8, 'CAR_MODEL', 'SAFARI', 'SAFARI', 500.00, 'FIXED', NULL, 'ACTIVE', '2026-07-05 15:42:17', '2026-07-05 15:42:17'),
(9, 'CAR_MODEL', 'SIERRA', 'SIERRA', 500.00, 'FIXED', NULL, 'ACTIVE', '2026-07-05 15:42:17', '2026-07-05 15:42:17'),
(10, 'SERVICE', 'EXTENDED_WARRANTY', 'Extended Warranty', 200.00, 'FIXED', NULL, 'ACTIVE', '2026-07-05 15:42:55', '2026-07-05 15:42:55'),
(11, 'SERVICE', 'AMC', 'AMC', 100.00, 'FIXED', NULL, 'ACTIVE', '2026-07-05 15:42:55', '2026-07-05 15:42:55'),
(12, 'SERVICE', 'INSURANCE_RENEWAL', 'Insurance Renewal', 100.00, 'FIXED', NULL, 'ACTIVE', '2026-07-05 15:42:55', '2026-07-05 15:42:55'),
(13, 'SERVICE', 'SERVICE_BILL', 'Service Bill', 1.00, 'PER_AMOUNT', 100.00, 'ACTIVE', '2026-07-05 15:42:55', '2026-07-05 15:42:55'),
(14, 'SERVICE', 'BODY_PAINT_JOB', 'Body & Paint Job', 1.00, 'PER_AMOUNT', 500.00, 'ACTIVE', '2026-07-05 15:42:55', '2026-07-05 15:42:55');

-- --------------------------------------------------------

--
-- Table structure for table `service_records`
--

CREATE TABLE `service_records` (
  `id` int(11) NOT NULL,
  `customer_registration_no` varchar(50) NOT NULL,
  `invoice_no` varchar(100) NOT NULL,
  `bill_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `extended_warranty` enum('Yes','No') NOT NULL DEFAULT 'No',
  `amc` enum('Yes','No') NOT NULL DEFAULT 'No',
  `insurance_renewal` enum('Yes','No') NOT NULL DEFAULT 'No',
  `earned_points` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_records`
--

INSERT INTO `service_records` (`id`, `customer_registration_no`, `invoice_no`, `bill_amount`, `extended_warranty`, `amc`, `insurance_renewal`, `earned_points`, `created_by`, `created_at`) VALUES
(1, 'NC-2026-6847-2205', 'TestServ123', 100000.00, 'Yes', 'Yes', 'Yes', 1400, 2, '2026-07-07 18:34:31'),
(2, 'NC-2026-6847-2205', 'TestServ123', 1000.00, 'No', 'No', 'No', 10, 2, '2026-07-07 18:37:38'),
(3, 'NC-2026-6846-4205', 'TestServ123', 10000.00, 'Yes', 'Yes', 'Yes', 500, 2, '2026-07-07 18:55:47'),
(4, 'NC-2026-6847-2205', 'TestServ123', 15000.00, 'Yes', 'Yes', 'Yes', 550, 2, '2026-07-08 17:31:48'),
(5, 'NC-2026-6847-2205', 'TestServ1232', 10000.00, 'No', 'No', 'No', 100, 2, '2026-07-08 17:32:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `Lname` varchar(50) NOT NULL,
  `user_type` varchar(30) NOT NULL,
  `emailid` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `fname`, `Lname`, `user_type`, `emailid`, `password`, `created_at`) VALUES
(1, 'User01', 'Darshan', 'Solanki', 'Admin', 'solankidarshan3593@gmail.com', '$2y$10$9nA3CiXUG85uYQilB/u1hutvAGfIj5C3N7OMIzCT5xTHNTuB4Wg8u', '2026-07-05 04:47:25'),
(2, 'User02', 'Darshan', 'Solanki', 'Admin', 'solankidarshan3593f@gmail.com', '$2y$10$2ugLpo7MtnyfBvfBlTE1ze86ROA65ZHrFsHZd/.RYIqzOyFnmnOTm', '2026-07-05 05:04:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bodyshop_records`
--
ALTER TABLE `bodyshop_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_customer_reg_no` (`customer_registration_no`),
  ADD KEY `idx_invoice_no` (`bodyshop_invoice_no`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_registration_no` (`customer_registration_no`);

--
-- Indexes for table `points_master`
--
ALTER TABLE `points_master`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_code` (`item_code`);

--
-- Indexes for table `service_records`
--
ALTER TABLE `service_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_customer_reg_no` (`customer_registration_no`),
  ADD KEY `idx_invoice_no` (`invoice_no`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `emailid` (`emailid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bodyshop_records`
--
ALTER TABLE `bodyshop_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `points_master`
--
ALTER TABLE `points_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `service_records`
--
ALTER TABLE `service_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
