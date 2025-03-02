-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2025. Már 01. 23:23
-- Kiszolgáló verziója: 10.4.32-MariaDB
-- PHP verzió: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `luxhorizon`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `car_id` int(11) DEFAULT NULL,
  `track_id` int(11) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `booking_type` enum('rental','track_day') NOT NULL,
  `laps` int(11) DEFAULT NULL,
  `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `car_id`, `track_id`, `start_date`, `end_date`, `total_price`, `booking_type`, `laps`, `status`, `created_at`) VALUES
(0, 1, 1, 1, '2025-03-01', '2025-03-01', 150.00, 'track_day', 1, 'confirmed', '2025-03-01 22:21:11');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `brands`
--

CREATE TABLE `brands` (
  `brand_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `brands`
--

INSERT INTO `brands` (`brand_id`, `name`, `logo_url`, `description`) VALUES
(1, 'Audi', '../assets/BrandLogos/Audi.png', 'German luxury car manufacturer'),
(2, 'Porsche', '../assets/BrandLogos/Porsche.png', 'High-performance sports cars'),
(3, 'BMW', '../assets/BrandLogos\\BMW.png', 'Luxury vehicles and motorcycles'),
(4, 'Mercedes', '../assets/BrandLogos/Mercedes.png', 'Luxury and high-performance vehicles'),
(5, 'Ferrari', '../assets/BrandLogos/Ferrari.png', 'Italian luxury sports car manufacturer'),
(6, 'Lamborghini', '../assets/BrandLogos/Lamborghini.png', 'Italian super sports cars'),
(7, 'McLaren', '../assets/BrandLogos/McLaren.png', 'British high-performance sports cars'),
(8, 'Dodge', '../assets/BrandLogos/Dodge.png', 'American muscle cars');
-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `cars`
--

