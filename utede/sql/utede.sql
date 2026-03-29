-- ============================================================
--  Base de datos UTEDE
-- ============================================================
CREATE DATABASE IF NOT EXISTS utede
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE utede;

-- Tabla de usuarios del sistema
CREATE TABLE IF NOT EXISTS usuarios (
    id_usuario      INT          NOT NULL AUTO_INCREMENT,
    identificacion  VARCHAR(20)  NOT NULL,
    apellidos       VARCHAR(100) NOT NULL,
    nombres         VARCHAR(100) NOT NULL,
    usuario         VARCHAR(50)  NOT NULL,
    clavehash       VARCHAR(255) NOT NULL,
    PRIMARY KEY (id_usuario),
    UNIQUE KEY uq_identificacion (identificacion),
    UNIQUE KEY uq_usuario        (usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de estudiantes
CREATE TABLE IF NOT EXISTS estudiantes (
    id_estudiante   INT          NOT NULL AUTO_INCREMENT,
    identificacion  VARCHAR(20)  NOT NULL,
    apellidos       VARCHAR(100) NOT NULL,
    nombre          VARCHAR(100) NOT NULL,
    email           VARCHAR(150) NOT NULL,
    PRIMARY KEY (id_estudiante),
    UNIQUE KEY uq_est_identificacion (identificacion),
    UNIQUE KEY uq_est_email          (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
