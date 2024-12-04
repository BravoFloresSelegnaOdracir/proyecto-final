/*
    Integridad de los datos
*/
DELETE FROM mantenimiento WHERE `noMantenimiento` = 12;
DELETE FROM `materialesMantenimiento` WHERE mantenimiento = 4;
UPDATE incidencia 
SET descripcion = 'Oil leakage in the injection machine.',
prioridad = 'ALT',
equipo = 'ST7890UV12'
WHERE `noIncidencia` = 3;
UPDATE materiales
 SET stock = 190
  WHERE `codigoMaterial` = 'MAT04';
UPDATE mantenimiento
SET costo = 45.00
WHERE `noMantenimiento` = 4;
DELETE FROM `materialesReparacion` WHERE reparacion = 2;
UPDATE `solicitudMateriales`
SET equipo = 'EF5678GH90'
WHERE `noSolicitud` = 1;
DELETE from `materialSolicitud` WHERE solicitud = 1;
UPDATE mantenimiento
SET `tiempoInactivo` = 0.0
WHERE `noMantenimiento` = 3;
DELETE FROM reparacion WHERE `noRepar` = 11;
call `modificarTecnico`(112, 'mec', 'mor');