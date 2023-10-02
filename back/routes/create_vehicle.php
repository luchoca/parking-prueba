<?php
include_once("backend.php");
require_once('../config/database.php');

header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Origin: *");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"));

    if (
        isset($data->matricula) &&
        isset($data->marca) &&
        isset($data->modelo) &&
        isset($data->color) &&
        isset($data->lugar)
    ) {
        $matricula = $data->matricula;
        $marca = $data->marca;
        $modelo = $data->modelo;
        $color = $data->color;
        $lugar = $data->lugar;

        $countVehiclesQuery = "SELECT COUNT(*) AS vehicle_count FROM vehiculos";
        $countResult = $conexion->query($countVehiclesQuery);
        $row = $countResult->fetch_assoc();
        $vehicleCount = (int) $row["vehicle_count"];

        $response = array();

        if ($vehicleCount >= 50) {
            http_response_code(400);
            $response["message"] = "El estacionamiento está lleno. No se puede agregar más vehículos.";
        } else {
            $verificarMatricula = "SELECT id FROM vehiculos WHERE matricula = ?";
            $stmt_verificar = $conexion->prepare($verificarMatricula);
            $stmt_verificar->bind_param("s", $matricula);
            $stmt_verificar->execute();
            $stmt_verificar->store_result();

            if ($stmt_verificar->num_rows > 0) {
                http_response_code(400);
                $response["message"] = "La matrícula ya está registrada.";
            } else {
                $verificarLugar = "SELECT id FROM vehiculos WHERE lugar = ?";
                $stmt_verificar_lugar = $conexion->prepare($verificarLugar);
                $stmt_verificar_lugar->bind_param("i", $lugar);
                $stmt_verificar_lugar->execute();
                $stmt_verificar_lugar->store_result();

                if ($stmt_verificar_lugar->num_rows > 0) {
                    http_response_code(400);
                    $response["message"] = "El lugar ya está ocupado por otro vehículo.";
                } else {
                    $insertarVehiculo = "INSERT INTO vehiculos (matricula, marca, modelo, color, lugar)
                            VALUES (?, ?, ?, ?, ?)";

                    $stmt = $conexion->prepare($insertarVehiculo);
                    $stmt->bind_param("ssssi", $matricula, $marca, $modelo, $color, $lugar);

                    if ($stmt->execute()) {
                        http_response_code(201);
                        $response["message"] = "Vehículo creado con éxito.";
                    } else {
                        http_response_code(500);
                        $response["message"] = "Error al crear el vehículo.";
                    }
                }
            }
        }

        header("Content-Type: application/json");
        echo json_encode($response);
    } else {
        http_response_code(400);
        $response["message"] = "Faltan campos obligatorios en la solicitud.";
        echo json_encode($response);
    }
}
?>
