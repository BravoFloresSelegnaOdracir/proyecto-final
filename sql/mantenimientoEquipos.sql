CREATE DATABASE mantenimientoEquipo;
USE mantenimientoEquipo;
create table area(
    clave varchar(4) PRIMARY KEY,
    nombre varchar(50) not null,
    ubicacion varchar(200) not null,
    descripcion varchar(100)
);
create table estadoEquipo(
    codigo varchar(4) PRIMARY KEY,
    descripcion varchar(35) not null
);
create table tipoModelo(
    codigoTE varchar(4) PRIMARY KEY,
    nombre varchar (30) not null,
    descripcion varchar(80) not null
);
create table marca(
    codigoMarca varchar(3) PRIMARY KEY,
    nombre varchar(50) not null 
);
create table prioridad(
    codigo varchar(3) PRIMARY KEY,
    descripcion varchar(10) not null
);
CREATE TABLE estadoIncidencia(
    codigo varchar(3) PRIMARY KEY,
    descripcion varchar(20) not null
);
CREATE TABLE tipoMantenimiento(
    codMante VARCHAR(3) PRIMARY KEY,
    descripcion VARCHAR(15) NOT NULL UNIQUE
);
CREATE TABLE estadoMantenimiento(
    codigo VARCHAR(3) PRIMARY KEY,
    descripcion VARCHAR(10) NOT NULL
);
create table materiales(
    codigoMaterial VARCHAR(5) PRIMARY KEY,
    nombre varchar(60) not null,
    stock INT,
    precio INT NOT NULL
);
create table estadoSolicitud(
    codigo VARCHAR(3) PRIMARY KEY,
    descripcion VARCHAR(10) NOT NULL
);
CREATE TABLE tipoEmpleado(
    codigo VARCHAR(3) PRIMARY KEY,
    descripcion VARCHAR(15) NOT NULL
);
CREATE TABLE tipoReporte(
    codigo VARCHAR(4) PRIMARY KEY,
    descripcion VARCHAR(20) NOT NULL
);
CREATE TABLE especialidad(
    codigo VARCHAR(3) PRIMARY KEY,
    descripcion VARCHAR(50) not NULL
);
CREATE TABLE turnoLaboral(
    claveTurno VARCHAR(3) PRIMARY KEY,
    nombre VARCHAR(25) NOT NULL,
    horaInicio INT NOT NULL,
    horaFin INT NOT NULL
);
CREATE TABLE disponibilidad(
    codigo VARCHAR(3) PRIMARY KEY,
    descripcion VARCHAR(15) NOT NULL
);
create table modelo(
    codigoModelo varchar(3) primary key,
    nombre varchar(30) not null,
    vidaUtilEstimada decimal(4,2) not null,
    marca VARCHAR(3),
    Foreign Key (marca) REFERENCES marca(codigoMarca),
    tipo VARCHAR(4),
    Foreign Key (tipo) REFERENCES tipoModelo(codigoTE)
);
create table equipo(
numeroSerie varchar(10) PRIMARY KEY,
    nombre varchar(50) not null,
    fechaCompra date not null,
    tiempoInactivo decimal(10, 2),
    tiempoOperativo decimal(10, 2),
    funcionalidad decimal(5, 2) AS ((tiempoOperativo / (tiempoOperativo + tiempoInactivo)) * 100) STORED,
    precioCompra decimal(10, 2),
    costo decimal(10, 2) DEFAULT 0,
    modelo varchar(3),
    marca varchar(3),
    estadoEquipo varchar(4),
    area varchar(4),
    foreign key (modelo) references modelo(codigoModelo),
    foreign key (marca) references marca(codigoMarca),
    foreign key (estadoEquipo) references estadoEquipo(codigo),
    foreign key (area) references area(clave)
);
CREATE TABLE empleado(
    noEmpleado INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(35) NOT NULL,
    apellidoP VARCHAR(20) NOT NULL,
    apellidoM VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(50),
    noTelefono VARCHAR(13),
    tipoEmpleado VARCHAR(3),
    Foreign Key (tipoEmpleado) REFERENCES tipoEmpleado(codigo)
);
ALTER TABLE empleado AUTO_INCREMENT = 100;
CREATE TABLE tecnico (
    noTecnico INT PRIMARY KEY,
    noIncidenciasA INT,
    disponibilidad VARCHAR(3),
    especialidad VARCHAR(3),
    turnoLaboral VARCHAR(3),
    FOREIGN KEY (noTecnico) REFERENCES empleado(noEmpleado),
    FOREIGN KEY (disponibilidad) REFERENCES disponibilidad(codigo),
    FOREIGN KEY (especialidad) REFERENCES especialidad(codigo),
    FOREIGN KEY (turnoLaboral) REFERENCES turnoLaboral(claveTurno)
);
CREATE Table reporteEquipo(
    numeroReporte INT PRIMARY KEY AUTO_INCREMENT,
    reporte TEXT NOT NULL,
    fecha DATE DEFAULT CURRENT_TIMESTAMP,
    equipo VARCHAR(10),
    Foreign Key (equipo) REFERENCES equipo(numeroSerie),
    tipoReporte VARCHAR(4),
    Foreign Key (tipoReporte) REFERENCES tipoReporte(codigo),
    gerente INT,
    Foreign Key (gerente) REFERENCES empleado(noEmpleado)
);
CREATE table incidencia(
    noIncidencia INT PRIMARY KEY AUTO_INCREMENT,
    descripcion VARCHAR(500) NOT NULL,
    fechaInicio DATE DEFAULT CURRENT_TIMESTAMP,
    fechaCierre DATE,
    principalProblema VARCHAR(3),
    Foreign Key (principalProblema) REFERENCES especialidad(codigo),
    prioridad VARCHAR(3),
    Foreign Key (prioridad) REFERENCES prioridad(codigo),
    equipo VARCHAR(10),
    Foreign Key (equipo) REFERENCES equipo(numeroSerie),
    tecnicoAsignado INT,
    Foreign Key (tecnicoAsignado) REFERENCES tecnico(noTecnico),
    estado VARCHAR(3),
    Foreign Key (estado) REFERENCES estadoIncidencia(codigo)
);
ALTER TABLE incidencia
ADD COLUMN created INT AFTER tecnicoAsignado;
ALTER TABLE incidencia
ADD CONSTRAINT fk_created_empleado
FOREIGN KEY (created) REFERENCES empleado(noEmpleado);
CREATE TABLE mantenimiento(
    noMantenimiento INT PRIMARY KEY AUTO_INCREMENT,
    fechaProgramada DATETIME,
    descripcion VARCHAR(500) NOT NULL,
    tiempoInactivo DECIMAL(5, 2),
    costo DECIMAL(5, 2),
    tipoMantenimiento VARCHAR(3),
    Foreign Key (tipoMantenimiento) REFERENCES tipoMantenimiento(codMante),
    estado VARCHAR(3),
    Foreign Key (estado) REFERENCES estadoMantenimiento(codigo),
    incidencia INT,
    Foreign Key (incidencia) REFERENCES incidencia(noIncidencia),
    equipo VARCHAR(10),
    Foreign Key (equipo) REFERENCES equipo(numeroSerie)
);
ALTER TABLE mantenimiento
ADD COLUMN tecnico INT AFTER equipo;
ALTER TABLE mantenimiento
ADD CONSTRAINT fk_tecnico_empleado
FOREIGN KEY (tecnico) REFERENCES tecnico(noTecnico);
CREATE TABLE reparacion(
    noRepar INT PRIMARY KEY AUTO_INCREMENT,
    horaInicio DATETIME NOT NULL,
    horaFinal DATETIME,
    tiempoInactivo DECIMAL(5, 2),
    descripcion TEXT,
    costo DECIMAL(5, 2),
    mantenimiento INT,
    Foreign Key (mantenimiento) REFERENCES mantenimiento(noMantenimiento)
);
create table materialesReparacion(
    material VARCHAR(5),
    reparacion INT,
    cantidad INT NOT NULL,
    importe DECIMAL(5, 2) NOT NULL,
    Foreign Key (material) REFERENCES materiales(codigoMaterial),
    Foreign Key (reparacion) REFERENCES reparacion(noRepar)
);
create table materialesMantenimiento(
    mantenimiento INT,
    material VARCHAR(5),
    cantidad INT NOT NULL,
    importe DECIMAL(5, 2) NOT NULL,
    Foreign Key (mantenimiento) REFERENCES mantenimiento(noMantenimiento),
    Foreign Key (material) REFERENCES materiales(codigoMaterial)
);
create table solicitudMateriales(
    noSolicitud INT PRIMARY KEY AUTO_INCREMENT,
    fechaSolicitud DATE DEFAULT CURRENT_TIMESTAMP,
    fechaEntrega DATE,
    costoTotal DECIMAL(5, 2),
    estado VARCHAR(3),
    Foreign Key (estado) REFERENCES estadoSolicitud(codigo),
    gerente INT,
    Foreign Key (gerente) REFERENCES empleado(noEmpleado),
    equipo VARCHAR(10),
    Foreign Key (equipo) REFERENCES equipo(numeroSerie)
);
ALTER TABLE solicitudMateriales
ADD COLUMN tecnico INT AFTER gerente,
ADD FOREIGN KEY (tecnico) REFERENCES tecnico(noTecnico);

