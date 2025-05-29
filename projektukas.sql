-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2025 at 12:01 AM
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
(23, 1, 1, 5, 17, 2021, 120, 41000, '2', 'elektra', 19800.00, '0', '+37060000016', NULL, 0.0, 'automatinė', '', 'Nauji akumuliatoriai', '2025-04-29 09:40:00'),
(24, 1, 1, 6, 20, 2009, 90, 220000, '1', 'benzinas', 4000.00, '0', '+37060000017', NULL, 1.6, 'mechaninė', '', 'Reikia minimalių investicijų', '2025-04-29 09:41:00'),
(25, 1, 1, 1, 6, 2012, 103, 176000, '1', '', 7000.00, '0', '+37060000018', NULL, 1.7, 'mechaninė', '', 'Tvarkingas, su nauja technikine', '2025-04-29 09:42:00'),
(35, 3, 1, 2, 13, 2020, 250, 300000, 'universalas', 'benzinas', 20000.00, 'uploads/img_682e4d3d995389.40760182.jpg', '+3706123456789', 'Vilnius', 2.0, 'automatinė', '45646416457456165', 'Kuriasi važiuoja', '2025-05-21 22:01:33'),
(36, 3, 2, 12, 73, 2021, 35, 5000, NULL, 'benzinas', 5500.00, 'uploads/img_682e4d6cf24fe0.28728866.jpg', '+3706123456789', 'Kaunas', 1.0, NULL, '45498789789789797', 'Nekritęs, problemų nėra.', '2025-05-21 22:02:20'),
(37, 1, 1, 1, 2, 2006, 103, 350000, 'hečbekas', 'dyzelis', 2900.00, 'uploads/img_6836eaf7c19538.56244612.jpg', '+37061234567', 'Kaunas', 2.0, 'mechaninė', NULL, 'Naujas techas\r\nnaujas priekinis stiklas \r\nkrituliu daviklis \r\nauto sviesos \r\nbose aparatura \r\nsutvarkyta vazuokle \r\npakeista paskirstimo dirzas prie 20k km padaryta cheminis valymas', '2025-05-28 10:48:44'),
(38, 1, 1, 1, 2, 2009, 118, 182300, 'hečbekas', 'benzinas', 4800.00, 'uploads/img_6836ecf21782a4.22631517.jpg', '+37064578901', NULL, 1.8, 'mechaninė', NULL, 'KURO SUVARTOJIMAS L/100 KM\r\nMieste11,5\r\nUžmiestyje6,8\r\nVidutinės8,6', '2025-05-28 11:01:06'),
(39, 1, 1, 2, 12, 2010, 130, 285456, 'universalas', 'dyzelis', 3900.00, 'uploads/img_6836eddd1a0f77.22848594.jpg', '+37069912345', 'Vilnius', 2.0, 'automatinė', NULL, NULL, '2025-05-28 11:05:01'),
(41, 1, 1, 2, 12, 2003, 170, 380000, 'sedanas', 'benzinas/dujos', 3100.00, 'uploads/img_6836ef0f0fcaf3.98121100.jpg', '+37067711223', 'Šiauliai', 3.0, 'mechaninė', NULL, 'Parduodamas geros komplektacijos, tvarkingas BMW 330i\r\n\r\nKuriasi, važiuoja gerai, tiek benzinu, tiek dujomis. Važiuoklė tvarkinga, kėbulas gyvas. Ekonomiškas BMW.\r\nKomplektacija\r\n- juodos lubos;\r\n- el. stoglangis;\r\n- HI-Fi Harman Kardon audio sistema;\r\n- šildomos sėdynės;\r\n- elektrinis sėdynių valdymas su atmintimi;\r\n- Xenon žibintai.\r\nPlačiau telefonu.', '2025-05-28 11:10:07'),
(42, 1, 1, 2, 12, 2004, 110, 450000, 'coupe', 'dyzelis', 3200.00, 'uploads/img_6836ef9c5f3e88.77539160.jpg', '+37060456789', NULL, 2.0, 'mechaninė', NULL, 'Parduodamas mylėtas, techniškai prižiūrėtas automobilis, facelift modelis. Vairuojamas panelės. Kosmetiniai trūkumai matomi nuotraukose.\r\n\r\nTiesus išmetimas\r\nAndorid multimedija\r\nChip tiuning\r\nNaujos Dunlop Wintersport 5 žieminės padangos', '2025-05-28 11:12:28'),
(43, 1, 1, 7, 53, 2016, 309, 107000, 'visureigis', 'benzinas', 25000.00, 'uploads/img_6836f09c43a181.61298282.jpg', '+37068122334', 'Klaipėda', 3.6, 'automatinė', NULL, 'Parduodamas Porsche Cayenne S automobilis.\r\n\r\nAutomobilis pasižymi gausia komplektacija:\r\n\r\n* Premium Package Plus su naujausia multimedijos sistema - Apple CarPlay / Android (Waze, Spotify ir kt.) ;\r\n* Full LED adaptyvūs žibintai;\r\n* Panoraminis stogas su stoglangiu;\r\n* \"Bose\" garso sistema;\r\n* Be rakto naudojama atrakinimo / užrakinimo, bei užvedimo sistema;\r\n* Aklosios zonos funkcija;\r\n*Šildomas daugaifunkcinis vairas;\r\n* Šildomos ir ventiliuojamos priekinės sėdynės;\r\n* Šildomos galinės sėdynės;\r\n* Elektra valdomos sėdynės su memory funkcija\r\n* Elektra valdomas bagažinės dangtis;\r\n* Galinio vaizdo kamera;\r\n* Dviejų zonų klimato kontrolė;\r\n* Važiavimo rėžimo nustatymai;\r\n* Parkavimo jutikliai priekyje bei gale;\r\n* Laisvų rankų įranga;\r\n* Navigacijos sistema;\r\n* CD / USB / Bluetooth multimedijos prieigos;\r\n* Start / Stop sistema;\r\n* Šildomi, elektra valdomi, automatiškai užsilenkiantys veidrodėliai;\r\n* Kritulių jutiklis - automatiškai įsijungiantys žibintai bei valytuvai;\r\n* Elektrinis rankinis stabdys su \"Auto hold\" funkcija;\r\n* Lengvojo lydinio ratlankiai - R20;\r\n\r\nAutomobilį galima tikrinti jūsų pasirinktame servise.\r\n\r\nGalimas lizingas.\r\n\r\nDaugiau informacijos telefonu.', '2025-05-28 11:16:44'),
(44, 1, 1, 7, 54, 2018, 265, 95800, 'visureigis', 'benzinas', 38500.00, 'uploads/img_6836f12d92ae88.80665164.jpg', '+37068890123', 'Kaunas', 3.0, 'automatinė', NULL, 'PLACIAU KA DOMINA SKAMBINKIT.\r\nVisi atsiskaitymo budai tinkami.', '2025-05-28 11:19:09'),
(45, 1, 1, 4, 31, 2009, 100, 249000, 'visureigis', 'dyzelis', 2009.00, 'uploads/img_6836f2a0c8b805.96740723.jpg', '+37069844556', 'Utena', 2.2, 'mechaninė', NULL, 'Parduodama Toyota RAV4 D4D 2.2 dyzelis, 100 kW\r\n• Mechaninė pavarų dėžė\r\n• Rida: 249 000 km\r\n• Kaina: 4950 €\r\n\r\nTechninė būklė – labai gera, automobilis paruoštas eksploatacijai.\r\nVizualiai – nepriekaištinga išvaizda, labai tvarkingas kėbulas ir salonas.\r\nPuiki komplektacija, veikianti 4x4 sistema, mažos kuro sąnaudos.\r\nPadaryta antikorozinė danga, yra remonto darbų sąskaita – 1500 € (viskas atlikta laiku ir kokybiškai).\r\n\r\nAutomobilis prižiūrėtas, važiuoja sklandžiai – puikus pasirinkimas ieškantiems patikimo SUV!\r\nGalima apžiūra vietoje bet kuriuo metu.', '2025-05-28 11:25:20'),
(46, 1, 1, 4, 29, 2022, 162, 305000, 'sedanas', 'benzinas', 14300.00, 'uploads/img_6836f367c10c08.36650023.jpg', '+37067166778', 'Kaunas', 2.5, 'automatinė', NULL, NULL, '2025-05-28 11:28:39'),
(47, 1, 1, 1, 3, 2007, 103, 289646, 'sedanas', 'dyzelis', 1700.00, 'uploads/img_6836f3d0dafeb6.86079236.jpg', '+37061234567', 'Vilnius', 2.0, 'mechaninė', NULL, NULL, '2025-05-28 11:30:24'),
(48, 1, 1, 1, 3, 2006, 103, 369252, 'universalas', 'dyzelis', 2750.00, 'uploads/img_6836f459837945.68137346.jpg', '+37064578901', 'Jonava', 2.0, 'automatinė', NULL, 'Parduodamas puikus ir manevringas Audi A4 B7 automobilis turintis 2.0 103kW Dyzelinį variklį ir automatinę pavarų dėžę. Automobilis kuriasi, važiuoja puikiai, skydelyje nėra jokių klaidų. Variklis dirba gražiai, be jokių pašalinių garsų, nedūmija, traukia puikiai. Pavarų dėžė veikia sklandžiai, nemeta jokių avarinių režimų, pavaros persijunginėja sklandžiai kaip ir priklauso variatoriaus tipo dėžėms. Automobilio kebulo būklė išties gera, ką tik atlikti automobilio ir žibintų poliravimo darbai. Nebloga komplektacija: Xenon, DRL, Recaro šildomos sėdynės, rūko žibintai, kablys, autopilotas ir kiti privalumai matomi nuotraukuose. Daugiau informacijos apie automobilį suteiksime telefonu.\r\n\r\nTechninė apžiūra galioja iki: 2025-10-23', '2025-05-28 11:32:41'),
(49, 14, 1, 1, 4, 2014, 200, 213000, 'universalas', 'dyzelis', 19500.00, 'uploads/img_6838c6ebe1f5f5.07625070.jpg', '+3705051622', 'Jonava', 3.0, 'automatinė', NULL, NULL, '2025-05-29 20:43:23'),
(50, 14, 1, 2, 12, 2010, 120, 396000, 'universalas', 'dyzelis', 3500.00, 'uploads/img_6838c72c5e5538.30300733.jpg', '+3705088800', 'Vilnius', 2.0, 'mechaninė', NULL, 'Salonas\r\nŠildomos sėdynės Bagažinės uždangalas\r\nElektronika\r\nEl. reguliuojami veidrodėliai Automatiškai įsijungiantys žibintai Šildomi veidrodėliai Autopilotas Skaitmeninis prietaisų skydelis\r\nApsauga\r\nImobilaizeris Signalizacija\r\nAudio/video įranga\r\nCD grotuvas MP3 grotuvas AUX jungtis USB jungtis\r\nEksterjeras\r\nLengvojo lydinio ratlankiai LED dienos žibintai Žibintai „Xenon“ Kablys Priekinių žibintų plovimo įtaisas Žieminių padangų komplektas\r\nKiti ypatumai\r\nNeeksploatuota Lietuvoje Serviso knygelė Katalizatorius Atsarginis ratas\r\nSaugumas', '2025-05-29 20:44:28'),
(51, 14, 1, 2, 16, 2021, 215, 91500, 'sedanas', 'benzinas', 27000.00, 'uploads/img_6838c7e0843d38.59291246.jpg', '+3705140090', NULL, 2.0, 'automatinė', NULL, 'Parduodamas automobilis iš vieno savininko. Automobilis pilnai aptarnautas. Techninė ir vizualinė būklė – ideali. Originali maža rida. Yra visi tai patvirtinantys dokumentai. Nauja belgiška techninė apžiūra, galiojanti iki 2027 metų vasario mėnesio. Gera komplektacija. Automobilio salonas – idealios būklės. Daugiau informacijos telefonu.', '2025-05-29 20:47:28'),
(52, 1, 1, 2, 17, 2023, 250, 73000, 'visureigis', 'benzinas', 46500.00, 'uploads/img_6838c81c4e9b68.91604358.jpg', '+37061234567', 'Kaunas', 3.0, 'automatinė', '1HGCM82633A004352', 'BMW X5 XDRIVE40I\r\n\r\nM paket komplektaciją tiek viduje, tiek išorėje\r\nNavigacija(Pilni naujausi EU žemėlapiai)\r\nHead-Up displėjus\r\nParkavimo asistentas\r\nLinijų asistentas\r\n360° kameros\r\nCRYSTAL komplektas (šviečianti stiklinė bėgių svirtis, start stop, volume)\r\nLazeriniai Black žibintai\r\nŠildomas elektrinis vairas\r\nŠildomos priekinės sėdynes\r\nŠildomos galinės sėdynes\r\nSusidūrimo prevencijos sistema\r\nAklos zonos stebėjimo sistema\r\nBevielis telefono krovimas\r\nR21 ratai\r\nŽieminiu padangu komplektas\r\nAutomobilis padengtas keramikinė nano danga\r\nLT registracija\r\nMILD HYBRID versija\r\n\r\nIr tt...\r\n\r\nVisiškai tvarkingas automobilis. Jokių paslėptų defektų, galima tikrint betkuriame pasirinktame servise\r\n\r\nDomina keitimas i Tesla Y arba 3,taip pat nekilnojamas turtas\r\n\r\nSKUBIAI !!!', '2025-05-29 20:48:28'),
(53, 14, 2, 11, 71, 2025, 50, 0, NULL, 'benzinas', 7795.00, 'uploads/img_6838c88fd954f2.27119458.jpg', '+3705051628', 'Kaunas', 6.5, NULL, NULL, 'Nauji Kawasaki Z650 2025 metų modeliai.\r\n\r\nVežame naujus Kawasaki motociklus su dviejų metų garantija.', '2025-05-29 20:50:23'),
(54, 1, 1, 2, 18, 2016, 230, 280000, 'visureigis', 'dyzelis', 25400.00, 'uploads/img_6838c8ee253063.10604246.jpg', '+37069876543', 'Panevėžys', 3.0, 'automatinė', NULL, 'Įranga:\r\n•Komplektacija:\r\nM sport paketas\r\nM odinis vairas\r\nM aerodinaminis paketas\r\nSPORT+/SPORT/COMFORT/ECO važiavimo režimai\r\nGalinių durelių užuolaidėlės\r\nMedžiaginiai / guminiai originalus kilimėliai\r\nVidaus / išorės veidrodžiai su automatiniu pritemdymu\r\nVėdinamos / šildomos priekinės sėdynės\r\nComfort pripučiamos sėdynės su atmintimi\r\nAliumininės ir black piano apdailos\r\nKeraminiai valdymo elementai\r\nHead-Up Display\r\nHarman Kardon garso sistema\r\nŽenklų atpažinimas\r\nParkavimo jutikliai (PDC)\r\nAdaptyvūs LED žibintai (karpo)\r\nRankenėlių apšvietimas\r\nAutomatinės tolimosios šviesos\r\nPersirikiavimo įspėjimo sistema\r\nAktyvi pėsčiųjų apsauga\r\nAktyvioji apsauga\r\nLinijų asistentas\r\n360° kameros\r\nAktyvus vairas\r\nŠildomas vairas\r\nM ratai 469 modelis\r\nAdaptyvi M važiuoklė\r\nDinaminė adaptyvi pakaba\r\nKeyless Access\r\nDurų pritraukėjai\r\n„Shadow Line“ paketas\r\nJuodos spalvos lubos\r\nOdinė salono apdaila\r\nEl. bagažinė\r\n\r\n\r\nAutomobilis labai geros būklės, tvarkingas, galima tikrinti bet kuriame servise.\r\nSalone – aukštos kokybės medžiagos, įskaitant keramikinę apdailą, suteikiančią papildomo prabangos pojūčio.', '2025-05-29 20:51:58'),
(55, 14, 2, 11, 70, 2025, 50, 0, NULL, 'benzinas', 8445.00, 'uploads/img_6838c91397e149.37097227.jpg', '+3706695622', 'Vilnius', 6.5, NULL, NULL, 'Nauji Kawasaki Ninja 650 2025 metų modeliai.\r\n\r\nPrekiaujame naujais Kawasaki motociklus su dviejų metų garantija.', '2025-05-29 20:52:35'),
(56, 14, 1, 1, 3, 2011, 105, 274964, 'sedanas', 'dyzelis', 6900.00, 'uploads/img_6838c9b7446b42.72846661.jpg', '+3705651685', 'Klaipėda', 2.0, 'automatinė', NULL, NULL, '2025-05-29 20:55:19'),
(57, 1, 1, 2, 12, 2002, 142, 515000, 'universalas', 'dyzelis', 2550.00, 'uploads/img_6838c9cf07cc15.63023218.jpg', '+37061239876', 'Telšiai', 2.9, 'automatinė', NULL, 'Parduodamas BMW E39 Touring (M paketas, 3.0d, Individual salonas)\r\nVariklis: 3.0d (160 kW) su turbina ir IC iš 3.5D – 244 AG\r\n-Pavarų dėžė: Automatinė\r\n-Kėbulas: Be rūdžių, slenksčiai idealūs, dugnas padarytas antikorozinis.\r\n-Komplektacija:\r\n—Bi-xenon\r\n– M paketas\r\n– Individual odinis salonas\r\n– Juodos lubos\r\n–DSP garso sistema\r\n? Techninė būklė:\r\n– Vedasi ir važiuoja puikiai\r\n– Techninė apžiūra iki 2026.07\r\n– Važiuoklė tvarkinga, trauka gera\r\n\r\n\r\n– Kėbulas turi keleta įlenkimu (galima tvarkyti meniniu lyginimu – be dažymo)', '2025-05-29 20:55:43'),
(58, 14, 1, 1, 3, 2007, 105, 286156, 'universalas', 'dyzelis', 1900.00, 'uploads/img_6838ca41d90ae8.10978707.jpg', '+370505532', NULL, 2.0, 'mechaninė', NULL, NULL, '2025-05-29 20:57:37'),
(59, 1, 1, 2, 12, 2007, 120, 397000, 'universalas', 'dyzelis', 3250.00, 'uploads/img_6838ca48789f74.93125243.jpg', '+37069812345', 'Vilnius', 2.0, 'mechaninė', NULL, 'Tvarkingas automobilis, važiuoja gerai, ekonomiškas ir komfortiškas.\r\n+ Geros M+S padangos\r\n+ Techninė apžiūra galioja iki lapkričio mėnesio\r\n+ Gausi komplektacija\r\n-Yra kosmetinių defektų\r\n-Neveikia panorama', '2025-05-29 20:57:44'),
(60, 14, 1, 1, 3, 2006, 103, 369252, 'universalas', 'dyzelis', 2750.00, 'uploads/img_6838caf2d68cd3.11436424.jpg', '+3705001254', NULL, 1.9, 'automatinė', NULL, NULL, '2025-05-29 21:00:34'),
(61, 1, 1, 2, 13, 2018, 140, 172000, 'sedanas', 'dyzelis', 25300.00, 'uploads/img_6838cb8744d597.14989466.jpg', '+37060123456', 'Mažeikiai', 2.0, 'automatinė', NULL, 'Parduodamas BMW 5 serijos automobilis tvarkingas ir prižiūrėtas, visa serviso istorija. Neseniai pakeisti tepalai, visi filtrai, stabdžių diskai ir priekiniai amortizatoriai, išvalytas DPF filtras. Galingas ir ekonomiškas, komfortiškas automobilis su gausia komplektacija. Salonas švarus,viskas funkcionuoja. Jokių papildomų investicijų (nebent gražių ratlankių) – sėdi ir važiuoji.', '2025-05-29 21:03:03'),
(62, 14, 1, 1, 3, 2010, 105, 286245, 'universalas', 'dyzelis', 3000.00, 'uploads/img_6838cba0b96476.44904663.jpg', '+3705251672', 'Klaipėda', 2.0, 'automatinė', NULL, NULL, '2025-05-29 21:03:28'),
(63, 1, 1, 2, 13, 2005, 130, 390000, 'universalas', 'dyzelis', 3000.00, 'uploads/img_6838cc341e7612.63594490.jpg', '+37067987654', 'Kaunas', 2.5, 'automatinė', NULL, 'Parduodamas tvarkingas BMW. Prieš 3 mėn. praeita nauja techninė apžiūra galioja iki 2027-02 mėn. Investuota daug pinigų, pilnai sutvarkyta važiuoklė, rankinis.\r\nAutomobilis turi didelį TV, comfort juodą šildomą saloną, HiFi aparatūrą, Xenon dynamic žibintus.\r\nVariklis dirba be priekaištų, dėžė tai pat(puslė nepulsuoja)\r\nKėbulas geros būklės, neapdaužytas nesupuvęs.\r\nKaina derinama šiek tiek prie automobilio. Su savo kainų pasiūlymais negaiškit laiko, galit net nerašinėti/neskambinėti.\r\nParduodamas dėl to, kad nupirktas naujesnis BMW automobilis, šis daugiau stovi, negu važiuoja', '2025-05-29 21:05:56'),
(64, 14, 1, 1, 2, 2006, 103, 349000, 'hečbekas', 'dyzelis', 2900.00, 'uploads/img_6838cc3a5373f0.89272607.jpg', '+3709053322', NULL, 2.0, 'mechaninė', NULL, NULL, '2025-05-29 21:06:02'),
(65, 14, 1, 1, 3, 2009, 105, 194664, 'sedanas', 'dyzelis', 4400.00, 'uploads/img_6838ccf4c794f8.72674259.jpg', '+3705658899', NULL, 2.0, 'automatinė', NULL, NULL, '2025-05-29 21:09:08'),
(66, 1, 1, 2, 12, 2015, 185, 172000, 'universalas', 'benzinas', 15500.00, 'uploads/img_6838ccfc4a88e6.31069857.jpg', '+37064432109', 'Panevėžys', 2.0, 'automatinė', NULL, 'Parduodamas aki traukiantis BMW F31 lci turintis daug privalumu.\r\n\r\n\r\n\r\nPilnas Mpack su M suportais\r\n\r\nJuodos lubos\r\n\r\nHud (greicio projekcija ant stiklo)\r\n\r\nKeyless sistema,bagazine atsidaro nuo kojos \r\n\r\nFull led dynamic zibintai su tolimuju sviesu asistentu\r\n\r\nMaza Originali rida\r\n\r\nZenklu atpazinimo sistema\r\n\r\nHarman Kardon garso sistema\r\n\r\nr20 ratai\r\n\r\nnuimamas kablys ir daug kiti privalumu kurios galite pamatyti nuotraukose arba gyvai\r\n\r\nAuto techniskai tvarkingas jokiu tepalu ant variklio arba pasaliniu garsu\r\n\r\nVariklis ir greiciu deze tvarkinga,kebulas lygus\r\n\r\nJusu patogumui praeita tech apziura ,galima iskart registruoti.\r\n\r\nderybos tik prie auto.', '2025-05-29 21:09:16'),
(67, 1, 1, 2, 13, 2011, 180, 500000, 'sedanas', 'dyzelis', 6300.00, 'uploads/img_6838cd741f5078.40807985.jpg', '+37061287654', 'Vilnius', 3.0, 'automatinė', NULL, 'Tvarkingas bmw , važiuoja gerai ekonomiškas', '2025-05-29 21:11:16'),
(68, 14, 1, 1, 3, 2025, 105, 289645, 'universalas', 'dyzelis', 4500.00, 'uploads/img_6838cd8a516274.60067292.jpg', '+3703411622', NULL, 2.0, 'mechaninė', NULL, NULL, '2025-05-29 21:11:38'),
(69, 1, 1, 5, 42, 1998, 81, 203000, 'kabrioletas', 'benzinas', 3000.00, 'uploads/img_6838cdef2b5c27.38356040.jpg', '+37062234567', 'Mažeikiai', 1.6, 'mechaninė', NULL, 'Techniškai tvarkingas automobilis, sudeti reguliuojami \"coilai\" ir nauji ratlankiai su padangomis ,automobilis žiemą eksplotuojamas nebuvo ,važinėta tik šiltuoju metu laiku, stogas vandens neleidžia\r\nTrukūmai: yra šiek tiek rudelių ant arkų', '2025-05-29 21:13:19'),
(70, 1, 1, 5, 42, 2001, 81, 199360, 'kabrioletas', 'benzinas', 2700.00, 'uploads/img_6838ce56a7fbb0.29846129.jpg', '+37060098765', 'Vilnius', 1.6, 'mechaninė', NULL, 'Mašiniukas prieš metus registruotas Lietuvoj, praeita technikinė, galioja dar metus. Keistas akumuliatorius, keistos galinės stabdžių kaladėlės ir diskai, keisti tepalai, sudėti vokiški coilai. Auto įvykių neturėjo, kuriasi bei važiuoja labai gerai.\r\n\r\nSaugota ir labai mylėta, deja, žiemos sezonas iškėlė rūdžių aplink galines arkas. Kai kur matosi smulkiai pasilupę dažai, yra vienas-du smulkūs įlenkimai, tačiau bendras kėbulo vaizdas neblogas. Per sulenkimus plyšęs stogas (lyjant vanduo į saloną nebėga, tačiau kaupiasi drėgmė.) Pasitrynę sėdynės.\r\n\r\nParduodamas žaisliukas, kuris suteikia be galo daug malonumo tiek vairuotojui, tiek keleiviui :)\r\n\r\nDėl kainos galima kalbėt (proto ribose)', '2025-05-29 21:15:02'),
(71, 14, 1, 1, 3, 2011, 105, 395286, 'universalas', 'dyzelis', 5700.00, 'uploads/img_6838ce92a4b262.29932480.jpg', '+3705001542', 'Jonava', 1.9, 'automatinė', NULL, 'Salonas\r\nTamsinti stiklai Daugiafunkcinis vairas Šildomos sėdynės Dalinai odinis\r\nElektronika\r\nEl. reguliuojami veidrodėliai Elektra valdomas bagažinės dangtis Automatiškai įsijungiantys žibintai Šildomi veidrodėliai Autopilotas Start-Stop funkcija\r\nApsauga\r\nImobilaizeris Signalizacija\r\nAudio/video įranga\r\nCD grotuvas MP3 grotuvas Papildoma audio įranga CD keitiklis AUX jungtis Žemų dažnių garsiakalbis HiFi audio sistema USB jungtis\r\nEksterjeras\r\nLengvojo lydinio ratlankiai Rūko žibintai Kablys Stogo bagažinės laikikliai\r\nKiti ypatumai\r\nKatalizatorius Keli raktų komplektai Atsarginis ratas\r\nSaugumas\r\nTraukos kontrolės sistema ESP', '2025-05-29 21:16:02'),
(72, 1, 1, 6, 50, 2003, 208, 162000, 'coupe', 'benzinas', 8700.00, 'uploads/img_6838cecaa9add1.08395087.jpg', '+37065543210', 'Ukmergė', 3.5, 'automatinė', NULL, NULL, '2025-05-29 21:16:58'),
(73, 14, 1, 7, 52, 2008, 261, 84487, 'kabrioletas', 'benzinas', 45900.00, 'uploads/img_6838cf4174d192.07500173.jpg', '+3705051699', NULL, 3.8, 'automatinė', NULL, 'Salonas\r\nSportinės sėdynės Daugiafunkcinis vairas Odinis salonas Elektra valdomos sėdynės Elektra valdomos sėdynės su atmintimi\r\nElektronika\r\nEl. reguliuojami veidrodėliai Elektra valdomas bagažinės dangtis Kritulių jutiklis Autopilotas LCD ekranas Skaitmeninis prietaisų skydelis\r\nApsauga\r\nImobilaizeris\r\nKiti ypatumai\r\nParduodama lizingu Keli raktų komplektai', '2025-05-29 21:18:57'),
(75, 14, 1, 7, 56, 2021, 420, 195000, 'hečbekas', 'elektra', 53500.00, 'uploads/img_6838cfa4153272.51369728.jpg', '+3705055239', 'Kaunas', 5.7, 'automatinė', NULL, NULL, '2025-05-29 21:20:36'),
(76, 1, 1, 4, 31, 2010, 100, 260000, 'visureigis', 'dyzelis', 5400.00, 'uploads/img_6838d003e8c8b1.79837666.jpg', '+37069987654', 'Utena', 2.2, 'mechaninė', NULL, NULL, '2025-05-29 21:22:11'),
(78, 1, 1, 3, 20, 2001, 66, 380000, 'hečbekas', 'dyzelis', 850.00, 'uploads/img_6838d08d2dab39.65997654.jpg', '+37069987654', 'Vilnius', 1.9, 'mechaninė', NULL, NULL, '2025-05-29 21:24:29'),
(79, 14, 1, 7, 52, 2004, 235, 134300, 'kabrioletas', 'benzinas', 32000.00, 'uploads/img_6838d0b2a25381.10979500.jpg', '+37050002333', 'Klaipėda', 3.6, 'automatinė', NULL, NULL, '2025-05-29 21:25:06'),
(80, 1, 1, 7, 52, 2025, 398, 10000, 'kabrioletas', 'benzinas', 211000.00, 'uploads/img_6838d131a33cd4.36406462.jpg', '+37069987654', 'Alytus', 3.6, 'automatinė', NULL, '911 Carrera GTS\r\n\r\nPapildoma gamyklinė įranga:\r\n\r\n▪ SportDesign kėbulo apdailos paketas iš anglies pluošto\r\n(Carbon)\r\n▪ Išorės veidrodėlių viršutinė apdaila iš anglies pluošto\r\n(Carbon) kartu su pagrindu ir apatine apdaila dažyta\r\nkėbulo spalva\r\n▪ Šviečiantis „PORSCHE\'\' LED logotipas durelėse\r\n▪ Dekoratyviniai lipdukai ant variklio ir bagažo skyriaus\r\ndangčių\r\n▪ Aktyvi pakabos valdymo sistema „Porsche Active\r\nSuspension Management“ (PASM)\r\n▪ Priekinės ašies pakėlimo sistema\r\n▪ Vairo stiprintuvas „Plus“\r\n▪ Padangų sandarinimo mišinys ir elektrinis oro\r\nkompresorius\r\n▪ 18 padėčių, elektra valdomos, sportiškos priekinės\r\nsėdynės „Adaptive Sports Seats Plus\'\'\r\n▪ Raudonos (Carmine Red) spalvos prietaisų skydelio\r\nciferblatai\r\nPrie priekyje sėdinčio keleivio kojų įtaisyta tinklinė dėtuvė\r\n▪ Erdvinio garso sistema „Burmester® High-End Surround\r\nSound System“\r\n▪ Tonuoti šviesos diodų (LED) pagrindiniai priekiniai žibintai\r\nsu „HD-Matrix Beam“ šviesos technologija\r\n▪ \'\'Exclusive Design\'\' galiniai žibintai\r\n▪ Tonuota juosta priekinio stiklo viršutinėje dalyje\r\n▪ Beraktė automobilio atrakinimo ir važiavimo sistema\r\n„Porsche Entry & Drive“\r\n▪ Automobilio statymo sistema „Active Parking Support\'\' su\r\nvaizdą aplink automobilį fiksuojančia sistema „Surround\r\nView\'\'\r\n▪ Garažo duris atidaranti sistema „HomeLink®“\r\n▪ Adaptyvi pastovaus greičio palaikymo sistema\r\n▪ Pagalbos persirikiuojant sistema „Lane Change Assist“\r\n\r\nAutomobilį galima rezervuoti, tačiau pirkimas galimas nuo 2025.06.23\r\nRida iki 10 000 km.\r\n\r\nPorsche Approved\r\n\r\n„Porsche Approved“ – titulas, suteikiamas eksploatuotiems „Porsche“ automobiliams, atitinkantiems aukščiausius „Porsche“ kokybės standartus. Tad svarstydami, naują ar eksploatuotą automobilį rinktis, pagalvokite apie dar vieną galimybę: „Porsche Approved“ automobilį. Kuo „Porsche Approved“ automobilis skiriasi nuo įprasto eksploatuoto automobilio?\r\n• Kiekvieną automobilį įvertiname pagal 111 punktų patikros sąrašą;\r\n• Korekcijas atliekame laikydamiesi griežtų „Porsche“ kokybės reikalavimų;\r\n• Visais atvejais naudojame tik originalias „Porsche“ detales;\r\n• Jums suteikiame vienų arba, išreiškus pageidavimą, dvejų ar trejų metų visapusišką garantiją;\r\n• Galite naudotis techninės priežiūros paslaugomis „Porsche Assistance“.\r\n„Porsche“ garantijos ženklas patvirtina, kad automobilis paruoštas pagal „Porsche Approved“ eksploatuotų automobilių programą.\r\n\r\nAutomobiliui suteikiama gamyklinė Porsche Approved garantija 12 mėnesių nuo automobilio pardavimo su galimybe ją pratęstis 24 ar 36 mėnesiams.', '2025-05-29 21:27:13'),
(81, 14, 1, 5, 39, 2016, 88, 178201, 'visureigis', 'benzinas', 11999.00, 'uploads/img_6838d422343197.52561706.jpg', '+3705951611', 'Telšiai', 1.9, 'automatinė', NULL, 'Salonas\r\nTamsinti stiklai Daugiafunkcinis vairas Šildomos sėdynės Bagažinės uždangalas\r\nElektronika\r\nEl. reguliuojami veidrodėliai Automatiškai įsijungiantys žibintai Kritulių jutiklis Šildomi veidrodėliai Pritemstantis veidrodėlis Atstumo jutiklių sistema Beraktė sistema Autopilotas Start-Stop funkcija Valdymas balsu LCD ekranas Liečiamas ekranas\r\nApsauga\r\nImobilaizeris Signalizacija\r\nAudio/video įranga\r\nCD grotuvas MP3 grotuvas AUX jungtis DVD grotuvas USB jungtis Laisvų rankų įranga\r\nEksterjeras\r\nLengvojo lydinio ratlankiai LED dienos žibintai LED žibintai Rūko žibintai Automatiškai užsilenkiantys veidrodėliai Žieminių padangų komplektas\r\nKiti ypatumai\r\nNeeksploatuota Lietuvoje Domina keitimas Parduodama lizingu Serviso knygelė Katalizatorius Keli raktų komplektai\r\nSaugumas\r\nTraukos kontrolės sistema ESP Juostos palaikymo sistema ISOFIX tvirtinimo taškai Susidūrimo prevencijos sistema', '2025-05-29 21:39:46'),
(82, 14, 1, 5, 38, 2010, 103, 276000, 'universalas', 'dyzelis', 2000.00, 'uploads/img_6838d4c5d60778.34881920.jpg', '+3706255511', 'Ukmergė', 2.0, 'mechaninė', NULL, 'Salonas\r\nSportinės sėdynės Tamsinti stiklai Daugiafunkcinis vairas Šildomos sėdynės Odinis salonas Elektra valdomos sėdynės Elektra valdomos sėdynės su atmintimi\r\nElektronika\r\nEl. reguliuojami veidrodėliai Automatiškai įsijungiantys žibintai Elektra reguliuojama vairo padėtis Kritulių jutiklis Šildomi veidrodėliai Atstumo jutiklių sistema Autopilotas LCD ekranas Navigacija/GPS Liečiamas ekranas Virtualūs veidrodėliai\r\nApsauga\r\nImobilaizeris Signalizacija\r\nAudio/video įranga\r\nCD grotuvas MP3 grotuvas CD keitiklis AUX jungtis Žemų dažnių garsiakalbis HiFi audio sistema DVD grotuvas\r\nEksterjeras\r\nLengvojo lydinio ratlankiai LED dienos žibintai LED žibintai Žibintai „Xenon“ Rūko žibintai Kablys Stogo bagažinės laikikliai Žieminių padangų komplektas\r\nKiti ypatumai\r\nDomina keitimas Parduodama lizingu Serviso knygelė Katalizatorius Atsarginis ratas\r\nSaugumas\r\nESP ISOFIX tvirtinimo taškai', '2025-05-29 21:42:29'),
(83, 14, 1, 8, 60, 2010, 105, 264566, 'universalas', 'benzinas', 3800.00, 'uploads/img_6838d54b8ca123.04204921.jpg', '+3705552312', NULL, 2.0, 'automatinė', NULL, NULL, '2025-05-29 21:44:43'),
(84, 14, 1, 8, 61, 2007, 103, 278000, 'vienatūris', 'benzinas', 2450.00, 'uploads/img_6838d5d10b6327.53947108.jpg', '+3709851611', 'Mažeikiai', 1.8, 'automatinė', NULL, NULL, '2025-05-29 21:46:57'),
(85, 14, 1, 6, 51, 2016, 244, 96900, 'coupe', 'benzinas', 19999.00, 'uploads/img_6838d67e1bc967.09658818.jpg', '+3705445533', 'Vilnius', 3.7, 'automatinė', NULL, NULL, '2025-05-29 21:49:50'),
(87, 14, 1, 6, 51, 2016, 253, 25300, 'coupe', 'benzinas', 28990.00, 'uploads/img_6838d79c816ee1.68235179.jpg', '+37044160719', NULL, 3.7, 'mechaninė', NULL, 'Salonas\r\nSportinės sėdynės Tamsinti stiklai Daugiafunkcinis vairas\r\nElektronika\r\nEl. reguliuojami veidrodėliai Beraktė sistema\r\nApsauga\r\nImobilaizeris\r\nAudio/video įranga\r\nCD grotuvas CD keitiklis AUX jungtis HiFi audio sistema USB jungtis Laisvų rankų įranga\r\nEksterjeras\r\nLengvojo lydinio ratlankiai LED dienos žibintai Žibintai „Xenon“\r\nKiti ypatumai\r\nParduodama lizingu Katalizatorius\r\nSaugumas\r\nESP Įkalnės stabdys', '2025-05-29 21:54:36');

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
(31, 36, 'uploads/img_682e4d6cf2e179.79032758.jpg', 0, '2025-05-21 22:02:21'),
(56, 37, 'uploads/img_6836eaf7c19538.56244612.jpg', 1, '2025-05-28 10:52:39'),
(57, 38, 'uploads/img_6836ecf21782a4.22631517.jpg', 1, '2025-05-28 11:01:06'),
(58, 38, 'uploads/img_6836ecf2179ff5.19363242.jpg', 0, '2025-05-28 11:01:06'),
(59, 38, 'uploads/img_6836ecf217baf3.55497102.jpg', 0, '2025-05-28 11:01:06'),
(60, 38, 'uploads/img_6836ecf217d927.72249551.jpg', 0, '2025-05-28 11:01:06'),
(61, 38, 'uploads/img_6836ecf217f629.10500859.jpg', 0, '2025-05-28 11:01:06'),
(62, 38, 'uploads/img_6836ecf21810a6.38225066.jpg', 0, '2025-05-28 11:01:06'),
(63, 38, 'uploads/img_6836ecf2182a03.95068462.jpg', 0, '2025-05-28 11:01:06'),
(64, 38, 'uploads/img_6836ecf2184521.02935360.jpg', 0, '2025-05-28 11:01:06'),
(65, 39, 'uploads/img_6836eddd1a0f77.22848594.jpg', 1, '2025-05-28 11:05:01'),
(66, 39, 'uploads/img_6836eddd1a2814.97693356.jpg', 0, '2025-05-28 11:05:01'),
(67, 39, 'uploads/img_6836eddd1a46c4.26862533.jpg', 0, '2025-05-28 11:05:01'),
(68, 39, 'uploads/img_6836eddd1a5fe5.05846803.jpg', 0, '2025-05-28 11:05:01'),
(69, 39, 'uploads/img_6836eddd1a78e1.06012206.jpg', 0, '2025-05-28 11:05:01'),
(70, 39, 'uploads/img_6836eddd1a9607.03540945.jpg', 0, '2025-05-28 11:05:01'),
(71, 39, 'uploads/img_6836eddd1aaeb0.26701136.jpg', 0, '2025-05-28 11:05:01'),
(79, 41, 'uploads/img_6836ef0f0fcaf3.98121100.jpg', 1, '2025-05-28 11:10:07'),
(80, 41, 'uploads/img_6836ef0f0fef87.62262292.jpg', 0, '2025-05-28 11:10:07'),
(81, 41, 'uploads/img_6836ef0f101b21.28534392.jpg', 0, '2025-05-28 11:10:07'),
(82, 41, 'uploads/img_6836ef0f1036b0.35421829.jpg', 0, '2025-05-28 11:10:07'),
(83, 41, 'uploads/img_6836ef0f1050f9.12374301.jpg', 0, '2025-05-28 11:10:07'),
(84, 41, 'uploads/img_6836ef0f106b49.91823041.jpg', 0, '2025-05-28 11:10:07'),
(85, 41, 'uploads/img_6836ef0f1086a0.43415332.jpg', 0, '2025-05-28 11:10:07'),
(86, 41, 'uploads/img_6836ef0f109e74.00119161.jpg', 0, '2025-05-28 11:10:07'),
(87, 41, 'uploads/img_6836ef0f10b710.66965235.jpg', 0, '2025-05-28 11:10:07'),
(88, 41, 'uploads/img_6836ef0f10ce56.68721770.jpg', 0, '2025-05-28 11:10:07'),
(89, 42, 'uploads/img_6836ef9c5f3e88.77539160.jpg', 1, '2025-05-28 11:12:28'),
(90, 42, 'uploads/img_6836ef9c5f6133.04222856.jpg', 0, '2025-05-28 11:12:28'),
(91, 42, 'uploads/img_6836ef9c5f80b2.82055211.jpg', 0, '2025-05-28 11:12:28'),
(92, 42, 'uploads/img_6836ef9c5f9ee3.60678126.jpg', 0, '2025-05-28 11:12:28'),
(93, 42, 'uploads/img_6836ef9c5fba61.28157349.jpg', 0, '2025-05-28 11:12:28'),
(94, 42, 'uploads/img_6836ef9c5fd8e0.00135019.jpg', 0, '2025-05-28 11:12:28'),
(95, 42, 'uploads/img_6836ef9c6000b2.08031200.jpg', 0, '2025-05-28 11:12:28'),
(96, 43, 'uploads/img_6836f09c43a181.61298282.jpg', 1, '2025-05-28 11:16:44'),
(97, 43, 'uploads/img_6836f09c43c175.26348473.jpg', 0, '2025-05-28 11:16:44'),
(98, 43, 'uploads/img_6836f09c43dd39.26705460.jpg', 0, '2025-05-28 11:16:44'),
(99, 43, 'uploads/img_6836f09c43f477.96392146.jpg', 0, '2025-05-28 11:16:44'),
(100, 43, 'uploads/img_6836f09c440b12.08839787.jpg', 0, '2025-05-28 11:16:44'),
(101, 43, 'uploads/img_6836f09c442779.39953910.jpg', 0, '2025-05-28 11:16:44'),
(102, 43, 'uploads/img_6836f09c4442a3.10436261.jpg', 0, '2025-05-28 11:16:44'),
(103, 43, 'uploads/img_6836f09c445aa8.55259831.jpg', 0, '2025-05-28 11:16:44'),
(104, 43, 'uploads/img_6836f09c447146.17060980.jpg', 0, '2025-05-28 11:16:44'),
(105, 43, 'uploads/img_6836f09c448a24.67236898.jpg', 0, '2025-05-28 11:16:44'),
(106, 43, 'uploads/img_6836f09c44a467.89202637.jpg', 0, '2025-05-28 11:16:44'),
(107, 43, 'uploads/img_6836f09c44c0e6.48563273.jpg', 0, '2025-05-28 11:16:44'),
(108, 44, 'uploads/img_6836f12d92ae88.80665164.jpg', 1, '2025-05-28 11:19:09'),
(109, 44, 'uploads/img_6836f12d92ddb7.23234371.jpg', 0, '2025-05-28 11:19:09'),
(110, 44, 'uploads/img_6836f12d92f8f9.72675451.jpg', 0, '2025-05-28 11:19:09'),
(111, 44, 'uploads/img_6836f12d9312c4.84958491.jpg', 0, '2025-05-28 11:19:09'),
(112, 44, 'uploads/img_6836f12d932ee6.17047654.jpg', 0, '2025-05-28 11:19:09'),
(113, 44, 'uploads/img_6836f12d934728.86120024.jpg', 0, '2025-05-28 11:19:09'),
(114, 44, 'uploads/img_6836f12d936229.78265844.jpg', 0, '2025-05-28 11:19:09'),
(115, 44, 'uploads/img_6836f12d937c91.60780757.jpg', 0, '2025-05-28 11:19:09'),
(116, 44, 'uploads/img_6836f12d939673.85109979.jpg', 0, '2025-05-28 11:19:09'),
(117, 44, 'uploads/img_6836f12d93ae54.27383175.jpg', 0, '2025-05-28 11:19:09'),
(118, 44, 'uploads/img_6836f12d93c407.57032430.jpg', 0, '2025-05-28 11:19:09'),
(119, 44, 'uploads/img_6836f12d93d9f0.14923269.jpg', 0, '2025-05-28 11:19:09'),
(120, 44, 'uploads/img_6836f12d93ef69.96746437.jpg', 0, '2025-05-28 11:19:09'),
(121, 44, 'uploads/img_6836f12d940528.61328532.jpg', 0, '2025-05-28 11:19:09'),
(122, 44, 'uploads/img_6836f12d941a70.32729316.jpg', 0, '2025-05-28 11:19:09'),
(123, 44, 'uploads/img_6836f12d943544.59075455.jpg', 0, '2025-05-28 11:19:09'),
(124, 45, 'uploads/img_6836f2a0c8b805.96740723.jpg', 1, '2025-05-28 11:25:20'),
(125, 45, 'uploads/img_6836f2a0c8d0c5.77017480.jpg', 0, '2025-05-28 11:25:20'),
(126, 45, 'uploads/img_6836f2a0c8e931.66104736.jpg', 0, '2025-05-28 11:25:20'),
(127, 45, 'uploads/img_6836f2a0c8ffa0.79174219.jpg', 0, '2025-05-28 11:25:20'),
(128, 45, 'uploads/img_6836f2a0c91598.62542820.jpg', 0, '2025-05-28 11:25:20'),
(129, 45, 'uploads/img_6836f2a0c92a20.49436540.jpg', 0, '2025-05-28 11:25:20'),
(130, 45, 'uploads/img_6836f2a0c93fc5.69982746.jpg', 0, '2025-05-28 11:25:20'),
(131, 45, 'uploads/img_6836f2a0c95838.22483767.jpg', 0, '2025-05-28 11:25:20'),
(132, 45, 'uploads/img_6836f2a0c96b57.56276222.jpg', 0, '2025-05-28 11:25:20'),
(133, 45, 'uploads/img_6836f2a0c97ed1.70250622.jpg', 0, '2025-05-28 11:25:20'),
(134, 45, 'uploads/img_6836f2a0c99219.69205018.jpg', 0, '2025-05-28 11:25:20'),
(135, 45, 'uploads/img_6836f2a0c9a5e4.92963266.jpg', 0, '2025-05-28 11:25:20'),
(136, 45, 'uploads/img_6836f2a0c9b9a0.20780838.jpg', 0, '2025-05-28 11:25:20'),
(137, 45, 'uploads/img_6836f2a0c9d7e0.96220540.jpg', 0, '2025-05-28 11:25:20'),
(138, 45, 'uploads/img_6836f2a0c9eb40.75385775.jpg', 0, '2025-05-28 11:25:20'),
(139, 45, 'uploads/img_6836f2a0c9fd23.93108430.jpg', 0, '2025-05-28 11:25:20'),
(140, 45, 'uploads/img_6836f2a0ca1064.44509403.jpg', 0, '2025-05-28 11:25:20'),
(141, 45, 'uploads/img_6836f2a0ca2f41.62812429.jpg', 0, '2025-05-28 11:25:20'),
(142, 45, 'uploads/img_6836f2a0ca4572.78720742.jpg', 0, '2025-05-28 11:25:20'),
(143, 46, 'uploads/img_6836f367c10c08.36650023.jpg', 1, '2025-05-28 11:28:39'),
(144, 46, 'uploads/img_6836f367c12488.48647688.jpg', 0, '2025-05-28 11:28:39'),
(145, 46, 'uploads/img_6836f367c13d25.08333613.jpg', 0, '2025-05-28 11:28:39'),
(146, 46, 'uploads/img_6836f367c155a0.55549904.jpg', 0, '2025-05-28 11:28:39'),
(147, 46, 'uploads/img_6836f367c16c26.09791460.jpg', 0, '2025-05-28 11:28:39'),
(148, 46, 'uploads/img_6836f367c18246.57429353.jpg', 0, '2025-05-28 11:28:39'),
(149, 46, 'uploads/img_6836f367c19676.48445947.jpg', 0, '2025-05-28 11:28:39'),
(150, 46, 'uploads/img_6836f367c1a858.17742714.jpg', 0, '2025-05-28 11:28:39'),
(151, 46, 'uploads/img_6836f367c1c534.87631808.jpg', 0, '2025-05-28 11:28:39'),
(152, 46, 'uploads/img_6836f367c1d749.60449839.jpg', 0, '2025-05-28 11:28:39'),
(153, 46, 'uploads/img_6836f367c1e914.78666595.jpg', 0, '2025-05-28 11:28:39'),
(154, 46, 'uploads/img_6836f367c1fb58.87318580.jpg', 0, '2025-05-28 11:28:39'),
(155, 46, 'uploads/img_6836f367c20b94.78917112.jpg', 0, '2025-05-28 11:28:39'),
(156, 46, 'uploads/img_6836f367c21b80.45045624.jpg', 0, '2025-05-28 11:28:39'),
(157, 46, 'uploads/img_6836f367c22b43.90215435.jpg', 0, '2025-05-28 11:28:39'),
(158, 47, 'uploads/img_6836f3d0dafeb6.86079236.jpg', 1, '2025-05-28 11:30:24'),
(159, 47, 'uploads/img_6836f3d0db16d0.13921823.jpg', 0, '2025-05-28 11:30:24'),
(160, 47, 'uploads/img_6836f3d0db2aa0.53327817.jpg', 0, '2025-05-28 11:30:24'),
(161, 47, 'uploads/img_6836f3d0db3f95.30054700.jpg', 0, '2025-05-28 11:30:24'),
(162, 47, 'uploads/img_6836f3d0db5860.96756114.jpg', 0, '2025-05-28 11:30:24'),
(163, 48, 'uploads/img_6836f459837945.68137346.jpg', 1, '2025-05-28 11:32:41'),
(164, 48, 'uploads/img_6836f459839242.37616389.jpg', 0, '2025-05-28 11:32:41'),
(165, 48, 'uploads/img_6836f45983a7a7.19400120.jpg', 0, '2025-05-28 11:32:41'),
(166, 48, 'uploads/img_6836f45983bb90.70847094.jpg', 0, '2025-05-28 11:32:41'),
(167, 48, 'uploads/img_6836f45983d1b0.80389576.jpg', 0, '2025-05-28 11:32:41'),
(168, 48, 'uploads/img_6836f45983e671.52197325.jpg', 0, '2025-05-28 11:32:41'),
(169, 48, 'uploads/img_6836f45983fe86.69974375.jpg', 0, '2025-05-28 11:32:41'),
(170, 48, 'uploads/img_6836f4598415a2.52109053.jpg', 0, '2025-05-28 11:32:41'),
(171, 48, 'uploads/img_6836f459842c59.96341229.jpg', 0, '2025-05-28 11:32:41'),
(172, 48, 'uploads/img_6836f4598441e4.14724822.jpg', 0, '2025-05-28 11:32:41'),
(173, 48, 'uploads/img_6836f459845621.09726991.jpg', 0, '2025-05-28 11:32:41'),
(174, 48, 'uploads/img_6836f459846a01.56550161.jpg', 0, '2025-05-28 11:32:41'),
(175, 48, 'uploads/img_6836f459847d02.37429550.jpg', 0, '2025-05-28 11:32:41'),
(176, 48, 'uploads/img_6836f459849179.98897976.jpg', 0, '2025-05-28 11:32:41'),
(177, 48, 'uploads/img_6836f45984a704.40330646.jpg', 0, '2025-05-28 11:32:41'),
(178, 48, 'uploads/img_6836f45984be74.37079059.jpg', 0, '2025-05-28 11:32:41'),
(179, 48, 'uploads/img_6836f45984db65.74406295.jpg', 0, '2025-05-28 11:32:41'),
(180, 48, 'uploads/img_6836f45984efa9.26148907.jpg', 0, '2025-05-28 11:32:41'),
(181, 48, 'uploads/img_6836f4598503e8.27217510.jpg', 0, '2025-05-28 11:32:41'),
(182, 48, 'uploads/img_6836f459851792.61144277.jpg', 0, '2025-05-28 11:32:41'),
(183, 49, 'uploads/img_6838c6ebe1f5f5.07625070.jpg', 1, '2025-05-29 20:43:23'),
(184, 49, 'uploads/img_6838c6ebe22538.74424247.jpg', 0, '2025-05-29 20:43:23'),
(185, 49, 'uploads/img_6838c6ebe23fa1.29132103.jpg', 0, '2025-05-29 20:43:23'),
(186, 49, 'uploads/img_6838c6ebe25a78.00031539.jpg', 0, '2025-05-29 20:43:23'),
(187, 49, 'uploads/img_6838c6ebe27268.92259614.jpg', 0, '2025-05-29 20:43:23'),
(188, 49, 'uploads/img_6838c6ebe28b18.12910904.jpg', 0, '2025-05-29 20:43:23'),
(189, 49, 'uploads/img_6838c6ebe2a164.77212479.jpg', 0, '2025-05-29 20:43:23'),
(190, 49, 'uploads/img_6838c6ebe2bfc0.83350736.jpg', 0, '2025-05-29 20:43:23'),
(191, 49, 'uploads/img_6838c6ebe2df80.39640597.jpg', 0, '2025-05-29 20:43:23'),
(192, 49, 'uploads/img_6838c6ebe2f816.77286844.jpg', 0, '2025-05-29 20:43:23'),
(193, 49, 'uploads/img_6838c6ebe31119.47026981.jpg', 0, '2025-05-29 20:43:23'),
(194, 49, 'uploads/img_6838c6ebe33092.03138447.jpg', 0, '2025-05-29 20:43:23'),
(195, 49, 'uploads/img_6838c6ebe34dc6.40506905.jpg', 0, '2025-05-29 20:43:23'),
(196, 49, 'uploads/img_6838c6ebe36c08.40407256.jpg', 0, '2025-05-29 20:43:23'),
(197, 49, 'uploads/img_6838c6ebe38a55.19090005.jpg', 0, '2025-05-29 20:43:23'),
(198, 49, 'uploads/img_6838c6ebe3a046.42556875.jpg', 0, '2025-05-29 20:43:23'),
(199, 49, 'uploads/img_6838c6ebe3b5b1.71252160.jpg', 0, '2025-05-29 20:43:23'),
(200, 50, 'uploads/img_6838c72c5e5538.30300733.jpg', 1, '2025-05-29 20:44:28'),
(201, 50, 'uploads/img_6838c72c5e7291.33808071.jpg', 0, '2025-05-29 20:44:28'),
(202, 50, 'uploads/img_6838c72c5e9353.44272667.jpg', 0, '2025-05-29 20:44:28'),
(203, 50, 'uploads/img_6838c72c5eb180.65765941.jpg', 0, '2025-05-29 20:44:28'),
(204, 50, 'uploads/img_6838c72c5ecf41.42233335.jpg', 0, '2025-05-29 20:44:28'),
(205, 50, 'uploads/img_6838c72c5eeff0.80807369.jpg', 0, '2025-05-29 20:44:28'),
(206, 50, 'uploads/img_6838c72c5f1324.60292875.jpg', 0, '2025-05-29 20:44:28'),
(207, 50, 'uploads/img_6838c72c5f3503.06848336.jpg', 0, '2025-05-29 20:44:28'),
(208, 50, 'uploads/img_6838c72c5f5e24.28314835.jpg', 0, '2025-05-29 20:44:28'),
(209, 50, 'uploads/img_6838c72c5f7689.12362258.jpg', 0, '2025-05-29 20:44:28'),
(210, 50, 'uploads/img_6838c72c5f8dd2.35183199.jpg', 0, '2025-05-29 20:44:28'),
(211, 51, 'uploads/img_6838c7e0843d38.59291246.jpg', 1, '2025-05-29 20:47:28'),
(212, 51, 'uploads/img_6838c7e0845803.38731010.jpg', 0, '2025-05-29 20:47:28'),
(213, 51, 'uploads/img_6838c7e0847099.66448737.jpg', 0, '2025-05-29 20:47:28'),
(214, 51, 'uploads/img_6838c7e0848741.70086465.jpg', 0, '2025-05-29 20:47:28'),
(215, 51, 'uploads/img_6838c7e0849f76.68666138.jpg', 0, '2025-05-29 20:47:28'),
(216, 51, 'uploads/img_6838c7e084b951.14196480.jpg', 0, '2025-05-29 20:47:28'),
(217, 51, 'uploads/img_6838c7e084d0e3.37884160.jpg', 0, '2025-05-29 20:47:28'),
(218, 51, 'uploads/img_6838c7e084e744.74318220.jpg', 0, '2025-05-29 20:47:28'),
(219, 51, 'uploads/img_6838c7e084fd87.41951992.jpg', 0, '2025-05-29 20:47:28'),
(220, 51, 'uploads/img_6838c7e0851662.61431328.jpg', 0, '2025-05-29 20:47:28'),
(221, 51, 'uploads/img_6838c7e0852c80.46693317.jpg', 0, '2025-05-29 20:47:28'),
(222, 51, 'uploads/img_6838c7e0854dd6.92640215.jpg', 0, '2025-05-29 20:47:28'),
(223, 51, 'uploads/img_6838c7e0856665.43263592.jpg', 0, '2025-05-29 20:47:28'),
(224, 51, 'uploads/img_6838c7e0857c67.83972099.jpg', 0, '2025-05-29 20:47:28'),
(225, 51, 'uploads/img_6838c7e08591e4.50226055.jpg', 0, '2025-05-29 20:47:28'),
(226, 52, 'uploads/img_6838c81c4e9b68.91604358.jpg', 1, '2025-05-29 20:48:28'),
(227, 52, 'uploads/img_6838c81c4eba46.51268385.jpg', 0, '2025-05-29 20:48:28'),
(228, 52, 'uploads/img_6838c81c4ed292.09104313.jpg', 0, '2025-05-29 20:48:28'),
(229, 52, 'uploads/img_6838c81c4eeb60.53583384.jpg', 0, '2025-05-29 20:48:28'),
(230, 52, 'uploads/img_6838c81c4f0520.42680433.jpg', 0, '2025-05-29 20:48:28'),
(231, 52, 'uploads/img_6838c81c4f1fe4.17235899.jpg', 0, '2025-05-29 20:48:28'),
(232, 52, 'uploads/img_6838c81c4f3b51.68992146.jpg', 0, '2025-05-29 20:48:28'),
(233, 52, 'uploads/img_6838c81c4f5405.99448266.jpg', 0, '2025-05-29 20:48:28'),
(234, 52, 'uploads/img_6838c81c4f6d91.15028728.jpg', 0, '2025-05-29 20:48:28'),
(235, 52, 'uploads/img_6838c81c4f8431.96290849.jpg', 0, '2025-05-29 20:48:28'),
(236, 52, 'uploads/img_6838c81c4fa562.28517313.jpg', 0, '2025-05-29 20:48:28'),
(237, 53, 'uploads/img_6838c88fd954f2.27119458.jpg', 1, '2025-05-29 20:50:23'),
(238, 53, 'uploads/img_6838c88fd971d3.95731031.jpg', 0, '2025-05-29 20:50:23'),
(239, 53, 'uploads/img_6838c88fd98c44.75209386.jpg', 0, '2025-05-29 20:50:23'),
(240, 54, 'uploads/img_6838c8ee253063.10604246.jpg', 1, '2025-05-29 20:51:58'),
(241, 54, 'uploads/img_6838c8ee254de3.97838488.jpg', 0, '2025-05-29 20:51:58'),
(242, 54, 'uploads/img_6838c8ee256293.67589526.jpg', 0, '2025-05-29 20:51:58'),
(243, 54, 'uploads/img_6838c8ee257498.69584328.jpg', 0, '2025-05-29 20:51:58'),
(244, 54, 'uploads/img_6838c8ee2587d9.65149647.jpg', 0, '2025-05-29 20:51:58'),
(245, 54, 'uploads/img_6838c8ee259aa4.83701642.jpg', 0, '2025-05-29 20:51:58'),
(246, 54, 'uploads/img_6838c8ee25ad88.54612964.jpg', 0, '2025-05-29 20:51:58'),
(247, 54, 'uploads/img_6838c8ee25c013.82481293.jpg', 0, '2025-05-29 20:51:58'),
(248, 54, 'uploads/img_6838c8ee25d276.96163674.jpg', 0, '2025-05-29 20:51:58'),
(249, 54, 'uploads/img_6838c8ee25e529.92436499.jpg', 0, '2025-05-29 20:51:58'),
(250, 54, 'uploads/img_6838c8ee25ffc8.07333773.jpg', 0, '2025-05-29 20:51:58'),
(251, 54, 'uploads/img_6838c8ee261076.10313092.jpg', 0, '2025-05-29 20:51:58'),
(252, 54, 'uploads/img_6838c8ee262171.34145770.jpg', 0, '2025-05-29 20:51:58'),
(253, 55, 'uploads/img_6838c91397e149.37097227.jpg', 1, '2025-05-29 20:52:35'),
(254, 55, 'uploads/img_6838c91397fb71.43256186.jpg', 0, '2025-05-29 20:52:35'),
(255, 55, 'uploads/img_6838c913981277.54715541.jpg', 0, '2025-05-29 20:52:35'),
(256, 56, 'uploads/img_6838c9b7446b42.72846661.jpg', 1, '2025-05-29 20:55:19'),
(257, 56, 'uploads/img_6838c9b74480d6.02928768.jpg', 0, '2025-05-29 20:55:19'),
(258, 56, 'uploads/img_6838c9b74491e7.66831563.jpg', 0, '2025-05-29 20:55:19'),
(259, 56, 'uploads/img_6838c9b744a1f0.17903371.jpg', 0, '2025-05-29 20:55:19'),
(260, 56, 'uploads/img_6838c9b744b231.80985797.jpg', 0, '2025-05-29 20:55:19'),
(261, 56, 'uploads/img_6838c9b744c1d6.70437560.jpg', 0, '2025-05-29 20:55:19'),
(262, 56, 'uploads/img_6838c9b744d145.04381626.jpg', 0, '2025-05-29 20:55:19'),
(263, 56, 'uploads/img_6838c9b744e202.61350967.jpg', 0, '2025-05-29 20:55:19'),
(264, 56, 'uploads/img_6838c9b744f985.89832967.jpg', 0, '2025-05-29 20:55:19'),
(265, 57, 'uploads/img_6838c9cf07cc15.63023218.jpg', 1, '2025-05-29 20:55:43'),
(266, 57, 'uploads/img_6838c9cf07e7f8.58489733.jpg', 0, '2025-05-29 20:55:43'),
(267, 57, 'uploads/img_6838c9cf0800d6.84260214.jpg', 0, '2025-05-29 20:55:43'),
(268, 57, 'uploads/img_6838c9cf081844.64955952.jpg', 0, '2025-05-29 20:55:43'),
(269, 57, 'uploads/img_6838c9cf082fc4.36348179.jpg', 0, '2025-05-29 20:55:43'),
(270, 57, 'uploads/img_6838c9cf0847f9.24260622.jpg', 0, '2025-05-29 20:55:43'),
(271, 57, 'uploads/img_6838c9cf085c82.80214240.jpg', 0, '2025-05-29 20:55:43'),
(272, 57, 'uploads/img_6838c9cf086d25.39190539.jpg', 0, '2025-05-29 20:55:43'),
(273, 57, 'uploads/img_6838c9cf088154.09834850.jpg', 0, '2025-05-29 20:55:43'),
(274, 58, 'uploads/img_6838ca41d90ae8.10978707.jpg', 1, '2025-05-29 20:57:37'),
(275, 58, 'uploads/img_6838ca41d923f8.26904860.jpg', 0, '2025-05-29 20:57:37'),
(276, 58, 'uploads/img_6838ca41d93b14.08901419.jpg', 0, '2025-05-29 20:57:37'),
(277, 58, 'uploads/img_6838ca41d952a5.22000536.jpg', 0, '2025-05-29 20:57:37'),
(278, 58, 'uploads/img_6838ca41d97442.68115721.jpg', 0, '2025-05-29 20:57:37'),
(279, 58, 'uploads/img_6838ca41d98885.55105229.jpg', 0, '2025-05-29 20:57:37'),
(280, 58, 'uploads/img_6838ca41d99c65.02362357.jpg', 0, '2025-05-29 20:57:37'),
(281, 58, 'uploads/img_6838ca41d9afd4.74764257.jpg', 0, '2025-05-29 20:57:37'),
(282, 59, 'uploads/img_6838ca48789f74.93125243.jpg', 1, '2025-05-29 20:57:44'),
(283, 59, 'uploads/img_6838ca4878bd33.08547726.jpg', 0, '2025-05-29 20:57:44'),
(284, 59, 'uploads/img_6838ca4878d4a9.82097550.jpg', 0, '2025-05-29 20:57:44'),
(285, 59, 'uploads/img_6838ca4878e905.05455959.jpg', 0, '2025-05-29 20:57:44'),
(286, 59, 'uploads/img_6838ca4878fba8.76439552.jpg', 0, '2025-05-29 20:57:44'),
(287, 59, 'uploads/img_6838ca48790dc8.81422768.jpg', 0, '2025-05-29 20:57:44'),
(288, 59, 'uploads/img_6838ca48791f59.15849220.jpg', 0, '2025-05-29 20:57:44'),
(289, 59, 'uploads/img_6838ca487931e6.05214253.jpg', 0, '2025-05-29 20:57:44'),
(290, 60, 'uploads/img_6838caf2d68cd3.11436424.jpg', 1, '2025-05-29 21:00:34'),
(291, 60, 'uploads/img_6838caf2d6a2e7.09823129.jpg', 0, '2025-05-29 21:00:34'),
(292, 60, 'uploads/img_6838caf2d6b4a2.80176394.jpg', 0, '2025-05-29 21:00:34'),
(293, 60, 'uploads/img_6838caf2d6c466.31274860.jpg', 0, '2025-05-29 21:00:34'),
(294, 60, 'uploads/img_6838caf2d6d3e9.22635458.jpg', 0, '2025-05-29 21:00:34'),
(295, 60, 'uploads/img_6838caf2d6e317.00007219.jpg', 0, '2025-05-29 21:00:34'),
(296, 60, 'uploads/img_6838caf2d6f272.14749345.jpg', 0, '2025-05-29 21:00:34'),
(297, 60, 'uploads/img_6838caf2d701d8.03851982.jpg', 0, '2025-05-29 21:00:34'),
(298, 60, 'uploads/img_6838caf2d71163.44512803.jpg', 0, '2025-05-29 21:00:34'),
(299, 60, 'uploads/img_6838caf2d72247.87701485.jpg', 0, '2025-05-29 21:00:34'),
(300, 61, 'uploads/img_6838cb8744d597.14989466.jpg', 1, '2025-05-29 21:03:03'),
(301, 61, 'uploads/img_6838cb8744f308.58685584.jpg', 0, '2025-05-29 21:03:03'),
(302, 61, 'uploads/img_6838cb87450899.49346908.jpg', 0, '2025-05-29 21:03:03'),
(303, 61, 'uploads/img_6838cb87451d62.07694325.jpg', 0, '2025-05-29 21:03:03'),
(304, 61, 'uploads/img_6838cb874531f2.88854102.jpg', 0, '2025-05-29 21:03:03'),
(305, 61, 'uploads/img_6838cb87454664.36872948.jpg', 0, '2025-05-29 21:03:03'),
(306, 61, 'uploads/img_6838cb87456452.48509547.jpg', 0, '2025-05-29 21:03:03'),
(307, 61, 'uploads/img_6838cb87457601.24617332.jpg', 0, '2025-05-29 21:03:03'),
(308, 61, 'uploads/img_6838cb87458903.67948026.jpg', 0, '2025-05-29 21:03:03'),
(309, 61, 'uploads/img_6838cb87459d48.30370513.jpg', 0, '2025-05-29 21:03:03'),
(310, 61, 'uploads/img_6838cb8745ade2.97992981.jpg', 0, '2025-05-29 21:03:03'),
(311, 61, 'uploads/img_6838cb8745bd84.74747746.jpg', 0, '2025-05-29 21:03:03'),
(312, 61, 'uploads/img_6838cb8745cce7.15010180.jpg', 0, '2025-05-29 21:03:03'),
(313, 62, 'uploads/img_6838cba0b96476.44904663.jpg', 1, '2025-05-29 21:03:28'),
(314, 62, 'uploads/img_6838cba0b98573.87246619.jpg', 0, '2025-05-29 21:03:28'),
(315, 62, 'uploads/img_6838cba0b99f16.55877503.jpg', 0, '2025-05-29 21:03:28'),
(316, 62, 'uploads/img_6838cba0b9b979.68849603.jpg', 0, '2025-05-29 21:03:28'),
(317, 62, 'uploads/img_6838cba0b9d010.05687097.jpg', 0, '2025-05-29 21:03:28'),
(318, 62, 'uploads/img_6838cba0b9e613.49420186.jpg', 0, '2025-05-29 21:03:28'),
(319, 62, 'uploads/img_6838cba0b9fb95.45446214.jpg', 0, '2025-05-29 21:03:28'),
(320, 62, 'uploads/img_6838cba0ba12c3.26920347.jpg', 0, '2025-05-29 21:03:28'),
(321, 63, 'uploads/img_6838cc341e7612.63594490.jpg', 1, '2025-05-29 21:05:56'),
(322, 63, 'uploads/img_6838cc341e8de1.08295640.jpg', 0, '2025-05-29 21:05:56'),
(323, 63, 'uploads/img_6838cc341ea1b1.11523526.jpg', 0, '2025-05-29 21:05:56'),
(324, 63, 'uploads/img_6838cc341eb3a6.30936527.jpg', 0, '2025-05-29 21:05:56'),
(325, 63, 'uploads/img_6838cc341ec717.12653530.jpg', 0, '2025-05-29 21:05:56'),
(326, 63, 'uploads/img_6838cc341edab0.73239416.jpg', 0, '2025-05-29 21:05:56'),
(327, 63, 'uploads/img_6838cc341eee27.39405041.jpg', 0, '2025-05-29 21:05:56'),
(328, 63, 'uploads/img_6838cc341eff58.29779118.jpg', 0, '2025-05-29 21:05:56'),
(329, 63, 'uploads/img_6838cc341f1016.36562530.jpg', 0, '2025-05-29 21:05:56'),
(330, 63, 'uploads/img_6838cc341f2157.92488221.jpg', 0, '2025-05-29 21:05:56'),
(331, 63, 'uploads/img_6838cc341f3146.45693647.jpg', 0, '2025-05-29 21:05:56'),
(332, 64, 'uploads/img_6838cc3a5373f0.89272607.jpg', 1, '2025-05-29 21:06:02'),
(333, 64, 'uploads/img_6838cc3a538ed8.15564207.jpg', 0, '2025-05-29 21:06:02'),
(334, 64, 'uploads/img_6838cc3a53bb46.95952127.jpg', 0, '2025-05-29 21:06:02'),
(335, 64, 'uploads/img_6838cc3a53db28.90269509.jpg', 0, '2025-05-29 21:06:02'),
(336, 64, 'uploads/img_6838cc3a53f451.60776092.jpg', 0, '2025-05-29 21:06:02'),
(337, 64, 'uploads/img_6838cc3a540d84.69534223.jpg', 0, '2025-05-29 21:06:02'),
(338, 64, 'uploads/img_6838cc3a5424d9.47737017.jpg', 0, '2025-05-29 21:06:02'),
(339, 64, 'uploads/img_6838cc3a543c47.96279306.jpg', 0, '2025-05-29 21:06:02'),
(340, 64, 'uploads/img_6838cc3a545418.38575072.jpg', 0, '2025-05-29 21:06:02'),
(341, 64, 'uploads/img_6838cc3a546b44.13227671.jpg', 0, '2025-05-29 21:06:02'),
(342, 64, 'uploads/img_6838cc3a5484a9.69340217.jpg', 0, '2025-05-29 21:06:02'),
(343, 64, 'uploads/img_6838cc3a549be8.02534858.jpg', 0, '2025-05-29 21:06:02'),
(344, 64, 'uploads/img_6838cc3a54b373.65164840.jpg', 0, '2025-05-29 21:06:02'),
(345, 64, 'uploads/img_6838cc3a54cc05.57504538.jpg', 0, '2025-05-29 21:06:02'),
(346, 64, 'uploads/img_6838cc3a54e339.17991408.jpg', 0, '2025-05-29 21:06:02'),
(347, 64, 'uploads/img_6838cc3a54f8d3.29997406.jpg', 0, '2025-05-29 21:06:02'),
(348, 64, 'uploads/img_6838cc3a550f52.03725525.jpg', 0, '2025-05-29 21:06:02'),
(349, 65, 'uploads/img_6838ccf4c794f8.72674259.jpg', 1, '2025-05-29 21:09:08'),
(350, 65, 'uploads/img_6838ccf4c7af82.08553168.jpg', 0, '2025-05-29 21:09:08'),
(351, 65, 'uploads/img_6838ccf4c7c8d2.55767885.jpg', 0, '2025-05-29 21:09:08'),
(352, 65, 'uploads/img_6838ccf4c7e0e7.01495062.jpg', 0, '2025-05-29 21:09:08'),
(353, 65, 'uploads/img_6838ccf4c7f951.27569958.jpg', 0, '2025-05-29 21:09:08'),
(354, 65, 'uploads/img_6838ccf4c81098.07943861.jpg', 0, '2025-05-29 21:09:08'),
(355, 65, 'uploads/img_6838ccf4c82936.31351764.jpg', 0, '2025-05-29 21:09:08'),
(356, 65, 'uploads/img_6838ccf4c840b3.95067775.jpg', 0, '2025-05-29 21:09:08'),
(357, 65, 'uploads/img_6838ccf4c85a39.64457620.jpg', 0, '2025-05-29 21:09:08'),
(358, 65, 'uploads/img_6838ccf4c87196.97336398.jpg', 0, '2025-05-29 21:09:08'),
(359, 65, 'uploads/img_6838ccf4c888f0.08385242.jpg', 0, '2025-05-29 21:09:08'),
(360, 66, 'uploads/img_6838ccfc4a88e6.31069857.jpg', 1, '2025-05-29 21:09:16'),
(361, 66, 'uploads/img_6838ccfc4aa139.68486381.jpg', 0, '2025-05-29 21:09:16'),
(362, 66, 'uploads/img_6838ccfc4ab6a4.90293201.jpg', 0, '2025-05-29 21:09:16'),
(363, 66, 'uploads/img_6838ccfc4ac8a8.33935974.jpg', 0, '2025-05-29 21:09:16'),
(364, 66, 'uploads/img_6838ccfc4ada94.20386811.jpg', 0, '2025-05-29 21:09:16'),
(365, 66, 'uploads/img_6838ccfc4aec40.44092506.jpg', 0, '2025-05-29 21:09:16'),
(366, 66, 'uploads/img_6838ccfc4afda4.06911753.jpg', 0, '2025-05-29 21:09:16'),
(367, 66, 'uploads/img_6838ccfc4b0f12.62220680.jpg', 0, '2025-05-29 21:09:16'),
(368, 66, 'uploads/img_6838ccfc4b24f1.31107120.jpg', 0, '2025-05-29 21:09:16'),
(369, 66, 'uploads/img_6838ccfc4b37b6.61147313.jpg', 0, '2025-05-29 21:09:16'),
(370, 66, 'uploads/img_6838ccfc4b49e5.31979230.jpg', 0, '2025-05-29 21:09:16'),
(371, 66, 'uploads/img_6838ccfc4b6069.04106858.jpg', 0, '2025-05-29 21:09:16'),
(372, 66, 'uploads/img_6838ccfc4b71d5.21784051.jpg', 0, '2025-05-29 21:09:16'),
(373, 66, 'uploads/img_6838ccfc4b8165.19338943.jpg', 0, '2025-05-29 21:09:16'),
(374, 66, 'uploads/img_6838ccfc4b9152.43323882.jpg', 0, '2025-05-29 21:09:16'),
(375, 67, 'uploads/img_6838cd741f5078.40807985.jpg', 1, '2025-05-29 21:11:16'),
(376, 67, 'uploads/img_6838cd741f6fd1.57919983.jpg', 0, '2025-05-29 21:11:16'),
(377, 67, 'uploads/img_6838cd741f83d7.83189674.jpg', 0, '2025-05-29 21:11:16'),
(378, 67, 'uploads/img_6838cd741f95d5.76928961.jpg', 0, '2025-05-29 21:11:16'),
(379, 67, 'uploads/img_6838cd741fa611.52491863.jpg', 0, '2025-05-29 21:11:16'),
(380, 67, 'uploads/img_6838cd741fb5a6.58842958.jpg', 0, '2025-05-29 21:11:16'),
(381, 67, 'uploads/img_6838cd741fc9c7.08258188.jpg', 0, '2025-05-29 21:11:16'),
(382, 67, 'uploads/img_6838cd741fd949.39026031.jpg', 0, '2025-05-29 21:11:16'),
(383, 67, 'uploads/img_6838cd741fe879.73959622.jpg', 0, '2025-05-29 21:11:16'),
(384, 67, 'uploads/img_6838cd741ff796.45882117.jpg', 0, '2025-05-29 21:11:16'),
(385, 68, 'uploads/img_6838cd8a516274.60067292.jpg', 1, '2025-05-29 21:11:38'),
(386, 68, 'uploads/img_6838cd8a5178a1.65700238.jpg', 0, '2025-05-29 21:11:38'),
(387, 68, 'uploads/img_6838cd8a518ca8.03071951.jpg', 0, '2025-05-29 21:11:38'),
(388, 68, 'uploads/img_6838cd8a519f06.99785636.jpg', 0, '2025-05-29 21:11:38'),
(389, 68, 'uploads/img_6838cd8a51c3d5.22153352.jpg', 0, '2025-05-29 21:11:38'),
(390, 68, 'uploads/img_6838cd8a51e6f2.40201461.jpg', 0, '2025-05-29 21:11:38'),
(391, 68, 'uploads/img_6838cd8a51fa24.36600156.jpg', 0, '2025-05-29 21:11:38'),
(392, 68, 'uploads/img_6838cd8a520b98.18898444.jpg', 0, '2025-05-29 21:11:38'),
(393, 68, 'uploads/img_6838cd8a521c31.18091968.jpg', 0, '2025-05-29 21:11:38'),
(394, 68, 'uploads/img_6838cd8a522c97.56048395.jpg', 0, '2025-05-29 21:11:38'),
(395, 68, 'uploads/img_6838cd8a523d76.38305570.jpg', 0, '2025-05-29 21:11:38'),
(396, 69, 'uploads/img_6838cdef2b5c27.38356040.jpg', 1, '2025-05-29 21:13:19'),
(397, 69, 'uploads/img_6838cdef2b7635.04413129.jpg', 0, '2025-05-29 21:13:19'),
(398, 69, 'uploads/img_6838cdef2b8894.91437477.jpg', 0, '2025-05-29 21:13:19'),
(399, 69, 'uploads/img_6838cdef2b9c99.79346241.jpg', 0, '2025-05-29 21:13:19'),
(400, 69, 'uploads/img_6838cdef2baf71.54721328.jpg', 0, '2025-05-29 21:13:19'),
(401, 69, 'uploads/img_6838cdef2bc0b8.10562855.jpg', 0, '2025-05-29 21:13:19'),
(402, 69, 'uploads/img_6838cdef2bd1c9.10704405.jpg', 0, '2025-05-29 21:13:19'),
(403, 69, 'uploads/img_6838cdef2be2f3.17326836.jpg', 0, '2025-05-29 21:13:19'),
(404, 69, 'uploads/img_6838cdef2bf4f5.07995381.jpg', 0, '2025-05-29 21:13:19'),
(405, 70, 'uploads/img_6838ce56a7fbb0.29846129.jpg', 1, '2025-05-29 21:15:02'),
(406, 70, 'uploads/img_6838ce56a81578.39794607.jpg', 0, '2025-05-29 21:15:02'),
(407, 70, 'uploads/img_6838ce56a82fe7.72616611.jpg', 0, '2025-05-29 21:15:02'),
(408, 70, 'uploads/img_6838ce56a84990.01421501.jpg', 0, '2025-05-29 21:15:02'),
(409, 70, 'uploads/img_6838ce56a864f7.66665743.jpg', 0, '2025-05-29 21:15:02'),
(410, 70, 'uploads/img_6838ce56a87f33.78209148.jpg', 0, '2025-05-29 21:15:02'),
(411, 71, 'uploads/img_6838ce92a4b262.29932480.jpg', 1, '2025-05-29 21:16:02'),
(412, 71, 'uploads/img_6838ce92a4d0e2.20904650.jpg', 0, '2025-05-29 21:16:02'),
(413, 71, 'uploads/img_6838ce92a4eab5.96076079.jpg', 0, '2025-05-29 21:16:02'),
(414, 71, 'uploads/img_6838ce92a50dc7.42820604.jpg', 0, '2025-05-29 21:16:02'),
(415, 71, 'uploads/img_6838ce92a52849.22577120.jpg', 0, '2025-05-29 21:16:02'),
(416, 71, 'uploads/img_6838ce92a540a4.12087122.jpg', 0, '2025-05-29 21:16:02'),
(417, 71, 'uploads/img_6838ce92a559e5.27595120.jpg', 0, '2025-05-29 21:16:02'),
(418, 71, 'uploads/img_6838ce92a574b4.95179573.jpg', 0, '2025-05-29 21:16:02'),
(419, 71, 'uploads/img_6838ce92a58c84.58075481.jpg', 0, '2025-05-29 21:16:02'),
(420, 71, 'uploads/img_6838ce92a5a3d9.97864758.jpg', 0, '2025-05-29 21:16:02'),
(421, 71, 'uploads/img_6838ce92a5bb83.49126970.jpg', 0, '2025-05-29 21:16:02'),
(422, 71, 'uploads/img_6838ce92a5d2d5.47595280.jpg', 0, '2025-05-29 21:16:02'),
(423, 71, 'uploads/img_6838ce92a5ea06.97851709.jpg', 0, '2025-05-29 21:16:02'),
(424, 72, 'uploads/img_6838cecaa9add1.08395087.jpg', 1, '2025-05-29 21:16:58'),
(425, 72, 'uploads/img_6838cecaa9d284.92636819.jpg', 0, '2025-05-29 21:16:58'),
(426, 72, 'uploads/img_6838cecaa9ef89.05218809.jpg', 0, '2025-05-29 21:16:58'),
(427, 72, 'uploads/img_6838cecaaa0b59.00286988.jpg', 0, '2025-05-29 21:16:58'),
(428, 72, 'uploads/img_6838cecaaa2584.43727822.jpg', 0, '2025-05-29 21:16:58'),
(429, 73, 'uploads/img_6838cf4174d192.07500173.jpg', 1, '2025-05-29 21:18:57'),
(430, 73, 'uploads/img_6838cf4174f559.69695448.jpg', 0, '2025-05-29 21:18:57'),
(431, 73, 'uploads/img_6838cf417517b7.72968922.jpg', 0, '2025-05-29 21:18:57'),
(432, 73, 'uploads/img_6838cf41753974.52704831.jpg', 0, '2025-05-29 21:18:57'),
(433, 73, 'uploads/img_6838cf41755907.88522270.jpg', 0, '2025-05-29 21:18:57'),
(434, 73, 'uploads/img_6838cf417576f3.82097379.jpg', 0, '2025-05-29 21:18:57'),
(435, 73, 'uploads/img_6838cf417596c7.43394694.jpg', 0, '2025-05-29 21:18:57'),
(436, 73, 'uploads/img_6838cf4175ade5.78709757.jpg', 0, '2025-05-29 21:18:57'),
(437, 73, 'uploads/img_6838cf4175c317.37051340.jpg', 0, '2025-05-29 21:18:57'),
(438, 73, 'uploads/img_6838cf4175d9b0.60238754.jpg', 0, '2025-05-29 21:18:57'),
(439, 73, 'uploads/img_6838cf4175ee46.66481927.jpg', 0, '2025-05-29 21:18:57'),
(440, 73, 'uploads/img_6838cf417600e4.36407196.jpg', 0, '2025-05-29 21:18:57'),
(441, 73, 'uploads/img_6838cf41761329.25685841.jpg', 0, '2025-05-29 21:18:57'),
(442, 73, 'uploads/img_6838cf41762574.21939194.jpg', 0, '2025-05-29 21:18:57'),
(443, 73, 'uploads/img_6838cf417662e3.71083261.jpg', 0, '2025-05-29 21:18:57'),
(444, 73, 'uploads/img_6838cf417673a9.49731249.jpg', 0, '2025-05-29 21:18:57'),
(455, 75, 'uploads/img_6838cfa4153272.51369728.jpg', 1, '2025-05-29 21:20:36'),
(456, 75, 'uploads/img_6838cfa4155094.71443877.jpg', 0, '2025-05-29 21:20:36'),
(457, 75, 'uploads/img_6838cfa4156cd3.93842435.jpg', 0, '2025-05-29 21:20:36'),
(458, 75, 'uploads/img_6838cfa4158655.67669632.jpg', 0, '2025-05-29 21:20:36'),
(459, 75, 'uploads/img_6838cfa4159f68.08666164.jpg', 0, '2025-05-29 21:20:36'),
(460, 75, 'uploads/img_6838cfa415b8f8.09418718.jpg', 0, '2025-05-29 21:20:36'),
(461, 75, 'uploads/img_6838cfa415d253.26716258.jpg', 0, '2025-05-29 21:20:36'),
(462, 75, 'uploads/img_6838cfa415ea11.39955260.jpg', 0, '2025-05-29 21:20:36'),
(463, 75, 'uploads/img_6838cfa41601e3.45306307.jpg', 0, '2025-05-29 21:20:36'),
(464, 75, 'uploads/img_6838cfa41619f4.16571816.jpg', 0, '2025-05-29 21:20:36'),
(465, 76, 'uploads/img_6838d003e8c8b1.79837666.jpg', 1, '2025-05-29 21:22:11'),
(466, 76, 'uploads/img_6838d003e8e1a3.09858820.jpg', 0, '2025-05-29 21:22:11'),
(467, 76, 'uploads/img_6838d003e8f681.56111955.jpg', 0, '2025-05-29 21:22:11'),
(468, 76, 'uploads/img_6838d003e90ae6.25357241.jpg', 0, '2025-05-29 21:22:11'),
(469, 76, 'uploads/img_6838d003e92010.29568963.jpg', 0, '2025-05-29 21:22:11'),
(470, 76, 'uploads/img_6838d003e93223.59241246.jpg', 0, '2025-05-29 21:22:11'),
(471, 76, 'uploads/img_6838d003e943b2.22020190.jpg', 0, '2025-05-29 21:22:11'),
(472, 76, 'uploads/img_6838d003e957b5.72468676.jpg', 0, '2025-05-29 21:22:11'),
(473, 76, 'uploads/img_6838d003e96be8.36601149.jpg', 0, '2025-05-29 21:22:11'),
(474, 76, 'uploads/img_6838d003e97d84.04343713.jpg', 0, '2025-05-29 21:22:11'),
(495, 78, 'uploads/img_6838d08d2dab39.65997654.jpg', 1, '2025-05-29 21:24:29'),
(496, 78, 'uploads/img_6838d08d2dd2e1.73090145.jpg', 0, '2025-05-29 21:24:29'),
(497, 78, 'uploads/img_6838d08d2df183.01504506.jpg', 0, '2025-05-29 21:24:29'),
(498, 78, 'uploads/img_6838d08d2e0b51.45691751.jpg', 0, '2025-05-29 21:24:29'),
(499, 78, 'uploads/img_6838d08d2e1e94.97283194.jpg', 0, '2025-05-29 21:24:29'),
(500, 78, 'uploads/img_6838d08d2e31d5.80875052.jpg', 0, '2025-05-29 21:24:29'),
(501, 79, 'uploads/img_6838d0b2a25381.10979500.jpg', 1, '2025-05-29 21:25:06'),
(502, 79, 'uploads/img_6838d0b2a39ee3.49055662.jpg', 0, '2025-05-29 21:25:06'),
(503, 79, 'uploads/img_6838d0b2a3b366.00612524.jpg', 0, '2025-05-29 21:25:06'),
(504, 79, 'uploads/img_6838d0b2a3c403.14594313.jpg', 0, '2025-05-29 21:25:06'),
(505, 79, 'uploads/img_6838d0b2a3d471.41585611.jpg', 0, '2025-05-29 21:25:06'),
(506, 79, 'uploads/img_6838d0b2a3e4b3.14355098.jpg', 0, '2025-05-29 21:25:06'),
(507, 79, 'uploads/img_6838d0b2a3f468.31180900.jpg', 0, '2025-05-29 21:25:06'),
(508, 79, 'uploads/img_6838d0b2a40900.26702428.jpg', 0, '2025-05-29 21:25:06'),
(509, 79, 'uploads/img_6838d0b2a419e6.60914155.jpg', 0, '2025-05-29 21:25:06'),
(510, 79, 'uploads/img_6838d0b2a42be0.17758071.jpg', 0, '2025-05-29 21:25:06'),
(511, 80, 'uploads/img_6838d131a33cd4.36406462.jpg', 1, '2025-05-29 21:27:13'),
(512, 80, 'uploads/img_6838d131a356b8.11464258.jpg', 0, '2025-05-29 21:27:13'),
(513, 80, 'uploads/img_6838d131a36b74.60299746.jpg', 0, '2025-05-29 21:27:13'),
(514, 80, 'uploads/img_6838d131a37e17.32913580.jpg', 0, '2025-05-29 21:27:13'),
(515, 80, 'uploads/img_6838d131a38fd6.38055042.jpg', 0, '2025-05-29 21:27:13'),
(516, 80, 'uploads/img_6838d131a3a169.57054968.jpg', 0, '2025-05-29 21:27:13'),
(517, 80, 'uploads/img_6838d131a3b2b1.84694520.jpg', 0, '2025-05-29 21:27:13'),
(518, 80, 'uploads/img_6838d131a3c749.10257727.jpg', 0, '2025-05-29 21:27:13'),
(519, 80, 'uploads/img_6838d131a3d9d4.38630209.jpg', 0, '2025-05-29 21:27:13'),
(520, 80, 'uploads/img_6838d131a3eb77.07025055.jpg', 0, '2025-05-29 21:27:13'),
(521, 80, 'uploads/img_6838d131a3fba9.23665180.jpg', 0, '2025-05-29 21:27:13'),
(522, 80, 'uploads/img_6838d131a40bc8.77092176.jpg', 0, '2025-05-29 21:27:13'),
(523, 80, 'uploads/img_6838d131a41be0.78605614.jpg', 0, '2025-05-29 21:27:13'),
(524, 80, 'uploads/img_6838d131a42c56.66533392.jpg', 0, '2025-05-29 21:27:13'),
(525, 80, 'uploads/img_6838d131a44105.18855532.jpg', 0, '2025-05-29 21:27:13'),
(526, 80, 'uploads/img_6838d131a453d5.87877476.jpg', 0, '2025-05-29 21:27:13'),
(527, 80, 'uploads/img_6838d131a46559.90281750.jpg', 0, '2025-05-29 21:27:13'),
(528, 80, 'uploads/img_6838d131a475c7.57421725.jpg', 0, '2025-05-29 21:27:13'),
(529, 80, 'uploads/img_6838d131a48595.47419296.jpg', 0, '2025-05-29 21:27:13'),
(530, 80, 'uploads/img_6838d131a49613.94488814.jpg', 0, '2025-05-29 21:27:13'),
(531, 81, 'uploads/img_6838d422343197.52561706.jpg', 1, '2025-05-29 21:39:46'),
(532, 81, 'uploads/img_6838d422344fc9.97928917.jpg', 0, '2025-05-29 21:39:46'),
(533, 81, 'uploads/img_6838d422346fd3.57382736.jpg', 0, '2025-05-29 21:39:46'),
(534, 81, 'uploads/img_6838d422349110.15868386.jpg', 0, '2025-05-29 21:39:46'),
(535, 81, 'uploads/img_6838d42234aae0.31011357.jpg', 0, '2025-05-29 21:39:46'),
(536, 81, 'uploads/img_6838d42234c230.48222251.jpg', 0, '2025-05-29 21:39:46'),
(537, 81, 'uploads/img_6838d42234e1c8.89577695.jpg', 0, '2025-05-29 21:39:46'),
(538, 81, 'uploads/img_6838d42234fbb7.44786108.jpg', 0, '2025-05-29 21:39:46'),
(539, 81, 'uploads/img_6838d4223512d7.87212477.jpg', 0, '2025-05-29 21:39:46'),
(540, 81, 'uploads/img_6838d422352be8.78985060.jpg', 0, '2025-05-29 21:39:46'),
(541, 81, 'uploads/img_6838d4223547c4.74450214.jpg', 0, '2025-05-29 21:39:46'),
(542, 81, 'uploads/img_6838d422356136.47276767.jpg', 0, '2025-05-29 21:39:46'),
(543, 81, 'uploads/img_6838d422357ad5.14117048.jpg', 0, '2025-05-29 21:39:46'),
(544, 81, 'uploads/img_6838d4223593e1.39127888.jpg', 0, '2025-05-29 21:39:46'),
(545, 81, 'uploads/img_6838d42235ab87.07553527.jpg', 0, '2025-05-29 21:39:46'),
(546, 81, 'uploads/img_6838d42235c3a5.18330998.jpg', 0, '2025-05-29 21:39:46'),
(547, 81, 'uploads/img_6838d42235db52.10983298.jpg', 0, '2025-05-29 21:39:46'),
(548, 81, 'uploads/img_6838d42235f2c7.53437719.jpg', 0, '2025-05-29 21:39:46'),
(549, 81, 'uploads/img_6838d422360b45.27107111.jpg', 0, '2025-05-29 21:39:46'),
(550, 81, 'uploads/img_6838d422362356.45051690.jpg', 0, '2025-05-29 21:39:46'),
(551, 82, 'uploads/img_6838d4c5d60778.34881920.jpg', 1, '2025-05-29 21:42:29'),
(552, 82, 'uploads/img_6838d4c5d620a6.33616759.jpg', 0, '2025-05-29 21:42:29'),
(553, 82, 'uploads/img_6838d4c5d637a0.46674054.jpg', 0, '2025-05-29 21:42:29'),
(554, 82, 'uploads/img_6838d4c5d64fc1.25665900.jpg', 0, '2025-05-29 21:42:29'),
(555, 82, 'uploads/img_6838d4c5d66966.41722941.jpg', 0, '2025-05-29 21:42:29'),
(556, 82, 'uploads/img_6838d4c5d67e63.88514423.jpg', 0, '2025-05-29 21:42:29'),
(557, 82, 'uploads/img_6838d4c5d690f6.78905804.jpg', 0, '2025-05-29 21:42:29'),
(558, 82, 'uploads/img_6838d4c5d6a464.60092050.jpg', 0, '2025-05-29 21:42:29'),
(559, 82, 'uploads/img_6838d4c5d6b707.59051407.jpg', 0, '2025-05-29 21:42:29'),
(560, 82, 'uploads/img_6838d4c5d6c869.61629497.jpg', 0, '2025-05-29 21:42:29'),
(561, 82, 'uploads/img_6838d4c5d6d908.35602920.jpg', 0, '2025-05-29 21:42:29'),
(562, 82, 'uploads/img_6838d4c5d6ea86.53002645.jpg', 0, '2025-05-29 21:42:29'),
(563, 82, 'uploads/img_6838d4c5d6fbb7.81158345.jpg', 0, '2025-05-29 21:42:29'),
(564, 82, 'uploads/img_6838d4c5d70ba3.60872781.jpg', 0, '2025-05-29 21:42:29'),
(565, 83, 'uploads/img_6838d54b8ca123.04204921.jpg', 1, '2025-05-29 21:44:43'),
(566, 83, 'uploads/img_6838d54b8cb658.28382611.jpg', 0, '2025-05-29 21:44:43'),
(567, 83, 'uploads/img_6838d54b8ccb70.81120614.jpg', 0, '2025-05-29 21:44:43'),
(568, 83, 'uploads/img_6838d54b8cdfa4.59650240.jpg', 0, '2025-05-29 21:44:43'),
(569, 83, 'uploads/img_6838d54b8cf541.43379538.jpg', 0, '2025-05-29 21:44:43'),
(570, 83, 'uploads/img_6838d54b8d08d2.33299960.jpg', 0, '2025-05-29 21:44:43'),
(571, 83, 'uploads/img_6838d54b8d1bf0.45933868.jpg', 0, '2025-05-29 21:44:43'),
(572, 84, 'uploads/img_6838d5d10b6327.53947108.jpg', 1, '2025-05-29 21:46:57'),
(573, 84, 'uploads/img_6838d5d10b7ed6.63493853.jpg', 0, '2025-05-29 21:46:57'),
(574, 84, 'uploads/img_6838d5d10b9980.96270566.jpg', 0, '2025-05-29 21:46:57'),
(575, 84, 'uploads/img_6838d5d10bb240.95076369.jpg', 0, '2025-05-29 21:46:57'),
(576, 84, 'uploads/img_6838d5d10bcc07.19924412.jpg', 0, '2025-05-29 21:46:57'),
(577, 84, 'uploads/img_6838d5d10bed23.49910848.jpg', 0, '2025-05-29 21:46:57'),
(578, 84, 'uploads/img_6838d5d10c0535.73671771.jpg', 0, '2025-05-29 21:46:57'),
(579, 84, 'uploads/img_6838d5d10c1cf6.72265676.jpg', 0, '2025-05-29 21:46:57'),
(580, 84, 'uploads/img_6838d5d10c38b3.38262591.jpg', 0, '2025-05-29 21:46:57'),
(581, 84, 'uploads/img_6838d5d10c5792.57895196.jpg', 0, '2025-05-29 21:46:57'),
(582, 84, 'uploads/img_6838d5d10c6f17.57306641.jpg', 0, '2025-05-29 21:46:57'),
(583, 84, 'uploads/img_6838d5d10c8494.38376769.jpg', 0, '2025-05-29 21:46:57'),
(584, 84, 'uploads/img_6838d5d10c9b94.76841246.jpg', 0, '2025-05-29 21:46:57'),
(585, 84, 'uploads/img_6838d5d10cb088.26576025.jpg', 0, '2025-05-29 21:46:57'),
(586, 84, 'uploads/img_6838d5d10cc689.62178404.jpg', 0, '2025-05-29 21:46:57'),
(587, 84, 'uploads/img_6838d5d10cdbd8.66826159.jpg', 0, '2025-05-29 21:46:57'),
(588, 84, 'uploads/img_6838d5d10cf067.93979675.jpg', 0, '2025-05-29 21:46:57'),
(589, 84, 'uploads/img_6838d5d10d04e7.63614059.jpg', 0, '2025-05-29 21:46:57'),
(590, 84, 'uploads/img_6838d5d10d1a25.47613099.jpg', 0, '2025-05-29 21:46:57'),
(591, 84, 'uploads/img_6838d5d10d3133.76876609.jpg', 0, '2025-05-29 21:46:57'),
(592, 85, 'uploads/img_6838d67e1bc967.09658818.jpg', 1, '2025-05-29 21:49:50'),
(593, 85, 'uploads/img_6838d67e1be465.73772296.jpg', 0, '2025-05-29 21:49:50'),
(594, 85, 'uploads/img_6838d67e1bfad3.65957561.jpg', 0, '2025-05-29 21:49:50'),
(595, 85, 'uploads/img_6838d67e1c1050.37489812.jpg', 0, '2025-05-29 21:49:50'),
(596, 85, 'uploads/img_6838d67e1c25a6.34545128.jpg', 0, '2025-05-29 21:49:50'),
(597, 85, 'uploads/img_6838d67e1c3ab2.74470907.jpg', 0, '2025-05-29 21:49:50'),
(598, 85, 'uploads/img_6838d67e1c4de9.08116777.jpg', 0, '2025-05-29 21:49:50'),
(599, 85, 'uploads/img_6838d67e1c6102.86436994.jpg', 0, '2025-05-29 21:49:50'),
(600, 85, 'uploads/img_6838d67e1c7527.98435924.jpg', 0, '2025-05-29 21:49:50'),
(601, 85, 'uploads/img_6838d67e1c8723.85901622.jpg', 0, '2025-05-29 21:49:50'),
(602, 85, 'uploads/img_6838d67e1c98f0.50088685.jpg', 0, '2025-05-29 21:49:50'),
(603, 85, 'uploads/img_6838d67e1caa29.86154656.jpg', 0, '2025-05-29 21:49:50'),
(604, 85, 'uploads/img_6838d67e1cba97.81830889.jpg', 0, '2025-05-29 21:49:50'),
(605, 85, 'uploads/img_6838d67e1ccb14.64778960.jpg', 0, '2025-05-29 21:49:50'),
(606, 85, 'uploads/img_6838d67e1cdb75.39474634.jpg', 0, '2025-05-29 21:49:50'),
(607, 85, 'uploads/img_6838d67e1ceb33.97380428.jpg', 0, '2025-05-29 21:49:50'),
(608, 85, 'uploads/img_6838d67e1cfa66.44318472.jpg', 0, '2025-05-29 21:49:50'),
(609, 85, 'uploads/img_6838d67e1d09e9.44517445.jpg', 0, '2025-05-29 21:49:50'),
(610, 85, 'uploads/img_6838d67e1d1904.57235085.jpg', 0, '2025-05-29 21:49:50'),
(611, 85, 'uploads/img_6838d67e1d28e2.76825776.jpg', 0, '2025-05-29 21:49:50'),
(632, 87, 'uploads/img_6838d79c816ee1.68235179.jpg', 1, '2025-05-29 21:54:36'),
(633, 87, 'uploads/img_6838d79c818f37.91782004.jpg', 0, '2025-05-29 21:54:36'),
(634, 87, 'uploads/img_6838d79c81ad82.67985184.jpg', 0, '2025-05-29 21:54:36'),
(635, 87, 'uploads/img_6838d79c81ca20.67239930.jpg', 0, '2025-05-29 21:54:36'),
(636, 87, 'uploads/img_6838d79c81e7a4.70579568.jpg', 0, '2025-05-29 21:54:36'),
(637, 87, 'uploads/img_6838d79c8204b3.28414009.jpg', 0, '2025-05-29 21:54:36'),
(638, 87, 'uploads/img_6838d79c821fc8.45502498.jpg', 0, '2025-05-29 21:54:36'),
(639, 87, 'uploads/img_6838d79c823a11.73861569.jpg', 0, '2025-05-29 21:54:36'),
(640, 87, 'uploads/img_6838d79c825711.58877063.jpg', 0, '2025-05-29 21:54:36'),
(641, 87, 'uploads/img_6838d79c827686.89902358.jpg', 0, '2025-05-29 21:54:36'),
(642, 87, 'uploads/img_6838d79c829304.78947869.jpg', 0, '2025-05-29 21:54:36'),
(643, 87, 'uploads/img_6838d79c82b122.91989883.jpg', 0, '2025-05-29 21:54:36'),
(644, 87, 'uploads/img_6838d79c82d4f7.69692092.jpg', 0, '2025-05-29 21:54:36'),
(645, 87, 'uploads/img_6838d79c82f2a9.72726869.jpg', 0, '2025-05-29 21:54:36'),
(646, 87, 'uploads/img_6838d79c831017.47337762.jpg', 0, '2025-05-29 21:54:36'),
(647, 87, 'uploads/img_6838d79c832f07.46944931.jpg', 0, '2025-05-29 21:54:36'),
(648, 87, 'uploads/img_6838d79c835086.95741687.jpg', 0, '2025-05-29 21:54:36'),
(649, 87, 'uploads/img_6838d79c836de4.36632152.jpg', 0, '2025-05-29 21:54:36'),
(650, 87, 'uploads/img_6838d79c838bf3.78259666.jpg', 0, '2025-05-29 21:54:36'),
(651, 87, 'uploads/img_6838d79c83a7e4.64038717.jpg', 0, '2025-05-29 21:54:36');

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
(3, 'Marius', '$2y$10$ExKUQbe7R/OT6Kuh6qErAuBiYqsIRCoNwZircQ59z3G/CYMN0MDvC', 'test@test.lt', '+3706123456789', '2025-05-21 22:00:24'),
(14, 'martis', '$2y$10$CtS.iDWvDfUcjgdITMOk6uDnDsf.UVMNrPxtFbIdRFeXjnWP.K79S', 'martynas.meska@vvk.lt', '+3705051622', '2025-05-29 20:42:21');

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
(16, 3, 35, '2025-05-21 22:03:32'),
(22, 1, 45, '2025-05-28 11:43:38');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `listing_images`
--
ALTER TABLE `listing_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=652;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user_favorites`
--
ALTER TABLE `user_favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

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
