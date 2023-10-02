<?php
include_once("backend.php");
require_once('../config/database.php');

header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $listarVehiculos = "SELECT * FROM vehiculos";
    $resultado = $conexion->query($listarVehiculos);

    if ($resultado->num_rows > 0) {
        $vehiculos = array();
        while ($fila = $resultado->fetch_assoc()) {
            $vehiculos[] = $fila;
        }
        http_response_code(200);
        echo json_encode($vehiculos);
    } else {
        http_response_code(200); 
        echo json_encode(array());
    }
}
?>
