-- Fase 1: Crear la tabla para gestionar los tipos de servicio que ofrece el hotel.
-- Aquí se define el catálogo de servicios con sus precios en CLP.
CREATE TABLE IF NOT EXISTS `tipos_servicio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Fase 2: Borrar la tabla 'servicios' antigua para reemplazarla.
-- Esto es seguro porque vamos a crear una nueva estructura mejorada.
DROP TABLE IF EXISTS `servicios`;

-- Fase 3: Crear la nueva tabla 'servicios' que registrará cada servicio asignado a una reserva.
CREATE TABLE `servicios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reserva_id` int(11) NOT NULL,
  `tipo_servicio_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `precio_unitario` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `reserva_id` (`reserva_id`),
  KEY `tipo_servicio_id` (`tipo_servicio_id`),
  CONSTRAINT `servicios_ibfk_1` FOREIGN KEY (`reserva_id`) REFERENCES `reservas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `servicios_ibfk_2` FOREIGN KEY (`tipo_servicio_id`) REFERENCES `tipos_servicio` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Fase 4: Poblar la tabla de tipos de servicio con algunos ejemplos en CLP.
-- Esto nos permitirá probar la funcionalidad inmediatamente.
INSERT INTO `tipos_servicio` (`nombre`, `precio`) VALUES
('Servicio a la Habitación', 15000),
('Lavandería (por pieza)', 5000),
('Acceso a Spa', 30000),
('Cama Extra', 25000),
('Botella de Vino', 20000); 