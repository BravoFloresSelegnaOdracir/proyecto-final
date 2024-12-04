/*
    1. Equipos por área
*/
CREATE VIEW vw_equiposPorArea AS
SELECT a.clave AS claveArea,
a.nombre AS nombreArea,
a.descripcion AS descripcionArea,
e.numeroSerie AS NumeroSerieEquipo,
e.nombre AS nombreEquipo,
e.fechaCompra AS fechaCompraEquipo,
e.tiempoInactivo AS tiempoInactivoEquipo,
e.tiempoOperativo AS tiempoOperativoEquipo,
e.funcionalidad AS funcionalidadEquipo,
e.precioCompra AS precioAdquidoEquipo,
e.costo AS costoActualEquipo,
mo.nombre AS modeloEquipo,
ma.nombre AS marcaEquipo,
ee.descripcion AS estadoEquipo
FROM area AS a
INNER JOIN equipo AS e ON e.area = a.clave
INNER JOIN modelo AS mo ON e.modelo = mo.codigoModelo
INNER JOIN marca AS ma ON e.marca = ma.codigoMarca
INNER JOIN estadoEquipo AS ee ON e.estadoEquipo = ee.codigo;
SELECT nombreArea, nombreEquipo
FROM equiposPorArea
WHERE claveArea="MONT";
/*
    2. Modelos por Marca
*/
CREATE VIEW vw_marcaModelo AS
SELECT ma.codigoMarca AS codMarca,
ma.nombre AS Marca,
mode.codigoModelo AS codModelo,
mode.nombre AS modelo,
mode.vidaUtilEstimada AS vidaUtil,
te.nombre AS tipo
FROM marca AS ma
INNER JOIN modelo AS mode ON mode.marca = ma.codigoMarca
INNER JOIN tipoModelo AS te ON mode.tipo = te.codigoTE;
SELECT *
FROM vw_marcaModelo
WHERE codMarca="ABB";
/*
    3. Ver la información de las incidencias
*/
CREATE OR REPLACE VIEW vw_Incidencias AS
SELECT 
    i.noIncidencia AS num,
    i.descripcion AS description,
    i.fechaInicio AS startDate,
    i.fechaCierre AS finishedDate,
    es.descripcion AS problem,
    e.numeroSerie AS noEquipment,
    e.nombre AS equipment,
    t.noTecnico AS noTechnician,
    CONCAT(emT.nombre, ' ', emT.apellidoP, ' ', emT.apellidoM) AS technicianName,
    ei.descripcion AS status,
    pr.descripcion AS priority,
    i.created AS opeReported,
    CONCAT(emO.nombre, ' ', emO.apellidoP, ' ', emO.apellidoM) AS operatorName
