-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2023 at 09:36 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `contact_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `admin_name`, `email`, `password`) VALUES
(1, 'admin', 'admin@gmail.com', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `document` varchar(255) NOT NULL,
  `document_type` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `user_id`, `name`, `email`, `phone`, `address`, `document`, `document_type`) VALUES
(6, 27, 'Nischal Acharya', 'Nischal060@gmail.com', '+9779806081469', 'Gauradaha,Jhapa Nepal', 'WhatsApp Image 2023-06-19 at 11.16.15 AM.jpg', 'ID'),
(7, 27, 'Nischal Acharya', 'Nischal060@gmail.com', '+9779806081469', 'Gauradaha,Jhapa Nepal', 'pan no-min.jpg', 'Other'),
(8, 27, 'Nischal Acharya', 'Nischal060@gmail.com', '+9779806081469', 'Gauradaha,Jhapa Nepal', 'WhatsApp Image 2023-06-19 at 11.16.15 AM.jpeg', 'Passport'),
(9, 27, 'Nischal Acharya', 'Nischal060@gmail.com', '+9779806081469', 'Gauradaha,Jhapa Nepal', 'Screenshot_2019-03-10-05-43-57-079_com.google.android.youtube.png', 'Passport');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `action` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `action`, `created_at`, `user_id`) VALUES
(32, 'Added a contact', '2023-06-22 07:37:43', 22),
(33, 'Edited a contact', '2023-06-22 07:37:54', 22),
(34, 'Username updated from \'test\' to \'tes2\'', '2023-06-22 16:47:31', 24),
(35, 'Username updated from \'test\' to \'\'', '2023-06-22 18:17:09', 27),
(36, 'Email updated from \'Nischal060@gmail.com\' to \'\'', '2023-06-22 18:17:09', 27),
(37, 'Username updated from \'\' to \'test\'', '2023-06-22 18:17:40', 27),
(38, 'Email updated from \'\' to \'Nischal640@gmail.com\'', '2023-06-22 18:17:40', 27),
(39, 'Added a new contact', '2023-06-22 18:26:13', 27),
(40, 'Edited a contact', '2023-06-22 18:27:06', 27),
(41, 'Added a contact', '2023-06-22 18:27:17', 27),
(42, 'Added a contact', '2023-06-22 18:28:00', 27),
(43, 'Added a contact', '2023-06-22 18:28:58', 27),
(44, 'Added a contact', '2023-06-22 18:30:32', 27),
(45, 'Deleted a contact', '2023-06-22 19:02:14', 27),
(46, 'Username updated from \'lekhanath\' to \'lekhanath acharya\'', '2023-06-23 07:31:58', 29),
(47, 'Email updated from \'lekhanath@gmail.com\' to \'lekhanath69@gmail.com\'', '2023-06-23 07:31:58', 29),
(48, 'Added a contact', '2023-06-23 07:33:15', 27),
(49, 'Edited a contact', '2023-06-23 07:33:24', 27),
(50, 'Deleted a contact', '2023-06-23 07:33:30', 27);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `profile_picture`) VALUES
(27, 'user', 'Nischal060@gmail.com', 'user', 'WhatsApp Image 2023-06-19 at 11.16.15 AM.jpg'),
(28, 'admin', 'admin@gmail.com', 'admin', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contacts`
--
ALTER TABLE `contacts`
  ADD CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
