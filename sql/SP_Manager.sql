/*
    Procedimiento para la descarga de reportes
*/
DELIMITER $$
CREATE PROCEDURE viewReport(
    IN report INT
)
BEGIN
    SELECT *
    FROM vw_seeReports
    WHERE num = report;
END$$
/*
    Procedimiento para la descarga de incidencias
*/
CREATE PROCEDURE viewIncidents(
    IN incident INT
)
BEGIN
    SELECT *
    FROM vw_Incidencias
    WHERE num = incident;
END$$
/*
    32. Aprobar o negar la solicitud
*/
CREATE PROCEDURE managerRequest(
    IN status VARCHAR(3),
    IN num INT
)
BEGIN
    UPDATE solicitudMateriales
    SET estado = status
    WHERE noSolicitud = num;
END$$
/*
    Ver todas las reparaciones
*/
CREATE PROCEDURE seeAllRepairs()
BEGIN
    SELECT *
    FROM vw_repairs
    WHERE finishedTime IS NOT NULL;
END$$
/*
    35. Vista de solicitudes para el manager
*/
CREATE PROCEDURE seeRequestsMan(
    IN search VARCHAR(50)
)
BEGIN
    SELECT * 
    FROM vw_requests
    WHERE status LIKE CONCAT('%', search, '%')
       OR technician LIKE CONCAT('%', search, '%')
       OR name LIKE CONCAT('%', search, '%')
       OR noEquipment LIKE CONCAT('%', search, '%')
       OR nombre LIKE CONCAT('%', search, '%');
END$$
CALL `seeRequestsMan`('carlos');
CREATE PROCEDURE seeAllRequests()
BEGIN
    SELECT *
    FROM vw_requests;
END$$
/*
    32. Mostrar todas las reparaciones
*/
CREATE PROCEDURE seeRepairsMan(
    IN search VARCHAR(50)
)
BEGIN
    SELECT * 
    FROM vw_repairs
    WHERE status LIKE CONCAT('%', search, '%')
       OR equipment LIKE CONCAT('%', search, '%')
       OR noEquipment LIKE CONCAT('%', search, '%')
       OR name LIKE CONCAT('%', search, '%')
       OR description LIKE CONCAT('%', search, '%');
END$$
CALL `seeRepairsMan`('electrical');
/*
    18. Modificar datos del tècnico
*/
CREATE PROCEDURE modificarTecnico(
    IN noTec INT,
    IN espe VARCHAR(25),
    IN turno VARCHAR(25)
)
BEGIN
        DECLARE codEspe VARCHAR(3);
        DECLARE codTurno VARCHAR(3);
        SET codEspe = (SELECT codigo
                       FROM especialidad
                       WHERE descripcion LIKE CONCAT('%', espe, '%'));
        SET codTurno = (SELECT claveTurno
                        FROM turnoLaboral
                        WHERE nombre LIKE CONCAT('%', turno, '%'));
        UPDATE tecnico
        SET especialidad = codEspe,
        turnoLaboral = codTurno
        WHERE noTecnico = noTec;
END$$
CALL modificarTecnico(115, 'ele', 'mor');
/*
    7. Insertar datos a la tabla empleados
*/
CREATE PROCEDURE insertarEmpleado(
    IN nom VARCHAR(35),
    IN apeP VARCHAR(20),
    IN apeM VARCHAR(20),
    IN pas VARCHAR(255),
    IN em VARCHAR(50),
    IN noTe VARCHAR(13),
    IN tiEm VARCHAR(3)
)
BEGIN
    INSERT INTO empleado (nombre, apellidoP, apellidoM, password, email, noTelefono, tipoEmpleado) 
    VALUES (nom, apeP, apeM, SHA2(pas, 256), em, noTe, tiEm);
END$$
/*
    5. Insertar datos a la tabla reporteEquipo
*/
CREATE PROCEDURE insertarReporte(
    IN rep TEXT,
    IN eq VARCHAR(10),
    IN tipo VARCHAR(4),
    IN emp VARCHAR(5)
)
BEGIN
    INSERT INTO reporteEquipo(reporte, equipo, tipoReporte, gerente)
    VALUES (rep, eq, tipo, emp);
