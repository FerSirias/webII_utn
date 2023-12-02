CREATE DATABASE  IF NOT EXISTS `areaEstudiantil` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `areaEstudiantil`;

CREATE TABLE `Personas` (
  `persona_id` int unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `cedula` int unsigned UNIQUE NOT NULL,
  `nombre` varchar(20) NOT NULL,
  `apellidos` varchar(20) NOT NULL,
  `direccion` varchar(100),
  `genero` char NOT NULL,
  `fechaNacimiento` date NOT NULL,
  `nacionalidad` varchar(15) NOT NULL,
  `correo_electronico` varchar(30) NOT NULL,
  `num_telefonico` int NOT NULL
)engine InnoDB char set utf8mb4;

CREATE TABLE `Areas` (                           /*Debe estar los datos cargados a la BD*/
  `areaId` tinyint unsigned PRIMARY KEY NOT NULL,
  `nombre` varchar(35) NOT NULL
)engine InnoDB char set utf8mb4;

CREATE TABLE `Funcionarios` (
  `funcionarioId` smallint unsigned PRIMARY KEY NOT NULL,
  `correoInstitucional` varchar(30) NOT NULL,
  `password` varchar(50) NOT NULL,
  `cedulaFuncionario` int unsigned NOT NULL,
  `areaId` tinyint unsigned NOT NULL,
  `rol_id` tinyint unsigned NOT NULL
)engine InnoDB char set utf8mb4;

CREATE TABLE `Citas` (
  `citaId` smallint unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `solicitud_id` smallint unsigned NOT NULL,  /*capturar este dato en el procedimiento almacenado insertSolicitud*/
  `funcionarioId` smallint unsigned NOT NULL,          /*capturar este dato en el procedimiento almacenado insertFuncionario*/
  `fechaCita` datetime NOT NULL,
  `status` char(1) NOT NULL
)engine InnoDB char set utf8mb4;

CREATE TABLE `Informes`(
	`citaId` smallint unsigned PRIMARY KEY NOT NULL,
    `descripcion` text NOT NULL,
    `inicio` datetime NOT NULL,
    `final` datetime NOT NULL
)engine InnoDB char set utf8mb4;

CREATE TABLE `Estudiantes` (                                      /*Ya los estudiantes deben estar cargados en la base de datos*/
  `estudiante_id` int unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `cedulaEstudiante` int unsigned NOT NULL,
  `ayudaFamiliar` char(1)  NOT NULL,
  `beca` char(1) NOT NULL
)engine InnoDB char set utf8mb4;

CREATE TABLE `Solicitudes` (
  `solicitud_id` smallint unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `estudiante_id` int unsigned NOT NULL,
  `motivo` varchar(100) NOT NULL,
  `fechaSolicitud` datetime NOT NULL,
  `areaAtencion` tinyint unsigned NOT NULL
)engine InnoDB char set utf8mb4;

CREATE TABLE `Cursos` (
  `codigoCurso` smallint unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `estudiante_id` int unsigned NOT NULL,
  `cantCursos` tinyint unsigned NOT NULL,
  `codCuatri`  varchar(9) NOT NULL
)engine InnoDB char set utf8mb4;


CREATE TABLE `Roles` (                                /*Debe estar cargados en la BD*/
  `rol_id` tinyint unsigned PRIMARY KEY NOT NULL,
  `roleName` varchar(35) NOT NULL
)engine InnoDB char set utf8mb4;

ALTER TABLE `Informes` ADD FOREIGN KEY (`citaId`)  REFERENCES `Citas` (`citaId`);

ALTER TABLE `Funcionarios` ADD FOREIGN KEY (`cedulaFuncionario`) REFERENCES `Personas` (`persona_id`);

ALTER TABLE `Estudiantes` ADD FOREIGN KEY (`cedulaEstudiante`) REFERENCES `Personas` (`persona_id`);

ALTER TABLE `Funcionarios` ADD FOREIGN KEY (`rol_id`) REFERENCES `Roles` (`rol_id`);

ALTER TABLE `Solicitudes` ADD FOREIGN KEY (`estudiante_id`) REFERENCES `Estudiantes` (`estudiante_id`);

