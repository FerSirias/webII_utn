<?php
    // Get data written in json format
	$data = file_get_contents("php://input");

    // Convert json data into data array
	$valores = json_decode($data,TRUE);

    // Prepare SQL insert instruction
	$auxSql = "call insescala('".$valores['num_tracking']."','".$valores['codigo_escala']."')";


	// Execute query over user table
	$regis = mysqli_query($conex, $auxSql);
	if(!$regis) {
        // Display error information in json format
		deliver_response(500,"Internal Server Error",mysqli_error($conex));
	}else{
		// Get data encomienda from server.
		$auxSql = "SELECT * FROM paquetes_db.escalas where num_tracking= '".$valores['num_tracking']."'";
		$regis = mysqli_query($conex, $auxSql);
		$esca = mysqli_fetch_array($regis);

		// Prepare data package like associative array
		$escala = array('num_tracking'=>$esca['num_tracking'],
						 'codigo_escala'=>$esca['codigo_escala'],
						 'fech_escala'=>$esca['fech_escala']);

		// Release memory from data query again
		mysqli_free_result($regis);

        //display user data in json format and http request proccess successfully
		deliver_response(201,"Datas was created",$escala);
	}
?>