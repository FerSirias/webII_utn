<?php
// Define la estructura de datos
$datos = [
    "datos" => [
        "estudiante" => [
            "apellidos" => "Sirias Monge",
            "celular" => 14782256,
            "email" => "fsirias@utn.ac.cr",
            "id" => 119720786,
            "nivel" => "Junior",
            "nombre" => "Fernanda Sirias"
        ]
    ],
    "status" => 200,
    "status_message" => "OK"
];
deliver_response(200,"OK",$datos);
?>

