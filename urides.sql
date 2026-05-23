-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2024 at 09:58 PM
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
-- Database: `urides`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'Ahasan Arafath', 'ahasan151@gmail.com', 'Regarding to the Ride Sharing feature', 'jsndoei lkndjwoei njkdhfiwh dskjnfoiwi', '2024-10-04 14:54:38'),
(2, 'Mahdee Arnab', 'arnab0574@gmail.com', 'oiiewdh', 'oiewhr[whie', '2024-10-04 14:58:37');

-- --------------------------------------------------------

--
-- Table structure for table `cycles`
--

CREATE TABLE `cycles` (
  `cycle_id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `is_available` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cycles`
--

INSERT INTO `cycles` (`cycle_id`, `location`, `is_available`) VALUES
(1, 'Notunbazar', 0),
(2, 'UIU Campus', 0),
(3, 'UIU Campus', 1),
(4, 'Notunbazar', 0),
(5, 'Notunbazar', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cycle_rentals`
--

CREATE TABLE `cycle_rentals` (
  `rental_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `cycle_id` int(11) DEFAULT NULL,
  `rental_start_time` datetime NOT NULL,
  `rental_end_time` datetime DEFAULT NULL,
  `status` enum('rented','returned') NOT NULL,
  `phone` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cycle_rentals`
--

INSERT INTO `cycle_rentals` (`rental_id`, `user_id`, `cycle_id`, `rental_start_time`, `rental_end_time`, `status`, `phone`) VALUES
(1, 4, 1, '2024-09-13 20:28:00', '2024-09-21 13:36:08', '', '2423423'),
(2, 4, 2, '2024-09-13 02:41:00', '2024-09-20 00:38:05', '', '2423423'),
(3, 4, 3, '2024-09-13 02:41:00', '2024-09-20 00:39:02', '', '2423423'),
(4, 4, 4, '2024-09-14 13:12:00', '2024-09-20 00:39:05', '', '2423423'),
(5, 4, 5, '2024-09-14 20:57:00', '2024-09-20 00:39:07', '', '2423423'),
(6, 4, 1, '2024-09-12 00:40:00', '2024-09-21 13:36:08', '', '2423423'),
(7, 1, 4, '2024-10-01 21:42:00', NULL, 'rented', '01402038323'),
(8, 2, 5, '2024-09-21 01:30:00', '2024-09-21 01:30:43', '', '01234545'),
(9, 4, 1, '2024-09-14 20:28:00', NULL, 'rented', '2423423'),
(10, 19, 5, '2024-09-12 20:28:00', '2024-09-28 19:27:54', '', '24324'),
(11, 10, 2, '2024-09-14 20:02:00', NULL, 'rented', '4368734738'),
(12, 18, 5, '2024-10-01 00:40:00', '2024-10-07 02:30:38', '', '01751423255'),
(13, 18, 5, '2024-09-26 11:00:00', '2024-10-07 02:30:38', '', '01751423255'),
(14, 18, 3, '2024-09-21 20:02:00', '2024-10-07 02:31:05', '', '01751423255'),
(15, 22, 5, '2024-09-14 20:57:00', '2024-10-08 23:47:48', '', '09871278387'),
(16, 22, 3, '2024-09-21 20:57:00', '2024-10-08 23:49:58', '', '09871278387');

-- --------------------------------------------------------

--
-- Table structure for table `declined_requests`
--

CREATE TABLE `declined_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ride_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `declined_requests`
--

INSERT INTO `declined_requests` (`id`, `user_id`, `ride_id`) VALUES
(6, 1, 5),
(11, 9, 5),
(17, 7, 14),
(18, 7, 14),
(19, 1, 17),
(20, 1, 17),
(21, 19, 22),
(23, 6, 29),
(24, 6, 29),
(25, 18, 31),
(27, 22, 31);

-- --------------------------------------------------------

--
-- Table structure for table `garages`
--

CREATE TABLE `garages` (
  `garage_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `timestamp`) VALUES
(2, 1, 9, 'hey', '2024-09-26 21:05:16'),
(3, 1, 9, 'Hello', '2024-09-26 21:09:07'),
(4, 7, 1, 'Hey.', '2024-09-27 16:19:17'),
(5, 1, 7, 'Hello', '2024-09-27 16:19:24'),
(6, 7, 1, 'Hey.', '2024-09-27 16:19:33'),
(7, 7, 1, 'Hey.', '2024-09-27 16:20:55'),
(8, 7, 1, 'Hey.', '2024-09-27 16:21:00'),
(9, 7, 1, 'hello', '2024-09-27 20:24:10'),
(10, 16, 10, 'Hey', '2024-10-02 19:23:56'),
(11, 10, 16, 'jkjkj', '2024-10-02 21:19:41'),
(12, 10, 16, 'Hey.', '2024-10-02 21:19:56'),
(13, 16, 10, 'Whats up?', '2024-10-03 08:42:00'),
(14, 10, 16, 'Awesom', '2024-10-03 08:42:20'),
(15, 10, 16, 'Nice', '2024-10-03 09:19:58'),
(16, 10, 18, 'What\'s up?', '2024-10-03 09:26:11'),
(17, 18, 10, 'Awesome', '2024-10-03 09:28:05'),
(18, 18, 10, 'What about you', '2024-10-03 09:28:26'),
(19, 10, 18, 'I\'m fine', '2024-10-03 09:28:39'),
(20, 18, 10, 'Where to go?', '2024-10-03 11:46:31'),
(24, 10, 18, 'UIU', '2024-10-03 13:50:24'),
(25, 18, 10, 'Let\'s go...', '2024-10-03 13:58:54'),
(26, 10, 18, 'Al right', '2024-10-03 13:59:19'),
(27, 18, 10, 'Hey', '2024-10-03 13:59:47'),
(28, 10, 18, 'hey', '2024-10-03 14:00:06'),
(29, 18, 10, 'Hey', '2024-10-03 14:50:42'),
(30, 10, 18, 'Hey', '2024-10-03 14:51:09'),
(31, 10, 18, 'Hello', '2024-10-03 14:52:10'),
(32, 1, 10, 'Hey', '2024-10-03 14:58:20'),
(33, 10, 1, 'Hello', '2024-10-03 14:58:55'),
(34, 10, 1, 'What\'s up?', '2024-10-03 14:59:12'),
(35, 1, 10, 'Awesome', '2024-10-03 14:59:18'),
(36, 10, 18, 'Hey', '2024-10-03 15:10:28'),
(37, 10, 1, 'Hey', '2024-10-03 15:31:31'),
(38, 10, 19, 'Hello', '2024-10-03 17:21:18'),
(39, 10, 19, 'Hey', '2024-10-03 17:22:22'),
(40, 19, 10, 'Hello', '2024-10-03 17:22:26'),
(41, 10, 19, 'ad', '2024-10-03 17:22:56'),
(42, 19, 10, 'zx', '2024-10-03 17:23:09'),
(43, 10, 18, 'Hello', '2024-10-03 18:15:55'),
(44, 10, 18, 'hhh', '2024-10-03 18:20:46'),
(45, 10, 18, 'hg', '2024-10-03 18:28:39'),
(46, 10, 18, 'iuiui', '2024-10-03 18:28:45'),
(47, 10, 18, 'fdd', '2024-10-03 18:33:13'),
(48, 10, 18, 'dsjfjdf', '2024-10-03 18:33:19'),
(49, 10, 18, 'df', '2024-10-03 18:35:37'),
(50, 10, 18, 'd', '2024-10-03 18:35:57'),
(51, 18, 10, 'hey', '2024-10-03 20:51:19'),
(52, 1, 7, 'Hello', '2024-10-03 21:05:52'),
(53, 7, 1, 'Hi', '2024-10-03 21:08:10'),
(54, 1, 7, 'Hi', '2024-10-03 21:08:14'),
(55, 6, 21, 'hey', '2024-10-04 15:08:15'),
(56, 6, 20, 'Hello', '2024-10-04 15:08:50'),
(57, 21, 6, 'Hello', '2024-10-04 15:24:57'),
(58, 6, 21, 'What\'s up?', '2024-10-04 15:25:13'),
(60, 1, 6, 'Hey', '2024-10-04 19:07:32'),
(61, 6, 1, 'Hello', '2024-10-04 19:09:10'),
(62, 18, 20, 'Hello', '2024-10-05 07:46:36'),
(63, 18, 10, 'hello', '2024-10-05 08:00:59'),
(64, 10, 18, 'hey', '2024-10-05 08:01:03'),
(65, 18, 10, 'Hello', '2024-10-07 03:48:30'),
(66, 10, 18, 'Hey', '2024-10-07 03:48:33'),
(67, 23, 22, 'Hey Cristiano.', '2024-10-07 05:03:43'),
(68, 10, 22, 'Hey', '2024-10-08 17:56:22'),
(69, 10, 22, 'Hello', '2024-10-08 17:56:45'),
(70, 22, 10, 'Hi', '2024-10-08 17:56:49'),
(71, 10, 22, 'He', '2024-10-08 18:00:03'),
(72, 22, 10, 'Hey', '2024-10-08 18:00:11');

-- --------------------------------------------------------

--
-- Table structure for table `rides`
--

CREATE TABLE `rides` (
  `ride_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ride_type` enum('rickshaw','bike') NOT NULL,
  `start_location` varchar(255) NOT NULL,
  `end_location` varchar(255) NOT NULL,
  `ride_date` date NOT NULL,
  `ride_time` time NOT NULL,
  `status` enum('pending','confirmed','completed','cancelled') NOT NULL,
  `accepter_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rides`
--

INSERT INTO `rides` (`ride_id`, `user_id`, `ride_type`, `start_location`, `end_location`, `ride_date`, `ride_time`, `status`, `accepter_id`) VALUES
(5, 2, 'rickshaw', 'UIU Campus', 'Sayednagar', '2024-09-21', '12:40:00', 'confirmed', NULL),
(12, 9, 'rickshaw', 'Mirpur', 'UIU Campus', '2024-09-26', '11:00:00', 'completed', NULL),
(13, 4, 'bike', 'Mirpur', 'UIU Campus', '2024-09-26', '12:00:00', 'confirmed', NULL),
(14, 4, 'bike', 'Mirpur', 'UIU Campus', '2024-09-26', '12:00:00', 'completed', 1),
(15, 2, 'rickshaw', 'Sayednagar', 'UIU Campus', '2024-09-27', '11:00:00', 'completed', 7),
(16, 7, 'bike', 'Mirpur', 'UIU Campus', '2024-09-26', '12:00:00', 'completed', 1),
(17, 7, 'bike', 'Mirpur', 'UIU Campus', '2024-09-26', '12:00:00', 'completed', 8),
(18, 8, 'rickshaw', 'Sayednagar', 'UIU Campus', '2024-09-27', '11:00:00', 'completed', 7),
(20, 16, 'bike', 'Mirpur', 'UIU Campus', '2024-09-28', '08:28:00', 'completed', 19),
(22, 18, 'bike', 'Mohammadpur', 'UIU Campus', '2024-09-28', '12:00:00', 'completed', 10),
(23, 8, 'rickshaw', 'UIU Campus', 'Sayednagar', '2024-09-26', '11:30:00', 'completed', 19),
(25, 10, 'rickshaw', 'Mirpur', 'UIU Campus', '2024-10-03', '12:00:00', 'completed', 19),
(26, 19, 'rickshaw', 'UIU Campus', 'Sayednagar', '2024-09-12', '12:00:00', 'completed', 10),
(27, 10, 'rickshaw', 'UIU Campus', 'Sayednagar', '2024-09-12', '12:00:00', 'completed', 18),
(28, 1, 'bike', 'Mohakhali', 'UIU Campus', '2024-10-01', '08:02:00', 'confirmed', 6),
(29, 8, 'rickshaw', 'Familybazar', 'UIU Campus', '2024-02-10', '08:02:00', 'pending', NULL),
(30, 20, 'bike', 'UIU Campus', 'Uttara', '2024-10-02', '08:57:00', 'confirmed', 18),
(31, 21, 'bike', 'UIU Campus', 'Mirpur', '2024-10-03', '11:00:00', 'pending', NULL),
(32, 6, 'rickshaw', 'UIU Campus', 'Notunbazar', '2024-10-02', '11:00:00', 'completed', 18),
(34, 23, 'rickshaw', 'UIU Campus', 'Badda', '2024-09-14', '08:02:00', 'completed', 22),
(37, 22, 'bike', 'Dhanmondi', 'UIU Campus', '2024-10-02', '12:00:00', 'pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ride_reviews`
--

CREATE TABLE `ride_reviews` (
  `review_id` int(11) NOT NULL,
  `ride_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shuttles`
--

CREATE TABLE `shuttles` (
  `shuttle_id` int(11) NOT NULL,
  `shuttle_name` varchar(100) NOT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `capacity` int(11) NOT NULL,
  `route` varchar(255) NOT NULL,
  `schedule` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shuttle_schedules`
--

CREATE TABLE `shuttle_schedules` (
  `schedule_id` int(11) NOT NULL,
  `shuttle_id` int(11) DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `days` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shuttle_tracking`
--

CREATE TABLE `shuttle_tracking` (
  `track_id` int(11) NOT NULL,
  `shuttle_id` int(11) DEFAULT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `code` int(6) DEFAULT NULL,
  `user_type` enum('rider','requester') NOT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `bike_number` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `student_id`, `phone`, `code`, `user_type`, `profile_photo`, `bike_number`) VALUES
(1, 'Mubasshir Ahmed', 'arnab0574@gmail.com', '12345', '01122263', '01402038323', NULL, 'rider', 'memory.jpg', NULL),
(2, 'Matt Henry', 'henry69@gmail.com', '12345', '011222111', '01234545', NULL, 'rider', NULL, NULL),
(4, 'Arnab', 'arnab@gmail.com', '12345', '011222333', '2423423', NULL, 'rider', 'uploads/222.png', NULL),
(5, 'xyz', 'xyz@gmail.com', '123', '1244', '123342', NULL, 'rider', NULL, NULL),
(6, 'Noman Hossain', 'mhossain222159@bscse.uiu.ac.bd', '12345', '011222159', '01319641152', NULL, 'rider', 'noman.jpg', NULL),
(7, 'Toni Kroos', 'toni123@gmail.com', '12345', '1234567', '123445', NULL, 'rider', NULL, NULL),
(8, 'Eden Hazard', 'hazard@gmail.com', '123', '123456', '12323232', NULL, 'rider', NULL, NULL),
(9, 'Jimmy Anderson', 'jimmy@gmail.com', '123', '1234', '2345566', NULL, 'requester', 'uploads/222.png', NULL),
(10, 'ABD', 'abd@gmail.com', '123', '123', '4368734738', NULL, 'requester', 'uploads/Dog.png', NULL),
(16, 'ABC', 'abc@gmail.com', '123', '1222', '12345', NULL, 'rider', 'uploads/OIP.jpg', '12565137623'),
(18, 'Mubasshir Ahmed Arnab', 'arnab07@gmail.com', '12345', '5686788', '01751423255', NULL, 'rider', 'uploads/Arnab 2.jpeg', '216731821'),
(19, 'AS', 'asss@gmail.com', '123', '112233', '24324', NULL, 'requester', 'uploads/Arnab 2.jpeg', NULL),
(20, 'Monirul Islam', 'monirul88@gmail.com', '123', '011222088', '123456', NULL, 'rider', 'uploads/Monirul.jpg', '88#128'),
(21, 'Ahasan Arafath', 'arafath151@gmail.com', '12345', '011222151', '01712345678', NULL, 'rider', 'uploads/arafath.jpeg', '231@!4561#'),
(22, 'Cristiano Ronaldo', 'ronaldo@gmail.com', '123', '011222222', '098712783872', NULL, 'rider', 'uploads/Ronaldo.jpg', '87232f287t3d'),
(23, 'Tom Cruise', 'tomcruise07@gmail.com', '123', '011267257', '01234612223', NULL, 'rider', 'uploads/Tom.jpg', '627f2673dq672');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cycles`
--
ALTER TABLE `cycles`
  ADD PRIMARY KEY (`cycle_id`);

--
-- Indexes for table `cycle_rentals`
--
ALTER TABLE `cycle_rentals`
  ADD PRIMARY KEY (`rental_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `cycle_id` (`cycle_id`);

--
-- Indexes for table `declined_requests`
--
ALTER TABLE `declined_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `declined_requests_ibfk_2` (`ride_id`);

--
-- Indexes for table `garages`
--
ALTER TABLE `garages`
  ADD PRIMARY KEY (`garage_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `rides`
--
ALTER TABLE `rides`
  ADD PRIMARY KEY (`ride_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_accepter_id` (`accepter_id`);

--
-- Indexes for table `ride_reviews`
--
ALTER TABLE `ride_reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `ride_id` (`ride_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `shuttles`
--
ALTER TABLE `shuttles`
  ADD PRIMARY KEY (`shuttle_id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Indexes for table `shuttle_schedules`
--
ALTER TABLE `shuttle_schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `shuttle_id` (`shuttle_id`);

--
-- Indexes for table `shuttle_tracking`
--
ALTER TABLE `shuttle_tracking`
  ADD PRIMARY KEY (`track_id`),
  ADD KEY `shuttle_id` (`shuttle_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cycles`
--
ALTER TABLE `cycles`
  MODIFY `cycle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cycle_rentals`
--
ALTER TABLE `cycle_rentals`
  MODIFY `rental_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `declined_requests`
--
ALTER TABLE `declined_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `garages`
--
ALTER TABLE `garages`
  MODIFY `garage_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `rides`
--
ALTER TABLE `rides`
  MODIFY `ride_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `ride_reviews`
--
ALTER TABLE `ride_reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shuttles`
--
ALTER TABLE `shuttles`
  MODIFY `shuttle_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shuttle_schedules`
--
ALTER TABLE `shuttle_schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shuttle_tracking`
--
ALTER TABLE `shuttle_tracking`
  MODIFY `track_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cycle_rentals`
--
ALTER TABLE `cycle_rentals`
  ADD CONSTRAINT `cycle_rentals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `cycle_rentals_ibfk_2` FOREIGN KEY (`cycle_id`) REFERENCES `cycles` (`cycle_id`);

--
-- Constraints for table `declined_requests`
--
ALTER TABLE `declined_requests`
  ADD CONSTRAINT `declined_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `declined_requests_ibfk_2` FOREIGN KEY (`ride_id`) REFERENCES `rides` (`ride_id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `rides`
--
ALTER TABLE `rides`
  ADD CONSTRAINT `fk_accepter_id` FOREIGN KEY (`accepter_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `rides_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `ride_reviews`
--
ALTER TABLE `ride_reviews`
  ADD CONSTRAINT `ride_reviews_ibfk_1` FOREIGN KEY (`ride_id`) REFERENCES `rides` (`ride_id`),
  ADD CONSTRAINT `ride_reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `shuttles`
--
ALTER TABLE `shuttles`
  ADD CONSTRAINT `shuttles_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `shuttle_schedules`
--
ALTER TABLE `shuttle_schedules`
  ADD CONSTRAINT `shuttle_schedules_ibfk_1` FOREIGN KEY (`shuttle_id`) REFERENCES `shuttles` (`shuttle_id`);

--
-- Constraints for table `shuttle_tracking`
--
ALTER TABLE `shuttle_tracking`
  ADD CONSTRAINT `shuttle_tracking_ibfk_1` FOREIGN KEY (`shuttle_id`) REFERENCES `shuttles` (`shuttle_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
