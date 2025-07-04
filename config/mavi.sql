-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 04-07-2025 a las 19:43:35
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.3.14

-- Base de datos: `mavi`

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE DATABASE IF NOT EXISTS `mavi` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;

USE `mavi`;

DROP TABLE IF EXISTS `clientes`;
CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombres` varchar(100) NOT NULL,
  `apellido_paterno` varchar(50) NOT NULL,
  `apellido_materno` varchar(50) NOT NULL,
  `domicilio` text NOT NULL,
  `correo_electronico` varchar(100) NOT NULL,
  `estatus` enum('activo','inactivo') DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `correo_electronico` (`correo_electronico`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombres`, `apellido_paterno`, `apellido_materno`, `domicilio`, `correo_electronico`, `estatus`, `created_at`, `updated_at`) VALUES
(1, 'Juan Carlos', 'García', 'López', 'Av. Reforma 123, Col. Centro, CDMX', 'juan.garcia@email.com', 'inactivo', '2025-07-04 17:48:29', '2025-07-04 19:09:02'),
(2, 'María Elena', 'Martínez', 'Rodríguez', 'Calle Juárez 456, Col. Roma Norte, CDMX', 'maria.martinez@email.com', 'inactivo', '2025-07-04 17:48:29', '2025-07-04 19:37:47'),
(3, 'Pedro Antonio', 'Sánchez', 'Hernández', 'Blvd. Insurgentes 789, Col. Condesa, CDMX', 'pedro.sanchez@email.com', 'inactivo', '2025-07-04 17:48:29', '2025-07-04 19:20:54'),
(4, 'Ana Sofía', 'Ramírez', 'Morales', 'Calle Madero 321, Col. Polanco, CDMX', 'ana.ramirez@email.com', 'activo', '2025-07-04 17:48:29', '2025-07-04 17:48:29'),
(5, 'Carlos Eduardo', 'Torres', 'Vega', 'Av. Universidad 654, Col. Del Valle, CDMX', 'carlos.torres@email.com', 'activo', '2025-07-04 17:48:29', '2025-07-04 17:48:29'),
(7, 'Uriel Israel', 'Valencia', 'Saldaña', 'Calle San Jorge, Zapopan, Jalisco\r\n', 'urielvalencia799@email.com', 'activo', '2025-07-04 17:57:35', '2025-07-04 17:57:35'),
(8, 'María Elena', 'García', 'López', 'Av. Revolución 1234, Col. Centro, Guadalajara, Jalisco', 'maria.garcia@email.com', 'activo', '2025-07-04 18:07:52', '2025-07-04 18:07:52'),
(9, 'Carlos Alberto', 'Rodríguez', 'Martínez', 'Calle Independencia 567, Col. Americana, Guadalajara, Jalisco', 'carlos.rodriguez@email.com', 'activo', '2025-07-04 18:07:52', '2025-07-04 18:07:52'),
(10, 'Ana Patricia', 'Hernández', 'Sánchez', 'Av. Chapultepec 890, Col. Moderna, Guadalajara, Jalisco', 'ana.hernandez@email.com', 'activo', '2025-07-04 18:07:52', '2025-07-04 18:07:52'),
(11, 'José Luis', 'Jiménez', 'Ramírez', 'Calle Morelos 345, Col. Centro Histórico, Guadalajara, Jalisco', 'jose.jimenez@email.com', 'activo', '2025-07-04 18:07:52', '2025-07-04 18:07:52'),
(12, 'Laura Beatriz', 'Morales', 'Torres', 'Av. Patria 678, Col. Jardines del Bosque, Guadalajara, Jalisco', 'laura.morales@email.com', 'activo', '2025-07-04 18:07:52', '2025-07-04 18:07:52'),
(13, 'Roberto Carlos', 'Vargas', 'Mendoza', 'Calle Juárez 123, Col. Lafayette, Guadalajara, Jalisco', 'roberto.vargas@email.com', 'activo', '2025-07-04 18:07:52', '2025-07-04 18:07:52'),
(14, 'Claudia Isabel', 'Castillo', 'Flores', 'Av. Américas 456, Col. Providencia, Guadalajara, Jalisco', 'claudia.castillo@email.com', 'activo', '2025-07-04 18:07:52', '2025-07-04 18:07:52'),
(15, 'Fernando', 'Ruiz', 'Gutiérrez', 'Calle Federalismo 789, Col. Mezquitán, Guadalajara, Jalisco', 'fernando.ruiz@email.com', 'activo', '2025-07-04 18:07:52', '2025-07-04 18:07:52'),
(16, 'Gabriela', 'Ortega', 'Vázquez', 'Av. Alcalde 234, Col. Analco, Guadalajara, Jalisco', 'gabriela.ortega@email.com', 'activo', '2025-07-04 18:07:52', '2025-07-04 18:07:52'),
(17, 'Diego Armando', 'Pérez', 'Aguilar', 'Calle Hidalgo 567, Col. San Juan de Dios, Guadalajara, Jalisco', 'diego.perez@email.com', 'activo', '2025-07-04 18:07:52', '2025-07-04 18:07:52'),
(18, 'Sofía Alejandra', 'Medina', 'Cervantes', 'Av. Vallarta 890, Col. Arcos Vallarta, Guadalajara, Jalisco', 'sofia.medina@email.com', 'activo', '2025-07-04 18:07:52', '2025-07-04 18:07:52'),
(19, 'Alejandro', 'Reyes', 'Delgado', 'Calle Colón 123, Col. Centro, Guadalajara, Jalisco', 'alejandro.reyes@email.com', 'activo', '2025-07-04 18:07:52', '2025-07-04 18:07:52'),
(20, 'Valentina', 'Guerrero', 'Herrera', 'Av. Aviación 456, Col. Jardines del Bosque, Guadalajara, Jalisco', 'valentina.guerrero@email.com', 'activo', '2025-07-04 18:07:52', '2025-07-04 18:07:52'),
(21, 'Andrés Felipe', 'Ramos', 'Ponce', 'Calle Garibaldi 789, Col. Tlaquepaque, Guadalajara, Jalisco', 'andres.ramos@email.com', 'activo', '2025-07-04 18:07:52', '2025-07-04 18:07:52'),
(22, 'Natalia', 'Cruz', 'Espinoza', 'Av. Guadalupe 234, Col. Chapalita, Guadalajara, Jalisco', 'natalia.cruz@email.com', 'activo', '2025-07-04 18:07:52', '2025-07-04 18:07:52'),
(23, 'Sebastián', 'Moreno', 'Lozano', 'Calle Libertad 567, Col. Americana, Guadalajara, Jalisco', 'sebastian.moreno@email.com', 'activo', '2025-07-04 18:07:52', '2025-07-04 18:07:52'),
(24, 'Isabella', 'Romero', 'Campos', 'Av. Niños Héroes 890, Col. Moderna, Guadalajara, Jalisco', 'isabella.romero@email.com', 'activo', '2025-07-04 18:07:52', '2025-07-04 18:07:52'),
(25, 'Mateo', 'Navarro', 'Cortés', 'Calle Prisciliano Sánchez 123, Col. Centro, Guadalajara, Jalisco', 'mateo.navarro@email.com', 'activo', '2025-07-04 18:07:52', '2025-07-04 18:07:52'),
(26, 'Camila', 'Iglesias', 'Rojas', 'Av. López Mateos 456, Col. Jardines de Guadalupe, Guadalajara, Jalisco', 'camila.iglesias@email.com', 'activo', '2025-07-04 18:07:52', '2025-07-04 18:07:52'),
(27, 'Emilio', 'Mendez', 'Silva', 'Calle Degollado 789, Col. Centro Histórico, Guadalajara, Jalisco', 'emilio.mendez@email.com', 'activo', '2025-07-04 18:07:52', '2025-07-04 18:07:52'),
(6, 'Cesar Omar', 'Rodriguez', 'Padilla', 'Isis 43, Col. Forum, Tlaquepaque, Jalisco', 'cesar.padilla@email.com', 'activo', '2025-07-04 18:27:14', '2025-07-04 18:27:14'),
(28, 'Luis Ángel', 'Pérez', 'Muñoz', 'Calle Ejemplo #43, Col Centro, Jalisco', 'luis.perez@mail.com', 'activo', '2025-07-04 19:21:50', '2025-07-04 19:21:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`, `created_at`) VALUES
(3, 'admin', 'admin123', '2025-07-04 17:45:08');
COMMIT;