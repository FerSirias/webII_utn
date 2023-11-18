<?php
// insertSolicitud.inc
include_once('conexion.inc'); // Incluye tu script de conexión a la base de datos.

// Obtener los datos proporcionados en formato JSON.
$data = file_get_contents("php://input");
$valores = json_decode($data, TRUE);

try {
    // Iniciar transacción.
    $conex->begin_transaction();

    // Preparar e insertar en la tabla 'solicitudes'.
    $stmt = $conex->prepare("INSERT INTO solicitudes (Solicitud_id, CedulaEst, Motivo, FechaSolicitud, AreaAtencion) VALUES (?, ?, ?, ?, ?)");
    
    // Convertir la fecha ISO 8601 a un formato de fecha y hora MySQL (YYYY-MM-DD HH:MM:SS).
    //$fechaSolicitud = new DateTime($valores['FechaSolicitud']);
    //$fechaSolicitudMySQL = $fechaSolicitud->format('Y-m-d H:i:s');
    
    // Asegúrate de que los tipos de datos en bind_param sean correctos: 'i' para enteros, 's' para cadenas.
    $stmt->bind_param("issds", $valores['Solicitud_id'], $valores['CedulaEst'], $valores['Motivo'], $valores['FechaSolicitud'], $valores['AreaAtencion']);
    $stmt->execute();

    // Si todo fue bien, commit a la transacción.
    $conex->commit();
    deliver_response(201, "Solicitud creada", NULL);
} catch (Exception $e) {
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
