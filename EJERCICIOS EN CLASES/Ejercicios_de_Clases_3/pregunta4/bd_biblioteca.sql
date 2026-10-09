CREATE DATABASE IF NOT EXISTS bd_biblioteca
CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE bd_biblioteca;

CREATE TABLE IF NOT EXISTS libros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    autor VARCHAR(100) NOT NULL,
    anio INT NOT NULL,
    editorial VARCHAR(100) NOT NULL
);

INSERT INTO libros (titulo, autor, anio, editorial) VALUES
('Introducción a la Informática', 'Michael Miller', 2015, 'Pearson'),
('Arquitectura de Computadoras', 'Patricio Quiroga', 2010, 'Alfaomega');
