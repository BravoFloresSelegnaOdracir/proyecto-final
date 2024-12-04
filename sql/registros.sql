-- Active: 1731395308412@@127.0.0.1@3306@mantenimientoEquipo
INSERT INTO area(clave, nombre, ubicacion, descripcion) VALUES
('ESTP', 'Stamping', 'Building A, Floor 1, Sector 3', 'Moulding of metal parts for the bodywork.'),
('CARR', 'Bodywork', 'Building B, Floor 2, Sector 5', 'Assembly of vehicle body parts.'),
('PINT', 'Painting', 'Building C, Floor 3, Sector 2', 'Application of protective and decorative paint.'),
('MONT', 'Assembly', 'Building D, Floor 3, Sector 1', 'Installation of engine, transmission and other components.'),
('PLMT', 'Engine Plant', 'Building E, Floor 1, Sector 2', 'Engine manufacturing and assembly.'),
('TRNS', 'Transmission', 'Building F, Floor 3, Sector 5', 'Assembly of transmission systems.'),
('CHAS', 'Chassis', 'Building G, Floor 1, Sector 4', 'Assembly of the vehicles base structure.');
INSERT INTO estadoEquipo (codigo, descripcion) VALUES
('ACTV', 'Active.'),
('INAC', 'Inactive.'),
('BAJA', 'Removed.');
INSERT INTO tipoModelo (codigoTE, nombre, descripcion) VALUES
('PRSE', 'Hydraulic Press', 'Equipment used to mold and assemble metal components.'),
('SLDW', 'Automatic welding machine', 'Machine that performs precise welds on metal parts.'),
('PNTM', 'Painting Machine', 'A device that applies paint to vehicle bodies.'),
('CNCN', 'CNC Machine', 'Computerized numerical control equipment used for cutting and milling.'),
('ASMB', 'Assembly Line', 'Automated system for assembling different parts of the vehicle.'),
('WRBT', 'Welding Robot', 'Robotic arm that performs automated welding in various areas.'),
('FRNM', 'Braking machine', 'Equipment that verifies and adjusts brake systems in vehicles.'),
('CLTP', 'Lifting platform', 'A machine used to lift and move large parts or vehicles within the plant.'),
('ENGT', 'Motor Tester', 'Equipment used to evaluate the performance of manufactured engines.'),
('DRPT', 'Transmission Test Bench', 'Equipment used to test the performance and quality of transmissions.'),
('STML', 'Plastics Molding System', 'A machine that creates plastic parts for the vehicle.'),
('BTRY', 'Battery Tester', 'Device for checking the capacity and condition of the vehicles batteries.'),
('RGWH', 'Wheel Mounting Robot', 'Robotic system in charge of mounting and adjusting the wheels on the vehicle.'),
('PHLT', 'Quality Control Platform', 'Equipment used to verify alignment and assembly quality.'),
('LSRM', 'Laser Cutting Machine', 'Used to cut metal parts with great precision using laser.'),
('CHRG', 'Electric Vehicle Charger', 'Device for charging electric vehicles during quality testing.');
INSERT INTO marca (codigoMarca, nombre) VALUES
('CAT', 'Caterpillar'),
('FAN', 'Fanuc'),
('ABB', 'ABB Robotics'),
('KOM', 'Komatsu'),
('HAA', 'Haas Automation'),
('YAS', 'Yaskawa Electric'),
('KUK', 'KUKA Robotics');
INSERT INTO modelo (codigoModelo, nombre, vidaUtilEstimada, marca, tipo) VALUES
('PR1', 'Press', 15, 'CAT', 'PRSE'), -- Prensa hidráulica
('RBT', 'Robot', 10, 'FAN', 'WRBT'), -- Robot de soldadura
('CNT', 'Centered', 12, 'ABB', 'CNCN'), -- Máquina CNC
('HRN', 'Radio Tool', 8, 'KOM', 'CNCN'), -- Máquina CNC
('CNC', 'Numerical Control', 10, 'HAA', 'CNCN'), -- Máquina CNC
('TRN', 'Lathe', 15, 'YAS', 'CNCN'), -- Máquina CNC
('INJ', 'Injector', 12, 'KUK', 'STML'), -- Sistema de moldeo de plásticos
('CPR', 'Compression', 10, 'CAT', 'CLTP'), -- Plataforma elevadora
('PLS', 'Ironer', 14, 'ABB', 'STML'), -- Sistema de moldeo de plásticos
('TLD', 'Drill', 10, 'CAT', 'CNCN'), -- Máquina CNC
('LAS', 'Lasser', 8, 'HAA', 'LSRM'), -- Máquina de corte láser
('EHV', 'Hydraulic Lift', 15, 'KOM', 'CLTP'), -- Plataforma elevadora
('SLD', 'Welding machine', 12, 'CAT', 'SLDW'), -- Soldadora automática
('BAL', 'Balancer', 9, 'YAS', 'FRNM'); -- Máquina de frenado
INSERT INTO prioridad (codigo, descripcion) VALUES
('ALT', 'High'),
('MED', 'Media'),
('BAJ', 'Low');
INSERT INTO estadoIncidencia (codigo, descripcion) VALUES
('EPR', 'In Process'),
('TER', 'Finished');
INSERT INTO tipoMantenimiento (codMante, descripcion) VALUES
('COR', 'Corrective'),
('PRE', 'Preventive');
INSERT INTO estadoMantenimiento (codigo, descripcion) VALUES
('EPR', 'In Process'),
('TER', 'Finished');
INSERT INTO materiales (codigoMaterial, nombre, stock, precio) VALUES
('MAT01', 'Stainless Steel Screw', 500, 1.5),
('MAT02', '5m power cord', 300, 15.75),
('MAT03', 'Air filter', 150, 12),
('MAT04', 'Lubricating oil', 200, 8.5),
('MAT05', 'Sturdy rubber gasket', 100, 3.2),
('MAT06', 'Tin Soldering', 250, 5),
('MAT07', 'Industrial Drive Belt', 75, 20),
('MAT08', 'High Pressure Water Filter', 60, 22.5),
('MAT09', 'Professional Electrical Tape', 400, 2.5),
('MAT10', '10A fuse', 350, 0.75);
INSERT INTO estadoSolicitud (codigo, descripcion) VALUES
('ENP', 'In Process'),
('APR', 'Approved'),
('DEN', 'Denied');
INSERT INTO tipoEmpleado (codigo, descripcion) VALUES
('GER', 'Manager'),
('TEC', 'Technician'),
('OPE', 'Operator'),
('DBA', 'Decommissioned');
INSERT INTO tipoReporte (codigo, descripcion) VALUES
('MANT', 'Maintenance'),
('REPR', 'Repair'),
('INCI', 'Incidence'),
('GNRL', 'General');
INSERT INTO especialidad (codigo, descripcion) VALUES
('MEC', 'Mechanical'),
('ELE', 'Electric'),
('MTO', 'Maintenance');
INSERT INTO turnoLaboral (claveTurno, nombre, horaInicio, horaFin) VALUES
('MAT', 'Morning', 6, 14),
('VES', 'Evening', 22, 6),
('MIX', 'Mixed', 12, 20);
INSERT INTO disponibilidad (codigo, descripcion) VALUES
('DIS', 'Available'),
('ASI', 'Assigned');
INSERT INTO equipo (numeroSerie, nombre, fechaCompra, tiempoInactivo, tiempoOperativo, funcionalidad, precioCompra, costo, modelo, marca, estadoEquipo, area) VALUES
('AB1234CD56', 'Hydraulic Press', '2020-03-15', 120.5, 5800, NULL, 150000.00, NULL, 'PR1', 'YAS', 'ACTV', 'ESTP'),
('EF7890GH12', 'Welding Robot', '2019-07-10', 75, 4000, NULL, 85000.00, NULL, 'RBT', 'FAN', 'ACTV', 'CARR'),
('IJ3456KL78', 'Conveyor belt', '2018-11-05', 45, 7600, NULL, 30000.00, NULL, 'CNT', 'KOM', 'ACTV', 'MONT'),
('MN9012OP34', 'Paint oven', '2021-01-20', 160, 6400, NULL, 120000.00, NULL, 'HRN', 'ABB', 'ACTV', 'PINT'),
('QR5678ST90', 'Assembler Robot', '2022-08-08', 50, 3200, NULL, 100000.00, NULL, 'RBT', 'KUK', 'ACTV', 'ESTP'),
('UV1234WX56', 'CNC Milling Machine', '2017-06-12', 200, 8500, NULL, 75000.00, NULL, 'CNC', 'HAA', 'ACTV', 'CHAS'),
('YZ7890AB12', 'Automatic Lathe', '2018-09-22', 100, 8900, NULL, 50000.00, NULL, 'TRN', 'YAS', 'ACTV', 'PLMT'),
('CD3456EF78', 'Injection Machine', '2020-04-18', 140, 6700, NULL, 130000.00, NULL, 'INJ', 'HAA', 'ACTV', 'TRNS'),
('GH9012IJ34', 'Air Compressor', '2019-05-30', 50, 4500, NULL, 25000.00, NULL, 'CPR', 'ABB', 'ACTV', 'CHAS'),
('KL5678MN90', 'Floor Polisher', '2021-12-01', 20, 500, NULL, 5500.00, NULL, 'PLS', 'KOM', 'ACTV', 'PINT'),
('OP1234QR56', 'Industrial Drill', '2017-02-14', 300, 9500, NULL, 45000.00, NULL, 'TLD', 'CAT', 'ACTV', 'CARR'),
('ST7890UV12', 'Painting Robot', '2019-03-25', 150, 7200, NULL, 110000.00, NULL, 'RBT', 'ABB', 'ACTV', 'PINT'),
('WX3456YZ78', 'Laser Cutting Machine', '2022-05-14', 10, 2000, NULL, 95000.00, NULL, 'LAS', 'CAT', 'ACTV', 'ESTP'),
('AB9012CD34', 'Hydraulic Lift', '2016-10-05', 500, 15000, NULL, 15000.00, NULL, 'EHV', 'KUK', 'ACTV', 'CARR'),
('EF5678GH90', 'Welding Machine', '2020-06-22', 130, 5100, NULL, 95000.00, NULL, 'SLD', 'YAS', 'ACTV', 'CARR'),
('IJ1234KL56', 'Balancing Machine', '2018-08-30', 100, 7800, NULL, 80000.00, NULL, 'BAL', 'HAA', 'ACTV', 'CHAS');

INSERT INTO equipo (numeroSerie, nombre, fechaCompra, tiempoInactivo, tiempoOperativo, funcionalidad, precioCompra, costo, modelo, marca, estadoEquipo, area) VALUES
('QR9012ST34', 'Vacuum Pump', '2019-11-10', 60, 4300, NULL, 45000.00, NULL, 'RBT', 'ABB', 'ACTV', 'TRNS'),
('UV5678WX90', 'Thermal Scanner', '2021-03-20', 30, 1800, NULL, 65000.00, NULL, 'THM', 'CAT', 'ACTV', 'PLMT'),
('YZ1234AB56', 'Press Brake', '2017-05-05', 220, 8700, NULL, 120000.00, NULL, 'BRK', 'YAS', 'ACTV', 'CHAS'),
('CD7890EF12', 'Grinder Machine', '2022-01-18', 25, 2500, NULL, 40000.00, NULL, 'GRN', 'KOM', 'ACTV', 'PINT');