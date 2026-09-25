CREATE DATABASE IF NOT EXISTS sistema_noticias CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_noticias;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100),
    email VARCHAR(100),
    password_hash VARCHAR(100),
    rol VARCHAR(50),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS autores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    apellidos VARCHAR(50)
);
CREATE TABLE IF NOT EXISTS reportajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150),
    resumen_corto VARCHAR(255),
    desarrollo TEXT,
    foto_principal VARCHAR(255),
    pdf_adjunto VARCHAR(255),
    fecha_publicacion DATETIME,
    es_destacado BOOLEAN DEFAULT FALSE,
    autor_id INT,
    usuario_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (autor_id) REFERENCES autores(id) ON DELETE SET NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);
CREATE TABLE IF NOT EXISTS reportajes_fotos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reportaje_id INT,
    url_foto VARCHAR(255),
    orden INT,
    descripcion VARCHAR(255),
    FOREIGN KEY (reportaje_id) REFERENCES reportajes(id) ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS noticias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150),
    foto VARCHAR(255),
    link_externo VARCHAR(255),
    fecha_publicacion DATETIME,
    usuario_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);
CREATE TABLE IF NOT EXISTS boletines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_boletin VARCHAR(50),
    resumen VARCHAR(255),
    foto_portada VARCHAR(255),
    archivo_pdf VARCHAR(255),
    fecha_publicacion DATETIME,
    usuario_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);
CREATE TABLE IF NOT EXISTS podcasts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150),
    url_embed VARCHAR(255),
    fecha_publicacion DATETIME,
    usuario_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);
CREATE TABLE IF NOT EXISTS videos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150),
    url_embed VARCHAR(255),
    fecha_publicacion DATETIME,
    usuario_id INT,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

INSERT INTO usuarios (nombre_completo, email, password_hash, rol)
SELECT 'mauricio', 'mauricio@localhost.test', '1234', 'administrador'
WHERE NOT EXISTS (SELECT 1 FROM usuarios WHERE nombre_completo='mauricio');

INSERT INTO autores (nombre, apellidos)
SELECT 'Mauricio', 'Gamarra'
WHERE NOT EXISTS (SELECT 1 FROM autores WHERE nombre='Mauricio' AND apellidos='Gamarra');
