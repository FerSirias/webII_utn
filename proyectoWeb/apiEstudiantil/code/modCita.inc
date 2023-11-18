<?php
// updateCita.inc
include_once('conexion.inc'); 

// Asumiendo que `$request` contiene el ID de la cita que se va a actualizar
// y que ya ha sido extraído de la URL de la solicitud PUT.
$request = ...; // Debes obtener el ID de la cita de la URL aquí.

// Obtener los datos proporcionados en formato JSON.
$data = file_get_contents("php://input");
$valores = json_decode($data, TRUE);

// Convertir la fecha ISO 8601 a un formato de fecha y hora MySQL (YYYY-MM-DD HH:MM:SS).
$fechaCita = new DateTime($valores['fechaCita']);
$fechaCitaMySQL = $fechaCita->format('Y-m-d H:i:s');

try {
    // Iniciar transacción.
    $conex->begin_transaction();

    // Preparar y ejecutar la instrucción SQL de actualización en la tabla 'citas'.
    $stmt = $conex->prepare("UPDATE citas SET Id_Solicitud = ?, funcionarioCedula = ?, fechaCita = ?, status = ? WHERE CitaId = ?");
    $stmt->bind_param("isssi", $valores['Id_Solicitud'], $valores['funcionarioCedula'], $fechaCitaMySQL, $valores['status'], $request);
    $stmt->execute();

    // Verificar si la actualización fue exitosa.
    if ($stmt->affected_rows === 0) {
        throw new Exception('No se encontró la cita con el ID proporcionado o los datos son los mismos.');
    }

    // Si todo fue bien, commit a la transacción.
    $conex->commit();
    deliver_response(200, "Cita actualizada", NULL);
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