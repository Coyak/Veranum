-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para veranum
CREATE DATABASE IF NOT EXISTS `veranum` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `veranum`;

-- Volcando estructura para tabla veranum.clientes
CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('cliente','admin','recepcionista','cocinero','gerente') NOT NULL DEFAULT 'cliente',
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla veranum.clientes: ~6 rows (aproximadamente)
INSERT INTO `clientes` (`id`, `nombre`, `email`, `password`, `role`, `creado_en`) VALUES
	(1, 'admin', 'admin@admin.com', '$2y$10$f1ccF6QNgXFN0I0mBcGHse1gMTEfMA3Qb96hJxFfr/xAcDxXFDuQC', 'admin', '2025-06-21 03:14:24'),
	(6, 'Pepito Recp', 'recp2@veranum.test', '$2y$10$A2s0mlzC3Xtvg2xCcNZ1fexxGbuLY80kflhUzVKpVV.0xh4AJvqAu', 'recepcionista', '2025-06-22 21:33:43'),
	(7, 'Pedrito', 'cliente@veranum.test', '$2y$10$viPz.DwgEHC40i5P8xk.X.tKgBTEMf5mj3mXhtoBcgJi9Nx8uUyXa', 'cliente', '2025-06-22 22:05:22'),
	(8, 'asd', 'asda@gmail.com', '$2y$10$jbkL/EvUTY7/F6WHWTTf7.H2HaG4duto71MGmJblq4on0HlRxuDV6', 'cocinero', '2025-06-22 23:05:59'),
	(9, 'Administrador', 'admin@veranum.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-06-22 23:12:07'),
	(10, 'Angel', 'a@a.com', '$2y$10$Qkt3XxuN5Ht4DwVHvGju8.f5KChEgfdAzUMVwTVbymfZx5qbvGw16', 'cliente', '2025-06-22 23:14:13');

-- Volcando estructura para tabla veranum.habitaciones
CREATE TABLE IF NOT EXISTS `habitaciones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `hotel_id` int NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `foto` varchar(255) DEFAULT '',
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `hotel_id` (`hotel_id`),
  CONSTRAINT `habitaciones_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hoteles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla veranum.habitaciones: ~2 rows (aproximadamente)
INSERT INTO `habitaciones` (`id`, `hotel_id`, `nombre`, `precio`, `foto`, `creado_en`) VALUES
	(2, 11, 'Angel', 3000.00, '', '2025-06-22 20:43:29'),
	(3, 11, 'asd', 2000.00, '', '2025-06-22 22:12:56'),
	(4, 11, 'Suit Presidencial', 7000000.00, '', '2025-06-23 03:27:42');

-- Volcando estructura para tabla veranum.hoteles
CREATE TABLE IF NOT EXISTS `hoteles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla veranum.hoteles: ~1 rows (aproximadamente)
INSERT INTO `hoteles` (`id`, `nombre`, `creado_en`) VALUES
	(11, 'Hotel Puerto Montt', '2025-06-22 19:29:02');

-- Volcando estructura para tabla veranum.insumos
CREATE TABLE IF NOT EXISTS `insumos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unidad` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock_actual` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla veranum.insumos: ~0 rows (aproximadamente)

-- Volcando estructura para tabla veranum.movimientos_insumo
CREATE TABLE IF NOT EXISTS `movimientos_insumo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_insumo` int NOT NULL,
  `tipo_movimiento` enum('ingreso','consumo') COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `observacion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_insumo` (`id_insumo`),
  CONSTRAINT `movimientos_insumo_ibfk_1` FOREIGN KEY (`id_insumo`) REFERENCES `insumos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla veranum.movimientos_insumo: ~0 rows (aproximadamente)

-- Volcando estructura para tabla veranum.reservas
CREATE TABLE IF NOT EXISTS `reservas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int NOT NULL,
  `habitacion_id` int NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `status` enum('pendiente','checkin','checkout') CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'pendiente',
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `habitacion_id` (`habitacion_id`),
  CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`habitacion_id`) REFERENCES `habitaciones` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

-- Volcando datos para la tabla veranum.reservas: ~2 rows (aproximadamente)
INSERT INTO `reservas` (`id`, `cliente_id`, `habitacion_id`, `fecha_inicio`, `fecha_fin`, `status`, `creado_en`) VALUES
	(2, 10, 2, '2025-06-23', '2025-06-24', 'checkin', '2025-06-23 01:27:00'),
	(3, 10, 3, '2025-06-23', '2025-06-24', 'checkin', '2025-06-23 01:31:14'),
	(4, 10, 4, '2025-06-23', '2025-06-24', 'pendiente', '2025-06-23 03:28:08');

-- Volcando estructura para tabla veranum.servicios
CREATE TABLE IF NOT EXISTS `servicios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reserva_id` int NOT NULL,
  `tipo_servicio_id` int NOT NULL,
  `cantidad` int NOT NULL DEFAULT '1',
  `precio_unitario` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `reserva_id` (`reserva_id`),
  KEY `tipo_servicio_id` (`tipo_servicio_id`),
  CONSTRAINT `servicios_ibfk_1` FOREIGN KEY (`reserva_id`) REFERENCES `reservas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `servicios_ibfk_2` FOREIGN KEY (`tipo_servicio_id`) REFERENCES `tipos_servicio` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla veranum.servicios: ~4 rows (aproximadamente)
INSERT INTO `servicios` (`id`, `reserva_id`, `tipo_servicio_id`, `cantidad`, `precio_unitario`, `created_at`) VALUES
	(13, 2, 3, 1, 25000.00, '2025-06-23 03:19:52'),
	(14, 2, 5, 1, 20000.00, '2025-06-23 03:19:52'),
	(15, 3, 3, 1, 25000.00, '2025-06-23 03:21:36'),
	(16, 3, 5, 1, 20000.00, '2025-06-23 03:21:36'),
	(17, 3, 4, 1, 25000.00, '2025-06-23 03:21:36');

-- Volcando estructura para tabla veranum.tipos_servicio
CREATE TABLE IF NOT EXISTS `tipos_servicio` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla veranum.tipos_servicio: ~5 rows (aproximadamente)
INSERT INTO `tipos_servicio` (`id`, `nombre`, `precio`, `created_at`, `updated_at`) VALUES
	(1, 'Servicio a la Habitación', 15000.00, '2025-06-23 02:11:20', '2025-06-23 02:11:20'),
	(2, 'Lavandería (por pieza)', 5000.00, '2025-06-23 02:11:20', '2025-06-23 02:11:20'),
	(3, 'Acceso a Spa', 25000.00, '2025-06-23 02:11:20', '2025-06-23 02:27:55'),
	(4, 'Cama Extra', 25000.00, '2025-06-23 02:11:20', '2025-06-23 02:11:20'),
	(5, 'Botella de Vino', 20000.00, '2025-06-23 02:11:20', '2025-06-23 02:11:20');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
