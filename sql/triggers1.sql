DELIMITER $$


/*
    11. Actulizar los costos del equipo
    --Reparacion
    --Mantenimiento
*/
CREATE TRIGGER finalizarReparacion
AFTER UPDATE ON reparacion
FOR EACH ROW
BEGIN
    DECLARE eq VARCHAR(10);
    SET eq = (SELECT m.equipo
              FROM mantenimiento AS m
              INNER JOIN reparacion AS r ON r.mantenimiento = m.noMantenimiento
              WHERE r.noRepar = OLD.noRepar);
        UPDATE equipo AS eq
        SET eq.costo = eq.costo + NEW.costo,
        eq.tiempoInactivo = eq.tiempoInactivo + NEW.tiempoInactivo,
        eq.estadoEquipo = 'ACTV'
        WHERE eq.numeroSerie = eq;
        UPDATE mantenimiento
        SET tiempoInactivo = NEW.tiempoInactivo
        WHERE noMantenimiento = OLD.mantenimiento;
END$$

CREATE TRIGGER actualizarCostosM
AFTER UPDATE ON mantenimiento
FOR EACH ROW
BEGIN
    DECLARE eq VARCHAR(10);
    SET eq = OLD.equipo;
    IF (OLD.tipoMantenimiento = 'PRE' AND NEW.estado = 'TER')
    THEN
        UPDATE equipo AS eq
        SET eq.tiempoInactivo = eq.tiempoInactivo + NEW.tiempoInactivo,
            eq.estadoEquipo = 'ACTV',
            eq.costo = eq.costo + NEW.costo
        WHERE eq.numeroSerie = eq;
    END IF;
END$$

CREATE TRIGGER actualizarTiempoInactivoM
AFTER INSERT ON mantenimiento
FOR EACH ROW
    IF NEW.tipoMantenimiento = 'PRE' THEN
        UPDATE equipo
        SET estadoEquipo = 'INAC'
        WHERE numeroSerie = NEW.equipo;
    END IF;
END$$
SELECT *
FROM equipo
WHERE `numeroSerie` = 'QR9012ST34';
UPDATE equipo
SET `tiempoOperativo` = 119.8
WHERE `numeroSerie` = 'QR9012ST34';