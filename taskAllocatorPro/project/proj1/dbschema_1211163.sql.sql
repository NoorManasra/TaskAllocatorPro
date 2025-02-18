-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 16, 2025 at 02:19 PM
-- Server version: 8.0.40
-- PHP Version: 8.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web1211163_Tap`
--

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `id` int NOT NULL,
  `project_id` varchar(20) NOT NULL,
  `project_title` varchar(255) NOT NULL,
  `project_description` text NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `total_budget` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `supporting_documents` json DEFAULT NULL,
  `document_titles` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`id`, `project_id`, `project_title`, `project_description`, `customer_name`, `total_budget`, `start_date`, `end_date`, `supporting_documents`, `document_titles`, `created_at`) VALUES
(4, '1', 'shop website', 'zsxdcfgtvhjunpl[;', 'hector', 10000.00, '2025-01-01', '2025-01-31', '[\"67850ef8c9a4e1.07431871.jpg\"]', '[\"nothing-or\"]', '2025-01-13 13:02:48'),
(12, '3', 'website', 'qwertyu', 'khaled', 1245.00, '2025-01-22', '2025-01-30', '[\"67851261e0bce0.51055399.jpg\"]', '[\"nothing-or\"]', '2025-01-13 13:17:21'),
(14, '4', 'baby product shop website', 'a website that sell a baby products', 'jana', 300000.00, '2025-02-08', '2025-02-28', '[\"67858b7c765399.70305016.png\"]', '[\"baby_shop\"]', '2025-01-13 21:54:04'),
(15, '4', 'baby product shop website', 'a website that sell a baby products', 'jana', 300000.00, '2025-02-08', '2025-02-28', '[\"67858bf0cc0110.88146965.png\"]', '[\"baby_shop\"]', '2025-01-13 21:56:00'),
(16, '5', 'task allocator pro', 'to manage tasks', 'mohammad', 200000.00, '2025-02-05', '2025-02-08', '[\"6786c239c663a9.71902618.jpg\"]', '[\"nothing-or\"]', '2025-01-14 19:59:53');

-- --------------------------------------------------------

--
-- Table structure for table `project_team_leaders`
--

CREATE TABLE `project_team_leaders` (
  `id` int NOT NULL,
  `project_id` varchar(20) DEFAULT NULL,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `project_team_leaders`
--

INSERT INTO `project_team_leaders` (`id`, `project_id`, `user_id`) VALUES
(1, '1', 2),
(2, '1', 2),
(3, '4', 3),
(4, '4', 2),
(5, '4', 3),
(6, '3', 10),
(7, '3', 13),
(8, '5', 2),
(9, '5', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `project_id` varchar(20) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `effort` int NOT NULL,
  `status` enum('Pending','In Progress','Completed') DEFAULT 'Pending',
  `priority` enum('Low','Medium','High') DEFAULT 'Medium'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `task_name`, `description`, `project_id`, `start_date`, `end_date`, `effort`, `status`, `priority`) VALUES
(1, 'Task 1', 'Description for Task 1', '1', '2025-02-01', '2025-03-01', 3, 'Pending', 'High'),
(2, 'Task 2', 'Description for Task 2', '1', '2025-02-05', '2025-04-01', 5, 'In Progress', 'Medium'),
(3, 'Testing', 'test', '3', '2025-01-29', '2025-02-06', 1234, 'Pending', 'Low'),
(4, 'Testing', 'test', '3', '2025-01-29', '2025-02-06', 1234, 'Pending', 'Low'),
(5, 'Testing', 'test', '3', '2025-01-29', '2025-02-06', 1234, 'Pending', 'Low'),
(9, 'developing', 'develope the project', '4', '2025-01-15', '2025-01-31', 20, 'In Progress', 'Medium'),
(10, 'coding', 'write code', '3', '2025-01-15', '2025-01-24', 1, 'Pending', 'Low');

-- --------------------------------------------------------

--
-- Table structure for table `task_team_assignments`
--

CREATE TABLE `task_team_assignments` (
  `assignment_id` int NOT NULL,
  `task_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL,
  `contribution_percentage` decimal(5,2) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `status` enum('Pending','In Progress','Completed','Accepted','Rejected') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `task_team_assignments`
--

INSERT INTO `task_team_assignments` (`assignment_id`, `task_id`, `user_id`, `role`, `contribution_percentage`, `start_date`, `status`) VALUES
(1, 9, 5, 'Developer', 30.00, '2025-01-15', 'Pending'),
(2, 10, 5, 'Developer', 80.00, '2025-01-15', 'Pending'),
(3, 4, 15, 'Designer', 40.00, '2025-01-30', 'Completed'),
(4, 3, 17, 'Support', 10.00, '2025-02-05', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `address_flat_no` varchar(50) NOT NULL,
  `address_street` varchar(255) NOT NULL,
  `address_city` varchar(100) NOT NULL,
  `address_country` varchar(100) NOT NULL,
  `date_of_birth` date NOT NULL,
  `id_number` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(15) NOT NULL,
  `role` enum('Manager','Project Leader','Team Member') NOT NULL,
  `qualification` varchar(255) NOT NULL,
  `skills` text NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `address_flat_no`, `address_street`, `address_city`, `address_country`, `date_of_birth`, `id_number`, `email`, `telephone`, `role`, `qualification`, `skills`, `username`, `password_hash`) VALUES
