DELIMITER $$
/*
    17. Inicio de sesion
*/
CREATE PROCEDURE inicioSesion(
    IN pas VARCHAR(255),
    IN numE INT,
    OUT tipo VARCHAR(10),
    OUT nom VARCHAR(70)
)
BEGIN
    IF EXISTS (
        SELECT 1
        FROM empleado
        WHERE noEmpleado = numE 
          AND password = SHA2(pas, 256)
    ) THEN
        SELECT CONCAT(e.nombre, ' ', e.apellidoP, ' ', e.apellidoM),
        e.tipoEmpleado
        INTO nom, tipo
        FROM empleado AS e
        WHERE e.noEmpleado = numE;
    END IF;
END$$
SELECT @tipo AS tipoEmpleado, @nom AS nombreCompleto;
CALL inicioSesion('elcuarteto89', 100, @tipo, @nom);
/*
    9. Ver incidencias creadas por el operador
*/
CREATE PROCEDURE verIncidenciasOpe(
    IN emple INT
)
BEGIN
    SELECT * 
    FROM vw_Incidencias
    WHERE opeReported = emple;
END$$
CALL verIncidenciasOpe(108);
/*
    1. Insertar datos a la tabla Incidencias
*/
CREATE PROCEDURE insertarIncidencias(
    IN descri VARCHAR(500),
    IN problema VARCHAR(3),
    IN pri VARCHAR(3),
    IN equ VARCHAR(10),
    IN emp INT
)
BEGIN
    INSERT INTO incidencia(descripcion, principalProblema, prioridad, equipo, created)
    VALUES
        (descri, problema, pri, equ, emp);
END$$
CALL insertarIncidencias("Inconsistencies in hydraulic system pressure.", "ELE", "ALT", "YZ1234AB56", 105);
/*
    10. Cambiar la contraseña de tu perfil
*/
CREATE PROCEDURE changePassword(
    IN newPass VARCHAR(255),
    IN noEmpl INT
)
BEGIN
    DECLARE currentPass VARCHAR(255);
    IF CHAR_LENGTH(newPass) < 8 OR CHAR_LENGTH(newPass) > 12 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'The password must be between 8 and 12 characters.';
    ELSE
        SELECT password INTO currentPass
        FROM empleado
        WHERE noEmpleado = noEmpl;

        IF SHA2(newPass, 256) = currentPass THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'The new password cannot be the same as the old password.';
        ELSE
            UPDATE empleado
            SET password = SHA2(newPass, 256)
            WHERE noEmpleado = noEmpl;
        END IF;
    END IF;
END$$
CALL changePassword('astrid19', 104);
SELECT @msg;
/*
    11. Ver el perfil de un empleado
*/
CREATE PROCEDURE seeProfile(
    IN noEmpl INT
)
BEGIN
    SELECT *
    FROM vw_profileInfo
    WHERE num = noEmpl;
END$$
CALL seeProfile(101);