END$$
/*
    38. Ver las notificaciones del manager
*/
CREATE PROCEDURE notificationRequestMade(
    IN manager INT
)
BEGIN
    SELECT n.noNotificacion, 
           n.fecha, 
           sm.fechaEntrega,
           sm.costoTotal,
           sm.equipo,
           n.leida
    FROM notificacionMaterial AS n 
    INNER JOIN solicitudMateriales AS sm ON n.solicitudMaterial = sm.noSolicitud
    WHERE n.gerenteAsignado = manager AND sm.estado = 'ENP'
    ORDER BY n.fecha DESC;
END$$
CALL `notificationRequestMade`(104);
/*
    20. Ver a los empleados y permitir búsqueda por tipo de empleado
        y nombre
*/
CREATE PROCEDURE buscarEmpleados(
    IN search VARCHAR(35)
)
BEGIN
    SELECT *
    FROM vw_employees
    WHERE 
        (search IS NULL OR name LIKE CONCAT('%', search, '%'))
        OR (search IS NULL OR middleName LIKE CONCAT('%', search, '%'))
        OR (search IS NULL OR title LIKE CONCAT('%', search, '%'));
END$$
CALL buscarEmpleados('mendoza');
/*
    19. Ver la informaciòn de los tècnicos
*/
CREATE PROCEDURE verTecnicos()
BEGIN
    SELECT * 
    FROM vw_technician;
END$$
CALL verTecnicos();
/*
    28. Mostrar todos los mantenimientos creados
*/
CREATE PROCEDURE seeMaintenance()
BEGIN
    SELECT * FROM vw_maintenance;
END$$
/*
    29. Búsqueda de mantenimientos
*/
CREATE PROCEDURE maintenanceSearch(
    IN search VARCHAR(50)
)
BEGIN
    SELECT * 
    FROM vw_maintenance
    WHERE type LIKE CONCAT('%', search, '%')
       OR status LIKE CONCAT('%', search, '%')
       OR technician LIKE CONCAT('%', search, '%')
       OR name LIKE CONCAT('%', search, '%')
       OR noEquipment LIKE CONCAT('%', search, '%')
       OR equipment LIKE CONCAT('%', search, '%')
       OR description LIKE CONCAT('%', search, '%');
END$$
/*
    25. Actualizar la información del empleado
*/
CREATE PROCEDURE updateEmployee(
    IN noEmp INT,
    IN nom VARCHAR(35),
    IN apeP VARCHAR(20),
    IN apeM VARCHAR(20),
    IN em VARCHAR(50),
    IN noTel VARCHAR(13)
)
BEGIN
    IF EXISTS (SELECT 1 FROM empleado WHERE noEmpleado = noEmp) THEN
        UPDATE empleado
        SET nombre = nom,
            apellidoP = apeP,
            apellidoM = apeM,
            email = em,
            noTelefono = noTel
        WHERE noEmpleado = noEmp;
    ELSE
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Employee not found.';
    END IF;
END$$
CALL updateEmployee(102, 'Saul', 'Marquez', 'Peña', 'saul.marquez@outlook.com','665-987-98-78');
/*
    23. Mostrar todos los reportes creados
*/
CREATE PROCEDURE seeReports()
BEGIN
    SELECT * FROM vw_seeReports;
END$$
/*
    24. Búsqueda de reportes
*/
CREATE PROCEDURE reportSearch(
    IN search VARCHAR(50)
)
BEGIN
    SELECT * 
    FROM vw_seeReports
    WHERE type LIKE CONCAT('%', search, '%')
       OR name LIKE CONCAT('%', search, '%')
       OR lastName LIKE CONCAT('%', search, '%')
       OR equipment LIKE CONCAT('%', search, '%')
       OR noEquipment LIKE CONCAT('%', search, '%')
       OR model LIKE CONCAT('%', search, '%')
       OR brand LIKE CONCAT('%', search, '%')
       OR area LIKE CONCAT('%', search, '%')
       OR status LIKE CONCAT('%', search, '%');
END$$
CALL reportSearch('drill');
/*
    21. Ver todos los empleados
*/
CREATE PROCEDURE verEmpleados()
BEGIN
    SELECT *
    FROM vw_employees
    ORDER BY num;
END$$
CALL `verEmpleados`();
/*
    22. Permitir la búsqueda de los equipos
*/
CREATE PROCEDURE searchEquipments(
    IN search VARCHAR(50)
)
BEGIN
    SELECT *
    FROM vw_equiposPorArea
    WHERE `nombreArea` LIKE CONCAT('%', search, '%')
       OR `NumeroSerieEquipo` LIKE CONCAT('%', search, '%')
       OR `nombreEquipo` LIKE CONCAT('%', search, '%')
       OR `modeloEquipo` LIKE CONCAT('%', search, '%')
       OR `marcaEquipo` LIKE CONCAT('%', search, '%')
       OR `estadoEquipo` LIKE CONCAT('%', search, '%');