ALTER TABLE `Cursos` ADD FOREIGN KEY (`estudiante_id`) REFERENCES `Estudiantes` (`estudiante_id`);

ALTER TABLE `Citas` ADD FOREIGN KEY (`solicitud_id`) REFERENCES `Solicitudes` (`solicitud_id`);

ALTER TABLE `Funcionarios` ADD FOREIGN KEY (`areaId`) REFERENCES `Areas` (`areaId`);

ALTER TABLE `Citas` ADD FOREIGN KEY (`funcionarioId`) REFERENCES `Funcionarios` (`funcionarioId`);

/*INSERT INTO personas (cedula,nombre,apellidos,direccion,genero,fechaNacimiento,nacionalidad,correo_electronico,num_telefonico)
VALUES (118967542, 'Maria', 'Monge Rodriguez','Tivives,Puntarenas','F','2004-02-10','Costarricense','marry10gmail.com',88782007);*/

delimiter //
create procedure insestudiante(in ayuda  char(1), 
                               in beca   char(1), 
                               in cursos tinyint ,
							   in cuatri varchar(9))
begin
	set @var=(select max(persona_id) from personas);
	insert into estudiantes(cedulaEstudiante,ayudaFamiliar,beca) values(@var,ayuda,beca);
    set @conce=(select max(estudiante_id) from estudiantes);
    insert into cursos(estudiante_id,cantCursos,codCuatri) values(@conce,cursos,cuatri);
end//
delimiter ;

-- call insestudiante('S','N',5,'III-2023');
/*select * from personas;
select * from estudiantes;
select * from cursos;*/

/*INSERT INTO personas(cedula,nombre,apellidos,direccion,genero,fechaNacimiento,nacionalidad,correo_electronico,num_telefonico)
VALUES (217438745, 'Ana Cecilia', 'Viquez Cantilano','Puntarenas,Puntarenas','F','1978-07-04','Nicaragüense','anav78gmail.com',20177321);*/

delimiter $$
create procedure insfunc(in func   smallint unsigned, 
						 in correo varchar(30), 
						 in clave  varchar(50) ,
                         in area   tinyint unsigned,
                         in rol    tinyint unsigned)
begin
	set @var=(select max(persona_id) from personas);
	insert into funcionarios(funcionarioId,correoInstitucional,password,cedulaFuncionario,areaId,rol_id) 
    values(func,correo,md5(clave),@var,area,rol);
end$$
delimiter ;

-- call insfunc(1,'cecia@est.utn.ac.cr','panda29',1,1);
/*select * from funcionarios;
select * from roles;
select * from areas;*/

delimiter $$
create procedure inssolic(in motivo varchar(100), 
						  in areaA  tinyint unsigned)
begin
	set @var=(select max(estudiante_id) from estudiantes);
	insert into solicitudes(estudiante_id,motivo,fechaSolicitud,areaAtencion) 
    values(@var,motivo,now(),areaA);
end$$
delimiter ;

-- Call inssolic('Requiero de su ayuda, ya que he pensado darme de baja de un curso que estoy llevando actualmente.',3);
/*select * from solicitudes;
select * from estudiantes;
select * from areas;*/

delimiter //
create procedure inscita (in fecha datetime,
						  in status char(1))
begin
	set @var=(select max(solicitud_id) from solicitudes);
    set @conce=(select max(funcionarioId) from funcionarios);
	insert into citas(solicitud_id,funcionarioId,fechaCita,status) values(@var,@conce,fecha,status);
end//
delimiter ;

-- Call inscita('2023-12-01 13:00:00','A');
-- Select * from citas;

delimiter $$
create procedure insinforme(in descripcion text, 
						    in inicio datetime,
                            in final datetime)
begin
	set @var=(select max(citaId) from citas);
	insert into informes(citaId,descripcion,inicio,final) 
    values(@var,descripcion,inicio,final);
end$$
delimiter ;