create table materialSolicitud(
    material VARCHAR(5),
    solicitud INT,
    cantidad INT NOT NULL,
    importe DECIMAL(5, 2),
    Foreign Key (material) REFERENCES materiales(codigoMaterial),
    Foreign Key (solicitud) REFERENCES solicitudMateriales(noSolicitud)
);
CREATE TABLE notificacionIncidencia (
    noNotificacion INT PRIMARY KEY AUTO_INCREMENT,
    noIncidencia INT,
    tecnicoAsignado INT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    leida BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (noIncidencia) REFERENCES incidencia(noIncidencia),
    FOREIGN KEY (tecnicoAsignado) REFERENCES tecnico(noTecnico)
);
create table notificacionMaterial(
    noNotificacion INT PRIMARY KEY AUTO_INCREMENT,
    solicitudMaterial INT,
    gerenteAsignado INT,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    leida BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (solicitudMaterial) REFERENCES solicitudMateriales(noSolicitud),
    FOREIGN KEY (gerenteAsignado) REFERENCES empleado(noEmpleado)   
);
create table solicitudAprobada(
    noNotificacion INT PRIMARY KEY AUTO_INCREMENT,
    noSolicitud INT,
    tecnicoAsignado INT,
    estadoAprobacion ENUM('Approved', 'Rejected'),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    leida BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (noSolicitud) REFERENCES solicitudMateriales(noSolicitud),
    FOREIGN KEY (tecnicoAsignado) REFERENCES tecnico(noTecnico)
);


select * from empleado