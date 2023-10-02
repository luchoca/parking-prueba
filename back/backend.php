<?php
$host2 = "localhost";
$usuario2 = "root";
$contrasena2 = "root";
$bd = "parking";

$conexion = new mysqli($host2, $usuario2, $contrasena2); 

$crearBD = "CREATE DATABASE IF NOT EXISTS $bd";

if ($conexion->query($crearBD) === TRUE) {
    echo "Base de datos creada o ya existente.";
} else {
    echo "Error al crear la base de datos: " . $conexion->error;
}

$conexion->select_db("parking");

$crearTabla = "CREATE TABLE IF NOT EXISTS vehiculos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricula VARCHAR(20) NOT NULL UNIQUE,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    color VARCHAR(20) NOT NULL,
    lugar INT NOT NULL UNIQUE
)";

if ($conexion->query($crearTabla) === TRUE) {
    echo "Tabla 'vehiculos' creada o ya existente.";
} else {
    echo "Error al crear la tabla 'vehiculos': " . $conexion->error;
}
?>
