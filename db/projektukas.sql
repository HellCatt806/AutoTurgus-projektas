-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2025 at 12:04 AM
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
-- Database: `projektukas`
--

-- --------------------------------------------------------

--
-- Table structure for table `listings`
--

CREATE TABLE `listings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vehicle_type_id` int(11) NOT NULL,
  `make_id` int(11) NOT NULL,
  `model_id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `power` int(11) NOT NULL COMMENT 'Galia kW',
  `mileage` int(11) NOT NULL COMMENT 'Rida km',
  `body_type` varchar(30) DEFAULT NULL COMMENT 'Automobiliams',
  `fuel_type` enum('benzinas','dyzelis','elektra','hibridas','dujos','benzinas/dujos') NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `city` varchar(100) DEFAULT NULL COMMENT 'Miestas',
  `engine_capacity` decimal(4,1) DEFAULT NULL COMMENT 'Variklio turis l',
  `transmission` varchar(20) DEFAULT NULL COMMENT 'Automobiliams',
  `vin` varchar(17) DEFAULT NULL COMMENT 'VIN kodas',
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `listings`
--

INSERT INTO `listings` (`id`, `user_id`, `vehicle_type_id`, `make_id`, `model_id`, `year`, `power`, `mileage`, `body_type`, `fuel_type`, `price`, `image_url`, `phone`, `city`, `engine_capacity`, `transmission`, `vin`, `description`, `created_at`) VALUES
(1, 1, 1, 1, 2, 2009, 92, 230000, '0', 'benzinas', 5400.00, '0', '+37063622806', NULL, 1.4, 'mechaninė', '', 'Lietuvoje automobilis 3 metai. Didelių remontų, avarijų neturėjas. Prieš 3 metus pakeista grandine ir jos itempėjas. Gruodį pakeisti visi rolikai, vandens pompa, generatoriaus dirželis, tepalai, filtrai. Naujas akumuliatorius. Yra galinė vaizdo kamera. Žiemą praeita tech apžiūra. Kaina derinama', '2025-04-28 21:38:49'),
(2, 1, 1, 1, 2, 2009, 92, 230000, '0', 'benzinas', 5400.00, '0', '+37063622806', NULL, 1.4, 'mechaninė', '', 'Kaina derinama', '2025-04-28 21:38:49'),
(3, 1, 1, 2, 5, 2015, 110, 155000, '1', '', 8900.00, '0', '+37061234567', NULL, 1.6, 'mechaninė', '', 'Tvarkingas automobilis, atlikti visi aptarnavimai', '2025-04-29 09:00:00'),
(4, 1, 1, 3, 8, 2012, 140, 210000, '2', 'benzinas', 7600.00, '0', '+37069876543', NULL, 2.0, 'automatinė', '', 'Gerai išlaikytas, naujos padangos', '2025-04-29 09:05:00'),
(5, 1, 1, 4, 11, 2018, 85, 96000, '0', 'benzinas', 11500.00, '0', '+37061239876', NULL, 1.2, 'mechaninė', '', 'Vienas savininkas, mažos kuro sąnaudos', '2025-04-29 09:10:00'),
(6, 1, 1, 5, 14, 2020, 130, 54000, '1', '', 15500.00, '0', '+37064567891', NULL, 2.0, 'automatinė', '', 'Pilna serviso istorija, garantija', '2025-04-29 09:15:00'),
(7, 1, 1, 1, 3, 2007, 77, 275000, '2', 'benzinas', 3200.00, '0', '+37060012345', NULL, 1.6, 'mechaninė', '', 'Reikalinga kosmetinė priežiūra, techninė apžiūra iki 2026', '2025-04-29 09:20:00'),
(8, 1, 1, 2, 6, 2014, 103, 167000, '1', '', 8200.00, '0', '+37060000001', NULL, 1.6, 'mechaninė', '', 'Ekonomiškas, ką tik praeita TA', '2025-04-29 09:25:00'),
(9, 1, 1, 3, 9, 2016, 120, 134000, '1', 'benzinas', 9900.00, '0', '+37060000002', NULL, 1.8, 'automatinė', '', 'Pilna serviso istorija', '2025-04-29 09:26:00'),
(10, 1, 1, 4, 12, 2017, 95, 89000, '0', 'benzinas', 12300.00, '0', '+37060000003', NULL, 1.4, 'mechaninė', '', 'Mažai važinėtas miesto automobilis', '2025-04-29 09:27:00'),
(11, 1, 1, 5, 15, 2021, 150, 47000, '2', '', 18800.00, '0', '+37060000004', NULL, 2.2, 'automatinė', '', 'Prailginta garantija', '2025-04-29 09:28:00'),
(12, 1, 1, 6, 18, 2011, 85, 199000, '0', 'benzinas', 5900.00, '0', '+37060000005', NULL, 1.2, 'mechaninė', '', 'Pakeisti stabdžių diskai', '2025-04-29 09:29:00'),
(13, 1, 1, 1, 4, 2010, 102, 214000, '1', '', 5100.00, '0', '+37060000006', NULL, 1.9, 'mechaninė', '', 'Techniškai tvarkingas', '2025-04-29 09:30:00'),
(14, 1, 1, 2, 7, 2013, 88, 178000, '1', '', 7800.00, '0', '+37060000007', NULL, 1.5, 'mechaninė', '', 'Ekonomiškas pasirinkimas', '2025-04-29 09:31:00'),
(15, 1, 1, 3, 10, 2015, 133, 142000, '2', 'benzinas', 9700.00, '0', '+37060000008', NULL, 2.0, 'automatinė', '', 'Patikimas automobilis ilgoms kelionėms', '2025-04-29 09:32:00'),
(16, 1, 1, 4, 13, 2018, 82, 98000, '0', 'benzinas', 11200.00, '0', '+37060000009', NULL, 1.2, 'mechaninė', '', 'Labai ekonomiškas', '2025-04-29 09:33:00'),
(17, 1, 1, 5, 16, 2022, 165, 25000, '2', 'elektra', 22000.00, '0', '+37060000010', NULL, 0.0, 'automatinė', '', 'Elektrinis automobilis su garantija', '2025-04-29 09:34:00'),
(18, 1, 1, 6, 19, 2008, 74, 243000, '0', 'benzinas', 3200.00, '0', '+37060000011', NULL, 1.4, 'mechaninė', '', 'Technikinė iki 2026', '2025-04-29 09:35:00'),
(19, 1, 1, 1, 5, 2011, 105, 190000, '1', '', 6200.00, '0', '+37060000012', NULL, 1.6, 'mechaninė', '', 'Idealus miesto automobilis', '2025-04-29 09:36:00'),
(20, 1, 1, 2, 8, 2016, 125, 122000, '1', 'benzinas', 10400.00, '0', '+37060000013', NULL, 1.8, 'automatinė', '', 'Mažai naudotas, viena savininkė', '2025-04-29 09:37:00'),
(21, 1, 1, 3, 11, 2017, 98, 93000, '0', 'benzinas', 11700.00, '0', '+37060000014', NULL, 1.4, 'mechaninė', '', 'Puikios būklės, niekada nebuvo daužtas', '2025-04-29 09:38:00'),
(22, 1, 1, 4, 14, 2020, 140, 57000, '2', '', 16500.00, '0', '+37060000015', NULL, 2.0, 'automatinė', '', 'Dar galiojanti gamintojo garantija', '2025-04-29 09:39:00'),
(23, 1, 1, 5, 17, 2021, 120, 41000, '2', 'elektra', 19800.00, '0', '+37060000016', NULL, 0.0, 'automatinė', '', 'Nauji akumuliatoriai', '2025-04-29 09:40:00'),
(24, 1, 1, 6, 20, 2009, 90, 220000, '1', 'benzinas', 4000.00, '0', '+37060000017', NULL, 1.6, 'mechaninė', '', 'Reikia minimalių investicijų', '2025-04-29 09:41:00'),
(25, 1, 1, 1, 6, 2012, 103, 176000, '1', '', 7000.00, '0', '+37060000018', NULL, 1.7, 'mechaninė', '', 'Tvarkingas, su nauja technikine', '2025-04-29 09:42:00'),
(35, 3, 1, 2, 13, 2020, 250, 300000, 'universalas', 'benzinas', 20000.00, 'uploads/img_682e4d3d995389.40760182.jpg', '+3706123456789', 'Vilnius', 2.0, 'automatinė', '45646416457456165', 'Kuriasi važiuoja', '2025-05-21 22:01:33'),
(36, 3, 2, 12, 73, 2021, 35, 5000, NULL, 'benzinas', 5500.00, 'uploads/img_682e4d6cf24fe0.28728866.jpg', '+3706123456789', 'Kaunas', 1.0, NULL, '45498789789789797', 'Nekritęs, problemų nėra.', '2025-05-21 22:02:20');

-- --------------------------------------------------------

--
-- Table structure for table `listing_images`
--

CREATE TABLE `listing_images` (
  `id` int(11) NOT NULL,
  `listing_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL COMMENT 'Kelias iki nuotraukos, pvz., uploads/pavadinimas.jpg',
  `is_primary` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Ar tai pagrindinė skelbimo nuotrauka (0 = Ne, 1 = Taip)',
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `listing_images`
--

