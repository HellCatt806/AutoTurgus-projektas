-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 12, 2025 at 11:30 AM
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
-- Table structure for table `listing`
--

CREATE TABLE `listing` (
  `aprasymas` text NOT NULL,
  `modelis` varchar(30) NOT NULL,
  `skelbimo_pavadinimas` varchar(40) NOT NULL,
  `kaina` int(8) NOT NULL,
  `kuro_tipas` tinyint(10) NOT NULL,
  `begiai` tinyint(1) NOT NULL,
  `tel_nr` text NOT NULL,
  `kebulo_tipas` tinyint(4) NOT NULL,
  `rida` int(11) NOT NULL,
  `varomi_ratai` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `listing`
--

INSERT INTO `listing` (`aprasymas`, `modelis`, `skelbimo_pavadinimas`, `kaina`, `kuro_tipas`, `begiai`, `tel_nr`, `kebulo_tipas`, `rida`, `varomi_ratai`) VALUES
('Test description', 'BMW', 'BMW test', 10000, 1, 2, '3706000000', 1, 100000, 1),
('Test description', 'Audi', 'Audi', 10000, 1, 2, '3706000000', 1, 100000, 1),
('Puikios būklės automobilis', 'BMW 520d', 'BMW 520d M Sport', 15500, 2, 1, '861112345', 1, 145000, 4),
('Ekonomiškas miesto automobilis', 'Toyota Yaris', 'Toyota Yaris Hybrid', 8900, 3, 1, '862223456', 2, 78000, 2),
('Galingas ir prabangus', 'Mercedes-Benz E220', 'MB E220 AMG', 19500, 1, 1, '863334567', 1, 125000, 4),
('Tvarkingas automobilis', 'Volkswagen Passat', 'Passat B8 2.0 TDI', 13500, 2, 1, '864445678', 1, 160000, 4),
('Sportinis ir greitas', 'Porsche 911', 'Porsche 911 Turbo', 75000, 1, 1, '865556789', 3, 90000, 4),
('Patikimas šeimos automobilis', 'Mazda 6', 'Mazda 6 Skyactiv', 10500, 2, 1, '866667890', 1, 140000, 4),
('Erdvus ir komfortiškas', 'Volvo XC60', 'Volvo XC60 D5 AWD', 18500, 2, 1, '867778901', 2, 155000, 4),
('Idealus pirmas automobilis', 'Ford Fiesta', 'Ford Fiesta 1.0 EcoBoost', 7500, 3, 1, '868889012', 2, 98000, 2),
('Puikus pasirinkimas ilgoms kelionėms', 'Skoda Superb', 'Skoda Superb 2.0 TDI', 14500, 2, 1, '869990123', 1, 135000, 4),
('Greitas ir agresyvus', 'Audi RS3', 'Audi RS3 Quattro', 50000, 1, 1, '870001234', 3, 55000, 4),
('Puikios būklės automobilis', 'BMW 520d', 'BMW 520d M Sport', 15500, 2, 1, '861112345', 1, 145000, 4),
('NEGRAI Juodi', 'BMW 520d', 'BMW 520d M Sport', 15500, 2, 1, 'C:xampphtd', 1, 145000, 4),
('E39', 'BMW 520d', 'BMW 520d M Sport', 15500, 2, 1, 'C:xampphtdocsimge90.jpg', 1, 145000, 4),
('Puikios būklės automobilis', 'BMW 520d', 'BMW 520d M Sport', 15500, 2, 1, 'C:xampphtdocsimge90.jpg', 1, 145000, 4),
('zajibala neveikia', 'BMW 520d', 'BMW 520d M Sport', 15500, 2, 1, 'C:\\xampp\\htdocs\\img\\e90.jpg', 1, 145000, 4),
('zajibala neveikia', 'BMW 520d', 'BMW 520d M Sport', 15500, 2, 1, 'C:/xampp/htdocs/img/e90.jpg', 1, 145000, 4),
('zajibala neveikia', 'BMW 520d', 'BMW 520d M Sport', 15500, 2, 1, 'file:///C:/xampp/htdocs/img/e90.jpg', 1, 145000, 4),
('zajibala neveikia', 'BMW 520d', 'BMW 520d M Sport', 15500, 2, 1, 'http://localhost/img/e90.jpg', 1, 145000, 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `tel_nr` varchar(20) NOT NULL,
  `vardas` varchar(20) NOT NULL,
  `pastas` varchar(40) NOT NULL,
  `slaptazodis` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
