DELIMITER $$
/*
    Ver todos los materiales
*/
CREATE PROCEDURE stock()
BEGIN
    SELECT *
    FROM materials;
END$$
/*
    31. Mostrar las reparaciones de un técnico
*/
CREATE PROCEDURE repairTec(
    IN numEmp INT
)
BEGIN
    SELECT *
    FROM vw_repairs
    WHERE technician = numEmp AND finishedTime IS NOT NULL;
END$$
/*
    16. Agregar mantenimiento preventivo
*/
CREATE PROCEDURE insertarMantePrev(
    IN descri VARCHAR(500),
    IN fecha DATE,
    IN equ VARCHAR(10),
    IN noEmpl INT
)
BEGIN
    INSERT INTO mantenimiento(fechaProgramada, descripcion, tipoMantenimiento, equipo, tecnico, tiempoInactivo)
    VALUES (fecha, descri, 'PRE', equ, noEmpl, 0.0);
    SET @event_query = CONCAT('CREATE EVENT IF NOT EXISTS cambiarEstadoTecnico_', noEmpl, '_', equ, '
                               ON SCHEDULE AT "', fecha, '"
                               DO
                               UPDATE tecnico
                               SET disponibilidad = ''ASI''
                               WHERE noTecnico = "', noEmpl, '";');
    PREPARE stmt FROM @event_query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
END$$
CALL insertarMantePrev('Preventive maintenance of the hydraulic system', '2024-11-25', 'AB9012CD34', 125);
/*
    39. Ver las notificaiones del técnico
*/
CREATE PROCEDURE notificationRequestFinish(
    IN technician INT
)
BEGIN
    SELECT n.noNotificacion,
           n.fecha, 
           n.estadoAprobacion,
           sm.fechaEntrega,
           sm.costoTotal,
           sm.equipo,
           n.leida
    FROM solicitudAprobada AS n 
    INNER JOIN solicitudMateriales AS sm ON n.noSolicitud = sm.noSolicitud
    WHERE n.tecnicoAsignado = technician AND n.leida = 0
    ORDER BY n.fecha DESC;
END$$
CALL `notificationRequestFinish`(103);
/*
    34. Vista de solicitudes para el tecnico
*/
CREATE PROCEDURE seeRequestsTec(
    IN tec INT
)
BEGIN
    SELECT * 
    FROM vw_requests 
    WHERE technician = tec;
END$$
CALL seeRequestsTec(100);
/*
    33. Insertar materiales a la solicitud
*/
CREATE PROCEDURE materialsRequest(
    IN mat VARCHAR(5),
    IN soli INT,
    IN amount INT
)
BEGIN
    INSERT INTO materialSolicitud(material, solicitud, cantidad)
    VALUES(mat, soli, amount);
END$$
/*
    31. Insertar una solicitud de materiales
*/
CREATE PROCEDURE startRequest(
    IN delivery DATE,
    IN tec INT,
    IN equ VARCHAR(10)
)
BEGIN
    INSERT INTO solicitudMateriales(fechaEntrega, tecnico, equipo)
    VALUES (delivery, tec, equ);
END$$
CALL `startRequest`('2024-11-26', 103, 'EF5678GH90');
/*
    5. Finalizar reparación
*/
CREATE PROCEDURE finReparacion(
    IN hFinal DATETIME,
    IN descr TEXT,
    IN repa INT
)
BEGIN
    DECLARE tInactivo DECIMAL(5, 2);
    DECLARE horaI DATETIME;
    SET horaI = (SELECT horaInicio
                 FROM reparacion
                 WHERE noRepar = repa);
    SET tInactivo = TIMESTAMPDIFF(HOUR, horaI, hFinal);

    UPDATE reparacion
    SET horaFinal = hFinal,
    tiempoInactivo = tInactivo,
    descripcion = descr
    WHERE noRepar = repa;
END$$
CALL `finReparacion`('2024-11-24 15:30:00', 'Conveyor belt replacement', 6);
/*
    36. Agregar materiales a una reparacion
*/
CREATE PROCEDURE materialsRepair(
    IN mate VARCHAR(5),
    IN rep INT,
    IN amount INT
)
BEGIN
    INSERT INTO materialesReparacion(material, reparacion, cantidad)
    VALUES(mate, rep, amount);
END$$
call `materialsRepair`('MAT07', 6, 2);
/*
    4. Insertar datos a la tabla reparación
*/
CREATE PROCEDURE iniciarReparacion(
    IN mant INT
)
BEGIN
    DECLARE hInicio DATETIME;
    SET hInicio = NOW();
    INSERT INTO reparacion(horaInicio, mantenimiento, tiempoInactivo, descripcion, costo)
    VALUES (hInicio, mant, 0.0, 'Repair Statated', 0.0);
END$$
CALL `iniciarReparacion`(11, 'Start');
/*
    2. Insertar datos a la tabla de Mantenimiento cuando viene de una incidencia
*/
CREATE PROCEDURE insertarMante(
    IN descri VARCHAR(500),
    IN incide INT
)
BEGIN
    INSERT INTO mantenimiento(descripcion, incidencia)
    VALUES (descri, incide);
END$$
CALL insertarMante("Mechanical Adjustments and Calibration", 3);
/*
    27. Ver las notificaciones de las incidencias
        del técnico
*/
CREATE PROCEDURE notificationsIncidents(
    IN tecnicoId INT
)
BEGIN
    SELECT n.noNotificacion, 
           n.fecha, 
           i.descripcion, 
           n.leida,
           i.noIncidencia AS incident
    FROM notificacionIncidencia n
    JOIN incidencia i ON n.noIncidencia = i.noIncidencia
    WHERE n.tecnicoAsignado = tecnicoId AND n.leida = 0
    ORDER BY n.fecha DESC;
END$$
CALL `notificationsIncidents`(100);
/*
    3. Finalizar un mantenimiento
*/
CREATE PROCEDURE finalizarMantenimiento(
    IN mante INT,
    IN tiempoInc DECIMAL(10, 2)
)
BEGIN
    DECLARE tec INT;
    SET tec = (SELECT tecnico
               FROM mantenimiento
               WHERE noMantenimiento = mante);
    UPDATE mantenimiento
    SET tiempoInactivo = tiempoInc,
        estado = 'TER'
    WHERE noMantenimiento = mante;
    UPDATE tecnico
    SET disponibilidad = 'DIS'
    WHERE noTecnico = tec;
END$$
CALL finalizarMantenimiento(13, 3.6, 'TER');
/*
    Mantenimietos preventivos en proceso
*/
CREATE PROCEDURE manteProcesoPrev(
    IN tec INT
)
BEGIN
    SELECT *
    FROM vw_maintenance
    WHERE status = 'In Process' AND type = 'Preventive' AND technician = tec;
END$$
/*
    37. Agregar materiales a un mantenimiento
*/
CREATE PROCEDURE materialsMaintenance(
    IN mate VARCHAR(5),
    IN maint INT,
    IN amount INT
)
BEGIN
    INSERT INTO materialesMantenimiento(material, mantenimiento, cantidad)
    VALUES(mate, maint, amount);
END$$
CALL `materialsMaintenance`('MAT08', 13, 2);
/*
    31. Mostrar todas las reparaciones en proceso
*/
CREATE PROCEDURE repairInProcess(
    IN numTec INT
)
BEGIN
    SELECT *
    FROM vw_repairs
    WHERE finishedTime IS NULL AND technician = numTec;
END$$
CALL `repairInProcess`(100);
/*
    30. Mostrar todos los mantenimientos en proceso
*/
CREATE PROCEDURE maintenanceInProcess(
    IN noEmp INT
)
BEGIN
    SELECT *
    FROM vw_maintenance
    WHERE status = 'In Process' AND technician = noEmp;
END$$
CALL `maintenanceInProcess`(100);
/*
    26. Ver los mantenimientos atendidios por un técnico
*/
CREATE PROCEDURE maintenanceTech(
    IN noEmp INT
)
BEGIN
    SELECT *
    FROM vw_maintenance
    WHERE technician = noEmp;
END$$
call maintenanceTech(100);
/*
    12. Ver las incidencias asignadas a un técnico
*/
CREATE PROCEDURE verIncidenciasTec(
    IN emple INT
)
BEGIN
    SELECT * 
    FROM vw_Incidencias
    WHERE noTechnician = emple;
END$$
CALL `verIncidenciasTec`(106);
/*
    Obtener los mantenimientos preventivos en proceso
*/
CREATE PROCEDURE preventiveMaintenance()
BEGIN
    SELECT 
        m.equipo, 
        e.nombre AS nombreEquipo,
        m.fechaProgramada, 
        em.descripcion AS estadoMantenimiento,
        t.noTecnico AS tecnico,
        CONCAT(ep.nombre,' ',ep.apellidoP) AS nombre
    FROM mantenimiento m
    INNER JOIN equipo e ON m.equipo = e.numeroSerie
    INNER JOIN estadoMantenimiento em ON m.estado = em.codigo
    INNER JOIN tecnico t ON m.tecnico = t.noTecnico
    INNER JOIN empleado ep ON ep.noEmpleado = t.noTecnico
    WHERE m.estado = 'EPR' AND m.tipoMantenimiento = 'PRE'
    ORDER BY m.fechaProgramada ASC;
END$$
CALL `preventiveMaintenance`();