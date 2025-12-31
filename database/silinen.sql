-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 31, 2025 at 12:10 PM
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
-- Table structure for table `distribusi_linen`
--

CREATE TABLE `distribusi_linen` (
  `id` int(11) NOT NULL,
  `id_linen_ruangan` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `distribusi_linen`
--

INSERT INTO `distribusi_linen` (`id`, `id_linen_ruangan`, `jumlah`, `tanggal_masuk`, `tanggal_selesai`, `status`) VALUES
(1, 1, 1, '2025-12-31', NULL, 3),
(2, 1, 3, '2025-12-31', NULL, 3),
(3, 2, 2, '2025-12-30', NULL, 2),
(4, 5, 2, '2025-12-04', NULL, 2),
(5, 7, 2, '2025-11-27', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `linen`
--

CREATE TABLE `linen` (
  `id` int(11) NOT NULL,
  `kode_linen` varchar(15) NOT NULL,
  `nama_linen` varchar(199) NOT NULL,
  `gambar` varchar(199) NOT NULL,
  `jumlah_linen` int(11) NOT NULL,
  `sisa_linen` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `linen`
--

INSERT INTO `linen` (`id`, `kode_linen`, `nama_linen`, `gambar`, `jumlah_linen`, `sisa_linen`, `tanggal`, `status`, `updated_at`) VALUES
(1, 'L001', 'Bantal', '1764771047_693044e79c82d.jpg', 10, 1, '2025-12-03 14:18:51', 1, '2025-12-03 15:10:47'),
(2, 'L002', 'Selimut', '1764768540_69303b1c4fee6.jpg', 10, 2, '2025-12-03 14:29:00', 1, '2025-12-06 08:45:42'),
(3, 'L003', 'Gorden', '1767099689_6953cd292d26e.jpg', 25, 0, '2025-12-30 14:01:29', 1, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `linen_ruangan`
--

CREATE TABLE `linen_ruangan` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_ruangan` int(11) NOT NULL,
  `id_linen` int(11) NOT NULL,
  `linen_terpakai` int(11) NOT NULL,
  `linen_cadangan` int(11) NOT NULL,
  `status_linen` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `linen_ruangan`
--

INSERT INTO `linen_ruangan` (`id`, `id_user`, `id_ruangan`, `id_linen`, `linen_terpakai`, `linen_cadangan`, `status_linen`) VALUES
(1, 2, 1, 3, 6, 0, 0),
(2, 7, 2, 3, 3, 5, 0),
(3, 8, 3, 3, 4, 1, 0),
(4, 2, 1, 2, 5, 0, 0),
(5, 8, 3, 1, 4, 1, 0),
(6, 2, 1, 1, 2, 0, 0),
(7, 8, 3, 2, 0, 1, 0);

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
(1, 2, 'Ratna', '12345678', 1),
(2, 7, 'Sandat', '123456', 1),
(3, 8, 'Anggrek', '123456', 1),
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
(7, 'Made Tika', 'tika12', 'tika@gmail.com', '25d55ad283aa400af464c76d713c07ad', 'admin_ruangan', 1, 1),
(8, 'Erni Yurnita', 'erniyurnita', 'erniyurnita@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'admin_ruangan', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `distribusi_linen`
--
ALTER TABLE `distribusi_linen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_linen_ruangan` (`id_linen_ruangan`);

--
-- Indexes for table `linen`
--
ALTER TABLE `linen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `linen_ruangan`
--
ALTER TABLE `linen_ruangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_user` (`id_user`),
  ADD KEY `fk_id_ruangan` (`id_ruangan`),
  ADD KEY `fk_id_linen` (`id_linen`);

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
-- AUTO_INCREMENT for table `distribusi_linen`
--
ALTER TABLE `distribusi_linen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `linen`
--
ALTER TABLE `linen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `linen_ruangan`
--
ALTER TABLE `linen_ruangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ruangan`
--
ALTER TABLE `ruangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
