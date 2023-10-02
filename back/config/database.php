<?php
$host = "localhost";
$usuario = "root";
$contrasena = "root";
$bd = "parking";

$conexion = new mysqli($host, $usuario, $contrasena,$bd);

if ($conexion->connect_error) {
    die("Error en la conexión a la base de datos: " . $conexion->connect_error);
}
?>
