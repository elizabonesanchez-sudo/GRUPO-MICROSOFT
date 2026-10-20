-- =============================================================================
-- Base de Datos: `eysfwis`
-- Sistema: eysfwis (CodeIgniter 4)
-- Script SQL preparado para creación local y despliegue en la nube (Cloud / Hosting)
-- Compatible con: MySQL 5.7+, MySQL 8.0+, MariaDB 10.3+, AWS RDS,
--                 Google Cloud SQL, Azure Database for MySQL, Railway,
--                 Clever Cloud, Aiven, phpMyAdmin, cPanel, Docker, etc.
-- =============================================================================

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- -----------------------------------------------------------------------------
-- 1. Creación y selección de la Base de Datos
-- NOTA PARA DESPLIEGUE CLOUD:
-- Si su proveedor de hosting o nube (ej. Clever Cloud, Railway, PlanetScale,
-- Freesqldatabase, etc.) ya le proporciona una base de datos con nombre asignado,
-- puede comentar o ignorar las dos líneas siguientes e importar el contenido
-- directamente en dicha base de datos.
-- -----------------------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS `eysfwis` 
  DEFAULT CHARACTER SET utf8mb4 
  COLLATE utf8mb4_unicode_ci;

USE `eysfwis`;

-- -----------------------------------------------------------------------------
-- 2. Limpieza de tablas y vistas previas
-- -----------------------------------------------------------------------------
DROP VIEW IF EXISTS `correo`;
DROP TABLE IF EXISTS `correoelectronico`;
DROP TABLE IF EXISTS `direccion`;
DROP TABLE IF EXISTS `persona`;
DROP TABLE IF EXISTS `estadocivil`;
DROP TABLE IF EXISTS `sexo`;
DROP TABLE IF EXISTS `migrations`;

