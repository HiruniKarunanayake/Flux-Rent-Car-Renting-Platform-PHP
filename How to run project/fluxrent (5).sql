-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2025 at 11:34 AM
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
-- Database: `fluxrent`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`) VALUES
(2, 'smith@gmail.com', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `with_driver` tinyint(1) DEFAULT 0,
  `user_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `driver_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `vehicle_id`, `start_date`, `end_date`, `location`, `with_driver`, `user_id`, `status`, `created_at`, `updated_at`, `driver_id`, `amount`) VALUES
(12, 11, '2024-10-18', '2024-10-22', 'colombo', 1, 7, 'approved', '2024-10-19 12:13:21', '2024-10-19 12:15:07', 9, 60000),
(13, 12, '2024-10-20', '2024-10-22', 'colombo', 1, 5, 'approved', '2024-10-19 16:53:06', '2024-10-19 16:54:02', 4, 150000),
(14, 11, '2024-10-23', '2024-10-24', 'colombo', 1, 8, 'pending', '2024-10-20 17:37:59', '2024-10-20 17:37:59', NULL, 24000),
(15, 13, '2024-10-22', '2024-10-24', 'colombo', 1, 9, 'approved', '2024-10-20 18:27:08', '2024-10-20 18:29:12', 5, 36000),
(16, 13, '2024-11-09', '2024-11-10', 'colombo', 1, 7, 'approved', '2024-10-21 07:14:44', '2024-10-21 07:52:42', 9, 24000),
(17, 13, '2024-10-24', '2024-10-29', 'colombo', 1, 8, 'approved', '2024-10-21 09:48:55', '2024-10-21 09:50:25', 9, 72000),
(18, 14, '2024-12-31', '2025-01-01', 'colombo', 1, 5, 'pending', '2024-12-30 16:57:59', '2024-12-30 16:57:59', NULL, 40000);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `preferred_contact_method` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `first_name`, `last_name`, `email`, `phone`, `password`, `dob`, `preferred_contact_method`, `created_at`) VALUES
(11, 'Tom', 'Wilson', 'tom@gmail.com', '1111555333', '$2y$10$v5Tu0Bg3AGDmjJc238Ope.6VAwTe7.0SzxyZzPzlEVO0LHWWFEpqe', '1997-02-18', 'email', '2025-05-20 09:16:09');

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `createdDate` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `subject`, `message`, `createdDate`, `updated_at`) VALUES
(3, 'Kaneesha Siribaddana', 'kaneeshasiribaddana@gmail.com', '0767390862', 'kkdmc', 's fdfsdfds ', '2024-10-09 05:44:46', '2024-10-09 09:14:46'),
(8, 'ayesh', 'ayeshkaru@gmail.com', '0711810037', 'cars', 'bad', '2024-10-19 08:25:25', '2024-10-19 11:55:25');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` timestamp NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `owner_id`, `amount`, `payment_date`, `status`) VALUES
(2, 3, 1834.00, '2024-10-25 13:00:00', 'Paid'),
(4, 4, 5000.00, '2024-10-14 13:17:51', 'Paid'),
(5, 4, 2500.00, '2024-10-19 14:02:28', 'Paid'),
(6, 6, 1800.00, '2024-10-20 15:00:34', 'Paid'),
(7, 6, 1200.00, '2024-10-21 04:23:28', 'Paid'),
(8, 6, 3600.00, '2024-10-21 06:21:30', 'Paid');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `vehicle_id`, `customer_name`, `comment`, `rating`, `created_at`) VALUES
(1, 11, 'ayesh', 'good', 5, '2024-10-20 18:10:59'),
(2, 19, 'hiruni', 'good', 2, '2024-10-21 07:24:01'),
(3, 12, 'Hiruni', 'Highly recommend this vehicle.', 3, '2024-12-30 16:46:38'),
(4, 12, 'hiruni', 'perfecto', 4, '2025-03-24 08:40:01');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `make` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `year` year(4) NOT NULL,
  `type` varchar(100) NOT NULL,
  `fuel_type` varchar(50) NOT NULL,
  `transmission` varchar(50) NOT NULL,
  `seating_capacity` int(11) NOT NULL,
  `mileage` decimal(10,2) NOT NULL,
  `color` varchar(50) NOT NULL,
  `owner` int(11) NOT NULL DEFAULT 0,
  `driver` varchar(100) NOT NULL,
  `images` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `make`, `model`, `year`, `type`, `fuel_type`, `transmission`, `seating_capacity`, `mileage`, `color`, `owner`, `driver`, `images`, `created_at`, `updated_at`, `price`, `description`) VALUES
