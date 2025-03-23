-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2025 at 04:33 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `online_job_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`) VALUES
(1, 'admin@gmail.com', '$2y$10$.9p.NMXRI.60Uxup4FamG..vs0FE6/W.FIHHWPQXxTNlhtOnPj9UO');

-- --------------------------------------------------------

--
-- Table structure for table `companyregistration`
--

CREATE TABLE `companyregistration` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `company_type_id` int(11) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `contact_name` varchar(100) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companyregistration`
--

INSERT INTO `companyregistration` (`id`, `user_id`, `company_name`, `phone`, `company_type_id`, `city`, `location`, `contact_name`, `contact_email`) VALUES
(15, 47, 'Hardin Foreman Plc', '+1 (919) 696-16', 1, 'pokhara', 'Ipsa qui ad similiq', 'Ifeoma Mcknight', 'qiqazecaq@mailinator.com'),
(16, 48, 'Neal and Mckenzie Inc', '+1 (404) 829-92', 1, 'lalitpur', 'Dolor et eu commodi', 'Alfonso Chambers', 'foguvofu@mailinator.com');

-- --------------------------------------------------------

--
-- Table structure for table `company_type`
--

CREATE TABLE `company_type` (
  `id` int(11) NOT NULL,
  `type_name` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company_type`
--

