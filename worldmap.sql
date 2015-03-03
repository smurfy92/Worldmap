-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Client :  localhost:8889
-- Généré le :  Mar 03 Mars 2015 à 15:10
-- Version du serveur :  5.5.34
-- Version de PHP :  5.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `worldmap`
--

-- --------------------------------------------------------

--
-- Structure de la table `town`
--

CREATE TABLE `town` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `zone` varchar(255) NOT NULL,
  `lng` float NOT NULL,
  `lat` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Contenu de la table `town`
--

INSERT INTO `town` (`id`, `city`, `country`, `zone`, `lng`, `lat`) VALUES
(1, 'Nice , France', 'FR', 'Europe', 7.26195, 43.7102),
(2, 'Paris , France', 'FR', 'Europe', 2.35222, 48.8566),
(3, 'Angers , France', 'FR', 'Europe', -0.563166, 47.4784),
(4, 'Manchester, United Kingdom', 'GB', 'Europe', -2.24263, 53.4808),
(5, 'London, United Kingdom', 'GB', 'Europe', -0.127758, 51.5074),
(6, 'Nantes , France', 'FR', 'Europe', -1.55362, 47.2184),
(7, 'Rennes , France', 'FR', 'Europe', -1.67779, 48.1173),
(8, 'Munich , Germany', 'DE', 'Europe', 11.582, 48.1351),
(9, 'Saint-Brieuc , France', 'FR', 'Europe', -2.76584, 48.5142),
(10, 'Tours , France', 'FR', 'Europe', 0.68484, 47.3941),
(11, 'Chennai , India', 'IN', 'Asia', 80.2707, 13.0827),
(12, 'ChambYory , France', 'FR', 'Europe', 5.91778, 45.5646),
(13, 'Guadalajara , Mexico', 'MX', 'Americas', -103.35, 20.6597),
(14, 'Sydney , Australia', 'AU', 'Oceania', 151.207, -33.8675),
(15, 'Rouen , France', 'FR', 'Europe', 1.09997, 49.4432),
(16, 'Geneva , Switzerland', 'CH', 'Europe', 6.1423, 46.1984),
(17, 'Lyon , France', 'FR', 'Europe', 4.83566, 45.764),
(18, 'Barcelona , Spain', 'ES', 'Europe', 2.1734, 41.3851),
(19, 'El Paso, Texas ', 'US', 'Americas', -106.442, 31.7776),
(20, 'Montpellier , France', 'FR', 'Europe', 3.87672, 43.6108),
(21, 'Southend on Sea, United Kingdom', 'GB', 'Europe', 0.707712, 51.5459),
(22, 'Freiburg , Germany', 'DE', 'Europe', 7.8421, 47.999),
(23, 'Lille , France', 'FR', 'Europe', 3.05726, 50.6292),
(24, 'OrlYoans , France', 'FR', 'Europe', 1.90925, 47.903),
(25, 'Montreal, Canada ', 'CA', 'Americas', -73.5673, 45.5017),
(26, 'Dijon , France', 'FR', 'Europe', 5.04148, 47.322),
(27, 'Aulnay-Sous-Bois , France', 'FR', 'Europe', 2.49707, 48.9412),
(28, 'Quebec, Canada', 'CA', 'Americas', -73.5491, 52.9399);

-- --------------------------------------------------------

--
-- Structure de la table `town2`
--

CREATE TABLE `town2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(255) NOT NULL,
  `zone` varchar(255) NOT NULL,
  `lng` float NOT NULL,
  `lat` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Contenu de la table `town2`
--

INSERT INTO `town2` (`id`, `country`, `zone`, `lng`, `lat`) VALUES
(1, 'Ireland', 'Europe', -8.24389, 53.4129),
(2, 'Monaco ', 'Europe', 7.42462, 43.7384),
(3, 'France', 'Europe', 2.21375, 46.2276),
(4, 'India', 'Asia', 78.9629, 20.5937),
(5, 'San Francisco Bay ', 'Americas', -122.311, 37.691),
(6, 'Serbia', 'Europe', 21.0059, 44.0165),
(7, ' New York City ', 'Americas', -74.0059, 40.7128),
(8, 'Russian Federation', 'Europe', 105.319, 61.524),
(9, 'Tunisia', 'Africa', 9.5375, 33.8869),
(10, ' Los Angeles ', 'Americas', -118.244, 34.0522),
(11, 'Croatia', 'Europe', 15.2, 45.1),
(12, 'United Kingdom', 'Europe', -2.74278, 54.9714),
(13, 'Israel', 'Asia', 34.8516, 31.0461),
(14, ' Philadelphia ', 'Americas', -75.1652, 39.9526),
(15, 'Singapore', 'Asia', 103.82, 1.35208);
