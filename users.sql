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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `studentId` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `grade` varchar(20) NOT NULL,
  `major` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `raw_password` varchar(255) DEFAULT NULL,
  `bottles` int(11) NOT NULL DEFAULT 0,
  `points` float NOT NULL DEFAULT 0,
  `role` varchar(20) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_thai_520_w2;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `studentId`, `password`, `fullname`, `grade`, `major`, `created_at`, `raw_password`, `bottles`, `points`, `role`) VALUES
(1, '1113', '$2y$10$xK6nAF8lizkhhg0Dl0Ur.u.yChoTlCUaYbfzEkRNP.cBoUtkYvuca', 'บัญฑิต สุนทร', 'ปวช.3', 'เทคโนโลยีสารสนเทศ', '2025-11-22 05:06:40', '123456789', 22, 875, 'user'),
(4, '0001', '$2y$10$9F4cEsMv6dN9IM5msaHyyONAcfE0MaF1.WRZx7YMR3A8HhzJAUhoW', 'Admin', 'ปวส.2', 'เทคโนโลยีสารสนเทศ', '2026-02-15 16:55:17', '1234admin', 10009, 9954, 'ADMIN'),
(5, '007', '$2y$10$X4.naIj0C5MicZpkJeGCQuBfvBxg4rustE041whIv8KGb4JFGWw9a', 'Patsakorn Norohem', 'ปวส.2', 'คอมพิวเตอร์ธุรกิจ', '2026-02-16 07:26:14', '123456p', 9, 4.5, 'user'),
(6, '1234', '$2y$10$hdEFEsxblQC5V9fJS22XfuyghMFlvAUoK3Q.Tv0SQwZUQa7n4J2JC', 'test', 'ปวช.1', 'ช่างอิเล็กทรอนิกส์', '2026-02-16 07:44:56', '1234', 0, 0, 'user'),
(7, '1111', '$2y$10$WOxbt4TgM.uylD6CYB4tnOjeM2KOi9hAoHMUK7b65GI.6uPYefYlW', 'test1', 'ปวช.1', 'คอมพิวเตอร์ธุรกิจ', '2026-02-16 07:59:57', '1111', 0, 0, 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `studentId` (`studentId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
