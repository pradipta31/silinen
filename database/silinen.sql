-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2026 at 03:56 PM
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
(1, 'L001', 'Selimut', '1773744805_69b932a590b6f.png', 100, 100, '2026-03-17 11:53:25', 1, '0000-00-00 00:00:00'),
(2, 'L002', 'Sprei', '1773744890_69b932fa9e41f.png', 100, 100, '2026-03-17 11:54:50', 1, '0000-00-00 00:00:00'),
(3, 'L003', 'Sarung Bantal', '1773744913_69b933118f481.png', 100, 98, '2026-03-17 11:55:13', 1, '0000-00-00 00:00:00'),
(4, 'L004', 'Sapu Tangan', '1773744936_69b9332866e05.png', 100, 95, '2026-03-17 11:55:36', 1, '0000-00-00 00:00:00'),
(5, 'L005', 'Duk Lubang', '1773744954_69b9333a80d0d.png', 100, 90, '2026-03-17 11:55:54', 1, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `linen_ruangan`
--

CREATE TABLE `linen_ruangan` (
  `id` int(11) NOT NULL,
  `id_ruangan` int(11) NOT NULL,
  `id_linen` int(11) NOT NULL,
  `jumlah_linen` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `linen_ruangan`
--

INSERT INTO `linen_ruangan` (`id`, `id_ruangan`, `id_linen`, `jumlah_linen`, `tanggal`, `status`) VALUES
(1, 2, 5, 5, '2026-03-24 16:38:33', 1),
(2, 1, 3, 2, '2026-03-25 02:06:02', 1),
(3, 1, 4, 5, '2026-03-25 02:06:03', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pencucian`
--

CREATE TABLE `pencucian` (
  `id` int(11) NOT NULL,
  `id_linen_ruangan` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `keterangan` text NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pencucian`
--

INSERT INTO `pencucian` (`id`, `id_linen_ruangan`, `jumlah`, `tanggal`, `keterangan`, `status`) VALUES
(1, 2, 3, '2026-03-25 06:31:06', 'cuci karna kotor', 3),
(2, 2, 2, '2026-03-25 06:46:26', 'Kotor', 3);

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan`
--

CREATE TABLE `pengajuan` (
  `id` int(11) NOT NULL,
  `id_linen` int(11) NOT NULL,
  `id_ruangan` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `keterangan` text NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengajuan`
--

INSERT INTO `pengajuan` (`id`, `id_linen`, `id_ruangan`, `jumlah`, `tanggal`, `keterangan`, `status`) VALUES
(1, 5, 2, 5, '2026-03-24 16:35:12', 'pengajuan baru', 2),
(2, 4, 1, 5, '2026-03-25 02:05:35', 'Baru', 3),
(3, 3, 1, 7, '2026-03-25 02:05:45', 'Baru', 3),
(4, 1, 1, 9, '2026-03-30 13:39:17', 'Untuk acara Bakti sosial', 1);

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
(1, 5, 'Ratna', '12345678', 1),
(2, 3, 'Sandat', '123456', 1),
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
(1, 'admin', 'admin', 'admin@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'admin', 1, NULL),
(2, 'John Doe', 'johndoe', 'johndoe@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'kepala_penanggung_jawab', 1, 0),
(3, 'I Gede Pradipta Adi Nugraha', 'dipta', 'dipta@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'admin_ruangan', 1, 1),
(4, 'Ditan Hakim Arendrayuda', 'hakim', 'hakim@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'petugas_laundry', 1, NULL),
(5, 'Ni Luh Gede Dama Yanti', 'damayanti', 'luhdedamayanti08@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'admin_ruangan', 1, 1);

--
-- Indexes for dumped tables
--

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
  ADD KEY `fk_id_ruangan` (`id_ruangan`),
  ADD KEY `fk_id_linen` (`id_linen`);

--
-- Indexes for table `pencucian`
--
ALTER TABLE `pencucian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_linen_ruangan` (`id_linen_ruangan`);

--
-- Indexes for table `pengajuan`
--
ALTER TABLE `pengajuan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_id_linen` (`id_linen`),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `linen_ruangan`
--
ALTER TABLE `linen_ruangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pencucian`
--
ALTER TABLE `pencucian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pengajuan`
--
ALTER TABLE `pengajuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ruangan`
--
ALTER TABLE `ruangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
