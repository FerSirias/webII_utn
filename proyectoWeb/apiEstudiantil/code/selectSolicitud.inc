<?php
    // Get data written in json format
	$data = file_get_contents("php://input");

    // Convert json data into data array
	$valores = json_decode($data,TRUE);

    // Prepare SQL insert instruction
	$auxSql = "call selectsoli(".$valores['solicitud_id'].")";


	// Execute query over user table
	$regis = mysqli_query($conex, $auxSql);
	if(!$regis) {
        // Display error information in json format
		deliver_response(500,"Internal Server Error",mysqli_error($conex));
	}else{
		// Get data encomienda from server.
		$enco = mysqli_fetch_array($regis);

		// Prepare data package like associative array
		$soli = array('solicitud_id'=>$enco['solicitud_id'],
					  'estudiante_id'=>$enco['estudiante_id'],
					  'motivo'=>$enco['motivo'],
					  'fechaSolicitud'=>$enco['fechaSolicitud'],
                      'areaAtencion'=>$enco['areaAtencion']);

		// Release memory from data query again
		mysqli_free_result($regis);

        //display user data in json format and http request proccess successfully
		deliver_response(201,"Datos cargados correctamente",$soli);
	}
?>