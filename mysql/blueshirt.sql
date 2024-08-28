-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 28, 2024 at 12:47 PM
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
-- Database: `blueshirt`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `v_name_id` int(11) NOT NULL,
  `v_member_id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `start_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `v_name_id`, `v_member_id`, `title`, `start_date`) VALUES
(1, 0, 0, 'e29ckgaaaa', '2024-08-09'),
(2, 0, 0, 'ฟฟาาา', '2024-08-08'),
(3, 0, 0, 'สสส', '2024-08-06'),
(4, 0, 0, 'สสสบบบบ', '2024-08-07'),
(6, 0, 0, 'ฟหๆหๆหๆหๆหๆหๆหๆห', '2024-08-08'),
(7, 0, 0, 'เวรกกก', '2024-08-19'),
(8, 0, 0, 'เวรกกก', '2024-08-20'),
(9, 0, 0, 'เวรกกก', '2024-08-28'),
(10, 0, 0, 'เวรกกก', '2024-08-26'),
(11, 0, 0, 'เวรกกก', '2024-08-26'),
(12, 0, 0, 'เวรกกก', '2024-08-25'),
(13, 0, 0, 'เวรกกก', '2024-08-28'),
(14, 0, 0, 'เวรกกก', '2024-08-29'),
(15, 2, 1, 'เวรกกก', '2024-08-30'),
(16, 2, 1, 'เวรกกก', '2024-08-26'),
(17, 2, 1, 'เวรกกก', '2024-09-02'),
(18, 2, 1, 'เวรกกก', '2024-08-29'),
(19, 2, 1, 'เวรกกก', '2024-09-05'),
(20, 2, 1, 'เวรกกก', '2024-08-27'),
(21, 2, 1, 'เวรกกก', '2024-09-02'),
(22, 2, 1, 'เวรกกก', '2024-07-29'),
(23, 2, 1, 'เวรกกก', '2024-08-05'),
(24, 2, 1, 'เวรกกก', '2024-08-01'),
(25, 5, 3, 'qqqq', '2024-08-12'),
(26, 19, 4, 'sss', '2024-08-13'),
(27, 19, 4, 'sss', '2024-08-11');

-- --------------------------------------------------------

--
-- Table structure for table `v_members`
--

CREATE TABLE `v_members` (
  `id` int(11) NOT NULL,
  `v_names_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `v_members`
--

INSERT INTO `v_members` (`id`, `v_names_id`, `name`, `sort`) VALUES
(8, 5, 'gggg', 0),
(9, 5, 'dddd', 0),
(12, 5, 'ww', 0),
(13, 5, 'dddd', 0);

-- --------------------------------------------------------

--
-- Table structure for table `v_names`
--

CREATE TABLE `v_names` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `v_names`
--

INSERT INTO `v_names` (`id`, `name`, `color`) VALUES
(5, 'เวรสาวเสื้อฟ้า1', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `v_members`
--
ALTER TABLE `v_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `v_names_id` (`v_names_id`);

--
-- Indexes for table `v_names`
--
ALTER TABLE `v_names`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `v_members`
--
ALTER TABLE `v_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `v_names`
--
ALTER TABLE `v_names`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `v_members`
--
ALTER TABLE `v_members`
  ADD CONSTRAINT `v_members_ibfk_1` FOREIGN KEY (`v_names_id`) REFERENCES `v_names` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