INSERT INTO `listing_images` (`id`, `listing_id`, `image_path`, `is_primary`, `uploaded_at`) VALUES
(25, 35, 'uploads/img_682e4d3d995389.40760182.jpg', 1, '2025-05-21 22:01:33'),
(26, 35, 'uploads/img_682e4d3d9994e6.89598740.jpg', 0, '2025-05-21 22:01:33'),
(27, 35, 'uploads/img_682e4d3d99d060.54849775.jpg', 0, '2025-05-21 22:01:33'),
(28, 36, 'uploads/img_682e4d6cf24fe0.28728866.jpg', 1, '2025-05-21 22:02:20'),
(29, 36, 'uploads/img_682e4d6cf280a7.60691604.jpg', 0, '2025-05-21 22:02:21'),
(30, 36, 'uploads/img_682e4d6cf2aa10.05931112.jpg', 0, '2025-05-21 22:02:21'),
(31, 36, 'uploads/img_682e4d6cf2e179.79032758.jpg', 0, '2025-05-21 22:02:21');

-- --------------------------------------------------------

--
-- Table structure for table `makes`
--

CREATE TABLE `makes` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `vehicle_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `makes`
--

INSERT INTO `makes` (`id`, `name`, `vehicle_type_id`) VALUES
(1, 'Audi', 1),
(2, 'BMW', 1),
(12, 'Ducati', 2),
(10, 'Harley-Davidson', 2),
(8, 'Honda', 1),
(11, 'Kawasaki', 2),
(5, 'Mazda', 1),
(6, 'Nissan', 1),
(7, 'Porsche', 1),
(13, 'Suzuki', 2),
(4, 'Toyota', 1),
(3, 'Volkswagen', 1),
(9, 'Yamaha', 2);

-- --------------------------------------------------------

--
-- Table structure for table `models`
--

CREATE TABLE `models` (
  `id` int(11) NOT NULL,
  `make_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `models`
