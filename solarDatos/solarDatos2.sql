-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 10-05-2012 a las 09:49:59
-- Versión del servidor: 5.1.49
-- Versión de PHP: 5.3.3-7+squeeze8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `wordpress`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solarDatos2`
--
-- Creación: 10-05-2012 a las 09:45:56
-- Última actualización: 10-05-2012 a las 09:45:56
--

DROP TABLE IF EXISTS `solarDatos2`;
CREATE TABLE IF NOT EXISTS `solarDatos2` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `BattV` float NOT NULL,
  `Temp_TC` float NOT NULL,
  `rh` float NOT NULL,
  `Rad_W` float NOT NULL,
  `Rad_kJ_Tot` float NOT NULL,
  PRIMARY KEY (`timestamp`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
