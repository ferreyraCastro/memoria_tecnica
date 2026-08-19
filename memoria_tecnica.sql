-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-08-2026 a las 12:11:57
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `memoria_tecnica`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accesos`
--

CREATE TABLE `accesos` (
  `id` int(11) NOT NULL,
  `servicio` varchar(150) NOT NULL,
  `usuario` varchar(150) NOT NULL,
  `password_cifrada` text NOT NULL,
  `iv` varchar(64) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `fecha_actualizacion` date NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `creado_por` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `accesos`
--

INSERT INTO `accesos` (`id`, `servicio`, `usuario`, `password_cifrada`, `iv`, `url`, `categoria_id`, `observaciones`, `fecha_actualizacion`, `created_at`, `updated_at`, `creado_por`) VALUES
(1, 'PC Biblioteca', 'admin', 'VNTHNbdQIwTTGWdiAirKyA==', 'Qh0DYIuYJM+xiMbXKPQLmA==', NULL, 7, 'Ubicación: Biblioteca PB | Red: Biblioteca | la contraseña es igual, pero no funciona mas como servidor | IP: 192.168.9.28', '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(2, 'Consola Unify', 'admin', 'eDRDqzkY1hioBLM4PY7xQA==', 'xzeA45Fx+4zjRuwW0eqX3A==', NULL, 7, 'Ubicación: Biblioteca PB | Red: Biblioteca | La contraseña es la misma pero el servidor se encuentra ahora en tutorias y tiene otra configuracion | IP: 192.168.9.28', '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(3, 'Servidor Linux', 'ngadmin', 'kQWw+zUZtwiPSd9Y95TzdA==', 'Y6qOoanCer6F2xyk9mPb7w==', NULL, 7, 'Ubicación: Gabinete PB | Red: Administracion | Obsoleta, el servidor fue retirado de servicio', '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(4, 'Servidor Linux', 'admin', 'DAOX4s1SWGBsZOBGqGxGzQ==', 'PJ2jWL6bjpvxkE1TShwsgw==', NULL, 7, 'Ubicación: Gabinete SS | Red: Administracion', '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(5, 'Routers', 'colegio', 'OrcGpMdp6uhPrUmFUsuzbg==', 'R9WlRGk/2SzjXlMKS/x4OA==', NULL, 7, 'Ubicación: General', '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(6, 'Switchs Aruba', 'colegio', 'rg9Tdp2Mz7qdps1flLCiFQ==', 'N+xOoBWfXQwwrVm051vanA==', NULL, 7, 'Ubicación: General', '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(8, 'PCs Aulas PB 1P', 'admin', 'pYwHv8gY6curG7Noe73+wA==', 'CMcGlf+8cpOzs8p6u1xyNQ==', NULL, 8, 'Red: Administracion | Contraseña anterior: admin1245', '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(9, 'PCs Aulas 2P', 'admin', 'fa4IYbQxr2bjYtwnBwf+IQ==', 'a/AQdAbl8IfegcynAEjdcg==', NULL, 8, NULL, '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(10, 'Cloud', 'csjose', 'Pdi8siwmOIuhGdQQidbs2g==', 'E4zJ2WjtkHPChkaPmGXtrg==', NULL, 9, NULL, '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(11, 'Router Biblioteca Mikrotik', 'admin', 'VVTReYdBCg7EZM2kegTz2Q==', 'fBJIiOm1TWhAia6NJfMPvg==', NULL, 7, 'Ubicación: Biblioteca PB | Red: Biblioteca', '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(12, 'Unifi Console SSH access', 'lucasgonzalez', 'X8FrhlGCUL1OFWbpbMrFwrB/+SEJ73urUujoxVFFVfs=', 'pk4FvB2vNSBorBg3J7+wkQ==', NULL, 7, 'Red: Biblioteca | IP: 192.168.251.0 (reconstruida, verificar) | Contraseña anterior: QQbQXglr5JVAyU3UyR8Ak7xG', '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(13, 'servidor Unifi', 'administrador', 'kmAHyWRdrGUhp4GD1Ztpww==', 'sOHdOK5aKKLLZeCvuqmDnw==', NULL, 7, 'Ubicación: Tutorías Entrepiso | Red: Biblioteca | Host: csj-network-server | es el nuevo servidor al dia 26 de AGOSTO', '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(14, 'PC Tercer Año A', 'Tercero', 'uFplHrW4psLsFojXBmyZFg==', 'o4o7N+PXUrbAqQ9ccsbCRA==', NULL, 8, 'Ubicación: Segundo Piso | Red: Biblioteca | recuperacion tercero', '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(15, 'PC Tercer Año A', 'Admin', 'P9f/8Y1A6WSvfP+Hty+rVQ==', 'ly6miNLUUd1iuKYpptf2YA==', NULL, 8, 'Ubicación: Segundo Piso | Red: Biblioteca | recuperacion admin', '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(16, 'PC Tercer Año A', 'Dirección', 'e1xir1Jr3TEFafwEB1oFew==', '6sn8SdsjcHfq+2U7AXO3jQ==', NULL, 8, 'Ubicación: Segundo Piso | Red: Biblioteca | recuperacion direccionF', '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(18, 'PC Docente', 'admin', 'IJQCuhLxe5loYCvv8cz1VA==', 'Wi6z/q0e9McJ1lxQtCK2mA==', NULL, 7, 'Ubicación: Gabinete Ciencias | Red: PP AP-1H-1P', '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(19, 'Pc Dirección Primaria', 'sin especificar', '3FbDmaChojY+R8w5rT0QHQ==', 'i5XNpA5XD3VqVUx2d2diMg==', NULL, 8, NULL, '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(20, 'PC 1-15 (GPB)', 'gferreyra', 'tgtgtTxAV37ExGIA8oeOlA==', 'lnqLEIY99gGwLyaGUc1e0A==', NULL, 8, 'Contraseña para las maquinas 1-15 | Contraseña anterior: admin1245', '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 22:21:16', NULL),
(21, 'PC 1-15 (GPB)', 'Administrator', 'PnhduC6Ihy1W49khQJ8kzQ==', 'F0SDPpT5gmVNAAZY/lZvNg==', NULL, 8, 'Contraseña nueva, implementada en la PC09 | Respuesta recuperación: colegiodesanjosegpb | Contraseña anterior: Admin1245', '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 22:20:08', NULL),
(22, 'PC 11-15 (GPB)', 'Usuario', 'CLbVOTtF80GRe82+G4VKlQ==', 'GcrvLtnqQ8mzeGay66rU2g==', NULL, 8, 'Respuesta recuperación: secundariocuartob', '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 22:18:00', NULL),
(27, 'PC Docente (GPB)', 'Tecnico', 'Flxub70I8aSKueLTfCNv8A==', 'lFykabFZbFpKDStH3vvG7g==', NULL, 8, 'Contraseña anterior: Admin1245', '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(28, 'PC Docente (GPB)', 'Profe Mayra', 'bUbilmDw1YS6XL99L56m2Q==', 'FTRwp2ndmfjh76k7XmM4MQ==', NULL, 8, NULL, '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(29, 'PC Docente (GPB)', 'Alumno', 'h0COFTxyNYJwlUcDa2X2Og==', 'cIIh5IQzADFCAvS94ms12Q==', NULL, 8, NULL, '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(30, 'PC Docente (GPB)', 'Profe Gastòn', 'Rke0vUZs2z0PR62mLMKSlA==', '3/e4b78dPgyxRBtGrJN1mg==', NULL, 8, NULL, '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(32, 'PC Docente (GPB)', 'Profe Pablo', 'c2aUUTcJsAhDsV4XjLS4dA==', 'o4N+jfjyW46gKnJqYkc6Zw==', NULL, 8, NULL, '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(33, 'PC Docente (GPB)', 'Profe Alejandra', 'VnCsiqVi0kNMR6SIEqDknw==', 'jQ8Y1Oh6Gc1xbB3UBd4uSQ==', NULL, 8, NULL, '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(34, 'PC Gabinete Subsuelo', 'Admin', 'M81eifgJ5z7GYcXcKQ/AAA==', '5gE8xjdDVWDT5Er7/tSRCg==', NULL, 8, NULL, '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(35, 'PC Gabinete Subsuelo', 'Primario', 'ijqzku/QPGtJoxTzv3BKuw==', 'cB5eNA26Pg9Y6r5XQdsNZQ==', NULL, 8, NULL, '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(36, 'PC Gabinete Subsuelo', 'Secundario', 'LzX4fdXrtNu5ATkL4wc2Og==', 'mk/8FjWTosD2porS5eDxow==', NULL, 8, NULL, '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(37, 'PC Gabinete Subsuelo', 'PRIMARIO', 'jypDdqbTsDPy/PcQw71kAA==', 'pGtxkwEgcTyXHI+8H0DeXA==', NULL, 8, NULL, '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(38, 'PC Gabinete Subsuelo', 'secundario', 'axIaIYRRnfnR2xIxo5/kaA==', 'eh0aGsNMQK2hUZUCW2mmDQ==', NULL, 8, NULL, '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(39, 'PC Gabinete Subsuelo', 'administrador', 'w94E444b8aC38W8nRHF9Ig==', 'evuIJAoi4qrOTx+LxE6hOw==', NULL, 8, NULL, '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(40, 'Notebook Tutorias (Tutorias Secundario 2P)', 'Tutoria', 'e1IbN+VHQUtAcGZY78vHLA==', 'OVcJ7fYHx11nYhpW09WkXg==', NULL, 8, 'Respuesta recuperación: Recup tuto', '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(41, 'Notebook Tutorias (Tutorias Secundario 2P)', 'Admin', 'yUsnuCSENr43jfjnDOjWTg==', '+/9x/iqI0PF+JSredQtUAQ==', NULL, 8, 'Confirmar contraseña', '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(43, 'Notebook Primaria (Direccion Primaria PB)', 'Docentes', '5dfluYgU7a119olVQ4PrHw==', 'zTZSOtZUgGlZKlS2jH++sQ==', NULL, 8, NULL, '2024-11-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(44, 'Notebook Secundaria (Direccion Primaria PB)', 'Docentes', '8DTbhaqjx8nvht72b9+XFQ==', '44jGQw0zBttNpsImscxmQQ==', NULL, 8, NULL, '2026-04-06', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(45, 'NUTHOST - Campus Virtual (Moodle)', 'administracion@colegiodesanjose.edu.ar', 'r0oU8+Q83DgHbdNZuhBRUA==', 'Xa/GauXm0nuAYiv610CJrA==', 'https://campuscolegiodesanjose.com', 9, 'Dominio vence 28/01/2027. Servidor vence 22/02/2027.', '2026-08-18', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(46, 'GoDaddy - Sitio web del colegio', 'administracion@colegiodesanjose.edu.ar', 'fs316ohMnMcTteeZCvRqSQ==', 'gpYE2PxfehE7OMu4KkOJJw==', 'https://godaddy.com', 9, 'También es la contraseña usada para backup. Cliente 144796833, PIN 9613. Vence 20/02/2028.', '2026-08-18', '2026-08-18 21:36:55', '2026-08-18 21:36:55', NULL),
(48, 'PC Biblioteca', 'admin', 'ZRuPKLo0h4FedPDb3GNmlQ==', 'CzWcrj05I1VioBVqYJdVHQ==', NULL, 7, 'Ubicación: Biblioteca PB | Red: Biblioteca | la contraseña es igual, pero no funciona mas como servidor | IP: 192.168.9.28', '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(51, 'Servidor Linux', 'admin', 'T1CghmBOccZWRNoeHwOK2Q==', 'AxVUnGF6pVoBj19DqXpkFw==', NULL, 7, 'Ubicación: Gabinete SS | Red: Administracion', '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(54, 'Modem', 'custadmin', 'dx+lmr0f99DVDO5lUbFYGw==', 'NDCkjIMZIm1h7PEftrZuCQ==', NULL, 7, 'Ubicación: Biblioteca PB | Red: Biblioteca', '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(55, 'PCs Aulas PB 1P', 'admin', 'YGrFap/ItZe4ypozfnSoxg==', 'nDfxgpA39xYmF1aPRCL+OQ==', NULL, 8, 'Red: Administracion | Contraseña anterior: admin1245', '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(56, 'PCs Aulas 2P', 'admin', 'fqIrf1bYRJ9OsOHDQnHZGQ==', 'v2sX1RUq+qXiDX3FKEMtaA==', NULL, 8, NULL, '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(62, 'PC Tercer Año A', 'Admin', 'PW5zKg7/khhRnWX+mGj2SQ==', 'eAv9uywj/L9D/0tEpZoXJg==', NULL, 8, 'Ubicación: Segundo Piso | Red: Biblioteca | recuperacion admin', '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(63, 'PC Tercer Año A', 'Dirección', 'BWraT9ZTuBCGzN2fB8Staw==', 'rSEkc1XXCX+4CxkjFEt7/g==', NULL, 8, 'Ubicación: Segundo Piso | Red: Biblioteca | recuperacion direccionF', '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(64, 'Veyon PC Docente', 'Profesor', 'm8hPCXWJOfuF2ac9FWTBUg==', '5mMxyr/3NqgzOsJm9frC3A==', NULL, 8, 'Ubicación: Gabinete PB | Red: Administracion', '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(65, 'PC Docente', 'admin', 'T1O5Tr9iAWFlwhowkmYsRQ==', 'Dg/XyvaoFE9d4S5uGkhh/g==', NULL, 7, 'Ubicación: Gabinete Ciencias | Red: PP AP-1H-1P', '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(66, 'Pc Dirección Primaria', 'sin especificar', 'F+YVc42d8KEbxVoKomWlZQ==', 'QWRU4KQshsXdMAcn+QvnTQ==', NULL, 8, NULL, '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(67, 'PC 1-15 (GPB)', 'Admin', '0nnw6kdpAoitlHnAEAo3Lw==', 'TVgpTdqn5kpccmlSrlyjvw==', NULL, 8, 'Contraseña para las maquinas 1-15 | Contraseña anterior: admin1245', '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(68, 'PC 1-15 (GPB)', 'Admin', 'wGlIBVnR3bwvQzRCNXYPKQ==', '1TYfD5kNkSft8VjpS08P8A==', NULL, 8, 'Contraseña nueva, implementada en la PC09 | Respuesta recuperación: colegiodesanjosegpb | Contraseña anterior: Admin1245', '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(73, 'PC 11-15 (GPB)', 'Colegio', 'HSw16FqEdFKkC3ifjKXohQ==', 'hiuHrMziWwWVmyq7pCbdAQ==', NULL, 8, 'Usuario para uso general por cursos invitados | Respuesta recuperación: colegiodesanjosecole', '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(74, 'PC Docente (GPB)', 'Tecnico', 'OSJL1TJjgL0VhEeFZjHc/Q==', '+onRrwT+y6/VbvpIEW2W0Q==', NULL, 8, 'Contraseña anterior: Admin1245', '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(75, 'PC Docente (GPB)', 'Profe Mayra', 'fitcbyQTYZnoVB45h3IjGg==', 'vbKiEVnrrZIT8+auEmNXzQ==', NULL, 8, NULL, '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(76, 'PC Docente (GPB)', 'Alumno', 'TFtmoUunlqwN39W3MxqAcA==', 'kqzMhzf4VCgQdgT4u8UINA==', NULL, 8, NULL, '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(78, 'PC Docente (GPB)', 'Profe Marit', 'Fra3MPngQCVzuR8QzSmHcA==', 'WCgcc6jW2WxNCeJtUposlg==', NULL, 8, NULL, '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(79, 'PC Docente (GPB)', 'Profe Pablo', 'ynimdzL6vsRE7m1Gj4ZxjA==', 'BDldNR/V/wfsDBbiEgliSA==', NULL, 8, NULL, '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(80, 'PC Docente (GPB)', 'Profe Alejandra', 'oEkXtbD+nvp7QhNtK/GuhQ==', 'QvOwFmvs1R72RdV2HeO1WQ==', NULL, 8, NULL, '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(81, 'PC Gabinete Subsuelo', 'Admin', 'po8PWmdiwVMx4s+9BRfZ8w==', 'aYaeMz0QafbST4QES1tl2A==', NULL, 8, NULL, '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(82, 'PC Gabinete Subsuelo', 'Primario', 'CnJO9eqkq+s7UcInCJbwKQ==', 'wL0LrDyo64ak9XWhwk4wzA==', NULL, 8, NULL, '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(83, 'PC Gabinete Subsuelo', 'Secundario', 'A7/pFZdRXTVDXi7bkrb2XA==', 'SWO2Ca/M/bvyL9kEBJK+Mg==', NULL, 8, NULL, '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(84, 'PC Gabinete Subsuelo', 'PRIMARIO', 'dOvJo6+okPTbQj5gmlDsXw==', 'SSF2uLbhgXLO3KE3Y2m3WA==', NULL, 8, NULL, '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(85, 'PC Gabinete Subsuelo', 'secundario', 'K2PhssoqOXdW8ChR6axE+Q==', 'e8PJp15TMgEAzcJlE0apLg==', NULL, 8, NULL, '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(86, 'PC Gabinete Subsuelo', 'administrador', 'luuSCU0shniskZhDtgAX5Q==', 'XTA0oHo5pioLdg8xTLcMXg==', NULL, 8, NULL, '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(87, 'Notebook Tutorias (Tutorias Secundario 2P)', 'Tutoria', 'AYiOKXkAluJwtNAoeoHc+w==', 'wnROpDhEQe8SLPZLOh2zQQ==', NULL, 8, 'Respuesta recuperación: Recup tuto', '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(88, 'Notebook Tutorias (Tutorias Secundario 2P)', 'Admin', 'ILO1UhPmeWmMjmNc99a3OQ==', 'felIWwrjINqo+qMB3BWi1A==', NULL, 8, 'Confirmar contraseña', '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(89, 'Notebook Primaria (Direccion Primaria PB)', 'Admin', 'cUDzeUF7XOxXNM+qXUalsg==', 'VdnZQflkQuooJzsSMUynJA==', NULL, 8, 'Confirmar contraseña', '2024-11-06', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL),
(94, 'Hostinger - Biblioteca y sitios de estudiantes', 'informaticasecundario@colegiodesanjose.edu.ar', '4DxLaN4FBFP8xNgThfJrTA==', 'xkxlRyRyfsq6Ezr0PQMCgQ==', 'https://hostinger.com', 9, 'Vence 18/08/2026.', '2026-08-18', '2026-08-18 23:17:58', '2026-08-18 23:17:58', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_accesos`
--

