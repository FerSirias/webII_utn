<?php
    // Get data written in json format
	$data = file_get_contents("php://input");

    // Convert json data into data array
	$valores = json_decode($data,TRUE);

    // Prepare SQL insert instruction
	$auxSql = "call inspersona('".$valores['cedula']."','".$valores['nombre']."','".$valores['apellidos']."','".$valores['direccion']."','".$valores['email']."','".$valores['password']."',".$valores['idRol'].")";


	// Execute query over user table
	$regis = mysqli_query($conex, $auxSql);
	if(!$regis) {
        // Display error information in json format
		deliver_response(500,"Internal Server Error",mysqli_error($conex));
	}else{
		// Get data encomienda from server.
		$auxSql = "SELECT * FROM paquetes_db.personas where cedula= '".$valores['cedula']."' order by idPersona desc limit 1";
		$regis = mysqli_query($conex, $auxSql);
		$enco = mysqli_fetch_array($regis);

		// Prepare data package like associative array
		$persona = array('idPersona'=>$enco['idPersona'],
						 'cedula'=>$enco['cedula'],
						 'nombre'=>$enco['nombre'],
						 'apellidos'=>$enco['apellidos'],
						 'direccion'=>$enco['direccion']);

		// Release memory from data query again
		mysqli_free_result($regis);

        //display user data in json format and http request proccess successfully
		deliver_response(201,"Datas was created",$persona);
	}
?>