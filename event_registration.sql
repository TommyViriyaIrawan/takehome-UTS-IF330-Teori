SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `name`, `email`, `password`) VALUES
(1, 'ayam', 'goreng@gmail.com', '$2y$10$Q6VeQm/KJMhOnPFGBlK.mePv0Wqs77zfHkPmwu2l7rw6GffhvpJpG');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `event_name` varchar(255) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `event_time` time DEFAULT NULL,
  `event_location` varchar(255) DEFAULT NULL,
  `event_description` text DEFAULT NULL,
  `max_participants` int(11) DEFAULT NULL,
  `event_image` varchar(255) DEFAULT NULL,
  `status` enum('open','closed','canceled') DEFAULT 'open'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_name`, `event_date`, `event_time`, `event_location`, `event_description`, `max_participants`, `event_image`, `status`) VALUES
(1, 'Fanmeet Lisa', '2024-10-29', NULL, 'Ancol', 'Lisa Blackpink', 5000, NULL, 'open'),
(2, 'comifuro', '2024-10-30', NULL, 'ICE BSD', 'wibu', 10000, NULL, 'open'),
(3, 'potong sapi', '2024-10-08', NULL, 'masjid', 'makan makan', 300, NULL, 'open'),
(4, 'makan makan', '2024-10-10', NULL, 'rumah hexsel', 'indomie 50 biji', 10, NULL, 'open'),
(5, 'makan makan', '2024-10-10', NULL, 'rumah hexsel', 'indomie 50 biji', 10, NULL, 'open'),
(8, 'asdf', '2024-10-15', NULL, 'adsf', 'asdf', 6, NULL, 'open');

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `registration_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`) VALUES
(11, 'Tommy Viriya Irawan', 'tommyviriya148@gmail.com', '$2y$10$O.ooDh/Adrs0q2sk6F.KhOEXl0giPiR9oxxy5VHzYVBp5oklGsJt.', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`registration_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `registration_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
