DELIMITER $$
/*
    10. Asignar automaticamente al técnico
*/
CREATE TRIGGER asignarTecnico
BEFORE INSERT ON incidencia
FOR EACH ROW
BEGIN
    DECLARE tecnicoA INT;
    SET tecnicoA = (SELECT noTecnico
                    FROM tecnico
                    WHERE especialidad = NEW.principalProblema
                        AND disponibilidad = 'DIS'
                    ORDER BY noIncidenciasA ASC
                    LIMIT 1);
    IF tecnicoA IS NULL THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'No technicians available, please try again later.';
    ELSE
        SET NEW.tecnicoAsignado = tecnicoA;

        UPDATE tecnico
        SET noIncidenciasA = noIncidenciasA + 1,
            disponibilidad = 'ASI'
        WHERE noTecnico = tecnicoA;

        SET NEW.estado = 'EPR';
    END IF;
END$$
/*
    14. Agregar un registro a la tabla de notificaciones
        de las incidencias
*/
CREATE TRIGGER incidentNotification
AFTER INSERT ON incidencia
FOR EACH ROW
BEGIN
    INSERT INTO notificacionIncidencia (noIncidencia, tecnicoAsignado)
    VALUES (NEW.noIncidencia, NEW.tecnicoAsignado);
END$$
/*
    4. Costo de materiales usados por mantenimiento
    Es el costo total de los materiales usados 
    para el mantenimiento.
    Actualiza los materiales de la tabla materiales
*/
CREATE TRIGGER mantenimientoMateriales
AFTER INSERT ON materialesMantenimiento
FOR EACH ROW
BEGIN
    UPDATE mantenimiento
    SET costo = costo + NEW.importe
    WHERE noMantenimiento = NEW.mantenimiento;
    UPDATE materiales
    SET stock = stock - NEW.cantidad
    WHERE codigoMaterial = NEW.material;
END$$
/*
    2. Fecha mantenimiento si viene de incidencia
    Es la fecha en la cual se pretende realizar el mantenimiento, en 
    caso de que el mantenimiento venga de una incidencia se asigna la fecha 
    de la incidencia y el equipo.
*/
CREATE TRIGGER inicilizarMantenimientoIncidencia
BEFORE INSERT ON mantenimiento
FOR EACH ROW
BEGIN
    DECLARE fechaIncidencia DATE;
    DECLARE tec INT;
    DECLARE equi VARCHAR(10);
    SET fechaIncidencia = (SELECT fechaInicio
                           FROM incidencia
                           WHERE noIncidencia = NEW.incidencia);
    SET tec = (SELECT tecnicoAsignado
               FROM incidencia
               WHERE noIncidencia = NEW.incidencia);
    SET equi = (SELECT equipo
                FROM incidencia
                WHERE noIncidencia = NEW.incidencia);
        IF NEW.incidencia IS NOT NULL THEN
        SET NEW.fechaProgramada = fechaIncidencia;
        SET NEW.estado = 'EPR';
        SET NEW.costo = 0;
        SET NEW.tecnico = tec;
        SET NEW.tipoMantenimiento = 'COR';
        SET NEW.tiempoInactivo = 0.0;
        SET NEW.equipo = equi;
    ELSE
        SET NEW.estado = 'EPR';
        SET NEW.costo = 0;
    END IF;
END$$
/*
    5 y 6. Verificar stock de materiales antes de permitir una insercion,
    verificacion del estado
    --Mantenimiento
    --Reparacion
*/
CREATE TRIGGER verificacionMaterialMantenimiento
BEFORE INSERT ON materialesMantenimiento
FOR EACH ROW
BEGIN
    DECLARE estadoM VARCHAR(3);
    DECLARE cantDisponible INT;
    DECLARE msg VARCHAR(100);
    SET estadoM = (SELECT estado
                   FROM mantenimiento
                   WHERE noMantenimiento = NEW.mantenimiento);
    IF(estadoM <> 'EPR')
    THEN
        SET msg = 'El mantenimiento ingresado ya fue terminado, no se pueden realizar más cambios';
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = msg;
    ELSE
        SET cantDisponible = (SELECT stock
                              FROM materiales
                              WHERE codigoMaterial = NEW.material);
        IF(cantDisponible < NEW.cantidad)
        THEN
            SET msg = CONCAT('No hay suficiente cantidad del material ',NEW.material,', realice una solicitud de material');
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = msg;
        ELSE
            SET NEW.importe = (SELECT precio
                               FROM materiales
                               WHERE codigoMaterial = NEW.material) * NEW.cantidad;
        END IF;
    END IF;
