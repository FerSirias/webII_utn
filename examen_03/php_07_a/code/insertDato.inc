<?php
session_start();

// Obtiene los datos del cuerpo de la solicitud POST
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true); // Convierte JSON a array

// Inicializa el array para almacenar los datos recibidos
$datosRecibidos = [];

// Verifica si se recibió algún dato y si es un array
if (is_array($input)) {
    // Almacena los valores recibidos en el array $datosRecibidos
    foreach ($input as $key => $value) {
        if (isset($input[$key])) {
            $datosRecibidos[$key] = $value;
        }
    }
}

// Asigna un valor a una variable de sesión
$_SESSION['datosRecibidos'] = $datosRecibidos;

// Cuenta el número de valores recibidos
$nValores = count($datosRecibidos);

// Crea la estructura de respuesta
$response = [
    "resultado" => [
        "datos" => [
            "nValores" => $nValores
        ]
    ],
    "status" => 201,
    "status_message" => "Data created"
];

// Utiliza la función para enviar la respuesta
deliver_response(201, "Data inserted", $response);
?>

