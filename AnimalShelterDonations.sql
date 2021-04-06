-- phpMyAdmin SQL Dump
-- version 4.0.8
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 06, 2021 at 07:10 PM
-- Server version: 5.1.71-community-log
-- PHP Version: 5.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `AnimalShelterDonations`
--

-- --------------------------------------------------------

--
-- Table structure for table `Donation`
--

CREATE TABLE IF NOT EXISTS `Donation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sum` decimal(10,0) NOT NULL,
  `date` date NOT NULL,
  `idPatron` int(11) NOT NULL,
  `idPet` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idPet` (`idPet`),
  KEY `idPatron` (`idPatron`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `Patron`
--

CREATE TABLE IF NOT EXISTS `Patron` (
  `idPatron` int(11) NOT NULL AUTO_INCREMENT,
  `fName` varchar(255) NOT NULL,
  `lName` varchar(255) NOT NULL,
  PRIMARY KEY (`idPatron`),
  KEY `id` (`idPatron`),
  KEY `idPatron` (`idPatron`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `Pet`
--

CREATE TABLE IF NOT EXISTS `Pet` (
  `idPet` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL,
  PRIMARY KEY (`idPet`),
  KEY `id` (`idPet`),
  KEY `idPet` (`idPet`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Donation`
--
ALTER TABLE `Donation`
  ADD CONSTRAINT `donation_ibfk_1` FOREIGN KEY (`idPatron`) REFERENCES `patron` (`idPatron`) ON DELETE CASCADE,
  ADD CONSTRAINT `donation_ibfk_2` FOREIGN KEY (`idPet`) REFERENCES `pet` (`idPet`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
