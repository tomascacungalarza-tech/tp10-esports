CREATE DATABASE IF NOT EXISTS esports_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
USE esports_db;

CREATE TABLE IF NOT EXISTS coach (
    id_coach INT AUTO_INCREMENT PRIMARY KEY,
    nombres VARCHAR(50) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    nacionalidad VARCHAR(50),
    fecha_nacimiento DATE,
    experiencia VARCHAR(100)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS contrato_coach (
    id_contrato_coach INT AUTO_INCREMENT PRIMARY KEY,
    id_coach INT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE,
    salario DECIMAL(10,2),
    descripcion TEXT,
    FOREIGN KEY (id_coach) REFERENCES coach(id_coach) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS equipo (
    id_equipo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    dueno VARCHAR(50),
    canti_integrantes INT DEFAULT 0,
    fecha_fundacion DATE,
    ranking INT,
    id_coach INT UNIQUE,
    FOREIGN KEY (id_coach) REFERENCES coach(id_coach) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS jugador (
    id_jugador INT AUTO_INCREMENT PRIMARY KEY,
    nickname VARCHAR(50) NOT NULL,
    nombres VARCHAR(50) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    posicion VARCHAR(30),
    rol VARCHAR(30),
    nacionalidad VARCHAR(50),
    fecha_nacimiento DATE,
    id_equipo INT,
    FOREIGN KEY (id_equipo) REFERENCES equipo(id_equipo) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS contrato_jugador (
    id_contrato_jugador INT AUTO_INCREMENT PRIMARY KEY,
    id_jugador INT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE,
    salario DECIMAL(10,2),
    descripcion TEXT,
    FOREIGN KEY (id_jugador) REFERENCES jugador(id_jugador) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS historial_jugador (
    id_historial INT AUTO_INCREMENT PRIMARY KEY,
    id_jugador INT NOT NULL,
    id_equipos_ant INT,
    estadisticas_en_torneos TEXT,
    tiempo_en_la_liga VARCHAR(50),
    FOREIGN KEY (id_jugador) REFERENCES jugador(id_jugador) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS sponsor (
    id_sponsor INT AUTO_INCREMENT PRIMARY KEY,
    nombre_empresa VARCHAR(50) NOT NULL,
    monto DECIMAL(12,2)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS equipo_sponsor (
    id_equipo INT NOT NULL,
    id_sponsor INT NOT NULL,
    fecha_ini DATE,
    fecha_fin DATE,
    PRIMARY KEY (id_equipo, id_sponsor),
    FOREIGN KEY (id_equipo) REFERENCES equipo(id_equipo) ON DELETE CASCADE,
    FOREIGN KEY (id_sponsor) REFERENCES sponsor(id_sponsor) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS torneos (
    id_torneo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo VARCHAR(50),
    ano INT,
    pais VARCHAR(50)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS participacion (
    id_equipo INT NOT NULL,
    id_torneo INT NOT NULL,
    ranking_equipo INT,
    PRIMARY KEY (id_equipo, id_torneo),
    FOREIGN KEY (id_equipo) REFERENCES equipo(id_equipo) ON DELETE CASCADE,
    FOREIGN KEY (id_torneo) REFERENCES torneos(id_torneo) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS partidas (
    id_partida INT AUTO_INCREMENT PRIMARY KEY,
    nro_partido INT NOT NULL,
    mapa VARCHAR(50),
    fecha DATE,
    horario TIME,
    score_equipo1 INT,
    score_equipo2 INT,
    resultado VARCHAR(50),
    id_torneo INT NOT NULL,
    FOREIGN KEY (id_torneo) REFERENCES torneos(id_torneo) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS estadisticas_desempeno (
    id_estadistica INT AUTO_INCREMENT PRIMARY KEY,
    id_partida INT NOT NULL,
    id_jugador INT NOT NULL,
    kills INT DEFAULT 0,
    muertes INT DEFAULT 0,
    asistencia INT DEFAULT 0,
    headshot INT DEFAULT 0,
    mvps INT DEFAULT 0,
    FOREIGN KEY (id_partida) REFERENCES partidas(id_partida) ON DELETE CASCADE,
    FOREIGN KEY (id_jugador) REFERENCES jugador(id_jugador) ON DELETE CASCADE
) ENGINE=InnoDB;