(2, 'noor', '89', 'n', 'birzeir', 'pal', '2025-01-22', '123456', 'noor.manasra03@gmail.com', '12345678', 'Project Leader', 'ghrknt', 'no', 'noorm', '$2y$10$zPJ.Zj3hMCRaVaN6Dwj6TOMK5xjhG4q.u5fqBsRveA3nFr1cDBvf6'),
(3, 'rawan zayyat', '7', 'sofian', 'nablus', 'palestine', '2025-01-17', '1209876', 'rawanzay.yat92@gmail.com', '087765443', 'Project Leader', 'ghrknt', 'no', 'rawan17', '$2y$10$lunDRvTyw5ctcBxLJ5HnJ..7AE8G891Wjd.uO16HQtQCRhQsXCvFW'),
(4, 'jana zayyat', '7', 'sofian', 'nablus', 'palestine', '2025-01-23', '1229876', 'janazayyat@gmail.com', '0987543', 'Manager', 'accounting', 'no', 'janazayyat', '$2y$10$VL.RTrTgVjjc6BbSuMuTDemsggIhX4Uuq2ZQ8rew9kLdRk667MKv6'),
(5, 'salee jamal', '5', 'al ameer hassan', 'birzeit', 'palestine', '2025-02-16', '121268909', 'salee@gamil.com', '0598736354', 'Team Member', 'accounting', 'no', 'saleejamal', '$2y$10$KPhjzMO5IUud21r4PpuPeOzUwT5G3XglxPQHJttt1GEt6L5g1kbvO'),
(7, 'khaled manasra', '9', 'al ameer hassan', 'birzeit', 'palestine', '2025-01-22', '123456789899', 'khaled@gmail.com', '0598736354', 'Manager', 'student', 'playing pubg', 'khaled123', '$2y$10$Xu1wuvDtFwaD4IW0or4NquwSRc.rY4H3EOt/2LIoi/VwBrdu0DluW'),
(8, 'hanan', '90', 'al mazraa', 'birzeit', 'palestine', '2025-01-29', '123456788', 'hanan@gmail.com', '0598736354', 'Manager', 'student', 'no', 'hanan12', '$2y$10$hwARqGDKa/v1BhQJ735tH.PiQJls6OAcGAsuPlj1PasQGnW1C5/WC'),
(9, 'sella', '9', 'sofian', 'birzeit', 'palestine', '2025-01-30', '12111167', 'sella@gmail.com', '0598736354', 'Manager', 'student', 'no', 'SellaAh', '$2y$10$wPfv26TE4W9E3KAyTNgMCeK9WmQbAquw1PzDC2vIN5R9FPRR2eJ0C'),
(10, 'noor manasra', '9', 'al ameer hassan', 'birzeit', 'palestine', '2025-01-08', '1234445566', 'noor@company.com', '0598736354', 'Project Leader', 'programmer', 'java', 'noor11', '$2y$10$MPGP.9Ed.sA1K7pb1k2dD.c6cD2oP4LLkr0ZChU4T83PMMJF9Bxcq'),
(13, 'noor manasra', '9', 'al ameer hassan', 'birzeit', 'palestine', '2025-01-22', '1122334455', 'noor.manasra03333@gmail.com', '0598736354', 'Project Leader', 'programmer', 'java', 'noor12222', '$2y$10$HRkDSXoQEGF6/AleFtPUb.CQ.CmsWHM5eAQ2LpjWkfjtMEuK5qBLG'),
(14, 'lara muhana', '9', 'abu shekhedem', 'ramallah', 'palestine', '2025-02-01', '1122445566333', 'lara@gmail.com', '0598736354', 'Manager', 'busniss', 'no', 'lara12', '$2y$10$II7./6XVpZjJJjPhzEKe7e.vLQMfGuoTrkxwXYRdoijk/E1vR.xi2'),
(15, 'ahmad', '7', 'al ameer hassan', 'birzeit', 'palestine', '2025-02-08', '888999666', 'ahmad@gmail.com', '12345678', 'Team Member', 'busniss', 'nothing', 'ahmad26', '$2y$10$GcmcecM0F6A1jCpbmRqwXu7ac58FVZJOn8j/RtkX10iGiS9aJ1wc.'),
(17, 'alma mohammad', '5', 'al ameer hassan', 'birzeit', 'palestine', '2025-01-29', '0099988666', 'alam@gmail.com', '0987543', 'Team Member', 'programmer', 'nothing', 'alma11', '$2y$10$W8dHwTgDfsZ9f9Wd4THBAuZ0uI/soAh3rTsYtsf0SOv7iKkCRfdpe');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_project_id` (`project_id`);

--
-- Indexes for table `project_team_leaders`
--
ALTER TABLE `project_team_leaders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `task_team_assignments`
--
ALTER TABLE `task_team_assignments`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `id_number` (`id_number`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `project_team_leaders`
--
ALTER TABLE `project_team_leaders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `task_team_assignments`
--
ALTER TABLE `task_team_assignments`
  MODIFY `assignment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `project_team_leaders`
--
ALTER TABLE `project_team_leaders`
  ADD CONSTRAINT `project_team_leaders_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`),
  ADD CONSTRAINT `project_team_leaders_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `project` (`project_id`);

--
-- Constraints for table `task_team_assignments`
--
ALTER TABLE `task_team_assignments`
  ADD CONSTRAINT `task_team_assignments_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`task_id`),
  ADD CONSTRAINT `task_team_assignments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