FROM incidencia AS i
INNER JOIN equipo AS e ON i.equipo = e.numeroSerie
INNER JOIN especialidad AS es ON i.principalProblema = es.codigo
INNER JOIN prioridad AS pr ON i.prioridad = pr.codigo
INNER JOIN tecnico AS t ON i.tecnicoAsignado = t.noTecnico
INNER JOIN estadoIncidencia AS ei ON i.estado = ei.codigo
INNER JOIN empleado AS emT ON t.noTecnico = emT.noEmpleado
INNER JOIN empleado AS emO ON i.created = emO.noEmpleado;
SELECT * FROM `vw_Incidencias`;
/*
    4. Ver la información de un empleado
*/
CREATE VIEW vw_profileInfo AS
SELECT noEmpleado AS num,
CONCAT(e.nombre,' ',e.apellidoP,' ',e.apellidoM) AS name,
e.email,
e.noTelefono AS cellphone,
te.descripcion AS title
FROM empleado AS e 
INNER JOIN tipoEmpleado AS te ON e.tipoEmpleado = te.codigo;
SELECT * FROM vw_profileinfo WHERE num = 100;
/*
    5. Ver las marcas
*/
CREATE VIEW vw_Marcas AS
SELECT codigoMarca AS codigo,
nombre
FROM marca;
SELECT * FROM vw_Marcas;
/*
    6. Ver las areas de la maquiladora
*/
CREATE VIEW vw_Areas AS
SELECT clave,
nombre,
ubicacion,
descripcion
FROM area;
SELECT * FROM vw_Areas;
/*
    7. Ver las incidencias que permite la búsqueda por:
    -No. equipo
    -Nombre equipo
    -No. empleado
    -Apellido empleado
    -Area
*/
CREATE VIEW vw_BusquedaIncidencias AS
SELECT i.noIncidencia AS num,
i.created AS opeReported,
i.descripcion AS description,
i.fechaInicio AS startDate,
i.fechaCierre AS finishedDate,
es.descripcion AS problem,
e.numeroSerie AS noEquipment,
e.nombre AS equipmentName,
a.nombre AS area,
t.noTecnico AS noTechnician,
ei.descripcion AS status,
pr.descripcion AS priority,
em.nombre AS name,
em.apellidoP AS lastName
FROM incidencia AS i
INNER JOIN equipo AS e ON i.equipo = e.numeroSerie
INNER JOIN especialidad AS es ON i.principalProblema = es.codigo
INNER JOIN prioridad AS pr ON i.prioridad = pr.codigo
INNER JOIN tecnico AS t ON i.tecnicoAsignado = t.noTecnico
INNER JOIN estadoIncidencia AS ei ON i.estado = ei.codigo
INNER JOIN area AS a ON e.area = a.clave
INNER JOIN empleado AS em ON t.noTecnico = em.noEmpleado;
/*
    8. Vista de los tècnicos
*/
CREATE VIEW vw_technician AS
SELECT e.noEmpleado AS noEmployee,
CONCAT(e.nombre,' ',e.apellidoP,' ',apellidoM) AS name,
t.noIncidenciasA AS assignedIncidents,
es.descripcion AS field,
tl.nombre AS scheduale,
d.descripcion AS availability
FROM tecnico AS t
INNER JOIN empleado AS e ON t.noTecnico = e.noEmpleado
INNER JOIN especialidad AS es ON t.especialidad = es.codigo
INNER JOIN turnoLaboral AS tl ON t.turnoLaboral = tl.claveTurno
INNER JOIN disponibilidad AS d ON t.disponibilidad = d.codigo
WHERE e.tipoEmpleado = 'TEC';
SELECT * FROM vw_technician;
/*
    9. Ver informaciòn de los todos los empleados
*/
CREATE VIEW vw_employees AS
SELECT noEmpleado AS num,
e.nombre AS name,
e.apellidoP AS middleName,
e.apellidoM AS lastName,
e.email,
e.noTelefono AS cellphone,
te.descripcion AS title
FROM empleado AS e 
INNER JOIN tipoEmpleado AS te ON e.tipoEmpleado = te.codigo;
/*
    10. Ver los reportes de los equipos
*/
CREATE VIEW vw_seeReports AS
SELECT re.numeroReporte AS num,
re.reporte AS details,
re.fecha AS date,
tr.descripcion AS type,
re.gerente AS created,
e.nombre AS name,
e.apellidoP AS lastName,
re.equipo AS noEquipment,
eq.nombre AS equipment,
eq.funcionalidad AS funcionability,
eq.tiempoInactivo AS down,
eq.tiempoOperativo AS running,
eq.precioCompra AS price,
eq.costo AS cost,
ee.descripcion AS status,
mo.nombre AS model,
ma.nombre AS brand,
a.nombre AS area
FROM reporteEquipo AS re
INNER JOIN tipoReporte AS tr ON re.tipoReporte = tr.codigo
INNER JOIN empleado AS e on re.gerente = noEmpleado
INNER JOIN equipo AS eq ON re.equipo = eq.numeroSerie
INNER JOIN estadoEquipo AS ee ON eq.estadoEquipo = ee.codigo
INNER JOIN modelo AS mo ON eq.modelo = mo.codigoModelo
INNER JOIN marca AS ma ON eq.marca = ma.codigoMarca
INNER JOIN area AS a ON eq.area = a.clave;
/*
    11. Vista de los mantenimientos
*/
CREATE VIEW vw_maintenance AS
SELECT m.`noMantenimiento` AS num,
m.`fechaProgramada` AS date,
m.descripcion AS description,
m.`tiempoInactivo` AS timeDown,
m.costo AS cost,
tm.descripcion AS type,
em.descripcion AS status,
e.`numeroSerie` AS noEquipment,
e.nombre AS equipment,
t.`noTecnico` AS technician,
CONCAT(ep.nombre,' ',ep.apellidoP) AS name
FROM mantenimiento AS m
INNER JOIN tipoMantenimiento AS tm ON m.`tipoMantenimiento` = tm.`codMante`
INNER JOIN equipo AS e ON m.equipo = e.`numeroSerie`
INNER JOIN `estadoMantenimiento` AS em ON m.estado = em.codigo
INNER JOIN tecnico AS t ON m.tecnico = t.`noTecnico`
INNER JOIN empleado AS ep ON t.`noTecnico` = ep.`noEmpleado`;
CREATE VIEW vw_repairs AS
SELECT r.noRepar AS numR,
r.horaInicio AS startTime,
r.horaFinal AS finishedTime,
r.tiempoInactivo AS downTime,
r.descripcion AS description,
r.costo AS cost,
r.mantenimiento AS maintenance,
m.estado AS status,
m.equipo AS noEquipment,
eq.nombre AS equipment,
m.tecnico AS technician,
CONCAT(em.nombre,' ',em.apellidoP) AS name
FROM reparacion AS r
INNER JOIN mantenimiento AS m ON r.mantenimiento = m.noMantenimiento
INNER JOIN equipo AS eq ON m.equipo = eq.numeroSerie
INNER JOIN tecnico AS t ON m.tecnico = t.noTecnico
INNER JOIN empleado AS em ON t.noTecnico = em.noEmpleado;
/*
    12. Vista de las solicitudes de materiales
*/
CREATE VIEW vw_requests AS
SELECT sm.noSolicitud AS numReq,
sm.fechaSolicitud as requestDate,
sm.fechaEntrega as delivery,
sm.costoTotal as cost,
es.codigo AS codStatus,
es.descripcion AS status,
sm.gerente AS manager,
sm.tecnico AS technician,
CONCAT(e.nombre,' ',e.apellidoP) AS name,
sm.equipo AS noEquipment,
eq.nombre
FROM solicitudMateriales AS sm
INNER JOIN estadoSolicitud AS es ON sm.estado = es.codigo
INNER JOIN empleado AS e ON sm.gerente = e.noEmpleado
INNER JOIN tecnico AS t ON sm.tecnico = t.noTecnico
INNER JOIN equipo AS eq ON sm.equipo = eq.numeroSerie;
/*
    13. Vista de los materiales
*/
CREATE VIEW materials AS
SELECT `codigoMaterial` AS code,
nombre AS name,
stock,
precio AS price
FROM materiales;