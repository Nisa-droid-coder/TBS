-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 30, 2025 at 04:56 AM
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
-- Database: `etow_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `book_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `vehicleType` varchar(255) NOT NULL,
  `licensePlate` varchar(7) NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`book_id`, `name`, `phone`, `email`, `vehicleType`, `licensePlate`, `location`, `description`, `status`) VALUES
(1, 'Adam Harris', '0128736484', 'adam@gmail.com', 'Car', 'KLA2829', 'Taman Bukit Beruntung', 'Car Crash', 'accepted'),
(2, 'Karol Harris', '01735273954', 'omar@gmail.com', 'Car', 'KLA2829', 'Jalan Kemuning', 'My tires flat', 'accepted'),
(3, 'Jacob Diaz', '01165437895', 'jacob@gmail.com', 'Truck', 'JWP9304', 'Jalan Air Hitam', 'My car got hit by the tree.', 'accepted');

-- --------------------------------------------------------

--
-- Table structure for table `insurance_verification`
--

CREATE TABLE `insurance_verification` (
  `insurance_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `insurance_status` enum('pending','verified','not_verified') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `insurance_verification`
--

INSERT INTO `insurance_verification` (`insurance_id`, `book_id`, `price`, `insurance_status`) VALUES
(1, 1, 100.00, 'verified'),
(2, 2, 50.00, 'not_verified'),
(3, 3, 50.50, 'not_verified');

-- --------------------------------------------------------

--
-- Table structure for table `managejob`
--

CREATE TABLE `managejob` (
  `job_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `insurance_id` int(11) NOT NULL,
  `tow_provider_id` int(11) NOT NULL,
  `job_status` enum('accepted','in-progress','completed','rejected') DEFAULT NULL,
  `estimated_arrival` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `managejob`
--

INSERT INTO `managejob` (`job_id`, `book_id`, `insurance_id`, `tow_provider_id`, `job_status`, `estimated_arrival`) VALUES
(4, 1, 1, 1, 'accepted', '2025-01-26 18:01:00'),
(5, 2, 2, 1, 'in-progress', '2025-01-26 18:22:00'),
(16, 3, 3, 3, 'accepted', '2025-01-30 09:56:00');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','completed','failed') NOT NULL,
  `payment_method` enum('credit_card','debit_card','paypal','bank_transfer') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `book_id`, `amount`, `payment_status`, `payment_method`, `created_at`) VALUES
(1, 1, 100.00, 'completed', 'credit_card', '2025-01-27 01:36:37'),
(3, 2, 50.00, 'completed', 'debit_card', '2025-01-27 01:40:43'),
(4, 3, 50.50, 'completed', 'credit_card', '2025-01-30 02:37:15');

-- --------------------------------------------------------

--
-- Table structure for table `tow_providers`
--

CREATE TABLE `tow_providers` (
  `tow_provider_id` int(11) NOT NULL,
  `provider_name` varchar(255) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `vehiclePlate` varchar(7) NOT NULL,
  `locationTow` varchar(255) NOT NULL,
  `timeAvailable` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tow_providers`
--

INSERT INTO `tow_providers` (`tow_provider_id`, `provider_name`, `company_name`, `vehiclePlate`, `locationTow`, `timeAvailable`) VALUES
(1, 'Miles Doe', 'CarSure', 'KYI0985', 'Cyberjaya', '9:00am - 2:00pm'),
(3, 'Jason Lyke', 'CarSure', 'WOB0236', 'Selangor', '8:00am - 4:00pm');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','admin','insuranceAgent','serviceProvider') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiration` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `created_at`, `reset_token`, `token_expiration`) VALUES
(1, 'adam@gmail.com', '$2y$10$Hol/puvlQAh1BRtxyPImVO0MtvaTsRihWPdHalkaTL4I5HqssCJ0y', 'customer', '2025-01-26 03:55:18', NULL, NULL),
(2, 'nisashahnahar@gmail.com', '$2y$10$kVHdUyQ/LDTjUyelu.Susedc8G/vS.ob4i4xcY1h1YatSbh1WLwLe', 'admin', '2025-01-27 02:04:00', '222b257b53293ac5da357e09284c6df01853d42618c3053fad2537928ba55eaba0181c5e762343cefc94c6b366dfb9b8d966', '2025-01-28 21:20:46'),
(3, 'Noah@gmail.com', '$2y$10$j9Fq8t6eptP0eCepM2V9qeWOwIksvrw7dyrWdY5IIOhP6k5zLF6jS', 'insuranceAgent', '2025-01-30 01:22:30', NULL, NULL),
(4, 'Miles@gmail.com', '$2y$10$3T1/GgkB32GDb1CHu3pSluj8ourSTInwlKJnC3L2yf.eh12A7hFum', 'serviceProvider', '2025-01-30 01:32:01', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `insurance_verification`
--
ALTER TABLE `insurance_verification`
  ADD PRIMARY KEY (`insurance_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `managejob`
--
ALTER TABLE `managejob`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `insurance_id` (`insurance_id`),
  ADD KEY `tow_provider_id` (`tow_provider_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `tow_providers`
--
ALTER TABLE `tow_providers`
  ADD PRIMARY KEY (`tow_provider_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `insurance_verification`
--
ALTER TABLE `insurance_verification`
  MODIFY `insurance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `managejob`
--
ALTER TABLE `managejob`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tow_providers`
--
ALTER TABLE `tow_providers`
  MODIFY `tow_provider_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `insurance_verification`
--
ALTER TABLE `insurance_verification`
  ADD CONSTRAINT `insurance_verification_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `bookings` (`book_id`) ON DELETE CASCADE;

--
-- Constraints for table `managejob`
--
ALTER TABLE `managejob`
  ADD CONSTRAINT `managejob_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `bookings` (`book_id`),
  ADD CONSTRAINT `managejob_ibfk_2` FOREIGN KEY (`insurance_id`) REFERENCES `insurance_verification` (`insurance_id`),
  ADD CONSTRAINT `managejob_ibfk_3` FOREIGN KEY (`tow_provider_id`) REFERENCES `tow_providers` (`tow_provider_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `bookings` (`book_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