END$$
CREATE TRIGGER verificacionMaterialReparacion
BEFORE INSERT ON materialesReparacion
FOR EACH ROW
BEGIN
    DECLARE cantDisponible INT;
    DECLARE msg VARCHAR(100);
    SET cantDisponible = (SELECT stock
                          FROM materiales
                          WHERE codigoMaterial = NEW.material);
        IF (cantDisponible < NEW.cantidad )
        THEN
            SET msg = CONCAT('No hay suficiente cantidad del material ',NEW.material,', realice una solicitud de material');
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = msg;
        ELSE
            SET NEW.importe = (SELECT precio
                               FROM materiales
                               WHERE codigoMaterial = NEW.material) * NEW.cantidad;
        END IF;
END$$
/*
    3. Costo de materiales usados por reparacion
    Es el costo total de los materiales usados para el reparacion.
    Actualiza los materiales de la tabla materiales

*/
CREATE TRIGGER reparacionMateriales
AFTER INSERT ON materialesReparacion
FOR EACH ROW
BEGIN
    UPDATE reparacion
    SET costo = costo + NEW.importe
    WHERE noRepar = NEW.reparacion;
    UPDATE materiales
    SET stock = stock - NEW.cantidad
    WHERE codigoMaterial = NEW.material;
END$$
/*
    1. Trigger que cuando el estado de un mantenimiento se actualice a terminado se actualice la incidencia 
    relacionada con el estado terminado, la fecha de cierre automatica y el 
    tecnico asignado vuelva a estar disponible.
*/
DELIMITER $$
CREATE TRIGGER mantenimientoFinalizado
AFTER UPDATE ON mantenimiento
FOR EACH ROW
BEGIN
    DECLARE inci INT;
    DECLARE tec INT;
    SET tec = (SELECT tecnicoAsignado
               FROM incidencia
               WHERE noIncidencia = OLD.incidencia);
    SET inci = (SELECT incidencia
                FROM mantenimiento
                WHERE noMantenimiento = OLD.noMantenimiento);
    IF NEW.estado = 'TER'
    THEN
        UPDATE incidencia
        SET estado = 'TER',
        fechaCierre = CURRENT_DATE
        WHERE noIncidencia = inci;
        UPDATE tecnico
        SET disponibilidad = 'DIS'
        WHERE noTecnico = tec;
    END IF;
END$$
/*
    15. Agregar un registro a la tabla de notificaciones
        de las solicitudes de materiales
*/
CREATE TRIGGER insertarNotificacionMaterial
AFTER INSERT ON solicitudMateriales
FOR EACH ROW
BEGIN
    INSERT INTO notificacionMaterial (solicitudMaterial, gerenteAsignado)
    VALUES (NEW.noSolicitud, NEW.gerente);
END$$
/*
    7. Asignacion automatica del estado
    --solicitud de materiales
    --Mantenimiento terminado
*/
CREATE TRIGGER estadoSolicitud
BEFORE INSERT ON solicitudMateriales
FOR EACH ROW
BEGIN
    DECLARE numGer INT;
    SET numGer = (SELECT noEmpleado
                  FROM empleado
                  WHERE tipoEmpleado = 'GER'
                  ORDER BY RAND()
                  LIMIT 1);
    SET NEW.estado = 'ENP';
    SET NEW.costoTotal = 0;
    SET NEW.gerente = numGer;
END$$

