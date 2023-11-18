CREATE DATABASE  IF NOT EXISTS `areaEstudiantil` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `areaEstudiantil`;

CREATE TABLE `Personas` (
  `persona_id` int PRIMARY KEY NOT NULL,
  `cedula` varchar(255) UNIQUE NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL,
  `idDistrito` int NOT NULL,
  `info_id` int NOT NULL,
  `genero` char NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `fechaNacimiento` date NOT NULL,
  `nacionalidad` varchar(255) NOT NULL,
  `foto` blob
);

CREATE TABLE `Info_contactos` (
  `info_id` int PRIMARY KEY NOT NULL,
  `correo_electronico` varchar(255) NOT NULL,
  `num_telefonico` int NOT NULL
);

CREATE TABLE `Funcionarios` (
  `FuncionarioId` varchar(255) PRIMARY KEY NOT NULL,
  `usuario_id` varchar(255) NOT NULL,
  `CedulaFuncionario` varchar(255) NOT NULL,
  `areaId` int NOT NULL
);

CREATE TABLE `Areas` (
  `AreaId` int PRIMARY KEY NOT NULL,
  `nombre` varchar(255) NOT NULL
);

CREATE TABLE `Citas` (
  `CitaId` int PRIMARY KEY,
  `Id_Solicitud` int UNIQUE NOT NULL,
  `funcionarioCedula` varchar(255) UNIQUE NOT NULL,
  `fechaCita` datetime NOT NULL,
  `status` varchar(150) NOT NULL
);

CREATE TABLE `Estudiantes` (
  `Estudiante_ID` int PRIMARY KEY NOT NULL,
  `CedulaEstudiante` varchar(255) UNIQUE NOT NULL,
  `AyudaFamiliar` boolean NOT NULL,
  `Beca` boolean NOT NULL,
  `CursoID` varchar(15) NOT NULL
);

CREATE TABLE `Solicitudes` (
  `Solicitud_id` int PRIMARY KEY NOT NULL,
  `CedulaEst` varchar(255) NOT NULL,
  `Motivo` varchar(255) NOT NULL,
  `FechaSolicitud` date NOT NULL,
  `AreaAtencion` varchar(255) NOT NULL
);

CREATE TABLE `Usuarios` (
  `usuario_id` varchar(255) PRIMARY KEY NOT NULL,
  `correoInstitucional` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
);

CREATE TABLE `Roles` (
  `Rol_Id` int PRIMARY KEY NOT NULL,
  `UsuarioId` VARCHAR(255) NOT NULL,
  `RoleName` varchar(255) NOT NULL
);

CREATE TABLE `Cursos` (
  `CodigoCurso` varchar(15) PRIMARY KEY NOT NULL,
  `NombreCurso` varchar(255) NOT NULL,
  `Profesor` varchar(255) NOT NULL
);

ALTER TABLE `Personas` ADD FOREIGN KEY (`info_id`) REFERENCES `Info_contactos` (`info_id`);

ALTER TABLE `Funcionarios` ADD FOREIGN KEY (`CedulaFuncionario`) REFERENCES `Personas` (`cedula`);

ALTER TABLE `Personas` ADD FOREIGN KEY (`cedula`) REFERENCES `Estudiantes` (`CedulaEstudiante`);

ALTER TABLE `Funcionarios` ADD FOREIGN KEY (`usuario_id`) REFERENCES `Usuarios` (`usuario_id`);

ALTER TABLE `Roles` ADD FOREIGN KEY (`UsuarioId`) REFERENCES `Usuarios` (`usuario_id`);

ALTER TABLE `Solicitudes` ADD FOREIGN KEY (`CedulaEst`) REFERENCES `Estudiantes` (`CedulaEstudiante`);

ALTER TABLE `Estudiantes` ADD FOREIGN KEY (`CursoID`) REFERENCES `Cursos` (`CodigoCurso`);

ALTER TABLE `Solicitudes` ADD FOREIGN KEY (`Solicitud_id`) REFERENCES `Citas` (`Id_Solicitud`);

ALTER TABLE `Funcionarios` ADD FOREIGN KEY (`areaId`) REFERENCES `Areas` (`AreaId`);

ALTER TABLE `Citas` ADD FOREIGN KEY (`funcionarioCedula`) REFERENCES `Funcionarios` (`CedulaFuncionario`);

