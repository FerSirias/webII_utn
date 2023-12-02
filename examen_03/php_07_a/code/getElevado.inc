<?php
session_start();

//$_SESSION['datosRecibidos'];

// Filtra y reindexa para obtener solo los valores numéricos

$valoresNumericos= [];
$valoresElevados= [];
foreach ( $_SESSION['datosRecibidos'] as $tipo => $valor ) {
            array_push($valoresNumericos,$valor);
            array_push($valoresElevados , $valor * $valor);
            };


// Crea la estructura de respuesta con los valores originales y elevados
$response = [
    "resultado" => [
        "d_elevados" => $valoresElevados, // Valores elevados al cuadrado
        "d_originales" => $_SESSION['datosRecibidos'] // Valores originales
    ],
    "status" => 200,
    "status_message" => "OK"
];
// Utiliza la función para enviar la respuesta
deliver_response(201, "Data inserted", $response);
?>