-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 20, 2025 at 02:49 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `orgenius2`
--

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan`
--

CREATE TABLE `kegiatan` (
  `idKegiatan` int NOT NULL,
  `namaKegiatan` varchar(150) NOT NULL,
  `deskripsi` text,
  `tanggal` date NOT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `idPembuat` int DEFAULT NULL,
  `idOrganisasi` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kegiatan`
--

INSERT INTO `kegiatan` (`idKegiatan`, `namaKegiatan`, `deskripsi`, `tanggal`, `lokasi`, `idPembuat`, `idOrganisasi`) VALUES
(8, 'Lokakarya Himakom 2024', 'Membangun anak ilkom ahahha', '2025-06-20', 'Gedung Ilmu Komputer Lantai 1C', 1, 1),
(10, 'apa aja yaaaaaaaaaaa', 'tester doang', '2025-06-21', 'UNILA', 4, 6);

-- --------------------------------------------------------

--
-- Table structure for table `organisasi`
--

CREATE TABLE `organisasi` (
  `idOrganisasi` int NOT NULL,
  `namaOrganisasi` varchar(100) NOT NULL,
  `deskripsi` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `organisasi`
--

INSERT INTO `organisasi` (`idOrganisasi`, `namaOrganisasi`, `deskripsi`) VALUES
(1, 'BEM Fakultas Teknik', 'Organisasi mahasiswa tingkat fakultas'),
(2, 'UKM Kesenian', 'Unit Kegiatan Mahasiswa di bidang seni'),
(3, 'Himpunan Mahasiswa Informatika', 'Organisasi mahasiswa jurusan Informatika'),
(4, 'UKM Olahraga', 'Unit Kegiatan Mahasiswa di bidang olahraga'),
(5, 'himaki', ''),
(6, 'Organisasi Memancing', '');

-- --------------------------------------------------------

--
-- Table structure for table `request_organisasi`
--

CREATE TABLE `request_organisasi` (
  `idRequest` int NOT NULL,
  `idUser` int NOT NULL,
  `idOrganisasi` int NOT NULL,
  `status` enum('pending','diterima','ditolak') DEFAULT 'pending',
  `tanggalRequest` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `request_organisasi`
--

INSERT INTO `request_organisasi` (`idRequest`, `idUser`, `idOrganisasi`, `status`, `tanggalRequest`) VALUES
(1, 2, 1, 'diterima', '2025-06-19 00:36:42'),
(2, 2, 2, 'pending', '2025-06-20 07:10:06'),
(3, 2, 3, 'pending', '2025-06-20 07:10:07'),
(4, 2, 4, 'pending', '2025-06-20 07:10:08'),
(5, 2, 5, 'pending', '2025-06-20 07:10:09'),
(6, 2, 6, 'diterima', '2025-06-20 07:10:10');

-- --------------------------------------------------------

--
-- Table structure for table `tugas`
--

CREATE TABLE `tugas` (
  `idTugas` int NOT NULL,
  `judul` varchar(150) NOT NULL,
  `deskripsi` text,
  `deadline` date NOT NULL,
  `status` enum('belum','proses','selesai') DEFAULT 'belum',
  `idUser` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tugas`
--

INSERT INTO `tugas` (`idTugas`, `judul`, `deskripsi`, `deadline`, `status`, `idUser`) VALUES
(9, 'ini sederhana tapi enak nyari bug nya dimana', 'kasi lagi dong matkul nya', '2025-06-12', 'selesai', 1),
(11, '121', '1212', '2025-06-06', 'selesai', 2),
(12, '121', '1212', '2025-06-06', 'selesai', 2),
(13, '4', '5', '2025-06-20', 'belum', 1),
(14, 'Membuat laporan memancing a', 'buatkan dengan jelas detail memancing di embung unila', '2025-06-21', 'belum', 4);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `idUser` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('anggota','pengurus') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`idUser`, `nama`, `email`, `password`, `role`) VALUES
(1, 'Bram Ahimsa', 'bramahimsa@example.com', '$2y$10$4tQVl.sK.PlPZGchSsLtZuqLcebEYJ8xPexY5yxL76l0THp7kJSZi', 'pengurus'),
(2, 'daniel simarmata', 'daniel123@gmail.com', '$2y$10$/eq64X/OmmJo10N8NRs9de0lPk/xKkmrciRuK9PlbhWVf7moq77kO', 'anggota'),
(3, 'bram2', 'bram2@gmail.com', '$2y$10$Owjc.v9boisISWnKZ2jtXuXZlX6fW3.Ikjq1PL4.ZlNbz754BW2he', 'pengurus'),
(4, 'wahyuSAP', 'wahyu@gmail.com', '$2y$10$f4B7DaEsp.MhUaPmQQey5O5EyFiMisiaZj/9RmuLb1mzX5xSA4oA.', 'pengurus');

-- --------------------------------------------------------

--
-- Table structure for table `user_organisasi`
--

CREATE TABLE `user_organisasi` (
  `idUser` int NOT NULL,
  `idOrganisasi` int NOT NULL,
  `role` enum('anggota','pengurus') DEFAULT 'anggota'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_organisasi`
--

INSERT INTO `user_organisasi` (`idUser`, `idOrganisasi`, `role`) VALUES
(1, 1, 'pengurus'),
(2, 1, 'anggota'),
(2, 6, 'anggota'),
(3, 5, 'pengurus'),
(4, 6, 'pengurus');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD PRIMARY KEY (`idKegiatan`),
  ADD KEY `idPembuat` (`idPembuat`),
  ADD KEY `idOrganisasi` (`idOrganisasi`);

--
-- Indexes for table `organisasi`
--
ALTER TABLE `organisasi`
  ADD PRIMARY KEY (`idOrganisasi`);

--
-- Indexes for table `request_organisasi`
--
ALTER TABLE `request_organisasi`
  ADD PRIMARY KEY (`idRequest`),
  ADD KEY `idUser` (`idUser`),
  ADD KEY `idOrganisasi` (`idOrganisasi`);

--
-- Indexes for table `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`idTugas`),
  ADD KEY `idUser` (`idUser`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_organisasi`
--
ALTER TABLE `user_organisasi`
  ADD PRIMARY KEY (`idUser`,`idOrganisasi`),
  ADD KEY `idOrganisasi` (`idOrganisasi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kegiatan`
--
ALTER TABLE `kegiatan`
  MODIFY `idKegiatan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `organisasi`
--
ALTER TABLE `organisasi`
  MODIFY `idOrganisasi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `request_organisasi`
--
ALTER TABLE `request_organisasi`
  MODIFY `idRequest` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas`
  MODIFY `idTugas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD CONSTRAINT `kegiatan_ibfk_1` FOREIGN KEY (`idPembuat`) REFERENCES `user` (`idUser`),
  ADD CONSTRAINT `kegiatan_ibfk_2` FOREIGN KEY (`idOrganisasi`) REFERENCES `organisasi` (`idOrganisasi`);

--
-- Constraints for table `request_organisasi`
--
ALTER TABLE `request_organisasi`
  ADD CONSTRAINT `request_organisasi_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE CASCADE,
  ADD CONSTRAINT `request_organisasi_ibfk_2` FOREIGN KEY (`idOrganisasi`) REFERENCES `organisasi` (`idOrganisasi`) ON DELETE CASCADE;

--
-- Constraints for table `tugas`
--
ALTER TABLE `tugas`
  ADD CONSTRAINT `tugas_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`);

--
-- Constraints for table `user_organisasi`
--
ALTER TABLE `user_organisasi`
  ADD CONSTRAINT `user_organisasi_ibfk_1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`),
  ADD CONSTRAINT `user_organisasi_ibfk_2` FOREIGN KEY (`idOrganisasi`) REFERENCES `organisasi` (`idOrganisasi`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
