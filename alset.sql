-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 28, 2011 at 03:21 
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `alset`
--

-- --------------------------------------------------------

--
-- Table structure for table `favoriti`
--

DROP TABLE IF EXISTS `favoriti`;
CREATE TABLE IF NOT EXISTS `favoriti` (
  `email` varchar(60) NOT NULL,
  `artikal` varchar(30) NOT NULL,
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `kontakt`
--

DROP TABLE IF EXISTS `kontakt`;
CREATE TABLE IF NOT EXISTS `kontakt` (
  `tel1` varchar(20) DEFAULT NULL,
  `tel2` varchar(20) DEFAULT NULL,
  `mob1` varchar(20) DEFAULT NULL,
  `mob2` varchar(20) DEFAULT NULL,
  `mob3` varchar(20) DEFAULT NULL,
  `email1` varchar(60) NOT NULL,
  `email2` varchar(60) DEFAULT NULL,
  `adresa1` varchar(50) DEFAULT NULL,
  `adresa2` varchar(50) DEFAULT NULL,
  `grad` varchar(30) NOT NULL,
  `KID` varchar(50) NOT NULL,
  PRIMARY KEY (`KID`),
  KEY `email1` (`email1`),
  KEY `KID` (`KID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `korisnici`
--

DROP TABLE IF EXISTS `korisnici`;
CREATE TABLE IF NOT EXISTS `korisnici` (
  `email` varchar(60) NOT NULL,
  `ime` varchar(15) NOT NULL,
  `prezime` varchar(25) DEFAULT NULL,
  `grad` varchar(15) NOT NULL,
  `telefon` varchar(15) DEFAULT NULL,
  `data_na_raganje` date DEFAULT NULL,
  `smetka` varchar(22) DEFAULT NULL,
  `karticka` varchar(15) DEFAULT NULL,
  `adresa` varchar(30) DEFAULT NULL,
  `lozinka` varchar(33) NOT NULL,
  `avatar` varchar(128) DEFAULT '0',
  `validacija` varchar(33) NOT NULL,
  `pol` tinyint(1) NOT NULL,
  `acc` char(1) DEFAULT NULL,
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `logovi`
--

DROP TABLE IF EXISTS `logovi`;
CREATE TABLE IF NOT EXISTS `logovi` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `tip` varchar(10) NOT NULL,
  `info` text NOT NULL,
  `data` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Table structure for table `man_sesija`
--

DROP TABLE IF EXISTS `man_sesija`;
CREATE TABLE IF NOT EXISTS `man_sesija` (
  `ses_kluc` varchar(32) NOT NULL DEFAULT '',
  `vreme_traenje` int(10) unsigned NOT NULL DEFAULT '0',
  `vrednost` text NOT NULL,
  PRIMARY KEY (`ses_kluc`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `naracka`
--

DROP TABLE IF EXISTS `naracka`;
CREATE TABLE IF NOT EXISTS `naracka` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(60) NOT NULL,
  `artikal` varchar(40) NOT NULL,
  `kolicina` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `email_2` (`email`,`artikal`),
  FULLTEXT KEY `email` (`email`),
  FULLTEXT KEY `artikal` (`artikal`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pretprijatie`
--

DROP TABLE IF EXISTS `pretprijatie`;
CREATE TABLE IF NOT EXISTS `pretprijatie` (
  `danocen_br` int(22) DEFAULT '0',
  `ime_pret` varchar(30) NOT NULL,
  `tip_pret` varchar(30) DEFAULT NULL,
  `slika` varchar(128) DEFAULT NULL,
  `validacija` varchar(33) NOT NULL,
  `lozinka` varchar(33) NOT NULL,
  `odobreno` tinyint(1) DEFAULT NULL,
  `user` varchar(60) NOT NULL,
  PRIMARY KEY (`ime_pret`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `prod1`
--

DROP TABLE IF EXISTS `prod1`;
CREATE TABLE IF NOT EXISTS `prod1` (
  `artikal` varchar(25) NOT NULL,
  `od_ime_pret` varchar(30) NOT NULL,
  `cena` int(11) NOT NULL,
  `model` char(1) NOT NULL,
  `velicina` varchar(5) NOT NULL,
  `kolicina` int(11) NOT NULL,
  `boja` varchar(15) NOT NULL,
  `materijal` varchar(30) DEFAULT NULL,
  `marka` varchar(20) NOT NULL,
  `sezona` varchar(1) NOT NULL,
  `od_tip_pret` varchar(20) NOT NULL,
  `tip_prod` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `prod2`
--

DROP TABLE IF EXISTS `prod2`;
CREATE TABLE IF NOT EXISTS `prod2` (
  `artikal` varchar(30) NOT NULL,
  `od_ime_pret` varchar(30) NOT NULL,
  `od_tip_pret` varchar(20) NOT NULL,
  `cena` int(11) NOT NULL,
  `vid` varchar(10) NOT NULL,
  `boja` varchar(15) NOT NULL,
  `cirkoni` varchar(1) NOT NULL,
  `golemina` varchar(10) NOT NULL,
  `kolicina` int(11) NOT NULL,
  `tezina` int(11) NOT NULL,
  `marka` varchar(20) NOT NULL,
  `tip_prod` int(11) NOT NULL,
  PRIMARY KEY (`artikal`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `prod3`
--

DROP TABLE IF EXISTS `prod3`;
CREATE TABLE IF NOT EXISTS `prod3` (
  `artikal` varchar(30) NOT NULL,
  `od_ime_pret` varchar(30) NOT NULL,
  `od_tip_pret` varchar(20) NOT NULL,
  `brend` varchar(30) NOT NULL,
  `cena` int(11) NOT NULL,
  `gramaza` int(11) NOT NULL,
  `boja` varchar(15) NOT NULL,
  `nijansa` varchar(20) NOT NULL,
  `tip_prod` varchar(30) NOT NULL,
  PRIMARY KEY (`artikal`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `produkti`
--

DROP TABLE IF EXISTS `produkti`;
CREATE TABLE IF NOT EXISTS `produkti` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `od_ime_pret` varchar(60) NOT NULL,
  `cena` varchar(11) DEFAULT NULL,
  `tip_prod` varchar(512) NOT NULL,
  `slika` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `pubs`
--

DROP TABLE IF EXISTS `pubs`;
CREATE TABLE IF NOT EXISTS `pubs` (
  `od_ime_pret` varchar(30) NOT NULL,
  `od_tip_pret` varchar(30) NOT NULL,
  `meni_id` int(11) NOT NULL,
  `nastan` int(11) NOT NULL,
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `search`
--

DROP TABLE IF EXISTS `search`;
CREATE TABLE IF NOT EXISTS `search` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ime` varchar(40) NOT NULL,
  `tabela` varchar(20) NOT NULL,
  `keyword` varchar(1024) NOT NULL,
  `info` varchar(2048) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ime` (`ime`),
  FULLTEXT KEY `keyword` (`keyword`),
  FULLTEXT KEY `info` (`info`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
