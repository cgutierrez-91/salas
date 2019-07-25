
CREATE TABLE `reservaciones` (
  `reservacion_id` int(11) NOT NULL AUTO_INCREMENT,
  `sala` int(11) NOT NULL,
  `cuenta` int(11) NOT NULL,
  `f_reserva` datetime NOT NULL,
  `f_uso_desde` datetime NOT NULL,
  `f_uso_hasta` datetime NOT NULL,
  `estado` enum('Pendiente','Autorizado','Rechazado','Cancelado') NOT NULL DEFAULT 'Pendiente',
  `observaciones` text NOT NULL,
  PRIMARY KEY (`reservacion_id`),
  KEY `sala` (`sala`),
  KEY `cuenta` (`cuenta`),
  CONSTRAINT `reservaciones_ibfk_1` FOREIGN KEY (`sala`) REFERENCES `salas` (`sala_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `reservaciones_ibfk_2` FOREIGN KEY (`cuenta`) REFERENCES `cuentas` (`cuenta_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;


--
-- Dumping data for table `reservaciones`
--

LOCK TABLES `reservaciones` WRITE;
INSERT INTO `reservaciones` VALUES (1,3,1000,'2018-11-16 21:06:53','2018-12-12 08:00:00','2018-12-12 11:59:59','Autorizado',''),(2,3,1004,'2018-11-16 21:24:01','2018-11-17 14:00:00','2018-11-17 15:59:59','Pendiente',''),(3,1,1000,'2018-11-18 17:53:40','2018-11-22 07:00:00','2018-11-22 14:59:59','Rechazado',''),(4,1,1000,'2018-11-18 17:55:44','2018-11-23 10:00:00','2018-11-23 11:59:59','Autorizado',''),(5,3,1004,'2018-11-25 01:52:03','2018-11-30 11:00:00','2018-11-30 13:59:59','Cancelado',''),(6,1,1004,'2018-11-25 01:52:33','2018-11-29 13:00:00','2018-11-29 14:59:59','Autorizado',''),(7,1,1000,'2018-11-25 22:40:55','2018-11-28 06:00:00','2018-11-28 06:29:59','Pendiente',''),(8,1,1000,'2018-11-25 22:41:14','2018-12-19 10:30:00','2018-12-19 11:59:59','Pendiente',''),(9,1,1000,'2018-11-25 22:41:24','2018-12-28 12:00:00','2018-12-28 13:29:59','Rechazado',''),(10,3,1004,'2018-11-25 22:41:58','2018-11-30 10:30:00','2018-11-30 10:59:59','Pendiente',''),(12,3,1004,'2018-11-25 22:42:28','2018-12-21 13:00:00','2018-12-21 13:59:59','Rechazado',''),(13,3,1004,'2018-11-25 22:42:37','2018-12-29 12:30:00','2018-12-29 15:59:59','Pendiente','');