-- Call insinforme('Durante nuestras sesiones, hemos explorado varios factores que podrían estar contribuyendo a su estado actual, incluyendo estrés laboral y dinámicas familiares. Hemos notado que "x" responde bien a la terapia cognitivo-conductual, mostrando una mejora gradual en la identificación y gestión de sus pensamientos y emociones negativas.El plan de tratamiento incluye continuar con sesiones semanales de terapia, con un enfoque en estrategias de afrontamiento y técnicas de relajación. Además, hemos discutido la posibilidad de incorporar medicación si los síntomas persisten o empeoran. Es importante destacar la actitud proactiva de "x", en su proceso de recuperación y su disposición a trabajar en las estrategias terapéuticas propuestas.Continuaremos monitoreando su progreso y ajustaremos el plan según sea necesario. También recomendamos una evaluación de seguimiento dentro de tres meses para revisar su estado general y hacer cualquier ajuste necesario en su tratamiento','2023-11-30 15:45:00','2023-11-30 16:50:30');
-- select * from informes;

delimiter $$
create procedure updcita(in cita smallint unsigned,
						 in fecha  datetime,
                         in statu  char(1))
begin
    UPDATE citas
    SET fechaCita = fecha,
		status = statu
    WHERE citaId = cita;
end$$
delimiter ;

/*Call updcita(1,'2024-01-28 20:14:43','T');
Select * from citas;*/

delimiter //
create procedure deleteinfo(in cita smallint unsigned)
begin
    delete from informes where citaId = cita;
end //
delimiter ;

/*Call deleteinfo(1);
select * from informes;*/

delimiter //
create procedure deletecita(in cita smallint unsigned)
begin
    delete from citas where citaId = cita;
end //
delimiter ;

/*Call deletecita(1);
select * from citas;*/

delimiter //
create procedure deletesoli(in solic smallint unsigned)
begin
    delete from solicitudes where solicitud_id = solic;
end //
delimiter ;

/*Call deletesoli(1);
select * from solicitudes;*/

Insert into areas(areaId,nombre)
values(1,'Psicología');
Insert into areas(areaId,nombre)
values(2,'Psicopedagogía');
Insert into areas(areaId,nombre)
values(3,'Orientación Estudiantil');

Insert into roles(rol_id,roleName)
values(1,'Admin');
Insert into roles(rol_id,roleName)
values(2,'Psicopedagogo');
Insert into roles(rol_id,roleName)
values(3,'Orientador');
Insert into roles(rol_id,roleName)
values(4,'Psicologo');

INSERT INTO personas (cedula,nombre,apellidos,direccion,genero,fechaNacimiento,nacionalidad,correo_electronico,num_telefonico)
VALUES (118967542, 'Maria', 'Monge Rodriguez','Tivives,Puntarenas','F','2004-02-10','Costarricense','marry10gmail.com',88782007);
INSERT INTO personas (cedula,nombre,apellidos,direccion,genero,fechaNacimiento,nacionalidad,correo_electronico,num_telefonico)
VALUES (122740124, 'Sebastián Alberto', 'Mata Vazquez','Fiestas del Mar,Puntarenas','M','2001-03-31','Costarricense','sebas@gmail.com',60128543);
INSERT INTO personas(cedula,nombre,apellidos,direccion,genero,fechaNacimiento,nacionalidad,correo_electronico,num_telefonico)
VALUES (217438745, 'Ana Cecilia', 'Viquez Cantilano','Puntarenas,Puntarenas','F','1978-07-04','Nicaragüense','anav78gmail.com',20177321);
INSERT INTO personas(cedula,nombre,apellidos,direccion,genero,fechaNacimiento,nacionalidad,correo_electronico,num_telefonico)
VALUES (737821956, 'Jorge Felix', 'Ruiz Vargas','El Roble,Puntarenas','M','1960-07-08','Estadounidense','ruizvfeliz@gmail.com',89784567);
INSERT INTO personas(cedula,nombre,apellidos,direccion,genero,fechaNacimiento,nacionalidad,correo_electronico,num_telefonico)
VALUES (519404182, 'Luis Miguel', 'Soto Brucellas','Esparza,Puntarenas','M','1958-02-14','Costarricense','luisga@gmail.com',58416978);
INSERT INTO personas(cedula,nombre,apellidos,direccion,genero,fechaNacimiento,nacionalidad,correo_electronico,num_telefonico)
VALUES (602593148, 'Ana Gabril', 'Quintanilla Céspedes','San Mateo,Alajuela','F','1971-09-21','Costarricense','quintana@gmail.com',89451203);