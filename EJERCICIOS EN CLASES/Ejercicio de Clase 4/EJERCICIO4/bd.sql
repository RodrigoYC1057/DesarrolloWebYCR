-- Importar en phpMyAdmin (se puede importar varias veces para restaurar las recetas)
SET NAMES utf8mb4;
CREATE DATABASE IF NOT EXISTS ejercicio4_db
    CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE ejercicio4_db;

DROP TABLE IF EXISTS recetas;
CREATE TABLE recetas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    imagen VARCHAR(200) NOT NULL,
    preparacion TEXT NOT NULL
);

INSERT INTO recetas (titulo, tipo, imagen, preparacion) VALUES
('Caldo de Pollo', 'Sopas', 'imagenes/caldo.svg',
 'Hervir el pollo con zanahoria, papa, apio y cebolla en agua con sal durante 40 minutos. Retirar el pollo, deshilacharlo y volver a agregarlo al caldo. Servir caliente con cilantro picado.'),
('Lomo Saltado', 'Platos principales', 'imagenes/lomo.svg',
 'Cortar la carne en tiras y saltearla a fuego alto con cebolla, tomate y aji. Agregar salsa de soya y vinagre. Servir con papas fritas y arroz blanco.'),
('Flan de Vainilla', 'Postres', 'imagenes/flan.svg',
 'Hacer un caramelo y verterlo en el molde. Licuar leche, huevos, azucar y vainilla, y colar sobre el caramelo. Cocinar a baño maria 50 minutos y enfriar antes de desmoldar.'),
('Bruschetta', 'Entradas', 'imagenes/bruschetta.svg',
 'Tostar rebanadas de pan con un poco de aceite de oliva. Mezclar tomate picado, ajo, albahaca, sal y aceite. Colocar la mezcla sobre el pan tostado y servir de inmediato.'),
('Silpancho', 'Platos principales', 'imagenes/silpancho.svg',
 'Aplanar la carne bien delgada, apanarla y freirla. Servir sobre arroz y papas cocidas, cubrir con huevo frito y acompañar con salsa de tomate y cebolla.'),
('Ensalada Cesar', 'Ensaladas', 'imagenes/ensalada.svg',
 'Lavar y trocear la lechuga. Mezclar con aderezo cesar, crutones y queso parmesano rallado. Agregar pollo a la plancha en tiras y servir fria.');
