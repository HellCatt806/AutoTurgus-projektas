-- MySQL dumps

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `projektukas` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `projektukas`;

-- Vartotoju lentele
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Transporto tipai
CREATE TABLE `vehicle_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Markiu lentele
CREATE TABLE `makes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `vehicle_type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_vehicle_type` (`name`, `vehicle_type_id`),
  KEY `vehicle_type_id` (`vehicle_type_id`),
  CONSTRAINT `makes_ibfk_1` FOREIGN KEY (`vehicle_type_id`) REFERENCES `vehicle_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Modeliu lentele
CREATE TABLE `models` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `make_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `make_id` (`make_id`),
  CONSTRAINT `models_ibfk_1` FOREIGN KEY (`make_id`) REFERENCES `makes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Skelbimu lentele
CREATE TABLE `listings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
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
  `engine_capacity` decimal(4,1) DEFAULT NULL COMMENT 'Variklio turis l',
  `transmission` varchar(20) DEFAULT NULL COMMENT 'Automobiliams',
  `vin` varchar(17) DEFAULT NULL COMMENT 'VIN kodas',
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `vehicle_type_id` (`vehicle_type_id`),
  KEY `make_id` (`make_id`),
  KEY `model_id` (`model_id`),
  CONSTRAINT `listings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `listings_ibfk_2` FOREIGN KEY (`vehicle_type_id`) REFERENCES `vehicle_types` (`id`),
  CONSTRAINT `listings_ibfk_3` FOREIGN KEY (`make_id`) REFERENCES `makes` (`id`),
  CONSTRAINT `listings_ibfk_4` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pradiniai duomenys
INSERT INTO `vehicle_types` (`id`, `name`) VALUES
(1, 'Automobilis'),
(2, 'Motociklas');

-- Automobiliu marks
INSERT INTO `makes` (`id`, `name`, `vehicle_type_id`) VALUES
(1, 'Audi', 1),
(2, 'BMW', 1),
(3, 'Volkswagen', 1),
(4, 'Toyota', 1),
(5, 'Mazda', 1),
(6, 'Nissan', 1),
(7, 'Porsche', 1),
(8, 'Honda', 1),
(9, 'Yamaha', 2),
(10, 'Harley-Davidson', 2),
(11, 'Kawasaki', 2),
(12, 'Ducati', 2),
(13, 'Suzuki', 2);

-- Automobiliu modeliai
INSERT INTO `models` (`id`, `make_id`, `name`) VALUES
-- Audi
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
-- BMW
(11, 2, '1 serija'),
(12, 2, '3 serija'),
(13, 2, '5 serija'),
(14, 2, 'M3'),
(15, 2, 'M5'),
(16, 2, 'X3'),
(17, 2, 'X5'),
(18, 2, 'X6'),
(19, 2, 'iX'),
-- Volkswagen
(20, 3, 'Golf'),
(21, 3, 'Polo'),
(22, 3, 'Passat'),
(23, 3, 'Tiguan'),
(24, 3, 'Touareg'),
(25, 3, 'Arteon'),
(26, 3, 'ID.4'),
(27, 3, 'Jetta'),
-- Toyota
(28, 4, 'Corolla'),
(29, 4, 'Camry'),
(30, 4, 'Yaris'),
(31, 4, 'RAV4'),
(32, 4, 'Hilux'),
(33, 4, 'Land Cruiser'),
(34, 4, 'Supra'),
(35, 4, 'GR86'),
-- Mazda
(36, 5, 'Mazda2'),
(37, 5, 'Mazda3'),
(38, 5, 'Mazda6'),
(39, 5, 'CX-3'),
(40, 5, 'CX-5'),
(41, 5, 'CX-9'),
(42, 5, 'MX-5 Miata'),
(43, 5, 'RX-7'),
-- Nissan
(44, 6, 'Micra'),
(45, 6, 'Almera'),
(46, 6, 'Qashqai'),
(47, 6, 'X-Trail'),
(48, 6, 'Navara'),
(49, 6, 'Skyline GT-R'),
(50, 6, '350Z'),
(51, 6, '370Z'),
-- Porsche
(52, 7, '911'),
(53, 7, 'Cayenne'),
(54, 7, 'Macan'),
(55, 7, 'Panamera'),
(56, 7, 'Taycan'),
(57, 7, '718 Boxster'),
(58, 7, '718 Cayman'),
-- Honda
(59, 8, 'Civic'),
(60, 8, 'Accord'),
(61, 8, 'CR-V'),
(62, 8, 'Jazz'),
-- Motociklu modeliai
-- Yamaha
(63, 9, 'YZF-R1'),
(64, 9, 'MT-07'),
(65, 9, 'MT-09'),
(66, 9, 'XSR700'),
-- Harley-Davidson
(67, 10, 'Sportster'),
(68, 10, 'Softail'),
(69, 10, 'Touring'),
-- Kawasaki
(70, 11, 'Ninja'),
(71, 11, 'Z'),
(72, 11, 'Vulcan'),
-- Ducati
(73, 12, 'Monster'),
(74, 12, 'Panigale'),
(75, 12, 'Multistrada'),
-- Suzuki
(76, 13, 'GSX-R'),
(77, 13, 'V-Strom'),
(78, 13, 'Hayabusa');