END$$
CALL `searchEquipments`('robot');
/*
    14. Ver todas las incidencias
*/
CREATE PROCEDURE seeAllIncidents()
BEGIN
    SELECT * FROM vw_BusquedaIncidencias;
END$$
CALL seeAllIncidents();
/*
    15. Procedimiento para buscar incidencias
*/
CREATE PROCEDURE incidentsSearch(
    IN search VARCHAR(50)
)
BEGIN
    SELECT * 
    FROM vw_BusquedaIncidencias
    WHERE noEquipment LIKE CONCAT('%', search, '%')
       OR equipmentName LIKE CONCAT('%', search, '%')
       OR opeReported LIKE CONCAT('%', search, '%')
       OR area LIKE CONCAT('%', search, '%')
       OR noTechnician LIKE CONCAT('%', search, '%')
       OR name LIKE CONCAT('%', search, '%')
       OR lastName LIKE CONCAT('%', search, '%');
END$$
/*
    13. Modificar los datos de un equipo
*/
CREATE PROCEDURE modificarEquipo(
    IN numS VARCHAR(10),
    IN nom VARCHAR(50),
    IN fec DATE,
    IN mode VARCHAR(30),
    IN mar VARCHAR(30),
    IN estEq VARCHAR(10),
    IN are VARCHAR(30)
)
BEGIN
    DECLARE codMod VARCHAR(3);
    DECLARE codMar VARCHAR(3);
    DECLARE codEst VARCHAR(4);
    DECLARE codArea VARCHAR(4);
    SET codMod = (SELECT codigoModelo
                    FROM modelo
                    WHERE nombre LIKE CONCAT('%', mode, '%'));
    SET codMar = (SELECT codigoMarca
                    FROM marca
                    WHERE nombre LIKE CONCAT('%', mar, '%'));
    SET codEst = (SELECT codigo
                    FROM estadoEquipo
                    WHERE descripcion LIKE CONCAT(estEq, '%'));
    SET codArea = (SELECT clave
                    FROM area
                    WHERE nombre LIKE CONCAT('%', are, '%'));
    UPDATE equipo
    SET nombre = nom,
    fechaCompra = fec,
    modelo = codMod,
    marca = codMar,
    estadoEquipo = codEst,
    area = codArea
    WHERE numeroSerie = numS;
END$$
SELECT codigo
FROM `estadoEquipo`
WHERE descripcion LIKE CONCAT('Active', '%');
CALL modificarEquipo('EF5678GH90', 'Welding', '2020-06-22', 'Welding machine', 'Yaskawa Electric', 'Active.', 'Bodywork');
/*
    6. Insertar datos a la tabla de equipo
*/
CREATE PROCEDURE insertarEquipo(
    IN nSerie VARCHAR(10),
    IN nom VARCHAR(50),
    IN fechaC DATE,
    IN precioC DECIMAL(10, 2),
    IN mode VARCHAR(3),
    IN mar VARCHAR(4),
    IN estadoE VARCHAR(4),
    IN ar VARCHAR(4)
)
BEGIN
    INSERT INTO equipo(numeroSerie, nombre, fechaCompra, tiempoInactivo, tiempoOperativo, precioCompra, costo, modelo, marca, estadoEquipo, area)
    VALUES (nSerie, nom, fechaC, 0.0, 0.1, precioC, 0.0, mode, mar, estadoE, ar);
END$$
CALL insertarEquipo('CD7890EF12', 'Grinder Machine', '2022-01-18', 40000.00, 'RBT', 'KOM', 'ACTV', 'PINT');
/*
    8. Ver los equipos
*/
CREATE PROCEDURE verEquipos(
)
BEGIN
    SELECT nombreArea,
    NumeroSerieEquipo,
    nombreEquipo,
    fechaCompraEquipo,
    tiempoInactivoEquipo,
    tiempoOperativoEquipo,
    funcionalidadEquipo,
    costoActualEquipo,
    modeloEquipo,
    marcaEquipo, 
    estadoEquipo
    FROM vw_equiposPorArea;
END$$
CALL verEquipos();