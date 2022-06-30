-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 30 jun 2022 om 18:57
-- Serverversie: 10.4.19-MariaDB
-- PHP-versie: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dinder`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dogs`
--

CREATE TABLE `dogs` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `age` int(2) DEFAULT NULL,
  `breed` varchar(255) DEFAULT NULL,
  `image` longtext DEFAULT NULL,
  `owner` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `dogs`
--

INSERT INTO `dogs` (`id`, `name`, `age`, `breed`, `image`, `owner`) VALUES
(12, 'Pietertje', 2, 'Duitse Herder', 'Pietertje31.jpg', 31),
(13, 'Klaas', 1, 'Chiwawa', 'Klaas31.jpg', 31),
(14, 'Boebie', 2, 'h7mar', 'Boebie32.jpg', 32),
(15, 'bobby', 3, 'Husky', 'bobby30.jpg', 30),
(17, 'Action Henk', 11, 'Pitbull', 'Action Henk34.jpg', 34),
(18, 'Rony', 1, 'Pitbull', 'Rony35.jpg', 35),
(19, 'Zeb', 1, 'Zebitje', 'Zeb36.jpg', 36);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `status` varchar(255) DEFAULT 'In afwachting'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `requests`
--

INSERT INTO `requests` (`id`, `sender`, `receiver`, `status`) VALUES
(21, 31, 34, 'Geaccepteerd'),
(22, 31, 30, 'Geaccepteerd'),
(23, 30, 31, 'Afgewezen'),
(24, 31, 36, 'Geaccepteerd');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` tinytext DEFAULT NULL,
  `profilepicture` varchar(255) DEFAULT 'placeholderprofilepicture.png',
  `biography` longtext DEFAULT NULL,
  `resetcode` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `profilepicture`, `biography`, `resetcode`) VALUES
(30, 'Amine', 'Amrani', 'testeen@hotmail.com', '$2y$10$L6MphK0fmNUuqgkPeZPzaOcVFBG4h.ot9AQpiifa6zpW1xszUlqEG', 'Amine30profilePicture.png', NULL, NULL),
(31, 'Sohaib', 'Khelanji', 'Sohaib1411@hotmail.com', '$2y$10$4h38eMzgLBmkvKkZcSbne.tqGy41YYrvKQcPgb4bNiJHXIoLjQiBe', 'Sohaib31profilePicture.png', NULL, NULL),
(32, 'Jan', 'Jansen', 'testtwee@hotmail.com', '$2y$10$x6X2y5k1Lap1PeemXiSnr.KE6gT9DmoVixNN2ZJQoT.4ZTfi/hmVO', 'Jan32profilePicture.png', 'Ik heb Honger', NULL),
(33, 'Aziz', 'Khelanji', 'aziz06hs@hotmail.com', '$2y$10$qV3QL75vamSGAPsbLCB2J.AtosPGIKgDTgKKteb13GMcJXRBuo3fC', 'Aziz33profilePicture.png', 'Ik hou van honden! en voetballen :)', NULL),
(34, 'piet', 'Jansen', 'pietjansen@hotmail.com', '$2y$10$jEu2uR5/5XvhmZiFjjL.bOiUaTjQTNOXdarHV2W2iQ9Jn4o70amMK', 'piet34profilePicture.png', 'Ik hou van honden en van programmeren !', NULL),
(35, 'Amina', 'Chan', 'aminechan@hotmail.com', '$2y$10$OA1cvaBqatLJ35t0tEy9H.xQATPqj0V8N0m1548aN4Vh/2H.3yf5O', 'Amienuh35profilePicture.jpg', 'OMG!', NULL),
(36, 'Aron', 'Djouya', 'aron@aron.nl', '$2y$10$Q3W8UA6e6.HeSJcfyqIs1O37gRW2MYxl4hP0GCmikiLBt6b09dCTa', 'Aron36profilePicture.png', 'De Goat', NULL);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `dogs`
--
ALTER TABLE `dogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dogs_ibfk_1` (`owner`);

--
-- Indexen voor tabel `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reciever` (`receiver`),
  ADD KEY `sender` (`sender`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `dogs`
--
ALTER TABLE `dogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT voor een tabel `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `dogs`
--
ALTER TABLE `dogs`
  ADD CONSTRAINT `dogs_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Beperkingen voor tabel `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `requests_ibfk_1` FOREIGN KEY (`receiver`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `requests_ibfk_2` FOREIGN KEY (`sender`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
