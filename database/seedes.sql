USE nibarra_db;

-- ROLES
INSERT INTO roles (nombre, descripcion) VALUES
  ('admin','Acceso completo'),
  ('tecnico','Gestiona mantenimientos'),
  ('visor','Sólo lectura')
ON DUPLICATE KEY UPDATE descripcion=VALUES(descripcion);

-- USUARIO ADMIN (password = "password")
-- hash bcrypt estándar ampliamente usado para "password"
INSERT INTO users (role_id, nombre, email, password, activo)
SELECT r.id, 'Administrador', 'admin@nibarra.local',
       '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1
FROM roles r WHERE r.nombre='admin'
ON DUPLICATE KEY UPDATE nombre=VALUES(nombre);

-- INVENTARIO BASE
INSERT INTO equipos (codigo, nombre, categoria, marca, modelo, nro_serie, ubicacion, fecha_compra, costo, estado)
VALUES
  ('EQ-0001','Compresor de aire','Mecánico','Atlas','C-200','SN-AX19','Taller A','2023-06-15', 3500.00,'operativo'),
  ('EQ-0002','Bomba hidráulica','Hidráulico','HydroMax','HB-45','SN-HB45','Planta 1','2022-11-02', 2100.00,'operativo')
ON DUPLICATE KEY UPDATE nombre=VALUES(nombre), ubicacion=VALUES(ubicacion);

-- ORDEN DE MANTENIMIENTO DE EJEMPLO
INSERT INTO mantenimientos (equipo_id, tipo, prioridad, titulo, descripcion, fecha_programada, estado, tecnico_id, costo_estimado)
SELECT e.id, 'preventivo','media','Mantenimiento trimestral',
       'Inspección general, lubricación, limpieza de filtros',
       DATE_ADD(CURDATE(), INTERVAL 3 DAY), 'pendiente', u.id, 120.00
FROM equipos e
JOIN users u ON u.email='admin@nibarra.local'
WHERE e.codigo='EQ-0001'
LIMIT 1;

-- TAREAS PARA LA ORDEN
INSERT INTO mantenimiento_tareas (mantenimiento_id, titulo, hecho, orden)
SELECT m.id, 'Lubricar rodamientos', 0, 1
FROM mantenimientos m
ORDER BY m.id DESC LIMIT 1;
INSERT INTO mantenimiento_tareas (mantenimiento_id, titulo, hecho, orden)
SELECT m.id, 'Limpiar filtros', 0, 2
FROM mantenimientos m
ORDER BY m.id DESC LIMIT 1;

-- EVENTO EN CALENDARIO LIGADO A LA ORDEN
INSERT INTO calendario_eventos (titulo, inicio, fin, all_day, color, mantenimiento_id, creado_por)
SELECT CONCAT('OM #', m.id, ' - ', m.titulo),
       m.fecha_programada, DATE_ADD(m.fecha_programada, INTERVAL 2 HOUR),
       0, '#2563eb', m.id, u.id
FROM mantenimientos m
JOIN users u ON u.email='admin@nibarra.local'
ORDER BY m.id DESC LIMIT 1;