CREATE TRIGGER finalizarMantenimiento
AFTER UPDATE ON reparacion
FOR EACH ROW
BEGIN
    IF NEW.horaFinal IS NOT NULL
    THEN
        UPDATE mantenimiento
        SET estado = 'TER',
        costo = NEW.costo,
        tiempoInactivo = NEW.tiempoInactivo
        WHERE noMantenimiento = NEW.mantenimiento;
    END IF;
END$$
/*
    9. Calculo del importe del material que se esta solicitando
*/
CREATE TRIGGER costoMaterialSolicitud
BEFORE INSERT ON materialSolicitud
FOR EACH ROW
BEGIN
    SET NEW.importe = (SELECT precio
                       FROM materiales
                       WHERE codigoMaterial = NEW.material) * NEW.cantidad;
END$$
/*
    8. Calculo del costo total por los materiales que se esta solicitando
*/
CREATE TRIGGER costoTotalSolicitud
AFTER INSERT ON materialSolicitud
FOR EACH ROW
BEGIN
    UPDATE solicitudMateriales
    SET costoTotal = costoTotal + NEW.importe
    WHERE noSolicitud = NEW.solicitud;
END$$
/*
/*
    16. Agregar un registro a la tabla de solicitud aprobada
*/
CREATE TRIGGER after_update_solicitudMateriales
AFTER UPDATE ON solicitudMateriales
FOR EACH ROW
BEGIN
    DECLARE estadoAprobacion VARCHAR(10);
    IF NEW.estado IN ('APR', 'DEN') THEN
        IF NEW.estado = 'APR' THEN
            SET estadoAprobacion = 'Approved';
        ELSE
            SET estadoAprobacion = 'Rejected';
        END IF;
        INSERT INTO solicitudAprobada (noSolicitud, tecnicoAsignado, estadoAprobacion, fecha, leida)
        VALUES (NEW.noSolicitud, OLD.tecnico, estadoAprobacion, CURRENT_TIMESTAMP, FALSE);
    END IF;
END$$
/*
    13. Agregar los datos bàsicos de un técnico cuando se agrega uno a la
        tabla de empleados
*/
CREATE TRIGGER addTechnician
AFTER INSERT ON empleado
FOR EACH ROW
BEGIN
    IF NEW.tipoEmpleado = 'TEC' THEN
        INSERT INTO tecnico (noTecnico, disponibilidad, noIncidenciasA, especialidad, turnoLaboral)
        VALUES (NEW.noEmpleado,'DIS',0, 'MTO', 'MAT');
    END IF;
END$$
/*
    12. Actualizar el porcentaje de funcionabilidad
    --reparacion
    --mantenimiento
*/
CREATE TRIGGER actualizarTiempoInactivoR
AFTER INSERT ON reparacion
FOR EACH ROW
BEGIN
    UPDATE equipo
    SET
    estadoEquipo = 'INAC'
    WHERE numeroSerie = (SELECT equipo 
                         FROM mantenimiento 
                         WHERE noMantenimiento = NEW.mantenimiento);
END$$ 
/*
    Actualización de los empleados sin afectar a los técnicos
*/
CREATE TRIGGER updateEmployee
AFTER UPDATE ON empleado
FOR EACH ROW
BEGIN
    DECLARE noIn INT;
    DECLARE dispo VARCHAR(3);
    DECLARE espe VARCHAR(3);
    DECLARE turn VARCHAR(3);
    SET noIn = (SELECT noIncidenciasA
                FROM tecnico
                WHERE noTecnico = OLD.noEmpleado);
    SET dispo = (SELECT disponibilidad
                FROM tecnico
                WHERE noTecnico = OLD.noEmpleado);
    SET espe = (SELECT especialidad
                FROM tecnico
                WHERE noTecnico = OLD.noEmpleado);
    SET turn = (SELECT turnoLaboral
                FROM tecnico
                WHERE noTecnico = OLD.noEmpleado);
    IF (OLD.tipoEmpleado = 'TEC')
    THEN
        UPDATE tecnico
        SET noIncidenciasA = noIn,
        disponibilidad = dispo,
        especialidad = espe,
        turnoLaboral = turn
        WHERE noTecnico = OLD.noEmpleado;
    END IF;
END$$
