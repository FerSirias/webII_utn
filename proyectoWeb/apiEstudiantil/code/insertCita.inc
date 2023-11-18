<?php
// insertCita.inc
include_once('conexion.inc'); 

// Obtener los datos proporcionados en formato JSON.
$data = file_get_contents("php://input");
$valores = json_decode($data, TRUE);

// Convertir la fecha ISO 8601 a un formato de fecha y hora MySQL (YYYY-MM-DD HH:MM:SS).
$fechaCita = new DateTime($valores['fechaCita']);
$fechaCitaMySQL = $fechaCita->format('Y-m-d H:i:s');

try {
    // Iniciar transacción.
    $conex->begin_transaction();

    // Preparar e insertar en la tabla 'citas'.
    // Luego, cuando preparas tu sentencia SQL, usarías $fechaCitaMySQL para 'fechaCita'.
    $stmt = $conex->prepare("INSERT INTO citas (CitaId, Id_Solicitud, funcionarioCedula, fechaCita, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiss", $valores['CitaId'], $valores['Id_Solicitud'], $valores['funcionarioCedula'], $fechaCitaMySQL, $valores['status']);
    $stmt->execute();

    // Si todo fue bien, commit a la transacción.
    $conex->commit();
    deliver_response(201, "Cita creada", NULL);
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

