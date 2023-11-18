<?php
// insertEstudiante.inc
include_once('conexion.inc');  

// Obtener los datos proporcionados en formato JSON.
$data = file_get_contents("php://input");
$valores = json_decode($data, TRUE);

// Convertir valores booleanos a enteros (1 o 0).
$valores['AyudaFamiliar'] = $valores['AyudaFamiliar'] ? 1 : 0;
$valores['Beca'] = $valores['Beca'] ? 1 : 0;

try {
    // Iniciar transacción.
    $conex->begin_transaction();

    // Preparar e insertar en la tabla 'estudiantes'.
    $stmt = $conex->prepare("INSERT INTO estudiantes (Estudiante_id, CedulaEstudiante, AyudaFamiliar, Beca, CursoID) VALUES (?, ?, ?, ?, ?)");
    // Asegúrate de que los tipos de datos en bind_param sean correctos: 'i' para enteros, 's' para cadenas.
    $stmt->bind_param("isiii", $valores['Estudiante_id'], $valores['CedulaEstudiante'], $valores['AyudaFamiliar'], $valores['Beca'], $valores['CursoID']);
    $stmt->execute();

    // Si todo fue bien, commit a la transacción.
    $conex->commit();
    deliver_response(201, "Estudiante creado", NULL);
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
