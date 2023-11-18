<?php
// insertFuncionario.inc
include_once('conexion.inc'); 

// Obtener los datos proporcionados en formato JSON.
$data = file_get_contents("php://input");
$valores = json_decode($data, TRUE);

try {
    // Iniciar transacción.
    $conex->begin_transaction();

    // Preparar e insertar en la tabla 'funcionarios'.
    $stmt = $conex->prepare("INSERT INTO funcionarios (FuncionarioID, usuario_id, CedulaFuncionario, areald) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $valores['FuncionarioID'], $valores['usuario_id'], $valores['CedulaFuncionario'], $valores['areald']);
    $stmt->execute();

    // Si todo fue bien, commit a la transacción.
    $conex->commit();
    deliver_response(201, "Funcionario creado", NULL);
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