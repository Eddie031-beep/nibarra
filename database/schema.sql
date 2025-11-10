-- SCHEMA NIBARRA – compatible MySQL 8.0 / MariaDB 10.4
-- crea DB si no existe y usa utf8mb4
CREATE DATABASE IF NOT EXISTS nibarra_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE nibarra_db;

-- -----------------------------------------------------
-- TABLAS BÁSICAS
-- -----------------------------------------------------

-- Roles de usuario (admin, técnico, visor)
CREATE TABLE IF NOT EXISTS roles (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nombre      VARCHAR(40) NOT NULL UNIQUE,
  descripcion VARCHAR(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Usuarios del sistema
CREATE TABLE IF NOT EXISTS users (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  role_id       INT UNSIGNED NOT NULL,
  nombre        VARCHAR(100) NOT NULL,
  email         VARCHAR(120) NOT NULL UNIQUE,
  password      VARCHAR(255) NOT NULL,
  activo        TINYINT(1) NOT NULL DEFAULT 1,
  remember_token VARCHAR(100) DEFAULT NULL,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_users_roles FOREIGN KEY (role_id)
    REFERENCES roles(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- INVENTARIO (Equipos/Activos)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS equipos (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  codigo        VARCHAR(40) NOT NULL UNIQUE,     -- código interno
  nombre        VARCHAR(120) NOT NULL,
  categoria     VARCHAR(80)  DEFAULT NULL,
  marca         VARCHAR(80)  DEFAULT NULL,
  modelo        VARCHAR(80)  DEFAULT NULL,
  nro_serie     VARCHAR(120) DEFAULT NULL,
  ubicacion     VARCHAR(150) DEFAULT NULL,
  fecha_compra  DATE DEFAULT NULL,
  proveedor     VARCHAR(120) DEFAULT NULL,
  costo         DECIMAL(12,2) DEFAULT NULL,
  estado        ENUM('operativo','fuera_de_servicio','baja') NOT NULL DEFAULT 'operativo',
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_equipos_nombre (nombre),
  INDEX idx_equipos_ubicacion (ubicacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- ÓRDENES / MANTENIMIENTOS
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS mantenimientos (
  id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  equipo_id        INT UNSIGNED NOT NULL,
  tipo             ENUM('preventivo','correctivo','inspeccion') NOT NULL DEFAULT 'preventivo',
  prioridad        ENUM('baja','media','alta','critica') NOT NULL DEFAULT 'media',
  titulo           VARCHAR(150) NOT NULL,
  descripcion      TEXT,
  fecha_programada DATETIME DEFAULT NULL,
  fecha_inicio     DATETIME DEFAULT NULL,
  fecha_cierre     DATETIME DEFAULT NULL,
  estado           ENUM('pendiente','en_progreso','completado','cancelado') NOT NULL DEFAULT 'pendiente',
  tecnico_id       INT UNSIGNED DEFAULT NULL,  -- asignado
  costo_estimado   DECIMAL(12,2) DEFAULT NULL,
  costo_real       DECIMAL(12,2) DEFAULT NULL,
  created_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_mant_equipo   FOREIGN KEY (equipo_id)  REFERENCES equipos(id) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_mant_tecnico  FOREIGN KEY (tecnico_id) REFERENCES users(id)   ON UPDATE CASCADE ON DELETE SET NULL,
  INDEX idx_mant_estado (estado),
  INDEX idx_mant_fprog (fecha_programada),
  INDEX idx_mant_equipo (equipo_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tareas checklist por orden de mantenimiento
CREATE TABLE IF NOT EXISTS mantenimiento_tareas (
  id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  mantenimiento_id INT UNSIGNED NOT NULL,
  titulo           VARCHAR(200) NOT NULL,
  hecho            TINYINT(1) NOT NULL DEFAULT 0,
  orden            INT UNSIGNED NOT NULL DEFAULT 1,
  CONSTRAINT fk_tareas_mant FOREIGN KEY (mantenimiento_id)
    REFERENCES mantenimientos(id) ON UPDATE CASCADE ON DELETE CASCADE,
  INDEX idx_tareas_orden (mantenimiento_id, orden)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- CALENDARIO (eventos generales o ligados a un mantenimiento)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS calendario_eventos (
  id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titulo            VARCHAR(150) NOT NULL,
  inicio            DATETIME NOT NULL,
  fin               DATETIME DEFAULT NULL,
  all_day           TINYINT(1) NOT NULL DEFAULT 0,
  color             VARCHAR(20) DEFAULT NULL,
  mantenimiento_id  INT UNSIGNED DEFAULT NULL,
  creado_por        INT UNSIGNED DEFAULT NULL,
  created_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_cal_mant FOREIGN KEY (mantenimiento_id)
    REFERENCES mantenimientos(id) ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_cal_user FOREIGN KEY (creado_por)
    REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL,
  INDEX idx_cal_inicio (inicio),
  INDEX idx_cal_mant (mantenimiento_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -----------------------------------------------------
-- LOGS (opcional, útil para auditoría básica)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS audit_logs (
  id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  usuario_id   INT UNSIGNED DEFAULT NULL,
  tabla        VARCHAR(64) NOT NULL,
  registro_id  VARCHAR(64) NOT NULL,
  accion       ENUM('insert','update','delete') NOT NULL,
  detalle      JSON DEFAULT NULL,                 -- cambios o payload
  created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_audit (tabla, registro_id, accion, created_at),
  CONSTRAINT fk_audit_user FOREIGN KEY (usuario_id)
    REFERENCES users(id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
