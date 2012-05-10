-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 10-05-2012 a las 09:51:01
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
-- Estructura de tabla para la tabla `solarUnidades`
--
-- Creación: 16-04-2012 a las 13:25:17
-- Última actualización: 16-04-2012 a las 13:27:00
--

DROP TABLE IF EXISTS `solarUnidades`;
CREATE TABLE IF NOT EXISTS `solarUnidades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campo` varchar(30) DEFAULT NULL,
  `etiqueta` varchar(200) NOT NULL,
  `unidad` varchar(30) DEFAULT NULL,
  `formato` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Volcar la base de datos para la tabla `solarUnidades`
--

INSERT INTO `solarUnidades` (`id`, `campo`, `etiqueta`, `unidad`, `formato`) VALUES
(1, 'timestamp', 'Fecha', 's', 'yyyy-mm-dd hh-mm-ss'),
(2, 'BattV', 'Carga', 'Volt', '%f.2'),
(3, 'Temp_TC', 'Tamb', 'C', '%f.2'),
(4, 'rh', 'HR', '%', '%f.2'),
(5, 'Rad_W', 'GHI', 'W/m^2 ', '%f.1'),
(6, 'Rad_kJ_Tot', 'GHI', 'kJ/m^2', '%f.2');
