<?php
session_start();

//$_SESSION['datosRecibidos'];

// Filtra y reindexa para obtener solo los valores numéricos

$valoresNumericos= [];
foreach ( $_SESSION['datosRecibidos'] as $tipo => $valor ) {
            array_push($valoresNumericos,$valor);
            };

// Cuenta el número de valores numéricos y calcula las estadísticas
$nValores = count($valoresNumericos);
$suma = array_sum($valoresNumericos);
$mayor = $nValores > 0 ? max($valoresNumericos) : null;
$menor = $nValores > 0 ? min($valoresNumericos) : null;
$promedio = $nValores > 0 ? $suma / $nValores : null;

// Crea la estructura de respuesta con los datos y estadísticas
$response = [
    "resultado" => [
        "datos" => $_SESSION['datosRecibidos'],
        "estadisticas" => [
            "mayor" => $mayor,
            "menor" => $menor,
            "nValores" => $nValores,
            "promedio" => $promedio,
            "suma" => $suma
        ]
    ],
    "status" => 200,
    "status_message" => "OK"
];

// Utiliza la función para enviar la respuesta
deliver_response(201, "Data inserted", $response);
?>
