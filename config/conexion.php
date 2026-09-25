<?php
$host     = 'sql110.infinityfree.com';
$dbname   = 'if0_43004905_revista_digital';
$username = 'if0_43004905';
$password = 'oYPfWnoPWjLPs';

$conexion = new mysqli($host, $username, $password, $dbname);

if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");
?>