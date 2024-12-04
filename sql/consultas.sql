SELECT nombre, funcionalidad, tiempoOperativo, tiempoInactivo
FROM equipo
ORDER BY funcionalidad ASC;
/*
    Incidencias por equipos
*/
SELECT i.equipo, 
COUNT(i.noIncidencia) 
AS totalIncidencias, 
p.descripcion AS prioridad
FROM incidencia i
INNER JOIN equipo e ON i.equipo = e.numeroSerie
INNER JOIN prioridad p ON i.prioridad = p.codigo
GROUP BY i.equipo
ORDER BY totalIncidencias DESC;
/*
    Reporte de costo de mantenimiento y reparaciones
*/
SELECT e.nombre AS equipo, 
       SUM(m.costo) AS costoMantenimiento, 
       SUM(r.costo) AS costoReparaciones, 
       (SUM(m.costo) + SUM(r.costo)) AS costoTotal
FROM equipo e
LEFT JOIN mantenimiento m ON e.numeroSerie = m.equipo
LEFT JOIN reparacion r ON m.noMantenimiento = r.mantenimiento
GROUP BY e.nombre
ORDER BY costoTotal DESC;
/*
    Eficiencia de los técnicos
*/
SELECT t.noTecnico AS tecnico, 
       CONCAT(e.nombre, ' ', e.apellidoP, ' ', e.apellidoM) AS nombreTecnico,
       COUNT(DISTINCT i.noIncidencia) AS totalIncidencias, 
       COUNT(DISTINCT m.noMantenimiento) AS totalMantenimientos
FROM tecnico t
LEFT JOIN empleado e ON t.noTecnico = e.noEmpleado
LEFT JOIN incidencia i ON t.noTecnico = i.tecnicoAsignado
LEFT JOIN mantenimiento m ON t.noTecnico = m.tecnico
GROUP BY t.noTecnico, nombreTecnico
ORDER BY totalIncidencias DESC;
/*
    Tiempo promedio de resulución de incidencias
*/
SELECT t.noTecnico AS tecnico, 
       CONCAT(e.nombre, ' ', e.apellidoP, ' ', e.apellidoM) AS nombreTecnico,
       AVG(DATEDIFF(i.fechaCierre, i.fechaInicio)) AS tiempoPromedioDias
FROM incidencia i
INNER JOIN tecnico t ON i.tecnicoAsignado = t.noTecnico
INNER JOIN empleado e ON t.noTecnico = e.noEmpleado
WHERE i.fechaCierre IS NOT NULL
GROUP BY t.noTecnico, nombreTecnico
ORDER BY tiempoPromedioDias ASC;
