<?php
include_once("backend.php");
require_once('../config/database.php');
header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Origin: *");


if ($_SERVER["REQUEST_METHOD"] === "DELETE") {
    $id = $_GET['id'];

    $obtenerLugarQuery = "SELECT lugar FROM vehiculos WHERE id = ?";
    $stmt_obtener_lugar = $conexion->prepare($obtenerLugarQuery);
    $stmt_obtener_lugar->bind_param("i", $id);
    $stmt_obtener_lugar->execute();
    $stmt_obtener_lugar->store_result();

    if ($stmt_obtener_lugar->num_rows > 0) {
        $stmt_obtener_lugar->bind_result($lugar);
        $stmt_obtener_lugar->fetch();

        $marcarLugarDisponibleQuery = "UPDATE vehiculos SET lugar = NULL WHERE lugar = ?";
        $stmt_marcar_lugar_disponible = $conexion->prepare($marcarLugarDisponibleQuery);
        $stmt_marcar_lugar_disponible->bind_param("i", $lugar);
        $stmt_marcar_lugar_disponible->execute();
    }

    $eliminarVehiculo = "DELETE FROM vehiculos WHERE id = ?";
    $stmt = $conexion->prepare($eliminarVehiculo);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        http_response_code(204);
        exit();
    } else {
        http_response_code(500);
        echo json_encode(array("message" => "Error al eliminar el vehículo."));
        exit();
    }
}
?>
