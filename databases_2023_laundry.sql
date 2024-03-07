-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2024 at 04:11 AM
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
-- Database: `databases_2023_laundry`
--

-- --------------------------------------------------------

--
-- Table structure for table `biaya_tambahan`
--

CREATE TABLE `biaya_tambahan` (
  `id_biaya` int(11) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `biaya` int(11) NOT NULL,
  `tipe` enum('diskon','pajak','biaya_tambahan') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `biaya_tambahan`
--

INSERT INTO `biaya_tambahan` (`id_biaya`, `jenis`, `biaya`, `tipe`) VALUES
(2, 'Parfum Eu de Parfum', 4000, 'biaya_tambahan'),
(3, 'Member', 10, 'diskon'),
(4, 'pajak', 2000, 'pajak');

-- --------------------------------------------------------

--
-- Table structure for table `tb_detail_transaksi`
--

CREATE TABLE `tb_detail_transaksi` (
  `id` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_paket` int(11) NOT NULL,
  `qty` double NOT NULL,
  `keterangan` text NOT NULL,
  `bukti_tran` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_detail_transaksi`
--

INSERT INTO `tb_detail_transaksi` (`id`, `id_transaksi`, `id_paket`, `qty`, `keterangan`, `bukti_tran`) VALUES
(1862, 8947, 9197, 3, '', '65e7f6aada6f9.jpg'),
(2950, 8476, 9197, 1, '', ''),
(4185, 6396, 9197, 1, 'Nama : yusuf Tlp: 097786555 Alamat: rt 09 Ket : ', '65e531fca9e18.png'),
(4724, 5190, 9197, 1, '', '65e7d21a6bdfb.jpg'),
(6120, 9074, 9197, 2, 'Nama : rudi Tlp: 082121232312 Alamat: unit 6 Ket : ', '65e7f6da0f1de.png'),
(9633, 4842, 9197, 2, 'Nama : tes Tlp: 098789865 Alamat: dimana Ket : ', '65e7d24c3d28e.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tb_member`
--

CREATE TABLE `tb_member` (
  `id` int(11) NOT NULL,
  `nama_member` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tlp` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_member`
--

INSERT INTO `tb_member` (`id`, `nama_member`, `alamat`, `jenis_kelamin`, `tlp`) VALUES
(5834, 'Reka ', 'Unit 12', 'P', '0987654454556');

-- --------------------------------------------------------

--
-- Table structure for table `tb_outlet`
--

CREATE TABLE `tb_outlet` (
  `id` int(11) NOT NULL,
  `nama_outlet` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `tlp` varchar(15) NOT NULL,
  `foto_outlet` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_outlet`
--

INSERT INTO `tb_outlet` (`id`, `nama_outlet`, `alamat`, `tlp`, `foto_outlet`) VALUES
(6695, 'Nisaa Laundry', 'Unit 12', '08888888', '65e019db618c0.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tb_paket`
--

CREATE TABLE `tb_paket` (
  `id` int(11) NOT NULL,
  `id_outlet` int(11) NOT NULL,
  `jenis` enum('kiloan','selimut','bed_cover','kaos','lain') NOT NULL,
  `nama_paket` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `estimasi` varchar(3) NOT NULL,
  `foto_paket` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_paket`
--

INSERT INTO `tb_paket` (`id`, `id_outlet`, `jenis`, `nama_paket`, `harga`, `estimasi`, `foto_paket`) VALUES
(9197, 6695, 'kiloan', '2 Selimut', 8000, '1', '65e01a8134bde.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id` int(11) NOT NULL,
  `id_outlet` int(11) NOT NULL,
  `kode_invoice` varchar(100) NOT NULL,
  `id_member` varchar(50) NOT NULL,
  `tgl` datetime NOT NULL,
  `batas_waktu` datetime NOT NULL,
  `tgl_bayar` datetime NOT NULL,
  `biaya_tambahan` int(11) NOT NULL,
  `diskon` double NOT NULL,
  `pajak` int(11) NOT NULL,
  `status` enum('baru','proses','selesai','diambil') NOT NULL DEFAULT 'baru',
  `dibayar` enum('dibayar','belum_dibayar') NOT NULL,
  `id_user` int(11) NOT NULL DEFAULT 1111
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tb_transaksi`
--

INSERT INTO `tb_transaksi` (`id`, `id_outlet`, `kode_invoice`, `id_member`, `tgl`, `batas_waktu`, `tgl_bayar`, `biaya_tambahan`, `diskon`, `pajak`, `status`, `dibayar`, `id_user`) VALUES
(4124, 6695, '202402291560', '5834', '2024-02-29 07:32:24', '2024-03-01 00:00:00', '2024-02-29 07:32:24', 0, 10, 0, 'baru', 'dibayar', 1111),
(4842, 6695, '202403069646', 'tes', '2024-03-06 09:17:48', '2024-03-07 00:00:00', '2024-03-06 09:17:48', 0, 0, 0, 'proses', 'dibayar', 1111),
(5190, 6695, '202403068799', '5834', '2024-03-06 09:16:58', '2024-03-07 00:00:00', '2024-03-06 09:16:58', 0, 10, 0, 'baru', 'dibayar', 1111),
(5567, 6695, '202402296095', '5834', '2024-02-29 07:35:38', '2024-03-01 00:00:00', '2024-02-29 07:35:38', 0, 10, 0, 'baru', 'dibayar', 1111),
(6396, 6695, '202403049536', 'yusuf', '2024-03-04 09:29:16', '2024-03-05 00:00:00', '2024-03-04 09:29:16', 0, 0, 0, 'baru', 'dibayar', 1111),
(7492, 6695, '202402295955', '5834', '2024-02-29 07:34:35', '2024-03-01 00:00:00', '2024-02-29 07:34:35', 0, 10, 0, 'baru', 'dibayar', 1111),
(8476, 6695, '202402295703', '5834', '2024-02-29 06:59:06', '2024-03-01 00:00:00', '2024-02-29 06:59:06', 0, 10, 0, 'baru', 'dibayar', 1111),
(8508, 6695, '202402293091', '5834', '2024-02-29 07:36:44', '2024-03-01 00:00:00', '2024-02-29 07:36:44', 0, 10, 0, 'baru', 'dibayar', 1111),
(8947, 6695, '202403066016', '5834', '2024-03-06 11:52:58', '2024-03-07 00:00:00', '2024-03-06 11:52:58', 0, 10, 0, 'baru', 'dibayar', 1111),
(9074, 6695, '202403069144', 'rudi', '2024-03-06 11:53:46', '2024-03-07 00:00:00', '2024-03-06 11:53:46', 0, 0, 0, 'baru', 'dibayar', 1111),
(9952, 6695, '202402298285', '5834', '2024-02-29 07:32:46', '2024-03-01 00:00:00', '2024-02-29 07:32:46', 0, 10, 0, 'baru', 'dibayar', 1111);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `id_outlet` int(11) NOT NULL,
  `nama_user` varchar(100) NOT NULL,
  `id_member` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` text NOT NULL,
  `peran` enum('admin','kasir','owner','member') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `id_outlet`, `nama_user`, `id_member`, `username`, `password`, `peran`) VALUES
(1072, 0, 'Nisa', 0, 'nisaa', 'nisa', 'owner'),
(1111, 0, 'Ressa Salma', 0, 'ressa', 'ressa', 'admin'),
(1127, 6695, 'Rana', 0, 'rana390', 'rana390', 'kasir'),
(7685, 0, 'Reka ', 5834, 'Reka ', 'reka', 'member');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `biaya_tambahan`
--
ALTER TABLE `biaya_tambahan`
  ADD PRIMARY KEY (`id_biaya`);

--
-- Indexes for table `tb_detail_transaksi`
--
ALTER TABLE `tb_detail_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_paket` (`id_paket`),
  ADD KEY `id_transaksi` (`id_transaksi`);

--
-- Indexes for table `tb_member`
--
ALTER TABLE `tb_member`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_outlet`
--
ALTER TABLE `tb_outlet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_paket`
--
ALTER TABLE `tb_paket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_outlet` (`id_outlet`);

--
-- Indexes for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_member` (`id_member`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_outlet` (`id_outlet`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `biaya_tambahan`
--
ALTER TABLE `biaya_tambahan`
  MODIFY `id_biaya` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_detail_transaksi`
--
ALTER TABLE `tb_detail_transaksi`
  ADD CONSTRAINT `tb_detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `tb_transaksi` (`id`),
  ADD CONSTRAINT `tb_detail_transaksi_ibfk_2` FOREIGN KEY (`id_paket`) REFERENCES `tb_paket` (`id`);

--
-- Constraints for table `tb_paket`
--
ALTER TABLE `tb_paket`
  ADD CONSTRAINT `tb_paket_ibfk_1` FOREIGN KEY (`id_outlet`) REFERENCES `tb_outlet` (`id`);

--
-- Constraints for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD CONSTRAINT `tb_transaksi_ibfk_1` FOREIGN KEY (`id_outlet`) REFERENCES `tb_outlet` (`id`),
  ADD CONSTRAINT `tb_transaksi_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