(11, 'mitsu', 'lancer', '2008', 'Sports Car', 'Diesel', 'Manual', 4, 12.00, '#bf2222', 5, 'with_driver', NULL, '2024-10-19 12:05:38', '2024-10-19 12:05:38', 12000.00, 'Features:\r\n\r\nAir Conditioning\r\nTouchscreen Infotainment System with Bluetooth and USB Connectivity\r\nRearview Camera\r\nCruise Control\r\nKeyless Entry\r\nAdaptive Cruise Control and Lane Assist\r\nABS with EBD (Electronic Brake Distribution)\r\nSpacious interior with comfortable fabric seats'),
(12, 'Toyota', 'corolla', '1995', 'SUV', 'Gasoline', 'Manual', 4, 65200.00, '#000000', 4, 'with_driver', NULL, '2024-10-19 16:50:23', '2024-10-19 17:35:30', 50000.00, 'good condition'),
(13, 'Suzuki  ', ' Wagon R', '2017', 'Van', 'Hybrid', 'Automatic', 4, 21.00, '#fafafa', 6, 'with_driver', NULL, '2024-10-20 17:52:19', '2024-10-20 17:52:19', 12000.00, 'The Wagon R stands out as a versatile hatchback, offering ample space and smart storage options thanks to its tall design. With a comfortable ride, decent features, and fuel-efficient engine, it\'s ideal for easy city commuting.'),
(14, 'Suzuki', ' Every', '2021', 'Van', 'Gasoline', 'Automatic', 6, 35.00, '#dc8e8e', 6, 'without_driver', NULL, '2024-10-20 17:58:28', '2024-10-20 17:58:28', 20000.00, 'Suzuki Bolan has ruled Pakistan’s commercial spaces for nearly four decades. Finally, the launch of Suzuki Every has been replaced with a similar concept but with a modern look. Every is a kei-class mini-van, meaning it’s designed for the compact car segment that’s immensely popular in Japan. Its compact size makes it an ideal choice for navigating crowds while still offering ample space for cargo or passengers. Its small engine, designed for efficiency and ease of maintenance, is perfect for those who prioritize fuel savings without sacrificing utility.'),
(15, 'Toyota', 'Prime', '2018', 'Sports Car', 'Hybrid', 'Automatic', 4, 35.00, '#000000', 7, 'with_driver', NULL, '2024-10-20 18:06:02', '2024-10-20 18:06:02', 25000.00, 'The 2018 Toyota Prius Prime is a plug-in hybrid version of the regular Prius. It has a bigger battery that you can recharge with an external power source.'),
(16, 'Toyota ', ' Premio', '2018', 'Sports Car', 'Hybrid', 'Manual', 4, 25.00, '#e45353', 7, 'without_driver', NULL, '2024-10-20 18:09:06', '2024-10-20 18:09:06', 30000.00, 'The Premio is the successor of the Corona which first appeared in 1957. The Corona EXiV, a four-door hardtop sedan that appeared in 1989, was replaced by the Progrès, which was also briefly available with the Premio until 2007. The Premio is exclusive to Toyopet Store dealerships, as a smaller companion to the Mark X.'),
(17, 'Toyota', 'KDH', '2017', 'Van', 'Diesel', 'Manual', 10, 15.00, '#ffffff', 9, 'with_driver', NULL, '2024-10-20 19:54:36', '2024-10-20 19:54:36', 30000.00, 'good condition'),
(18, 'Nissan', 'Sunny', '2020', 'SUV', 'Gasoline', 'Automatic', 8, 20.00, '#eb0000', 9, 'without_driver', NULL, '2024-10-20 19:56:48', '2024-10-20 19:56:48', 23000.00, 'good condition '),
(19, 'Range-rover', 'Red', '2018', 'SUV', 'Hybrid', 'Manual', 6, 16.00, '#fa0000', 10, 'with_driver', NULL, '2024-10-20 20:02:45', '2024-10-20 20:04:19', 35000.00, 'normal'),
(20, 'Toyota', 'Vitz', '2012', 'Sports Car', 'Hybrid', 'Automatic', 6, 30.00, '#1255f3', 10, 'without_driver', NULL, '2024-10-20 20:06:04', '2024-10-20 20:06:04', 14000.00, 'Normal');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_drivers`
--

CREATE TABLE `vehicle_drivers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `emergency_contact_name` varchar(100) NOT NULL,
  `emergency_contact_phone` varchar(15) NOT NULL,
  `driving_experience` int(11) NOT NULL,
  `charg_per_day` int(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `vehicle_drivers`
--

INSERT INTO `vehicle_drivers` (`id`, `first_name`, `last_name`, `email`, `phone`, `password`, `dob`, `emergency_contact_name`, `emergency_contact_phone`, `driving_experience`, `charg_per_day`, `created_at`) VALUES
(11, 'David', 'David', 'david@gmail.com', '3333555567', '$2y$10$VP/tbhWqHRNu4qAosPiNJ.oBIUHLJgL9udFWus8LUPC4mdbLSyl4i', '1998-05-10', 'Tom', '1234567654', 3, 0, '2025-05-20 09:22:25');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_images`
--

CREATE TABLE `vehicle_images` (
  `id` int(11) NOT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `image_path` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `vehicle_images`
--

INSERT INTO `vehicle_images` (`id`, `vehicle_id`, `image_path`) VALUES
(1, 4, 'uploads/vehicles/4_1728304432_manager-background.jpg'),
(2, 4, 'uploads/vehicles/4_1728304432_bmw-m-series-seo-overview-ms-04.jpg'),
(16, 3, 'uploads/vehicles/3_1728485807_temple-of-the-sacred-tooth-relic-kandy.jpg'),
(17, 3, 'uploads/vehicles/3_1728485807_sri-lanka5-1024x576 (1).jpg'),
(23, 11, 'uploads/vehicles/11_1729339538_433697353_926875619228368_8614829857281548740_n.jpg'),
(24, 12, 'uploads/vehicles/12_1729356623_1.jpg'),
(25, 13, 'uploads/vehicles/13_1729446739_Suzuki Wagonr2.jpg'),
(26, 13, 'uploads/vehicles/13_1729446739_Suzuki Wagonr.jpg'),
(27, 14, 'uploads/vehicles/14_1729447108_Suzuki Every1.jpg'),
(28, 14, 'uploads/vehicles/14_1729447108_swift every.jpg'),
(29, 15, 'uploads/vehicles/15_1729447562_2018_PRIUS_PRIME_.jpg'),
(30, 15, 'uploads/vehicles/15_1729447562_toyota prime.jpg'),
(31, 16, 'uploads/vehicles/16_1729447746_Toyota Premio.jpg'),
(32, 16, 'uploads/vehicles/16_1729447746_Toyota-Premio-G-Superior.jpg'),
(33, 17, 'uploads/vehicles/17_1729454076_kdh2.jpg'),
(34, 17, 'uploads/vehicles/17_1729454076_kdh.jpeg'),
(35, 18, 'uploads/vehicles/18_1729454208_sunny.jpeg'),
(36, 18, 'uploads/vehicles/18_1729454208_Nissan-Sunny-11.jpg'),
(37, 19, 'uploads/vehicles/19_1729454565_range-rover-sport-facelift-01.jpg'),
(38, 20, 'uploads/vehicles/20_1729454764_vitz.jpeg'),
(39, 20, 'uploads/vehicles/20_1729454764_2023-Toyota-Vitz-XR-First-Drive-7.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_owners`
--

CREATE TABLE `vehicle_owners` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `vehicle_owners`
--

INSERT INTO `vehicle_owners` (`id`, `first_name`, `last_name`, `email`, `phone`, `password`, `dob`, `created_at`) VALUES
(11, 'Mark', 'Davis', 'mark@gmail.com', '2222444456', '$2y$10$ClcH4.7NvYL1c5PViGviyeXgGZ2Jxr5bznLRaKA9lId9jpTOj8Uae', '1995-01-09', '2025-05-20 09:18:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `FK_bookings_vehicle_drivers` (`driver_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_id` (`vehicle_id`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_vehicles_vehicle_owners` (`owner`);

--
-- Indexes for table `vehicle_drivers`
--
ALTER TABLE `vehicle_drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `vehicle_images`
--
ALTER TABLE `vehicle_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vehicle_owners`
--
ALTER TABLE `vehicle_owners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `vehicle_drivers`
--
ALTER TABLE `vehicle_drivers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `vehicle_images`
--
ALTER TABLE `vehicle_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `vehicle_owners`
--
ALTER TABLE `vehicle_owners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `FK_bookings_vehicle_drivers` FOREIGN KEY (`driver_id`) REFERENCES `vehicle_drivers` (`id`),
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `customers` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `vehicle_owners` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `FK_vehicles_vehicle_owners` FOREIGN KEY (`owner`) REFERENCES `vehicle_owners` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
