-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 21.08.2023 klo 11:17
-- Palvelimen versio: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tietokanta`
--

-- --------------------------------------------------------

--
-- Rakenne taululle `ilmoitukset`
--

CREATE TABLE `ilmoitukset` (
  `ilmoitukset_id` int(6) NOT NULL,
  `ilmoitukset_laji` int(2) NOT NULL,
  `ilmoitukset_nimi` text NOT NULL,
  `ilmoitukset_kuvaus` text NOT NULL,
  `ilmoitukset_paivays` date NOT NULL,
  `myyja_id` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

-- --------------------------------------------------------

--
-- Rakenne taululle `kayttajat`
--

CREATE TABLE `kayttajat` (
  `kayttaja_id` int(6) NOT NULL,
  `kayttaja_taso` varchar(5) NOT NULL DEFAULT 'user',
  `kayttaja_tunnus` varchar(20) NOT NULL,
  `kayttaja_salasana` char(65) NOT NULL,
  `kayttaja_sahkoposti` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ilmoitukset`
--
ALTER TABLE `ilmoitukset`
  ADD PRIMARY KEY (`ilmoitukset_id`),
  ADD KEY `myyja_id` (`myyja_id`);

--
-- Indexes for table `kayttajat`
--
ALTER TABLE `kayttajat`
  ADD PRIMARY KEY (`kayttaja_id`),
  ADD UNIQUE KEY `kayttaja_tunnus` (`kayttaja_tunnus`),
  ADD UNIQUE KEY `kayttaja_sahkoposti` (`kayttaja_sahkoposti`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ilmoitukset`
--
ALTER TABLE `ilmoitukset`
  MODIFY `ilmoitukset_id` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kayttajat`
--
ALTER TABLE `kayttajat`
  MODIFY `kayttaja_id` int(6) NOT NULL AUTO_INCREMENT;

--
-- Rajoitteet vedostauluille
--

--
-- Rajoitteet taululle `ilmoitukset`
--
ALTER TABLE `ilmoitukset`
  ADD CONSTRAINT `ilmoitukset_ibfk_1` FOREIGN KEY (`myyja_id`) REFERENCES `kayttajat` (`kayttaja_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