CREATE TABLE `categorias_accesos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias_accesos`
--

INSERT INTO `categorias_accesos` (`id`, `nombre`) VALUES
(3, 'Correo institucional'),
(2, 'Dominios'),
(1, 'Hosting'),
(6, 'Otros'),
(8, 'PCs y Notebooks'),
(4, 'Plataformas educativas'),
(5, 'Redes sociales'),
(7, 'Servidores y Redes'),
(9, 'Sitios Web y Hosting');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_documentos`
--

CREATE TABLE `categorias_documentos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias_documentos`
--

INSERT INTO `categorias_documentos` (`id`, `nombre`) VALUES
(3, 'Distribución de aulas'),
(8, 'Documentación de instalaciones'),
(5, 'Esquemas de cableado'),
(6, 'Fotografías técnicas'),
(7, 'Manuales'),
(9, 'Otros'),
(2, 'Planos de red'),
(1, 'Planos del colegio'),
(4, 'Ubicación de racks');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos`
--

CREATE TABLE `documentos` (
  `id` int(11) NOT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `archivo_path` varchar(255) NOT NULL,
  `archivo_original` varchar(255) NOT NULL,
  `tipo_archivo` varchar(50) DEFAULT NULL,
  `tamano_bytes` bigint(20) DEFAULT NULL,
  `fecha_subida` datetime NOT NULL DEFAULT current_timestamp(),
  `subido_por` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `documentos`
--

INSERT INTO `documentos` (`id`, `categoria_id`, `nombre`, `descripcion`, `archivo_path`, `archivo_original`, `tipo_archivo`, `tamano_bytes`, `fecha_subida`, `subido_por`) VALUES
(2, 1, 'Plano Primer Piso', 'Plano con distribución de aulas por colores.', 'plano_primer_piso.png', 'CSJ Primer Piso con los colores.png', 'png', NULL, '2026-08-18 21:36:55', NULL),
(3, 1, 'Plano Segundo Piso', 'Plano con distribución de aulas por colores.', 'plano_segundo_piso.png', 'CSJ Segundo Piso con los colores.png', 'png', NULL, '2026-08-18 21:36:55', NULL),
(4, 1, 'Plano Planta Baja', 'Plano con distribución de aulas por colores.', 'plano_planta_baja.png', 'CSJ Planta Baja con los colores.png', 'png', NULL, '2026-08-18 23:17:58', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipos`
--

CREATE TABLE `equipos` (
  `id` int(11) NOT NULL,
  `nombre_pc` varchar(100) NOT NULL,
  `mac` varchar(50) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `subred` varchar(50) DEFAULT NULL,
  `tipo_conexion` enum('cableada','wifi') NOT NULL DEFAULT 'cableada',
  `aula` varchar(100) DEFAULT NULL,
  `sala` varchar(100) DEFAULT NULL,
  `piso` varchar(50) DEFAULT NULL,
  `curso` varchar(100) DEFAULT NULL,
  `sistema_operativo` varchar(100) DEFAULT NULL,
  `usuario_asignado` varchar(150) DEFAULT NULL,
  `anydesk_id` varchar(50) DEFAULT NULL,
  `anydesk_password_cifrada` text DEFAULT NULL,
  `anydesk_iv` varchar(64) DEFAULT NULL,
  `claves_info` text DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `estado` enum('activo','en_reparacion','de_baja') NOT NULL DEFAULT 'activo',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `equipos`
--

INSERT INTO `equipos` (`id`, `nombre_pc`, `mac`, `ip`, `subred`, `tipo_conexion`, `aula`, `sala`, `piso`, `curso`, `sistema_operativo`, `usuario_asignado`, `anydesk_id`, `anydesk_password_cifrada`, `anydesk_iv`, `claves_info`, `observaciones`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'PC-2G-2P', '60:83:E7:B7:94:D7', NULL, '192.168.9.1', 'cableada', '3ER AÑO B', '2G', '2P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(2, 'PC-2F-2P', '34-60-F9-1A-0A-2D', NULL, '192.168.9.1', 'cableada', '3ER AÑO A', '2F', '2P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(3, 'PC-2B-2P', '50-3E-AA-9D-DC-26', NULL, '192.168.9.1', 'cableada', '4TO AÑO A', '2B', '2P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(4, 'PC-2A-2P', '50-3E-AA-A2-97-D5', NULL, '192.168.9.1', 'cableada', 'SALA INGLES', '2A', '2P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(5, 'PC-VD-2P', '00-D8-61-4F-F7-33', NULL, '192.168.4.1', 'cableada', 'MARCELO', 'VICEDIRECCION', '2P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(6, 'PC-TT-2P-A', '04-7C-16-15-5C-2B', NULL, '192.168.9.1', 'cableada', 'TUTORIA', 'TUTORIA', '2P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(7, 'PC-2D-2P', 'C0-06-C3-BF-AC-7A', NULL, '192.168.9.1', 'cableada', '2DO AÑO B', '2D', '2P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(8, 'PC-2C-2P', '50-3E-AA-47-8E-AD', NULL, '192.168.9.1', 'cableada', '2DO AÑO A', '2C', '2P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(9, 'PC-2E-2P', '50-3E-AA-9D-C0-63', NULL, '192.168.9.1', 'cableada', '4TO AÑO B', '2E', '2P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(10, 'PC-1H-1P', NULL, NULL, NULL, 'cableada', 'LABORATORIO', NULL, '1P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(11, 'PC-1G-1P', 'E8-DE-27-9F-BD-A6', NULL, '192.168.9.1', 'cableada', '1ER AÑO B', '1G', '1P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(12, 'PC-1F-1P', '60-83-E7-B7-A3-6C', NULL, '192.168.9.1', 'wifi', '1° AÑO A', '1F', '1P', NULL, NULL, NULL, NULL, 'wc4HSyT8DOWZbCX+xFC0hw==', 'b35wGIegp0xQ1S3eqowzyw==', NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 23:24:32'),
(13, 'PC-1E-1P', '60-83-E7-B7-A1-56', NULL, '192.168.9.1', 'cableada', '5TO AÑO A', '1E', '1P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(14, 'PC-1D-1P', '14-CC-20-25-2D-EA', NULL, '192.168.9.1', 'cableada', '5TO AÑO B', '1D', '1P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(15, 'PC-1C-1P', '50-3E-AA-A2-AE-DB', NULL, '192.168.9.1', 'cableada', '6TO AÑO A', '1C', '1P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(16, 'PC-1B-1P', 'E8-DE-27-9F-A8-F8', NULL, '192.168.9.1', 'cableada', '6TO AÑO B', '1B', '1P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(17, 'PC-F-PB', '60-83-E7-B7-9F-96', NULL, '192.168.9.1', 'cableada', '6TO GRADO', 'F', 'PB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(18, 'PC-E-PB', '5C-62-8B-68-20-48', NULL, '192.168.9.1', 'cableada', '5TO GRADO', 'E', 'PB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(19, 'PC-D-PB', 'E8-DE-27-A0-28-CF', NULL, '192.168.9.1', 'cableada', '4TO GRADO', 'D', 'PB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(20, 'PC-A-PB', '50-3E-AA-A2-97-DA', NULL, '192.168.9.1', 'cableada', '2DO GRADO', 'A', 'PB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(21, 'PC-L-PB', 'C4-E9-84-1C-9C-2B', NULL, '192.168.9.1', 'cableada', '1ER GRADO', 'L', 'PB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(22, 'PC-K-PB', '5C-62-8B-68-1E-15', NULL, '192.168.9.1', 'cableada', '3ER GRADO', 'K', 'PB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(23, 'PC-I-PB', '1C-BF-CE-A0-F3-A5', NULL, '192.168.9.1', 'cableada', 'SALA DE 4', 'I', 'PB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(24, 'PC-H-PB', '1C-BF-CE-A0-F3-9D', NULL, '192.168.9.1', 'cableada', 'SALA DE 5', 'H', 'PB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(25, 'NB-SEC-A', 'C8-CB-9E-0A-0D-48', NULL, NULL, 'cableada', 'Notebook Secundario', NULL, '1P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(26, 'NB-SEC-B', '4C-ED-DE-CC-30-A5', NULL, NULL, 'cableada', 'Notebook Secundario', NULL, '1P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(27, 'NB-PRI-A', 'C8-CB-9E-06-26-C4', NULL, NULL, 'cableada', 'Notebook Primario', NULL, 'PB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(28, 'NB-IN-A', 'C8-CB-9E-4E-D7', NULL, NULL, 'cableada', 'Notebook Inicial', NULL, 'PB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(29, 'TEL-SEC-A', '62-17-6F-48-B7-65', NULL, NULL, 'cableada', 'Telefono Nivel Secundario', NULL, '1P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(30, 'TEL-PRI-A', '92-6A-F5-B2-4D-24', NULL, NULL, 'cableada', 'Telefono Nivel Primario', NULL, 'PB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(31, 'PC-DI-PB', NULL, NULL, NULL, 'cableada', 'PC dirección inicial', NULL, 'PB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(32, 'PC-2G-2P', '60:83:E7:B7:94:D7', NULL, '192.168.9.1', 'cableada', '3ER AÑO B', '2G', '2P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(33, 'PC-2F-2P', '34-60-F9-1A-0A-2D', NULL, '192.168.9.1', 'cableada', '3ER AÑO A', '2F', '2P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(34, 'PC-2B-2P', '50-3E-AA-9D-DC-26', NULL, '192.168.9.1', 'cableada', '4TO AÑO A', '2B', '2P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(35, 'PC-2A-2P', '50-3E-AA-A2-97-D5', NULL, '192.168.9.1', 'cableada', 'SALA INGLES', '2A', '2P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(36, 'PC-VD-2P', '00-D8-61-4F-F7-33', NULL, '192.168.4.1', 'cableada', 'MARCELO', 'VICEDIRECCION', '2P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(37, 'PC-TT-2P-A', '04-7C-16-15-5C-2B', NULL, '192.168.9.1', 'cableada', 'TUTORIA', 'TUTORIA', '2P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(38, 'PC-2D-2P', 'C0-06-C3-BF-AC-7A', NULL, '192.168.9.1', 'cableada', '2DO AÑO B', '2D', '2P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(39, 'PC-2C-2P', '50-3E-AA-47-8E-AD', NULL, '192.168.9.1', 'cableada', '2DO AÑO A', '2C', '2P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(40, 'PC-2E-2P', '50-3E-AA-9D-C0-63', NULL, '192.168.9.1', 'cableada', '4TO AÑO B', '2E', '2P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(41, 'PC-1H-1P', NULL, NULL, NULL, 'cableada', 'LABORATORIO', NULL, '1P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(42, 'PC-1G-1P', 'E8-DE-27-9F-BD-A6', NULL, '192.168.9.1', 'cableada', '1ER AÑO B', '1G', '1P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(43, 'PC-1F-1P', '60-83-E7-B7-A3-6C', NULL, '192.168.9.1', 'cableada', '1ER AÑO A', '1F', '1P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(44, 'PC-1E-1P', '60-83-E7-B7-A1-56', NULL, '192.168.9.1', 'cableada', '5TO AÑO A', '1E', '1P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(45, 'PC-1D-1P', '14-CC-20-25-2D-EA', NULL, '192.168.9.1', 'cableada', '5TO AÑO B', '1D', '1P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(46, 'PC-1C-1P', '50-3E-AA-A2-AE-DB', NULL, '192.168.9.1', 'cableada', '6TO AÑO A', '1C', '1P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(47, 'PC-1B-1P', 'E8-DE-27-9F-A8-F8', NULL, '192.168.9.1', 'cableada', '6TO AÑO B', '1B', '1P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(48, 'PC-F-PB', '60-83-E7-B7-9F-96', NULL, '192.168.9.1', 'cableada', '6TO GRADO', 'F', 'PB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(49, 'PC-E-PB', '5C-62-8B-68-20-48', NULL, '192.168.9.1', 'cableada', '5TO GRADO', 'E', 'PB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(50, 'PC-D-PB', 'E8-DE-27-A0-28-CF', NULL, '192.168.9.1', 'cableada', '4TO GRADO', 'D', 'PB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(51, 'PC-A-PB', '50-3E-AA-A2-97-DA', NULL, '192.168.9.1', 'cableada', '2DO GRADO', 'A', 'PB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(52, 'PC-L-PB', 'C4-E9-84-1C-9C-2B', NULL, '192.168.9.1', 'cableada', '1ER GRADO', 'L', 'PB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(53, 'PC-K-PB', '5C-62-8B-68-1E-15', NULL, '192.168.9.1', 'cableada', '3ER GRADO', 'K', 'PB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(54, 'PC-I-PB', '1C-BF-CE-A0-F3-A5', NULL, '192.168.9.1', 'cableada', 'SALA DE 4', 'I', 'PB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(55, 'PC-H-PB', '1C-BF-CE-A0-F3-9D', NULL, '192.168.9.1', 'cableada', 'SALA DE 5', 'H', 'PB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(56, 'NB-SEC-A', 'C8-CB-9E-0A-0D-48', NULL, NULL, 'cableada', 'Notebook Secundario', NULL, '1P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(57, 'NB-SEC-B', '4C-ED-DE-CC-30-A5', NULL, NULL, 'cableada', 'Notebook Secundario', NULL, '1P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(58, 'NB-PRI-A', 'C8-CB-9E-06-26-C4', NULL, NULL, 'cableada', 'Notebook Primario', NULL, 'PB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(59, 'NB-IN-A', 'C8-CB-9E-4E-D7', NULL, NULL, 'cableada', 'Notebook Inicial', NULL, 'PB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(60, 'TEL-SEC-A', '62-17-6F-48-B7-65', NULL, NULL, 'cableada', 'Telefono Nivel Secundario', NULL, '1P', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(61, 'TEL-PRI-A', '92-6A-F5-B2-4D-24', NULL, NULL, 'cableada', 'Telefono Nivel Primario', NULL, 'PB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(62, 'PC-DI-PB', NULL, NULL, NULL, 'cableada', 'PC dirección inicial', NULL, 'PB', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'activo', '2026-08-18 23:17:58', '2026-08-18 23:17:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimientos`
--

CREATE TABLE `mantenimientos` (
  `id` int(11) NOT NULL,
  `equipo_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `tipo` enum('mantenimiento_preventivo','reparacion','instalacion','otro') NOT NULL DEFAULT 'reparacion',
  `problema_detectado` text DEFAULT NULL,
  `descripcion` text NOT NULL,
  `componentes_reemplazados` varchar(255) DEFAULT NULL,
  `repuestos_utilizados` varchar(255) DEFAULT NULL,
  `tecnico` varchar(150) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `red_conexiones`
--

CREATE TABLE `red_conexiones` (
  `id` int(11) NOT NULL,
  `nodo_origen_id` int(11) NOT NULL,
  `puerto_origen` int(11) NOT NULL DEFAULT 1,
  `nodo_destino_id` int(11) NOT NULL,
  `puerto_destino` int(11) NOT NULL DEFAULT 1,
  `etiqueta` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `red_conexiones`
--

INSERT INTO `red_conexiones` (`id`, `nodo_origen_id`, `puerto_origen`, `nodo_destino_id`, `puerto_destino`, `etiqueta`, `created_at`) VALUES
(1, 1, 1, 2, 1, NULL, '2026-08-18 23:14:56'),
(2, 2, 2, 3, 1, NULL, '2026-08-18 23:14:56'),
(3, 3, 17, 7, 17, NULL, '2026-08-18 23:14:56'),
(4, 4, 1, 5, 1, NULL, '2026-08-18 23:14:56'),
(5, 5, 4, 7, 24, NULL, '2026-08-18 23:14:56'),
(6, 6, 2, 7, 16, NULL, '2026-08-18 23:14:56'),
(7, 8, 21, 7, 21, NULL, '2026-08-18 23:14:56'),
(8, 9, 1, 8, 9, NULL, '2026-08-18 23:14:56'),
(9, 10, 1, 7, 20, NULL, '2026-08-18 23:14:56'),
(10, 11, 1, 7, 15, NULL, '2026-08-18 23:14:56'),
(11, 12, 1, 7, 5, NULL, '2026-08-18 23:14:56'),
(12, 13, 1, 8, 13, NULL, '2026-08-18 23:14:56'),
(13, 31, 1, 14, 1, NULL, '2026-08-18 23:14:56'),
(14, 32, 1, 15, 1, NULL, '2026-08-18 23:14:56'),
(15, 33, 1, 16, 1, NULL, '2026-08-18 23:14:56'),
(16, 34, 1, 17, 1, NULL, '2026-08-18 23:14:56'),
(17, 35, 1, 18, 1, NULL, '2026-08-18 23:14:56'),
(18, 36, 1, 19, 1, NULL, '2026-08-18 23:14:56'),
(19, 38, 1, 21, 1, NULL, '2026-08-18 23:14:56'),
(20, 40, 1, 23, 1, NULL, '2026-08-18 23:14:56'),
(21, 53, 1, 3, 5, NULL, '2026-08-18 23:22:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `red_dispositivos`
--

CREATE TABLE `red_dispositivos` (
  `id` int(11) NOT NULL,
  `tipo` enum('access_point','switch','router','modem','otro') NOT NULL DEFAULT 'access_point',
  `nombre` varchar(150) NOT NULL,
  `mac` varchar(50) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `subred` varchar(50) DEFAULT NULL,
  `ssid` varchar(150) DEFAULT NULL,
  `password_wifi_cifrada` text DEFAULT NULL,
  `iv` varchar(64) DEFAULT NULL,
  `ubicacion` varchar(150) DEFAULT NULL,
  `piso` varchar(50) DEFAULT NULL,
  `marca_modelo` varchar(150) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `red_dispositivos`
--

INSERT INTO `red_dispositivos` (`id`, `tipo`, `nombre`, `mac`, `ip`, `subred`, `ssid`, `password_wifi_cifrada`, `iv`, `ubicacion`, `piso`, `marca_modelo`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, 'access_point', 'AP-L-PB', 'F4-92-BF-10-61-51', '192.168.9.12', NULL, NULL, NULL, NULL, 'L (Primer grado)', NULL, NULL, 'Conectado a switch ADMINISTRACION, puerto 11', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(2, 'access_point', 'AP-1F-1P', '74-83-C2-33-19-9A', '192.168.9.111', NULL, NULL, NULL, NULL, '1F', NULL, NULL, 'Conectado a switch TUTORIA, puerto 24', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(3, 'access_point', 'AP-1D-1P', '74-83-C2-33-19-91', '192.168.9.138', NULL, NULL, NULL, NULL, '1D (5TO AÑO)', NULL, NULL, 'Conectado a switch TUTORIA, puerto 22', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(4, 'access_point', 'AP-2B-2P', 'f4:92:bf:13:a0:71', '192.168.9.115', NULL, NULL, NULL, NULL, '2B', NULL, NULL, NULL, '2026-08-18 21:36:55', '2026-08-18 22:39:46'),
(5, 'access_point', 'AP-AD-PB', '70-A7-41-86-E2-40', '192.168.9.253', NULL, NULL, NULL, NULL, 'ADMINISTRACION', NULL, NULL, 'Conectado a switch ADMINISTRACION, puerto 2', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(6, 'access_point', 'AP-DO-1P', '74-83-C2-33-13-57', '192.168.9.142', NULL, NULL, NULL, NULL, 'GABINETE DOE', NULL, NULL, 'Conectado a switch TUTORIA, puerto 16', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(7, 'access_point', 'AP-1H-1P', '74:83:c2:33:3b:03', '192.168.9.16', NULL, NULL, '01HPrsww4ZNZJezmWc62yA==', 'tmyGVZz+v7TiQUleMXgV0A==', '1H', NULL, NULL, 'Conectado a switch BIBLIOTECA, puerto 8', '2026-08-18 21:36:55', '2026-08-18 22:36:32'),
(8, 'access_point', 'AP-PA-2P', '78-8A-20-F3-00-40', '192.168.9.110', NULL, NULL, NULL, NULL, 'PASILLO 2P ', NULL, NULL, 'Conectado a switch TUTORIA, puerto 9', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(9, 'access_point', 'AP-H-PB', 'f4:92:bf:10:61:39', '192.168.9.10', NULL, NULL, NULL, NULL, 'H (GABINETE DE CIENCIAS)', NULL, NULL, 'Conectado a switch BIBLIOTECA, puerto 4', '2026-08-18 21:36:55', '2026-08-18 22:38:44'),
(10, 'access_point', 'AP-TT-1P', '74-83-C2-33-39-EE', '192.168.9.14', NULL, NULL, NULL, NULL, 'TUTORIAS', NULL, NULL, 'Conectado a switch TUTORIA, puerto 14', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(11, 'access_point', 'AP-VD-2P', '74-83-C2-33-19-62', '192.168.9.18', NULL, NULL, NULL, NULL, 'VICEDIRECCION', NULL, NULL, 'Conectado a switch TUTORIA, puerto 10', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(12, 'switch', 'Biblioteca-Aruba-2530-24G', '3810f0b05360', '192.168.9.10', NULL, NULL, NULL, NULL, 'Biblioteca', 'PB', '2530-24G', 'Puerto 1 -> Router Biblioteca', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(13, 'switch', 'Switch-Administracion', 'B8:D4:E7:EA:98:E9', '192.168.9.15', NULL, NULL, NULL, NULL, 'Administracion', 'PB', '1930-24G', 'Puerto 21 -> Switch Tutoria', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(14, 'switch', 'Aruba-2530-24G-Tutoria-1P', '3810f0b04340', '192.168.9.3', NULL, NULL, NULL, NULL, 'Tutoria', '1P', '2530-24G', 'Puerto 15 -> Switch Biblioteca; Puerto 23 -> Router Administracion', '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(15, 'switch', 'USW 24 PoE', '6c:63:f8:ac:95:55', '192.168.9.85', NULL, NULL, 'Pr6B9Ft9GYDhKSOPDCjBkQ==', 'JwfcPr8SZgeypgYtlXt03Q==', 'Tutoria', '2P', NULL, NULL, '2026-08-18 22:32:09', '2026-08-18 22:32:50'),
(16, 'access_point', 'AP-I-PB', 'ac:8b:a9:99:56:aa', '192.168.9.196', NULL, NULL, NULL, NULL, NULL, 'Planta baja', NULL, NULL, '2026-08-18 22:35:17', '2026-08-18 22:35:17'),
(17, 'access_point', 'AP-E-PB', '8c:30:66:6c:76:70', '192.168.9.194', NULL, NULL, NULL, NULL, NULL, 'Planta baja', NULL, NULL, '2026-08-18 22:40:39', '2026-08-18 22:40:39'),
(18, 'access_point', 'AP-L-PB', 'F4-92-BF-10-61-51', '192.168.9.12', NULL, NULL, NULL, NULL, 'L (Primer grado)', NULL, NULL, 'Conectado a switch ADMINISTRACION, puerto 11', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(19, 'access_point', 'AP-1F-1P', '74-83-C2-33-19-9A', '192.168.9.111', NULL, NULL, NULL, NULL, '1F', NULL, NULL, 'Conectado a switch TUTORIA, puerto 24', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(20, 'access_point', 'AP-1D-1P', '74-83-C2-33-19-91', '192.168.9.138', NULL, NULL, NULL, NULL, '1D (5TO AÑO)', NULL, NULL, 'Conectado a switch TUTORIA, puerto 22', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(21, 'access_point', 'AP-2B-2P', 'F0-9F-C2-80-CE-E1', '192.168.9.13', NULL, NULL, NULL, NULL, '2B', NULL, NULL, NULL, '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(22, 'access_point', 'AP-AD-PB', '70-A7-41-86-E2-40', '192.168.9.253', NULL, NULL, NULL, NULL, 'ADMINISTRACION', NULL, NULL, 'Conectado a switch ADMINISTRACION, puerto 2', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(23, 'access_point', 'AP-DO-1P', '74-83-C2-33-13-57', '192.168.9.142', NULL, NULL, NULL, NULL, 'GABINETE DOE', NULL, NULL, 'Conectado a switch TUTORIA, puerto 16', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(24, 'access_point', 'AP-1H-1P', '74-83-C2-33-3B-02', '192.168.9.254', NULL, NULL, NULL, NULL, '1H', NULL, NULL, 'Conectado a switch BIBLIOTECA, puerto 8', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(25, 'access_point', 'AP-PA-2P', '78-8A-20-F3-00-40', '192.168.9.110', NULL, NULL, NULL, NULL, 'PASILLO 2P ', NULL, NULL, 'Conectado a switch TUTORIA, puerto 9', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(26, 'access_point', 'AP-H-PB', 'F4-92-BF-10-61-39', '192.168.9.105', NULL, NULL, NULL, NULL, 'H (GABINETE DE CIENCIAS)', NULL, NULL, 'Conectado a switch BIBLIOTECA, puerto 4', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(27, 'access_point', 'AP-TT-1P', '74-83-C2-33-39-EE', '192.168.9.14', NULL, NULL, NULL, NULL, 'TUTORIAS', NULL, NULL, 'Conectado a switch TUTORIA, puerto 14', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(28, 'access_point', 'AP-VD-2P', '74-83-C2-33-19-62', '192.168.9.18', NULL, NULL, NULL, NULL, 'VICEDIRECCION', NULL, NULL, 'Conectado a switch TUTORIA, puerto 10', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(29, 'switch', 'Biblioteca-Aruba-2530-24G', '3810f0b05360', '192.168.9.10', NULL, NULL, NULL, NULL, 'Biblioteca', 'PB', '2530-24G', 'Puerto 1 -> Router Biblioteca', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(30, 'switch', 'Switch-Administracion', 'B8:D4:E7:EA:98:E9', '192.168.9.15', NULL, NULL, NULL, NULL, 'Administracion', 'PB', '1930-24G', 'Puerto 21 -> Switch Tutoria', '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(31, 'switch', 'Aruba-2530-24G-Tutoria-1P', '3810f0b04340', '192.168.9.3', NULL, NULL, NULL, NULL, 'Tutoria', '1P', '2530-24G', 'Puerto 15 -> Switch Biblioteca; Puerto 23 -> Router Administracion', '2026-08-18 23:17:58', '2026-08-18 23:17:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `red_nodos`
--

CREATE TABLE `red_nodos` (
  `id` int(11) NOT NULL,
  `tipo` enum('pc','switch','router','modem','ap','impresora','camara','servidor','lector','conector','otro') NOT NULL DEFAULT 'otro',
  `nombre` varchar(150) NOT NULL,
  `subtitulo` varchar(150) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `grupo` varchar(100) DEFAULT NULL,
  `info_extra` text DEFAULT NULL,
  `num_puertos` int(11) NOT NULL DEFAULT 1,
  `pos_x` int(11) NOT NULL DEFAULT 100,
  `pos_y` int(11) NOT NULL DEFAULT 100,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `red_nodos`
--

INSERT INTO `red_nodos` (`id`, `tipo`, `nombre`, `subtitulo`, `ip`, `grupo`, `info_extra`, `num_puertos`, `pos_x`, `pos_y`, `created_at`, `updated_at`) VALUES
(1, 'otro', 'Internet', NULL, NULL, NULL, 'Enlace de internet - Biblioteca', 1, 40, 40, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(2, 'modem', 'Módem Internet Biblioteca', NULL, NULL, NULL, 'Ubicación: ETH1', 2, 40, 190, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(3, 'switch', 'Switch Biblioteca', NULL, NULL, NULL, 'Conecta a Switch Of Carlos, puerto 17', 24, 313, 104, '2026-08-18 23:14:56', '2026-08-18 23:33:21'),
(4, 'otro', 'Internet', NULL, NULL, NULL, 'Enlace de internet - Oficina Carlos', 1, 40, 360, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(5, 'router', 'Router Board 1100', NULL, NULL, NULL, 'Ubicación: ETH4. Conecta a Switch Of Carlos, puerto 24', 5, 40, 510, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(6, 'modem', 'Módem Internet Carlos', NULL, NULL, NULL, 'Conecta a Switch Of Carlos, puerto 16', 2, 272, 361, '2026-08-18 23:14:56', '2026-08-18 23:33:27'),
(7, 'switch', 'Switch Of Carlos', NULL, NULL, NULL, 'Switch troncal / central del colegio', 24, 594, 293, '2026-08-18 23:14:56', '2026-08-18 23:33:25'),
(8, 'switch', 'Switch Tutoria', NULL, NULL, NULL, 'Puerto propio: 3. Conecta a Switch Of Carlos, puerto 21', 24, 960, 150, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(9, 'switch', 'Switch Secretaria Secundario', NULL, NULL, NULL, 'Conecta a Switch Tutoria', 8, 1300, 20, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(10, 'switch', 'Switch Informática PB', NULL, NULL, NULL, 'Conecta a Switch Of Carlos, puerto 20', 16, 960, 510, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(11, 'switch', 'Switch Informática (Sub Suelo)', NULL, NULL, NULL, 'Conecta a Switch Of Carlos, puerto 15', 16, 623, 630, '2026-08-18 23:14:56', '2026-08-18 23:33:11'),
(12, 'servidor', 'Servidor Oficina Carlos', NULL, NULL, NULL, 'Conecta a Switch Of Carlos, puerto 5', 4, 40, 840, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(13, 'servidor', 'Servidor Técnica', NULL, NULL, NULL, 'Conecta a Switch Tutoria, puerto 13', 4, 1500, 510, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(14, 'pc', 'PC - Sergio', 'DESKTOP-H9BV0F2', '192.168.4.92', 'WORKGROUP', 'Puerto de referencia en Switch Tutoria: 5', 1, 1500, 1280, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(15, 'pc', 'PC - Mariel', 'VICEDIRECCION-NS', '192.168.4.107', 'WORKGROUP', 'Puerto de referencia en Switch Tutoria: 8', 1, 1500, 1130, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(16, 'pc', 'PC - Maru', 'adm-secm', '192.168.4.223', 'ADM-SANJOSE', 'Puerto de referencia en Switch Tutoria: 7', 1, 1500, 1430, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(17, 'pc', 'PC - Gaby', 'Secretaria-np', '192.168.4.155', 'WORKGROUP', 'Puerto de referencia en Switch Of Carlos: 9', 1, 1340, 840, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(18, 'pc', 'PC - Carlos', 'secAdmin', '192.168.4.30', 'RED', 'Puerto de referencia en Switch Of Carlos: 1', 1, 700, 840, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(19, 'pc', 'PC - Andres', 'DESKTOP-NM0V3PT', '192.168.4.4', NULL, 'Puerto de referencia en Switch Of Carlos: 3', 1, 910, 840, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(20, 'pc', 'PC - Liliana', 'DESKTOP-9123RD8', '192.168.4.82', 'WORKGROUP', 'Puerto de referencia en Switch Of Carlos: 7', 1, 1120, 840, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(21, 'pc', 'PC - Adriana', 'PC-DI-PB', '192.168.4.126', 'WORKGROUP', 'Puerto de referencia en Switch Of Carlos: 13', 1, 41, 1072, '2026-08-18 23:14:56', '2026-08-18 23:33:55'),
(22, 'pc', 'PC - Anto', 'admin-tuto', '192.168.9.60', 'WORKGROUP', NULL, 1, 1720, 190, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(23, 'pc', 'PC - Psicóloga', 'DESKTOP-H2GDQ0O', '192.168.9.169', 'WORKGROUP', NULL, 1, 1500, 190, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(24, 'pc', 'PC - Sala Profe Primaria', NULL, NULL, 'WORKGROUP', 'Puerto de referencia en Switch Of Carlos: 19', 1, 490, 840, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(25, 'pc', 'PC - Portería', NULL, NULL, 'WORKGROUP', 'Puerto de referencia en Switch Biblioteca: 1', 1, 40, 790, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(26, 'pc', 'PC - Biblioteca', NULL, NULL, 'WORKGROUP', 'Puerto de referencia en Switch Biblioteca: 3', 1, 460, 790, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(27, 'pc', 'PC - Sexto Grado', NULL, NULL, NULL, 'Puerto de referencia: 16', 1, 40, 960, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(28, 'pc', 'PC - Tutoría (Dany)', NULL, NULL, NULL, 'Puerto de referencia en Switch Tutoria: 16', 1, 1500, 980, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(29, 'pc', 'PC - Sala Profe Secundaria', NULL, NULL, NULL, 'Puerto de referencia en Switch Tutoria: 18', 1, 1500, 830, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(30, 'pc', 'PC - Técnica (Gastón)', NULL, NULL, NULL, 'Puerto de referencia en Switch Tutoria: 15', 1, 1500, 680, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(31, 'impresora', 'Impresora HP M203dw (USB)', NULL, NULL, NULL, 'USB en PC - Sergio', 1, 1720, 1280, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(32, 'impresora', 'Impresora HP M102W (USB)', NULL, NULL, NULL, 'USB en PC - Mariel', 1, 1720, 1130, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(33, 'impresora', 'Impresora M236sdw (USB)', NULL, NULL, NULL, 'USB en PC - Maru', 1, 1720, 1430, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(34, 'impresora', 'Impresora CP1025 nw (USB)', NULL, NULL, NULL, 'USB en PC - Gaby', 1, 1340, 990, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(35, 'impresora', 'Impresora HP M212 (USB)', NULL, NULL, NULL, 'USB en PC - Carlos', 1, 700, 990, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(36, 'impresora', 'Impresora HP M203dw (USB)', NULL, NULL, NULL, 'USB en PC - Andres', 1, 910, 990, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(37, 'impresora', 'Impresora M426FDW (Red)', NULL, NULL, NULL, 'Conectada por red (no USB) - PC Liliana', 1, 1120, 990, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(38, 'impresora', 'Impresora HP P1005 (USB)', NULL, NULL, NULL, 'USB en PC - Adriana', 1, 304, 1100, '2026-08-18 23:14:56', '2026-08-18 23:33:54'),
(39, 'impresora', 'Impresora HP M236sdw (red)', NULL, NULL, NULL, 'Conectada por red (no USB) - PC Anto', 1, 1930, 190, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(40, 'impresora', 'Impresora HP M111w (USB)', NULL, NULL, NULL, 'USB en PC - Psicóloga', 1, 1500, 340, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(41, 'impresora', 'Impresora Tutoría (M236sdw)', NULL, NULL, NULL, 'Puerto de referencia en Switch Tutoria: 11', 1, 1500, 1580, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(42, 'impresora', 'Impresora M236sdw - Secretaria Secundaria', NULL, NULL, NULL, 'Puerto de referencia en Switch Tutoria: 19', 1, 1500, 1730, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(43, 'ap', 'AP-AD-PB', NULL, NULL, NULL, 'Puerto de referencia: 2', 1, 700, 790, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(44, 'ap', 'AP-L-PB', NULL, NULL, NULL, 'Puerto de referencia: 11', 1, 250, 790, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(45, 'ap', 'AP-H-PB', NULL, NULL, NULL, 'Puerto de referencia: 4', 1, 910, 790, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(46, 'ap', 'AP-Sala de Cuatro', NULL, NULL, NULL, 'Puerto de referencia: 18', 1, 250, 960, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(47, 'ap', 'AP-BIBLIOTECA', NULL, NULL, NULL, 'Puerto de referencia: 23', 1, 460, 960, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(48, 'ap', 'AP-2B-2P', NULL, NULL, NULL, 'Puerto de referencia en Switch Tutoria: 6', 1, 960, 20, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(49, 'ap', 'AP-PA-2P', NULL, NULL, NULL, 'Puerto de referencia en Switch Tutoria: 12', 1, 1170, 20, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(50, 'ap', 'AP-TT-1P', NULL, NULL, NULL, 'Puerto de referencia en Switch Tutoria: 14', 1, 2140, 20, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(51, 'ap', 'AP-DO-1P', NULL, NULL, NULL, 'Puerto de referencia en Switch Tutoria: 22', 1, 2350, 20, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(52, 'ap', 'AP-1D-1P', NULL, NULL, NULL, 'Puerto de referencia en Switch Tutoria: 24', 1, 2560, 20, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(53, 'otro', 'Plato / molinete (control de acceso)', NULL, NULL, NULL, 'Puerto de referencia: 2', 1, 579, 65, '2026-08-18 23:14:56', '2026-08-18 23:33:23'),
(54, 'conector', 'Conector - Salón de Actos', NULL, NULL, NULL, 'Puerto de referencia: 12', 1, 1120, 790, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(55, 'conector', 'Conector - Sala de Profesores', NULL, NULL, NULL, 'Puerto de referencia: 10', 1, 237, 843, '2026-08-18 23:14:56', '2026-08-18 23:33:50'),
(56, 'camara', 'Cámara Biblioteca', NULL, NULL, NULL, 'Puerto de referencia: 20', 1, 670, 960, '2026-08-18 23:14:56', '2026-08-18 23:14:56'),
(57, 'camara', 'Cámara @lhua', NULL, NULL, NULL, NULL, 1, 503, 1144, '2026-08-18 23:14:56', '2026-08-18 23:33:52'),
(58, 'lector', 'Lector biométrico Planta Baja', NULL, NULL, NULL, 'Puerto de referencia: 18', 1, 739, 1144, '2026-08-18 23:14:56', '2026-08-18 23:33:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `renovaciones_historial`
--

CREATE TABLE `renovaciones_historial` (
  `id` int(11) NOT NULL,
  `suscripcion_id` int(11) NOT NULL,
  `fecha_renovacion` date NOT NULL,
  `fecha_vencimiento_anterior` date DEFAULT NULL,
  `fecha_vencimiento_nueva` date NOT NULL,
  `costo` decimal(12,2) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suscripciones`
--

CREATE TABLE `suscripciones` (
  `id` int(11) NOT NULL,
  `proveedor` varchar(150) NOT NULL,
  `servicio` varchar(150) NOT NULL,
  `costo` decimal(12,2) DEFAULT NULL,
  `moneda` varchar(10) NOT NULL DEFAULT 'ARS',
  `fecha_contratacion` date DEFAULT NULL,
  `fecha_vencimiento` date NOT NULL,
  `periodo_renovacion` enum('mensual','trimestral','semestral','anual','bianual','otro') NOT NULL DEFAULT 'anual',
  `observaciones` text DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `suscripciones`
--

INSERT INTO `suscripciones` (`id`, `proveedor`, `servicio`, `costo`, `moneda`, `fecha_contratacion`, `fecha_vencimiento`, `periodo_renovacion`, `observaciones`, `activo`, `created_at`, `updated_at`) VALUES
(1, 'NUTHOST', 'Dominio campuscolegiodesanjose.com (Moodle)', NULL, 'ARS', NULL, '2027-01-28', 'anual', 'Usuario: administracion@colegiodesanjose.edu.ar', 1, '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(4, 'HOSTINGER', 'Biblioteca y sitios de estudiantes', NULL, 'ARS', NULL, '2026-08-18', 'anual', 'Usuario: informaticasecundario@colegiodesanjose.edu.ar', 1, '2026-08-18 21:36:55', '2026-08-18 21:36:55'),
(6, 'NUTHOST', 'Servidor Campus Virtual (Moodle)', NULL, 'ARS', NULL, '2027-02-22', 'anual', 'Usuario: administracion@colegiodesanjose.edu.ar', 1, '2026-08-18 23:17:58', '2026-08-18 23:17:58'),
(7, 'GODADDY', 'Sitio web del colegio', NULL, 'ARS', NULL, '2028-02-20', 'anual', 'Cliente 144796833, PIN 9613.', 1, '2026-08-18 23:17:58', '2026-08-18 23:17:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_sistema`
--

CREATE TABLE `usuarios_sistema` (
  `id` int(11) NOT NULL,
  `nombre` varchar(120) NOT NULL,
  `email` varchar(150) NOT NULL,
  `usuario` varchar(60) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `rol` enum('admin','tecnico','lectura') NOT NULL DEFAULT 'tecnico',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `ultimo_acceso` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios_sistema`
--

INSERT INTO `usuarios_sistema` (`id`, `nombre`, `email`, `usuario`, `password_hash`, `rol`, `activo`, `ultimo_acceso`, `created_at`) VALUES
(1, 'Administrador', 'admin@colegio.edu.ar', 'admin', '$2y$12$hCqn3X3E0gOW5usBz7kSION.VtUs8Hy0ERA3YvjeVQR7Is/qswdum', 'admin', 1, '2026-08-18 21:40:10', '2026-08-18 21:34:02');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accesos`
--
ALTER TABLE `accesos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `creado_por` (`creado_por`);

--
-- Indices de la tabla `categorias_accesos`
--
ALTER TABLE `categorias_accesos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `categorias_documentos`
--
ALTER TABLE `categorias_documentos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `subido_por` (`subido_por`);

--
-- Indices de la tabla `equipos`
--
ALTER TABLE `equipos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_equipos_mac` (`mac`),
  ADD KEY `idx_equipos_ip` (`ip`),
  ADD KEY `idx_equipos_aula` (`aula`),
  ADD KEY `idx_equipos_piso` (`piso`),
  ADD KEY `idx_equipos_curso` (`curso`);

--
-- Indices de la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mantenimientos_equipo` (`equipo_id`),
  ADD KEY `idx_mantenimientos_fecha` (`fecha`);

--
-- Indices de la tabla `red_conexiones`
--
ALTER TABLE `red_conexiones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nodo_origen_id` (`nodo_origen_id`),
  ADD KEY `nodo_destino_id` (`nodo_destino_id`);

--
-- Indices de la tabla `red_dispositivos`
--
ALTER TABLE `red_dispositivos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `red_nodos`
--
ALTER TABLE `red_nodos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `renovaciones_historial`
--
ALTER TABLE `renovaciones_historial`
  ADD PRIMARY KEY (`id`),
  ADD KEY `suscripcion_id` (`suscripcion_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `suscripciones`
--
ALTER TABLE `suscripciones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios_sistema`
--
ALTER TABLE `usuarios_sistema`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accesos`
--
ALTER TABLE `accesos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT de la tabla `categorias_accesos`
--
ALTER TABLE `categorias_accesos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `categorias_documentos`
--
ALTER TABLE `categorias_documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `documentos`
--
ALTER TABLE `documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `equipos`
--
ALTER TABLE `equipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT de la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `red_conexiones`
--
ALTER TABLE `red_conexiones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `red_dispositivos`
--
ALTER TABLE `red_dispositivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `red_nodos`
--
ALTER TABLE `red_nodos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT de la tabla `renovaciones_historial`
--
ALTER TABLE `renovaciones_historial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `suscripciones`
--
ALTER TABLE `suscripciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuarios_sistema`
--
ALTER TABLE `usuarios_sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `accesos`
--
ALTER TABLE `accesos`
  ADD CONSTRAINT `accesos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_accesos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `accesos_ibfk_2` FOREIGN KEY (`creado_por`) REFERENCES `usuarios_sistema` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `documentos`
--
ALTER TABLE `documentos`
  ADD CONSTRAINT `documentos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_documentos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `documentos_ibfk_2` FOREIGN KEY (`subido_por`) REFERENCES `usuarios_sistema` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  ADD CONSTRAINT `mantenimientos_ibfk_1` FOREIGN KEY (`equipo_id`) REFERENCES `equipos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `red_conexiones`
--
ALTER TABLE `red_conexiones`
  ADD CONSTRAINT `red_conexiones_ibfk_1` FOREIGN KEY (`nodo_origen_id`) REFERENCES `red_nodos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `red_conexiones_ibfk_2` FOREIGN KEY (`nodo_destino_id`) REFERENCES `red_nodos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `renovaciones_historial`
--
ALTER TABLE `renovaciones_historial`
  ADD CONSTRAINT `renovaciones_historial_ibfk_1` FOREIGN KEY (`suscripcion_id`) REFERENCES `suscripciones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `renovaciones_historial_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios_sistema` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