-- -----------------------------------------------------------------------------
-- 3. Tabla: `sexo`
-- -----------------------------------------------------------------------------
CREATE TABLE `sexo` (
  `idsexo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`idsexo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `sexo` WRITE;
/*!40000 ALTER TABLE `sexo` DISABLE KEYS */;
INSERT INTO `sexo` (`idsexo`, `nombre`) VALUES
(1, 'Masculino'),
(2, 'Femenino'),
(3, 'No Binario'),
(4, 'Prefiero no especificar');
/*!40000 ALTER TABLE `sexo` ENABLE KEYS */;
UNLOCK TABLES;

-- -----------------------------------------------------------------------------
-- 4. Tabla: `estadocivil`
-- -----------------------------------------------------------------------------
CREATE TABLE `estadocivil` (
  `idestadocivil` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`idestadocivil`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `estadocivil` WRITE;
/*!40000 ALTER TABLE `estadocivil` DISABLE KEYS */;
INSERT INTO `estadocivil` (`idestadocivil`, `nombre`) VALUES
(1, 'Soltero/a'),
(2, 'Casado/a'),
(3, 'Divorciado/a'),
(4, 'Viudo/a'),
(5, 'Unión Libre / De Hecho');
/*!40000 ALTER TABLE `estadocivil` ENABLE KEYS */;
UNLOCK TABLES;

-- -----------------------------------------------------------------------------
-- 5. Tabla: `persona`
-- -----------------------------------------------------------------------------
CREATE TABLE `persona` (
  `idpersona` int(11) NOT NULL AUTO_INCREMENT,
  `cedula` varchar(20) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `fechanacimiento` date DEFAULT NULL,
  `idsexo` int(11) DEFAULT NULL,
  `idestadocivil` int(11) DEFAULT NULL,
  PRIMARY KEY (`idpersona`),
  UNIQUE KEY `cedula` (`cedula`),
  KEY `idsexo` (`idsexo`),
  KEY `idestadocivil` (`idestadocivil`),
  CONSTRAINT `persona_idsexo_foreign` FOREIGN KEY (`idsexo`) REFERENCES `sexo` (`idsexo`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `persona_idestadocivil_foreign` FOREIGN KEY (`idestadocivil`) REFERENCES `estadocivil` (`idestadocivil`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` (`idpersona`, `cedula`, `nombres`, `apellidos`, `fechanacimiento`, `idsexo`, `idestadocivil`) VALUES
(1, '0801234567', 'Carlos Alberto', 'Mendoza Zambrano', '1992-05-14', 1, 1),
(2, '0802345678', 'María Fernanda', 'Quiñónez Hurtado', '1995-11-23', 2, 2),
(3, '0803456789', 'Luis Eduardo', 'Valencia Caicedo', '1988-03-30', 1, 5),
(4, '0804567890', 'Ana Patricia', 'Estupiñán Cortez', '1999-08-19', 2, 1),
(5, '0805678901', 'Alex Gabriel', 'Montaño Bone', '2001-01-10', 3, 1),
(6, '0806789012', 'Rosa Elena', 'Arroyo Mina', '1985-07-04', 2, 3);
/*!40000 ALTER TABLE `persona` ENABLE KEYS */;
UNLOCK TABLES;

-- -----------------------------------------------------------------------------
-- 6. Tabla: `direccion`
-- -----------------------------------------------------------------------------
CREATE TABLE `direccion` (
  `iddireccion` int(11) NOT NULL AUTO_INCREMENT,
  `idpersona` int(11) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT 'Domicilio',
  PRIMARY KEY (`iddireccion`),
  KEY `idpersona` (`idpersona`),
  CONSTRAINT `direccion_idpersona_foreign` FOREIGN KEY (`idpersona`) REFERENCES `persona` (`idpersona`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `direccion` WRITE;
/*!40000 ALTER TABLE `direccion` DISABLE KEYS */;
INSERT INTO `direccion` (`iddireccion`, `idpersona`, `direccion`, `nombre`, `tipo`) VALUES
(1, 1, 'Av. Olmedo y Rocafuerte, Barrio Central', 'Av. Olmedo y Rocafuerte, Barrio Central', 'Domicilio'),
(2, 1, 'Ciudadela Tolita 1, Manzana B Villa 12', 'Ciudadela Tolita 1, Manzana B Villa 12', 'Trabajo'),
(3, 2, 'Calle Bolívar 405 y 10 de Agosto', 'Calle Bolívar 405 y 10 de Agosto', 'Domicilio'),
(4, 3, 'Av. Kennedy frente al Parque Infantil', 'Av. Kennedy frente al Parque Infantil', 'Domicilio'),
(5, 4, 'Sector Las Palmas, Malecón y Calle 6', 'Sector Las Palmas, Malecón y Calle 6', 'Domicilio'),
(6, 5, 'Av. Pedro Vicente Maldonado y Sucre', 'Av. Pedro Vicente Maldonado y Sucre', 'Domicilio'),
(7, 6, 'Barrio Nueva Esperanza, Calle Principal #45', 'Barrio Nueva Esperanza, Calle Principal #45', 'Domicilio');
/*!40000 ALTER TABLE `direccion` ENABLE KEYS */;
UNLOCK TABLES;

-- -----------------------------------------------------------------------------
-- 7. Tabla: `correoelectronico`
-- -----------------------------------------------------------------------------
CREATE TABLE `correoelectronico` (
  `idcorreoelectronico` int(11) NOT NULL AUTO_INCREMENT,
  `idpersona` int(11) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `nombre` varchar(150) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT 'Personal',
  `fechaoptencion` date DEFAULT NULL,
  PRIMARY KEY (`idcorreoelectronico`),
  KEY `idpersona` (`idpersona`),
  CONSTRAINT `correoelectronico_idpersona_foreign` FOREIGN KEY (`idpersona`) REFERENCES `persona` (`idpersona`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `correoelectronico` WRITE;
/*!40000 ALTER TABLE `correoelectronico` DISABLE KEYS */;
INSERT INTO `correoelectronico` (`idcorreoelectronico`, `idpersona`, `correo`, `nombre`, `tipo`, `fechaoptencion`) VALUES
(1, 1, 'carlos.mendoza@gmail.com', 'carlos.mendoza@gmail.com', 'Personal', '2023-01-15'),
(2, 1, 'cmendoza@empresa.ec', 'cmendoza@empresa.ec', 'Institucional', '2023-06-20'),
(3, 2, 'maria.fernanda.q@outlook.com', 'maria.fernanda.q@outlook.com', 'Personal', '2022-10-10'),
(4, 3, 'luis.valencia.c@hotmail.com', 'luis.valencia.c@hotmail.com', 'Personal', '2021-04-12'),
(5, 4, 'ana.estupinan@utlvte.edu.ec', 'ana.estupinan@utlvte.edu.ec', 'Institucional', '2024-02-01'),
(6, 5, 'alex.montano@yahoo.com', 'alex.montano@yahoo.com', 'Personal', '2024-05-18'),
(7, 6, 'rosa.arroyo@gmail.com', 'rosa.arroyo@gmail.com', 'Personal', '2020-09-05');
/*!40000 ALTER TABLE `correoelectronico` ENABLE KEYS */;
UNLOCK TABLES;

-- -----------------------------------------------------------------------------
-- 8. Tabla: `migrations` (Control de versiones de CodeIgniter 4)
-- -----------------------------------------------------------------------------
CREATE TABLE `migrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-10-12-212629', 'App\\Database\\Migrations\\CreatePersonaTables', 'default', 'App', 1791840434, 1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

-- -----------------------------------------------------------------------------
-- 9. Vista: `correo`
-- Creada sin cláusula DEFINER explícita para evitar errores de permisos 'SUPER'
-- o 'Definer user does not exist' en motores cloud gestionados.
-- -----------------------------------------------------------------------------
CREATE OR REPLACE VIEW `correo` AS 
SELECT 
  `idcorreoelectronico` AS `idcorreo`,
  `idcorreoelectronico`,
  `idpersona`,
  `correo`,
  `nombre`,
  `tipo`,
  `fechaoptencion` 
FROM `correoelectronico`;

-- -----------------------------------------------------------------------------
-- 10. Restauración de parámetros
-- -----------------------------------------------------------------------------
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Fin del script SQL