CREATE TABLE `cars` (
  `car_id` int(11) NOT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `model` varchar(100) NOT NULL,
  `hp` int(11) NOT NULL,
  `engine` varchar(50) NOT NULL,
  `top_speed` int(11) NOT NULL,
  `gears` int(11) NOT NULL,
  `drive` varchar(20) NOT NULL,
  `acceleration` decimal(4,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `rent_price` decimal(10,2) NOT NULL,
  `lap_price_1` decimal(10,2) NOT NULL,
  `lap_price_3` decimal(10,2) NOT NULL,
  `lap_price_5` decimal(10,2) NOT NULL,
  `lap_price_10` decimal(10,2) NOT NULL,
  `available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `cars`
--

INSERT INTO `cars` (`car_id`, `brand_id`, `category_id`, `model`, `hp`, `engine`, `top_speed`, `gears`, `drive`, `acceleration`, `image_url`, `rent_price`, `lap_price_1`, `lap_price_3`, `lap_price_5`, `lap_price_10`, `available`) VALUES
(1, 1, 1, 'R8', 620, '5.2l V10', 320, 7, 'RWD', 3.10, '..\\assets\\Cars\\Front\\Audi\\R8.png\", ..\\assets\\Cars\\Side\\Audi\\R8.png\"', 250000.00, 50000.00, 140000.00, 220000.00, 400000.00, 1),
(2, 1, 2, 'RS6 GT', 630, '4.0l V8', 305, 7, 'AWD', 3.30, '..\\assets\\Cars\\Front\\Audi\\RS6GT.png\", ..\\assets\\Cars\\Side\\Audi\\RS6GT.png\"', 140000.00, 45000.00, 125000.00, 200000.00, 380000.00, 1),
(3, 1, 4, 'Quattro', 720, '2.2l V5', 310, 6, 'AWD', 3.10, '..\\assets\\Cars\\Front\\Audi\\Quattro.png\", ..\\assets\\Cars\\Side\\Audi\\Quattro.png\"', 130000.00, 40000.00, 110000.00, 180000.00, 340000.00, 1),
(4, 2, 1, '911 GT3 RS', 525, '4.0l Flat-6', 296, 7, 'RWD', 3.20, '..\\assets\\Cars\\Front\\Porsche\\GT3.png\", ..\\assets\\Cars\\Side\\Porsche\\GT3.png\"', 200000.00, 60000.00, 170000.00, 280000.00, 520000.00, 1),
(5, 2, 2, 'Panamera GTS', 480, '4.0l V8', 300, 8, 'AWD', 3.90, '..\\assets\\Cars\\Front\\Porsche\\Panamera.png\", ..\\assets\\Cars\\Side\\Porsche\\Panamera.png\"', 180000.00, 55000.00, 160000.00, 260000.00, 500000.00, 1),
(6, 2, 2, 'Cayenne', 460, '4.0l V8', 286, 8, 'AWD', 4.50, '..\\assets\\Cars\\Front\\Porsche\\Cayenne.png\", ..\\assets\\Cars\\Side\\Porsche\\Cayenne.png\"\r\n ', 160000.00, 50000.00, 140000.00, 230000.00, 440000.00, 1),
(7, 3, 2, 'M5 CS', 635, '4.4l V8', 305, 8, 'AWD', 3.00, '..\\assets\\Cars\\Front\\BMW\\M5.png\", ..\\assets\\Cars\\Side\\BMW\\M5.png\"\r\n ', 120000.00, 40000.00, 110000.00, 180000.00, 340000.00, 1),
(8, 3, 2, 'M8', 625, '4.4l V8', 305, 8, 'AWD', 3.20, '..\\assets\\Cars\\Front\\BMW\\M8.png\", ..\\assets\\Cars\\Side\\BMW\\M8.png\"\r\n ', 115000.00, 38000.00, 105000.00, 170000.00, 320000.00, 1),
(9, 3, 2, 'X6M', 625, '4.4l V8', 290, 8, 'AWD', 3.80, '..\\assets\\Cars\\Front\\BMW\\X6M.png\", ..\\assets\\Cars\\Side\\BMW\\X6M.png\"\r\n \r\n ', 110000.00, 35000.00, 100000.00, 160000.00, 300000.00, 1),
(10, 4, 2, 'AMG GT63s', 639, '4.0l V8', 315, 9, 'AWD', 3.20, '..\\assets\\Cars\\Front\\Mercedes\\GT63s.png\", ..\\assets\\Cars\\Side\\Mercedes\\GT63s.png\"\r\n ', 220000.00, 65000.00, 180000.00, 290000.00, 550000.00, 1),
(11, 4, 1, 'AMG GT R', 585, '4.0l V8', 318, 7, 'RWD', 3.60, '..\\assets\\Cars\\Front\\Mercedes\\GT-R.png\", ..\\assets\\Cars\\Side\\Mercedes\\GT-R.png\"', 210000.00, 63000.00, 175000.00, 285000.00, 540000.00, 1),
(12, 4, 2, 'AMG CLS63s', 612, '4.0l V8', 300, 9, 'AWD', 3.40, '..\\assets\\Cars\\Front\\Mercedes\\CLS.png\", ..\\assets\\Cars\\Side\\Mercedes\\CLS.png\"', 200000.00, 60000.00, 170000.00, 280000.00, 520000.00, 1),
(13, 5, 1, 'SF90', 1000, '4.0l V8 Hybrid', 340, 8, 'AWD', 2.50, '..\\assets\\Cars\\Front\\Ferrari\\SF90.png\", ..\\assets\\Cars\\Side\\Ferrari\\SF90.png\"', 400000.00, 120000.00, 340000.00, 550000.00, 1000000.00, 1),
(14, 5, 1, 'LaFerrari', 963, '6.3l V12 Hybrid', 350, 7, 'RWD', 2.40, '..\\assets\\Cars\\Front\\Ferrari\\LaFerrari.png\", ..\\assets\\Cars\\Side\\Ferrari\\LaFerrari.png\"', 450000.00, 135000.00, 380000.00, 620000.00, 1200000.00, 1),
(15, 5, 1, '488-Pista', 720, '3.9l V8', 340, 7, 'RWD', 2.85, '..\\assets\\Cars\\Front\\Ferrari\\Pista.png\",\r\n..\\assets\\Cars\\Side\\Ferrari\\Pista.png\"', 380000.00, 114000.00, 320000.00, 520000.00, 980000.00, 1),
(16, 6, 1, 'Huracan STO', 640, '5.2l V10', 310, 7, 'RWD', 3.00, '..\\assets\\Cars\\Front\\Lamborghini\\Huracan.png\",\r\n..\\assets\\Cars\\Side\\Lamborghini\\Huracan.png\"', 420000.00, 126000.00, 355000.00, 580000.00, 1100000.00, 1),
(17, 6, 1, 'Aventador', 780, '6.5l V12', 355, 7, 'AWD', 2.80, '..\\assets\\Cars\\Front\\Lamborghini\\Aventador.png\",\r\n..\\assets\\Cars\\Side\\Lamborghini\\Aventador.png\"', 500000.00, 150000.00, 420000.00, 690000.00, 1300000.00, 1),
(18, 6, 2, 'Urus', 666, '4.0l V8', 305, 8, 'AWD', 3.60, '..\\assets\\Cars\\Front\\Lamborghini\\Urus.png\",\r\n..\\assets\\Cars\\Side\\Lamborghini\\Urus.png\"', 380000.00, 114000.00, 320000.00, 520000.00, 980000.00, 1),
(19, 7, 1, 'P1', 916, '3.8l V8 Hybrid', 350, 7, 'RWD', 2.80, '..\\assets\\Cars\\Front\\McLaren\\P1.png\",\r\n..\\assets\\Cars\\Side\\McLaren\\P1.png\"', 600000.00, 180000.00, 510000.00, 830000.00, 1600000.00, 1),
(20, 7, 1, '720s', 720, '4.0l V8', 341, 7, 'RWD', 2.90, '..\\assets\\Cars\\Front\\McLaren\\720.png\",\r\n..\\assets\\Cars\\Side\\McLaren\\720.png\"', 550000.00, 165000.00, 465000.00, 760000.00, 1450000.00, 1),
(21, 8, 3, 'Challenger', 717, '6.2l V8', 320, 8, 'RWD', 3.70, '..\\assets\\Cars\\Front\\Dodge\\Challenger.png\",\r\n..\\assets\\Cars\\Side\\Dodge\\Challenger.png\"', 70000.00, 21000.00, 59000.00, 96000.00, 180000.00, 1),
(22, 8, 3, 'Charger', 717, '6.2l V8', 315, 8, 'RWD', 3.60, '..\\assets\\Cars\\Front\\Dodge\\Charger.png\",\r\n..\\assets\\Cars\\Side\\Dodge\\Charger.png\"', 80000.00, 24000.00, 67000.00, 110000.00, 210000.00, 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `tracks`
--

CREATE TABLE `tracks` (
  `track_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `length` decimal(6,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `layout_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `tracks`
--

INSERT INTO `tracks` (`track_id`, `name`, `length`, `description`, `image_url`, `layout_url`) VALUES
(1, 'Hungaroring', 4.38, 'Formula 1 circuit in Mogyoród, Hungary', '../assets/Tracks/Hungaroring/HungaroringIndex.png', '../assets/Tracks/Hungaroring/Hungaroring_Black.png'),
(2, 'Balaton Park', 4.12, 'Modern racing circuit in Balatonfőkajár, near Lake Balaton', '../assets/Tracks/BalatonPark/BalatonPark_Index.png', '../assets/Tracks/BalatonPark/BalatonPark_Black.png'),
(3, 'M-Ring', 3.71, 'Technical track with challenging corners in Kakucs', '../assets/Tracks/MRing/M-RingIndex.png', '../assets/Tracks/MRing/mring_black.png,\r\n\r\n'),
(4, 'DRX Rally Arena', 2.45, 'Specialized rally and drift track in Nyirád', '../assets/Tracks/DRX/DRXIndex.png', '../assets/Tracks/DRX/DRX_Black.png');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password_hash`, `created_at`, `last_login`) VALUES
(8, 'Lszlo', 'laszlo@gmail.com', '$2y$10$wV/FUvQQcE6bXEMdSTAQEOoE9a9oJuxMimgrdAU29alHeDUlv.tM6', '2025-03-01 22:18:39', '2025-03-01 22:18:58');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- A tábla indexei `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`car_id`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `category_id` (`category_id`);

--
-- A tábla indexei `tracks`
--
ALTER TABLE `tracks`
  ADD PRIMARY KEY (`track_id`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT a táblához `cars`
--
ALTER TABLE `cars`
  MODIFY `car_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT a táblához `tracks`
--
ALTER TABLE `tracks`
  MODIFY `track_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
