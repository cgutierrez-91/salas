-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-07-2019 a las 22:38:05
-- Versión del servidor: 10.1.37-MariaDB
-- Versión de PHP: 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `reservaciones`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas`
--

CREATE TABLE `cuentas` (
  `cuenta_id` int(11) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `clave` varchar(40) NOT NULL,
  `salt` varchar(40) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nombre` varchar(200) NOT NULL,
  `nivel` enum('admin','usuario') DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `cuentas`
--

INSERT INTO `cuentas` (`cuenta_id`, `usuario`, `clave`, `salt`, `email`, `nombre`, `nivel`) VALUES
(1000, 'reservadmin', '74f0723da38b1437cf56db45dc3120704145aef3', 'e04d55ccde0800bf2b1766ec088d79420709c599', 'cgutierrezmh@gmail.com', 'César Gutiérrez G.', 'admin'),
(1001, 'usuario', '19e03697991b61a78e7dec85d767c14e773ef67d', '2e84506b496dfbf6ad448488bc3704ff39b6bb87', 'correo@gmail.com', 'Usuario Prueba', 'usuario'),
(1002, 'javier', 'b35d33f5080ad1a026610fd39ce046e9454e1838', 'a38ad0e83cecc5fb6fe2e3544ff1d13c026f064b', 'ppagos3@campestre.com.sv', 'Javier Canales', 'usuario'),
(1003, 'joaquin', '6d66a330829a7c57d1860348b2fe84d64a036513', '99c218d2dfb1a5b19a79f861836e794d6ec191cf', 'joaquinbernal@campestre.com.sv', 'Joaquin Bernal', 'usuario'),
(1004, 'milena', 'fa958daeb03fd0e099d1bd1ebd7a669136fcb7a4', '7782e8cd86b75584c550b444060897aab25e2144', 'cesaruchiha69@gmail.com', 'Milena G', 'usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservaciones`
--

CREATE TABLE `reservaciones` (
  `reservacion_id` int(11) NOT NULL,
  `sala` int(11) NOT NULL,
  `cuenta` int(11) NOT NULL,
  `f_reserva` datetime NOT NULL,
  `f_uso_desde` datetime NOT NULL,
  `f_uso_hasta` datetime NOT NULL,
  `estado` enum('Pendiente','Autorizado','Rechazado','Cancelado') NOT NULL DEFAULT 'Pendiente',
  `observaciones` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salas`
--

CREATE TABLE `salas` (
  `sala_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `proyector` tinyint(1) NOT NULL,
  `aire` tinyint(1) NOT NULL,
  `otros` text NOT NULL,
  `imagen` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  ADD PRIMARY KEY (`cuenta_id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `reservaciones`
--
ALTER TABLE `reservaciones`
  ADD PRIMARY KEY (`reservacion_id`),
  ADD KEY `sala` (`sala`),
  ADD KEY `cuenta` (`cuenta`);

--
-- Indices de la tabla `salas`
--
ALTER TABLE `salas`
  ADD PRIMARY KEY (`sala_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cuentas`
--
ALTER TABLE `cuentas`
  MODIFY `cuenta_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1005;

--
-- AUTO_INCREMENT de la tabla `reservaciones`
--
ALTER TABLE `reservaciones`
  MODIFY `reservacion_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `salas`
--
ALTER TABLE `salas`
  MODIFY `sala_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reservaciones`
--
ALTER TABLE `reservaciones`
  ADD CONSTRAINT `reservaciones_ibfk_1` FOREIGN KEY (`sala`) REFERENCES `salas` (`sala_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservaciones_ibfk_2` FOREIGN KEY (`cuenta`) REFERENCES `cuentas` (`cuenta_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