INSERT INTO `company_type` (`id`, `type_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'IT Company', 1, '2024-11-30 09:02:55', NULL),
(2, 'NGO', 1, '2024-11-30 09:02:55', NULL),
(3, 'accounting', 1, '2024-11-30 09:02:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `email` varchar(40) DEFAULT NULL,
  `user_type` enum('jobseeker','company') NOT NULL,
  `user_id` int(11) NOT NULL,
  `feedback_text` text NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `feedback_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','reviewed','resolved') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `email`, `user_type`, `user_id`, `feedback_text`, `rating`, `feedback_date`, `status`) VALUES
(2, NULL, 'jobseeker', 32, '', 0, '2025-02-27 07:22:07', 'reviewed'),
(3, 'jobseeker@gmail.com', 'jobseeker', 32, 'hello admin ', 0, '2025-02-27 07:55:43', 'reviewed'),
(4, 'company@gmail.com', 'company', 47, 'hello administartor please update the category of the job', 0, '2025-02-27 07:57:02', 'resolved');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `job_category_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `status` enum('open','reject','pending','close') DEFAULT 'pending',
  `location` varchar(255) DEFAULT NULL,
  `job_type` enum('Full-time','Part-time','Freelance') NOT NULL,
  `requirements` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `application_deadline` date DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `company_id`, `job_category_id`, `title`, `description`, `salary`, `status`, `location`, `job_type`, `requirements`, `created_at`, `application_deadline`, `updated_at`) VALUES
(16, 16, 1, 'Dolore quia in volup', 'Omnis ut do beatae s', '91.00', 'close', 'Minim vel dolores na', 'Part-time', 'skill is programming language', '2024-12-19 04:28:01', '2020-01-10', NULL),
(31, 16, NULL, 'Atque tenetur ullam', 'Officia aliquid ut i', '97.00', 'close', 'Laboriosam eligendi', 'Full-time', NULL, '2024-12-27 08:04:41', '2025-01-23', NULL),
(43, 15, 1, 'Earum et cumque exer', 'Dolor nulla cumque s', '95.00', 'open', 'Et voluptatem quis n', 'Part-time', 'advance level programming language', '2025-02-24 07:17:02', '2025-07-01', NULL),
(44, 15, 2, 'Est labore modi dol', 'At sint fugit volup', '59.00', 'open', 'Temporibus assumenda', 'Freelance', NULL, '2025-03-03 07:51:35', '2025-10-01', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobseeker`
--

CREATE TABLE `jobseeker` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `job_category_id` int(11) DEFAULT NULL,
  `skills` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobseeker`
--

INSERT INTO `jobseeker` (`id`, `user_id`, `first_name`, `last_name`, `phone`, `job_category_id`, `skills`) VALUES
(13, 32, 'Marcia Page', 'Noah Hodge', '9876987601', NULL, 'php , javascript, React'),
(19, 49, 'Mona', 'Clark', '9813445435', NULL, 'php,javascript');

-- --------------------------------------------------------

--
-- Table structure for table `job_application`
--

CREATE TABLE `job_application` (
  `id` int(11) NOT NULL,
  `jobseeker_id` int(11) DEFAULT NULL,
  `jobs_id` int(11) DEFAULT NULL,
  `name` varchar(40) NOT NULL,
  `email` varchar(40) NOT NULL,
  `phone` bigint(20) NOT NULL,
  `status` enum('approve','reject','pending') NOT NULL DEFAULT 'pending',
  `c_status` enum('pending','accept','reject') NOT NULL DEFAULT 'pending',
  `rejection_reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_application`
--

INSERT INTO `job_application` (`id`, `jobseeker_id`, `jobs_id`, `name`, `email`, `phone`, `status`, `c_status`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(3, 13, 43, 'Echo Obrien', 'nakivac@mailinator.com', 1, 'approve', 'reject', 'z\\xgxbvdsjkadf  asjd', '2025-02-25 08:07:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `job_category`
--

CREATE TABLE `job_category` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_category`
--

INSERT INTO `job_category` (`id`, `category_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'software engineer', 1, '2024-11-29 14:17:36', NULL),
(2, 'web developer', 1, '2024-11-30 10:03:19', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('jobseeker','company') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`) VALUES
(32, 'jobseeker@gmail.com', '$2y$10$5RDXUxb8EzO27FeXVoH23uQB3MqnGU2vIob8txFa7yTDgJeZW1USe', 'jobseeker'),
(47, 'company@gmail.com', '$2y$10$YEtqlKhALZvaMQFCgwLhPOnXYPT77S.R9VwhSbjeftDT9HfE/1mly', 'company'),
(48, 'company1@gmail.com', '$2y$10$JIi9.XOKGIRCnez9JySjcuWiyxlAbao8C/fwE/6XS5F6N2HMW/ziO', 'company'),
(49, 'jobseeker1@gmail.com', '$2y$10$/o/Wz5I3yfTnM7S9tYQCr.HoOLzTJ2V7elZVzIOCH3P3SV7UJ8hBW', 'jobseeker');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `companyregistration`
--
ALTER TABLE `companyregistration`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_company_type` (`company_type_id`),
  ADD KEY `companyregistration_ibfk_1` (`user_id`);

--
-- Indexes for table `company_type`
--
ALTER TABLE `company_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_userID` (`user_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_ibfk_2` (`job_category_id`),
  ADD KEY `job_ibfk3` (`company_id`);

--
-- Indexes for table `jobseeker`
--
ALTER TABLE `jobseeker`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_job_category` (`job_category_id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `job_application`
--
ALTER TABLE `job_application`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_application_ibfk_1` (`jobseeker_id`),
  ADD KEY `job_application_ibfk_2` (`jobs_id`);

--
-- Indexes for table `job_category`
--
ALTER TABLE `job_category`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `companyregistration`
--
ALTER TABLE `companyregistration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `company_type`
--
ALTER TABLE `company_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `jobseeker`
--
ALTER TABLE `jobseeker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `job_application`
--
ALTER TABLE `job_application`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `job_category`
--
ALTER TABLE `job_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `companyregistration`
--
ALTER TABLE `companyregistration`
  ADD CONSTRAINT `companyregistration_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_company_type` FOREIGN KEY (`company_type_id`) REFERENCES `company_type` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `fk_userID` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `job_ibfk3` FOREIGN KEY (`company_id`) REFERENCES `companyregistration` (`id`),
  ADD CONSTRAINT `jobs_ibfk_2` FOREIGN KEY (`job_category_id`) REFERENCES `job_category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `jobseeker`
--
ALTER TABLE `jobseeker`
  ADD CONSTRAINT `fk_job_category` FOREIGN KEY (`job_category_id`) REFERENCES `job_category` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `job_application`
--
ALTER TABLE `job_application`
  ADD CONSTRAINT `job_application_ibfk_1` FOREIGN KEY (`jobseeker_id`) REFERENCES `jobseeker` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `job_application_ibfk_2` FOREIGN KEY (`jobs_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
