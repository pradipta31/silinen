-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 06, 2025 at 02:44 PM
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
-- Database: `silinen`
--

-- --------------------------------------------------------

--
-- Table structure for table `linen`
--

CREATE TABLE `linen` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_ruangan` int(11) NOT NULL,
  `kode_linen` varchar(15) NOT NULL,
  `nama_linen` varchar(199) NOT NULL,
  `gambar` varchar(199) NOT NULL,
  `jumlah_linen` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ruangan`
--

CREATE TABLE `ruangan` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `nama_ruangan` varchar(199) NOT NULL,
  `telp_ruangan` varchar(15) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ruangan`
--

INSERT INTO `ruangan` (`id`, `id_user`, `nama_ruangan`, `telp_ruangan`, `status`) VALUES
(1, 2, 'Ratna', '123456', 1),
(2, 7, 'Sandat', '123456', 1),
(3, NULL, 'Anggrek', '123456', 1),
(4, NULL, 'Teratai', '123456', 1),
(5, NULL, 'Dahlia', '123456', 1),
(6, NULL, 'Ngurah Rai', '123456', 1),
(7, NULL, 'VK', '123456', 1),
(8, NULL, 'ICU', '123456', 1),
(9, NULL, 'Kartika', '123456', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `hak_akses` varchar(25) NOT NULL,
  `status` int(11) NOT NULL,
  `status_ruangan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `email`, `password`, `hak_akses`, `status`, `status_ruangan`) VALUES
(1, 'Admin Master', 'admin', 'admin@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'admin', 1, NULL),
(2, 'Ni Luh Gede Dama Yanti', 'damayanti', 'luhdedamayanti08@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'admin_ruangan', 1, 1),
(3, 'John Doe', 'johndoe', 'johndoe@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'petugas_laundry', 1, NULL),
(4, 'Jane Doe', 'janedoe', 'janedoe@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'kepala_penanggung_jawab', 1, NULL),
(5, 'John Daer', 'johndaer', 'johndae@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'admin_ruangan', 1, 0),
(6, 'I Gede Pradipta Adi Nugraha', 'pradipta31', 'pradiptadipta31@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'admin_ruangan', 1, 0),
(7, 'Made Tika', 'tika12', 'tika@gmail.com', '25d55ad283aa400af464c76d713c07ad', 'admin_ruangan', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `linen`
--
ALTER TABLE `linen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_user` (`id_user`),
  ADD KEY `fk_id_ruangan` (`id_ruangan`);

--
-- Indexes for table `ruangan`
--
ALTER TABLE `ruangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_user` (`id_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `linen`
--
ALTER TABLE `linen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ruangan`
--
ALTER TABLE `ruangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
