-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 07-02-2020 a las 06:37:38
-- Versión del servidor: 5.7.26
-- Versión de PHP: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dblibros`
--
CREATE DATABASE IF NOT EXISTS `dblibros` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `dblibros`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

DROP TABLE IF EXISTS `libros`;
CREATE TABLE IF NOT EXISTS `libros` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cod_libro` varchar(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `descripcion` varchar(240) NOT NULL,
  `titulo` varchar(25) NOT NULL,
  `autor` varchar(25) NOT NULL,
  `pvp` varchar(7) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO `libros` (`id`, `cod_libro`, `nombre`, `descripcion`, `titulo`, `autor`, `pvp`) VALUES
(1, '102', 'Prueba', 'es un libroddddddddddddddddddddddddddddddddddddddddddd', 'Cuentas de navidad', 'Pedro Perez', '56'),
(2, '102', 'Prueba', 'es un libroddddddddddddddddddddddddddddddddddddddddddd', 'Cuentas de navidad', 'Pedro Perez', '56'),
(3, '102', 'Prueba', 'es un libroddddddddddddddddddddddddddddddddddddddddddd', 'Cuentas de navidad', 'Pedro Perez', '56'),
(4, '102', 'Prueba', 'es un libroddddddddddddddddddddddddddddddddddddddddddd', 'Cuentas de navidad', 'Pedro Perez', '56'),
(5, '102', 'Prueba', 'es un libroddddddddddddddddddddddddddddddddddddddddddd', 'Cuentas de navidad', 'Pedro Perez', '56'),
(6, '102', 'Prueba', 'es un libroddddddddddddddddddddddddddddddddddddddddddd', 'Cuentas de navidad', 'Pedro Perez', '56'),
(7, '102', 'Prueba', 'es un libroddddddddddddddddddddddddddddddddddddddddddd', 'Cuentas de navidad', 'Pedro Perez', '56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `descripcion` varchar(245) NOT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=103 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `logs`
--

INSERT INTO `logs` (`id`, `id_usuario`, `descripcion`, `fecha`) VALUES
(1, 1, 'Ha iniciado sesion', '2020-02-07 01:27:54'),
(2, 1, 'Ha editado un usuario', '2020-02-07 01:28:33'),
(3, 1, 'Ha editado un usuario', '2020-02-07 01:29:00'),
(4, 1, 'Ha iniciado sesion', '2020-02-07 01:31:21'),
(5, 1, 'Ha editado un usuario', '2020-02-07 01:31:53'),
(6, 1, 'Ha iniciado sesion', '2020-02-07 01:50:19'),
(7, 1, 'Ha iniciado sesion', '2020-02-07 01:50:57'),
(8, 1, 'Ha editado un usuario', '2020-02-07 01:55:41'),
(9, 1, 'Ha editado un usuario', '2020-02-07 01:56:54'),
(10, 1, 'Ha iniciado sesion', '2020-02-07 01:57:25'),
(11, 1, 'Ha editado un usuario', '2020-02-07 01:57:33'),
(12, 1, 'Ha iniciado sesion', '2020-02-07 01:58:21'),
(13, 1, 'Ha iniciado sesion', '2020-02-07 01:58:51'),
(14, 1, 'Ha iniciado sesion', '2020-02-07 01:58:54'),
(15, 1, 'Ha iniciado sesion', '2020-02-07 02:13:08'),
(16, 1, 'Ha iniciado sesion', '2020-02-07 03:03:26'),
(17, 1, 'Ha iniciado sesion', '2020-02-07 03:04:59'),
(18, 1, 'Ha iniciado sesion', '2020-02-07 03:05:40'),
(20, 1, 'Ha iniciado sesion', '2020-02-07 04:11:36'),
(21, 1, 'Ha iniciado sesion', '2020-02-07 04:11:51'),
(22, 1, 'Ha iniciado sesion', '2020-02-07 04:13:14'),
(23, 1, 'Ha iniciado sesion', '2020-02-07 04:13:19'),
(24, 1, 'Ha iniciado sesion', '2020-02-07 04:32:49'),
(25, 1, 'Ha iniciado sesion', '2020-02-07 04:34:27'),
(26, 1, 'Ha iniciado sesion', '2020-02-07 04:39:21'),
(27, 1, 'Ha iniciado sesion', '2020-02-07 04:39:52'),
(28, 1, 'Ha iniciado sesion', '2020-02-07 04:40:10'),
(29, 1, 'Ha iniciado sesion', '2020-02-07 04:42:13'),
(30, 1, 'Ha iniciado sesion', '2020-02-07 04:43:45'),
(31, 1, 'Ha iniciado sesion', '2020-02-07 04:44:01'),
(32, 1, 'Ha iniciado sesion', '2020-02-07 04:44:54'),
(33, 1, 'Ha iniciado sesion', '2020-02-07 04:45:01'),
(34, 1, 'Ha iniciado sesion', '2020-02-07 04:45:36'),
(35, 1, 'Ha iniciado sesion', '2020-02-07 04:48:11'),
(36, 1, 'Ha iniciado sesion', '2020-02-07 04:52:22'),
(37, 1, 'Ha iniciado sesion', '2020-02-07 04:55:29'),
(38, 1, 'Ha iniciado sesion', '2020-02-07 04:56:56'),
(39, 1, 'Ha iniciado sesion', '2020-02-07 05:05:38'),
(40, 1, 'Ha iniciado sesion', '2020-02-07 05:05:59'),
(41, 1, 'Ha iniciado sesion', '2020-02-07 05:06:44'),
(42, 1, 'Ha iniciado sesion', '2020-02-07 05:10:42'),
(43, 1, 'Ha iniciado sesion', '2020-02-07 05:11:09'),
(44, 1, 'Ha iniciado sesion', '2020-02-07 05:11:10'),
(45, 1, 'Ha iniciado sesion', '2020-02-07 05:16:48'),
(46, 1, 'Ha iniciado sesion', '2020-02-07 05:17:01'),
(47, 1, 'Ha iniciado sesion', '2020-02-07 05:17:10'),
(48, 1, 'Ha iniciado sesion', '2020-02-07 05:17:13'),
(49, 1, 'Ha iniciado sesion', '2020-02-07 05:17:16'),
(50, 1, 'Ha iniciado sesion', '2020-02-07 05:19:10'),
(51, 1, 'Ha iniciado sesion', '2020-02-07 05:20:28'),
(52, 1, 'Ha iniciado sesion', '2020-02-07 05:21:08'),
(53, 1, 'Ha iniciado sesion', '2020-02-07 05:21:50'),
(54, 1, 'Ha iniciado sesion', '2020-02-07 05:24:02'),
(55, 1, 'Ha iniciado sesion', '2020-02-07 05:24:31'),
(56, 1, 'Ha iniciado sesion', '2020-02-07 05:45:22'),
(57, 1, 'Ha iniciado sesion', '2020-02-07 05:45:24'),
(58, 1, 'Ha iniciado sesion', '2020-02-07 05:45:30'),
(59, 1, 'Ha iniciado sesion', '2020-02-07 05:45:53'),
(60, 1, 'Ha iniciado sesion', '2020-02-07 05:46:10'),
(61, 1, 'Ha iniciado sesion', '2020-02-07 06:03:49'),
(62, 1, 'Ha iniciado sesion', '2020-02-07 06:08:23'),
(63, 1, 'Ha iniciado sesion', '2020-02-07 06:08:34'),
(64, 1, 'Ha iniciado sesion', '2020-02-07 06:08:38'),
(65, 1, 'Ha iniciado sesion', '2020-02-07 06:08:49'),
(66, 1, 'Ha iniciado sesion', '2020-02-07 06:10:01'),
(67, 1, 'Ha iniciado sesion', '2020-02-07 06:10:06'),
(68, 1, 'Ha iniciado sesion', '2020-02-07 06:10:10'),
(69, 1, 'Ha iniciado sesion', '2020-02-07 06:10:26'),
(70, 1, 'Ha iniciado sesion', '2020-02-07 06:10:29'),
(71, 1, 'Ha editado un usuario', '2020-02-07 06:10:40'),
(72, 1, 'Ha editado un usuario', '2020-02-07 06:16:13'),
(73, 1, 'Ha iniciado sesion', '2020-02-07 06:16:47'),
(74, 1, 'Ha iniciado sesion', '2020-02-07 06:22:23'),
(75, 1, 'Ha iniciado sesion', '2020-02-07 06:22:52'),
(76, 1, 'Ha iniciado sesion', '2020-02-07 06:23:46'),
(77, 1, 'Ha iniciado sesion', '2020-02-07 06:23:50'),
(78, 1, 'Ha iniciado sesion', '2020-02-07 06:23:52'),
(79, 1, 'Ha iniciado sesion', '2020-02-07 06:23:59'),
(80, 1, 'Ha iniciado sesion', '2020-02-07 06:24:05'),
(81, 1, 'Ha iniciado sesion', '2020-02-07 06:30:49'),
(82, 1, 'Ha iniciado sesion', '2020-02-07 06:32:31'),
(83, 1, 'Ha iniciado sesion', '2020-02-07 06:32:35'),
(84, 1, 'Ha iniciado sesion', '2020-02-07 06:43:27'),
(85, 1, 'Ha iniciado sesion', '2020-02-07 06:43:29'),
(86, 1, 'Ha iniciado sesion', '2020-02-07 06:43:36'),
(87, 1, 'Ha iniciado sesion', '2020-02-07 06:49:21'),
(88, 1, 'Ha iniciado sesion', '2020-02-07 06:49:27'),
(89, 1, 'Ha iniciado sesion', '2020-02-07 06:51:08'),
(90, 1, 'Ha iniciado sesion', '2020-02-07 06:51:10'),
(91, 1, 'Ha iniciado sesion', '2020-02-07 06:51:24'),
(92, 1, 'Ha iniciado sesion', '2020-02-07 06:51:29'),
(93, 1, 'Ha iniciado sesion', '2020-02-07 06:57:14'),
(94, 1, 'Ha iniciado sesion', '2020-02-07 06:58:21'),
(95, 1, 'Ha iniciado sesion', '2020-02-07 06:58:24'),
(96, 1, 'Ha iniciado sesion', '2020-02-07 07:01:09'),
(97, 1, 'Ha iniciado sesion', '2020-02-07 07:05:24'),
(98, 1, 'Ha iniciado sesion', '2020-02-07 07:08:20'),
(99, 1, 'Ha iniciado sesion', '2020-02-07 07:08:55'),
(100, 1, 'Ha iniciado sesion', '2020-02-07 07:09:51'),
(101, 1, 'Ha iniciado sesion', '2020-02-07 07:09:54'),
(102, 1, 'Ha iniciado sesion', '2020-02-07 07:15:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(30) NOT NULL,
  `password` varchar(230) NOT NULL,
  `email` varchar(30) NOT NULL,
  `rol` varchar(30) NOT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario` (`usuario`),
  UNIQUE KEY `correo` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password`, `email`, `rol`, `fecha`) VALUES
(1, 'root', '9d6d4a31bc7e26ec4b826d8e6a2afdbacd95341a', 'mirkohijo@gmail.com', 'administrador', '2020-02-06 04:14:39'),
(3, 'user', '9d6d4a31bc7e26ec4b826d8e6a2afdbacd95341a', 'mirkohijo2@gmail.com', 'registrado', '2020-02-06 04:14:39');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
