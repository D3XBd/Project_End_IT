-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 23, 2026 at 02:39 AM
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
-- Database: `bottle_exchange`
--

-- --------------------------------------------------------

--
-- Table structure for table `exchange_requests`
--

CREATE TABLE `exchange_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reward_name` varchar(100) NOT NULL,
  `point_used` int(11) NOT NULL,
  `status` varchar(20) DEFAULT 'PENDING',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_thai_520_w2;

--
-- Dumping data for table `exchange_requests`
--

INSERT INTO `exchange_requests` (`id`, `user_id`, `reward_name`, `point_used`, `status`, `created_at`) VALUES
(1, 1, 'น้ำ 1 ขวด', 10, 'REJECTED', '2026-02-15 16:41:20'),
(2, 1, 'น้ำ 1 ขวด', 10, 'APPROVED', '2026-02-15 16:41:27'),
(3, 1, 'คะแนนกิจกรรม 1 ชม', 15, 'APPROVED', '2026-02-15 17:03:32'),
(4, 1, 'น้ำ 1 ขวด', 10, 'REJECTED', '2026-02-15 17:03:34'),
(5, 4, 'คะแนนกิจกรรม 1 ชม', 15, 'PENDING', '2026-02-15 17:07:15'),
(6, 4, 'น้ำ 1 ขวด', 10, 'APPROVED', '2026-02-15 17:07:16'),
(7, 4, 'คะแนนกิจกรรม 1 ชม', 15, 'REJECTED', '2026-02-15 17:14:41'),
(8, 4, 'คะแนนกิจกรรม 1 ชม', 15, 'PENDING', '2026-02-16 01:59:41'),
(9, 4, 'น้ำ 1 ขวด', 10, 'PENDING', '2026-02-16 01:59:44'),
(10, 1, 'น้ำ 1 ขวด', 10, 'APPROVED', '2026-02-16 07:24:41'),
(11, 4, 'น้ำ 1 ขวด', 10, 'APPROVED', '2026-02-16 07:25:17'),
(12, 4, 'คะแนนกิจกรรม 1 ชม', 15, 'APPROVED', '2026-02-16 07:28:31'),
(13, 4, 'คะแนนกิจกรรม 1 ชม', 15, 'APPROVED', '2026-02-16 07:53:19'),
(14, 1, 'คะแนนกิจกรรม 1 ชม', 15, 'PENDING', '2026-02-16 07:57:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `exchange_requests`
--
ALTER TABLE `exchange_requests`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `exchange_requests`
--
ALTER TABLE `exchange_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
