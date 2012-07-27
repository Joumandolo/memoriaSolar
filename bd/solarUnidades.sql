-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- Servidor: 50.63.244.83
-- Tiempo de generación: 24-07-2012 a las 06:54:00
-- Versión del servidor: 5.0.92
-- Versión de PHP: 5.1.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `solar`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solarUnidades`
--
-- Creación: 10-05-2012 a las 10:45:41
-- Última actualización: 10-05-2012 a las 14:17:53
--

CREATE TABLE `solarUnidades` (
  `id` int(11) NOT NULL auto_increment,
  `campo` varchar(30) default NULL,
  `etiqueta` varchar(200) NOT NULL,
  `unidad` varchar(30) default NULL,
  `formato` varchar(30) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Volcar la base de datos para la tabla `solarUnidades`
--

INSERT INTO `solarUnidades` VALUES(1, 'timestamp', 'UTC', 's', 'yyyy-mm-dd hh-mm-ss');
INSERT INTO `solarUnidades` VALUES(2, 'BattV', 'Carga', 'Volt', '%f.2');
INSERT INTO `solarUnidades` VALUES(3, 'Temp_TC', 'Tamb', 'C', '%f.2');
INSERT INTO `solarUnidades` VALUES(4, 'rh', 'HR', '%', '%f.2');
INSERT INTO `solarUnidades` VALUES(5, 'Rad_W', 'GHI', 'W/m^2 ', '%f.1');
INSERT INTO `solarUnidades` VALUES(6, 'Rad_kJ_Tot', 'GHI', 'kJ/m^2', '%f.2');