--

INSERT INTO `models` (`id`, `make_id`, `name`) VALUES
(1, 1, 'A1'),
(2, 1, 'A3'),
(3, 1, 'A4'),
(4, 1, 'A6'),
(5, 1, 'A8'),
(6, 1, 'Q3'),
(7, 1, 'Q5'),
(8, 1, 'Q7'),
(9, 1, 'RS4'),
(10, 1, 'RS6'),
(11, 2, '1 serija'),
(12, 2, '3 serija'),
(13, 2, '5 serija'),
(14, 2, 'M3'),
(15, 2, 'M5'),
(16, 2, 'X3'),
(17, 2, 'X5'),
(18, 2, 'X6'),
(19, 2, 'iX'),
(20, 3, 'Golf'),
(21, 3, 'Polo'),
(22, 3, 'Passat'),
(23, 3, 'Tiguan'),
(24, 3, 'Touareg'),
(25, 3, 'Arteon'),
(26, 3, 'ID.4'),
(27, 3, 'Jetta'),
(28, 4, 'Corolla'),
(29, 4, 'Camry'),
(30, 4, 'Yaris'),
(31, 4, 'RAV4'),
(32, 4, 'Hilux'),
(33, 4, 'Land Cruiser'),
(34, 4, 'Supra'),
(35, 4, 'GR86'),
(36, 5, 'Mazda2'),
(37, 5, 'Mazda3'),
(38, 5, 'Mazda6'),
(39, 5, 'CX-3'),
(40, 5, 'CX-5'),
(41, 5, 'CX-9'),
(42, 5, 'MX-5 Miata'),
(43, 5, 'RX-7'),
(44, 6, 'Micra'),
(45, 6, 'Almera'),
(46, 6, 'Qashqai'),
(47, 6, 'X-Trail'),
(48, 6, 'Navara'),
(49, 6, 'Skyline GT-R'),
(50, 6, '350Z'),
(51, 6, '370Z'),
(52, 7, '911'),
(53, 7, 'Cayenne'),
(54, 7, 'Macan'),
(55, 7, 'Panamera'),
(56, 7, 'Taycan'),
(57, 7, '718 Boxster'),
(58, 7, '718 Cayman'),
(59, 8, 'Civic'),
(60, 8, 'Accord'),
(61, 8, 'CR-V'),
(62, 8, 'Jazz'),
(63, 9, 'YZF-R1'),
(64, 9, 'MT-07'),
(65, 9, 'MT-09'),
(66, 9, 'XSR700'),
(67, 10, 'Sportster'),
(68, 10, 'Softail'),
(69, 10, 'Touring'),
(70, 11, 'Ninja'),
(71, 11, 'Z'),
(72, 11, 'Vulcan'),
(73, 12, 'Monster'),
(74, 12, 'Panigale'),
(75, 12, 'Multistrada'),
(76, 13, 'GSX-R'),
(77, 13, 'V-Strom'),
(78, 13, 'Hayabusa');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `phone`, `created_at`) VALUES
(1, 'hellcatt806', '$2y$10$cL4VO0dH.447NUBucEHWVOy.vfZMiB5P/ZVfOZLkDCDyTMDdDCjAy', 'hellcatt806@gmail.com', '+37064588603', '2025-04-28 20:55:54'),
(3, 'Marius', '$2y$10$ExKUQbe7R/OT6Kuh6qErAuBiYqsIRCoNwZircQ59z3G/CYMN0MDvC', 'test@test.lt', '+3706123456789', '2025-05-21 22:00:24');

