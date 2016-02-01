-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-06-2013 a las 01:31:22
-- Versión del servidor: 5.5.27
-- Versión de PHP: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `rv3d`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas`
--

CREATE TABLE IF NOT EXISTS `notas` (
  `notaID` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `fechaTest` datetime NOT NULL,
  `nota` float NOT NULL,
  PRIMARY KEY (`notaID`),
  KEY `usuario` (`usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `problemas`
--

CREATE TABLE IF NOT EXISTS `problemas` (
  `problemaID` int(11) NOT NULL AUTO_INCREMENT,
  `enunciado` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `tipoOperacion` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `numeroVectores` int(11) NOT NULL,
  PRIMARY KEY (`problemaID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=25 ;

--
-- Volcado de datos para la tabla `problemas`
--

INSERT INTO `problemas` (`problemaID`, `enunciado`, `tipoOperacion`, `numeroVectores`) VALUES
(1, 'Sea vector v = (x1,y1,z1) y vector w = (x2,y2,z2). Calcular el vector suma resultante.', 'suma', 2),
(2, 'Sea vector v = (x1,y1,z1) y vector w = (x2,y2,z2). Calcular el vector resta resultante.', 'resta', 2),
(3, 'Sea vector v = (x1,y1,z1) y vector w = (x2,y2,z2). Calcular el producto vectorial de ambos.', 'productoVectorial', 2),
(4, 'Sea vector u = (x1,y1,z1), vector v = (x2,y2,z2) y vector w = (x3,y3,z3). Calcular su producto vectorial.', 'productoVectorial', 3),
(5, 'Sea vector u = (x1,y1,z1), vector v = (x2,y2,z2) y vector w = (x3,y3,z3). Calcular el vector suma resultante.', 'suma', 3),
(6, 'Sea vector u = (x1,y1,z1), vector v = (x2,y2,z2) y vector w = (x3,y3,z3). Calcular el vector resta resultante.', 'resta', 3),
(7, 'Sea vector u = (x1,y1,z1), vector v = (x2,y2,z2) y vector w = (x3,y3,z3). Calcular su producto mixto.', 'productMixto', 3),
(8, 'Sea vector v = (x1,y1,z1) y vector w = (x2,y2,z2). Calcular su producto escalar.', 'productoEscalar', 2),
(9, 'Sea vector v = (x1,y1,z1) y vector w = (x2,y2,z2). Calcular el ángulo, en radianes, que forman (Redondea la solución a 2 decimales).', 'anguloRadianes', 2),
(10, 'Sea vector v = (x1,y1,z1) y vector w = (x2,y2,z2). Calcular el ángulo, en grados, que forman (Redondea la solución a 2 decimales).', 'anguloGrados', 2),
(11, 'Sea vector v = (x1,y1,z1). Calcular el ángulo, en grados, que forma con el eje x positivo (Redondea la solución a 2 decimales).', 'anguloGradosX+', 2),
(12, 'Sea vector v = (x1,y1,z1). Calcular el ángulo, en grados, que forma con el eje x negativo (Redondea la solución a 2 decimales).', 'anguloGradosX-', 2),
(13, 'Sea vector v = (x1,y1,z1). Calcular el ángulo, en grados, que forma con el eje y positivo (Redondea la solución a 2 decimales).', 'anguloGradosY+', 2),
(14, 'Sea vector v = (x1,y1,z1). Calcular el ángulo, en grados, que forma con el eje y negativo (Redondea la solución a 2 decimales).', 'anguloGradosY-', 2),
(15, 'Sea vector v = (x1,y1,z1). Calcular el ángulo, en grados, que forma con el eje z positivo (Redondea la solución a 2 decimales).', 'anguloGradosZ+', 2),
(16, 'Sea vector v = (x1,y1,z1). Calcular el ángulo, en grados, que forma con el eje z negativo (Redondea la solución a 2 decimales).', 'anguloGradosZ-', 2),
(17, 'Sea vector v = (x1,y1,z1). Calcular el ángulo, en radianes, que forma con el eje x positivo (Redondea la solución a 2 decimales).', 'anguloRadianesX+', 2),
(18, 'Sea vector v = (x1,y1,z1). Calcular el ángulo, en radianes, que forma con el eje x negativo (Redondea la solución a 2 decimales).', 'anguloRadianesX-', 2),
(19, 'Sea vector v = (x1,y1,z1). Calcular el ángulo, en radianes, que forma con el eje y positivo (Redondea la solución a 2 decimales).', 'anguloRadianesY+', 2),
(20, 'Sea vector v = (x1,y1,z1). Calcular el ángulo, en radianes, que forma con el eje y negativo (Redondea la solución a 2 decimales).', 'anguloRadianesY-', 2),
(21, 'Sea vector v = (x1,y1,z1). Calcular el ángulo, en radianes, que forma con el eje z positivo (Redondea la solución a 2 decimales).', 'anguloRadianesZ+', 2),
(22, 'Sea vector v = (x1,y1,z1). Calcular el ángulo, en radianes, que forma con el eje z negativo (Redondea la solución a 2 decimales).', 'anguloRadianesZ-', 2),
(23, 'Sea vector v = (x1,y1,z1) y vector w = (x2,y2,z2), calcular el área del paralelogramo que forman (Redondea la solución a 2 decimales).', 'area', 2),
(24, 'Sea vector u = (x1,y1,z1), vector v = (x2,y2,z2) y vector w = (x3,y3,z3), calcular el volumen del paralelepípedo que forman (Redondea la solución a 2 decimales).', 'volumen', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `personaID` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `primerApellido` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `segundoApellido` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `usuario` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `contrasena` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `rol` int(11) NOT NULL,
  `notaMasAlta` decimal(10,0) DEFAULT NULL,
  `notaMasBaja` decimal(10,0) DEFAULT NULL,
  `tiempo` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`usuario`),
  UNIQUE KEY `personaID` (`personaID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`personaID`, `nombre`, `primerApellido`, `segundoApellido`, `email`, `usuario`, `contrasena`, `rol`, `notaMasAlta`, `notaMasBaja`, `tiempo`) VALUES
(1, 'Pilar', 'Martínez', 'Jiménez', 'fa1majip@uco.es', 'profesor', 'profesor', 1, 10, 10, 1371166145);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `notas`
--
ALTER TABLE `notas`
  ADD CONSTRAINT `notas_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
