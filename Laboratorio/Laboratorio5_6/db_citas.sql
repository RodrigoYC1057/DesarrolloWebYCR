CREATE DATABASE IF NOT EXISTS db_citas_medicas;
USE db_citas_medicas;

-- Tabla de médicos
CREATE TABLE medicos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    especialidad VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    correo VARCHAR(100)
);

-- Tabla de pacientes
CREATE TABLE pacientes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    documento VARCHAR(20) NOT NULL,
    telefono VARCHAR(20),
    correo VARCHAR(100)
);

-- Tabla de citas
CREATE TABLE citas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_paciente INT NOT NULL,
    id_medico INT NOT NULL,
    fecha_cita DATE NOT NULL,
    hora_cita TIME NOT NULL,
    estado ENUM('Pendiente', 'Atendida', 'Cancelada') DEFAULT 'Pendiente',
    motivo TEXT,
    FOREIGN KEY (id_paciente) REFERENCES pacientes(id),
    FOREIGN KEY (id_medico) REFERENCES medicos(id)
);

-- Datos de prueba (opcional)
INSERT INTO medicos (nombre, especialidad, telefono, correo) VALUES
('Dr. Carlos Mendoza', 'Cardiología', '591-70000001', 'cmendoza@clinica.com'),
('Dra. Ana Quispe', 'Pediatría', '591-70000002', 'aquispe@clinica.com');

INSERT INTO pacientes (nombre, documento, telefono, correo) VALUES
('Juan Pérez', '12345678', '591-70000010', 'jperez@mail.com'),
('María López', '87654321', '591-70000011', 'mlopez@mail.com');