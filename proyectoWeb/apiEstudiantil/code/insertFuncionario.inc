<?php
    // Get data written in json format
	$data = file_get_contents("php://input");

    // Convert json data into data array
	$valores = json_decode($data,TRUE);

    // Prepare SQL insert instruction
	$auxSql = "call insfunc(".$valores['funcionarioId'].",'".$valores['correoInstitucional']."','".$valores['password']."',".$valores['areaId'].",".$valores['rol_id'].")";


	// Execute query over user table
	$regis = mysqli_query($conex, $auxSql);
	if(!$regis) {
        // Display error information in json format
		deliver_response(500,"Internal Server Error",mysqli_error($conex));
	}else{
		// Get data encomienda from server.
		$enco = mysqli_fetch_array($regis);

		// Prepare data package like associative array
		$funcionario = array('funcionarioId'=>$enco['funcionarioId'],
						    'correoInstitucional'=>$enco['correoInstitucional'],
						    'password'=>$enco['password'],
						    'cedulaFuncionario'=>$enco['cedulaFuncionario'],
                            'areaId'=>$enco['areaId'],
                            'rol_id'=>$enco['rol_id']);

		// Release memory from data query again
		mysqli_free_result($regis);

        //display user data in json format and http request proccess successfully
		deliver_response(201,"Se insertó correctamente",$funcionario);
	}
?>