-- --------------------------------------------------------

--
-- Table structure for table `user_favorites`
--

CREATE TABLE `user_favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `listing_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_favorites`
--

INSERT INTO `user_favorites` (`id`, `user_id`, `listing_id`, `created_at`) VALUES
(15, 3, 36, '2025-05-21 22:02:22'),
(16, 3, 35, '2025-05-21 22:03:32');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_types`
--

CREATE TABLE `vehicle_types` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicle_types`
--

INSERT INTO `vehicle_types` (`id`, `name`) VALUES
(1, 'Automobilis'),
(2, 'Motociklas');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `listings`
--
ALTER TABLE `listings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `vehicle_type_id` (`vehicle_type_id`),
  ADD KEY `make_id` (`make_id`),
  ADD KEY `model_id` (`model_id`);

--
-- Indexes for table `listing_images`
--
ALTER TABLE `listing_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `listing_id` (`listing_id`);

--
-- Indexes for table `makes`
--
ALTER TABLE `makes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_vehicle_type` (`name`,`vehicle_type_id`),
  ADD KEY `vehicle_type_id` (`vehicle_type_id`);

--
-- Indexes for table `models`
--
ALTER TABLE `models`
  ADD PRIMARY KEY (`id`),
  ADD KEY `make_id` (`make_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_favorites`
--
ALTER TABLE `user_favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_listing_unique` (`user_id`,`listing_id`),
  ADD KEY `listing_id` (`listing_id`);

--
-- Indexes for table `vehicle_types`
--
ALTER TABLE `vehicle_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `listings`
--
ALTER TABLE `listings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `listing_images`
--
ALTER TABLE `listing_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `makes`
--
ALTER TABLE `makes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `models`
--
ALTER TABLE `models`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_favorites`
--
ALTER TABLE `user_favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `vehicle_types`
--
ALTER TABLE `vehicle_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `listings`
--
ALTER TABLE `listings`
  ADD CONSTRAINT `listings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `listings_ibfk_2` FOREIGN KEY (`vehicle_type_id`) REFERENCES `vehicle_types` (`id`),
  ADD CONSTRAINT `listings_ibfk_3` FOREIGN KEY (`make_id`) REFERENCES `makes` (`id`),
  ADD CONSTRAINT `listings_ibfk_4` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`);

--
-- Constraints for table `listing_images`
--
ALTER TABLE `listing_images`
  ADD CONSTRAINT `listing_images_ibfk_1` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `makes`
--
ALTER TABLE `makes`
  ADD CONSTRAINT `makes_ibfk_1` FOREIGN KEY (`vehicle_type_id`) REFERENCES `vehicle_types` (`id`);

--
-- Constraints for table `models`
--
ALTER TABLE `models`
  ADD CONSTRAINT `models_ibfk_1` FOREIGN KEY (`make_id`) REFERENCES `makes` (`id`);

--
-- Constraints for table `user_favorites`
--
ALTER TABLE `user_favorites`
  ADD CONSTRAINT `user_favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_favorites_ibfk_2` FOREIGN KEY (`listing_id`) REFERENCES `listings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
