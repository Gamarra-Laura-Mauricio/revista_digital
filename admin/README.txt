SISTEMA DE NOTICIAS - PANEL ADMINISTRATIVO
===========================================

1. Copia la carpeta sistema_noticias_admin dentro de C:\xampp\htdocs\
2. Abre phpMyAdmin (http://localhost/phpmyadmin)
3. Importa el archivo sql_instalacion.sql
4. Si tu MySQL no usa root sin contraseña, edita config/config.php
5. Abre: http://localhost/sistema_noticias_admin/

LOGIN DE PRUEBA
Usuario: mauricio
Contraseña: 1234

IMPORTANTE
La autenticación consulta directamente la tabla `usuarios`.
En esta versión la columna `password_hash` guarda la contraseña en texto plano,
tal como se solicitó para el proyecto académico/local. Por tanto, si agregas o
modificas un usuario desde el panel, la contraseña se guarda exactamente como
la escribas y esa misma contraseña se utiliza para iniciar sesión.

ESTRUCTURA
- login.php              Login obligatorio
- index.php              Dashboard
- gestion.php            CRUD de contenidos y catálogos
- logout.php             Cierre de sesión
- config/                Conexión a MySQL
- includes/              Autenticación y layout
- uploads/               Archivos subidos por el administrador
- sql_instalacion.sql    Base de datos + usuario administrador

NOTA
El diseño usa la plantilla entregada (Adminator) como base visual y conserva sus assets locales.
