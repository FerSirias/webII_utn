<?php
// insertPersona.inc
include_once('conexion.inc');  

// Obtener los datos proporcionados en formato JSON.
$data = file_get_contents("php://input");
$valores = json_decode($data, TRUE);

// Iniciar transacción.
$conex->begin_transaction();

try {
    // Iniciar transacción.
    $conex->begin_transaction();

    // Preparar e insertar en la tabla 'personas'.
    $stmt = $conex->prepare("INSERT INTO personas (persona_id, cedula, nombre, apellidos, idDistrito, info_id, genero, direccion, fechaNacimiento, nacionalidad, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    // Asegúrate de que los tipos de datos en bind_param sean correctos, por ejemplo, 'i' para enteros y 's' para cadenas.
    $stmt->bind_param("isssiissdsb", $valores['persona_id'], $valores['cedula'], $valores['nombre'], $valores['apellidos'], $valores['idDistrito'], $valores['info_id'], $valores['genero'], $valores['direccion'], $valores['fechaNacimiento'], $valores['nacionalidad'], $valores['foto']);
    $stmt->execute();

    // Preparar e insertar en la tabla 'info_contactos'.
    $stmt = $conex->prepare("INSERT INTO info_contactos (info_id, correo_electronico, num_telefonico) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $valores['info_id'], $valores['correo_electronico'], $valores['num_telefonico']);
    $stmt->execute();

    // Si todo fue bien, commit a la transacción.
    $conex->commit();
    deliver_response(201, "Datos creados", NULL);
} catch (Exception $e) {
    // Si algo falla, hacer rollback a la transacción.
    $conex->rollback();
    deliver_response(500, "Error interno del servidor", $e->getMessage());
}
   catch (Exception $e) {
    // Si algo falla, hacer rollback a la transacción.
    $conex->rollback();
    deliver_response(500, "Error interno del servidor", $e->getMessage());
}

// Función para enviar la respuesta.
function deliver_response($status, $status_message, $data) {
    header("HTTP/1.1 $status $status_message");
    $response = [
        'status' => $status,
        'status_message' => $status_message,
        'data' => $data
    ];
    echo json_encode($response);
}
?>
