<?php
    // Get data written in json format
	$data = file_get_contents("php://input");

    // Convert json data into data array
	$valores = json_decode($data,TRUE);

    // Prepare SQL insert instruction
	$auxSql = "call insusuario('".$valores['num_tracking']."','".$valores['peso_paquete']."','".$valores['idUsuario_envia']."','".$valores['idUsuario_recibe']."','".$valores['fech_encomienda']."','".$valores['costo_reportado']."','".$valores['costo_MasEnvio']."','".$valores['idEmpleado']."','".$valores['status']."')";

	// Execute query over user table
	$regis = mysqli_query($conex, $auxSql);
	if(!$regis) {
        // Display error information in json format
		deliver_response(500,"Internal Server Error",mysqli_error($conex));
	}else{
		// Get data user from server.
		$auxSql = "Select * from encomiendas where num_tracking = '".$valores['num_tracking']."'";
		$regis = mysqli_query($conex, $auxSql);
		$enco = mysqli_fetch_array($regis);

		// Prepare data package like associative array
		$encomienda = array('num_tracking'=>$enco['num_tracking'],
						 'peso_paquete'=>$enco['peso_paquete'],
						 'idUsuario_envia'=>$enco['idUsuario_envia'],
						 'idUsuario_recibe'=>$enco['idUsuario_recibe'],
						 'fech_encomienda'=>$enco['fech_encomienda'],
						 'costo_reportado'=>$enco['costo_reportado'],
						 'costo_MasEnvio'=>$enco['costo_MasEnvio'],
						 'idEmpleado'=>$enco['idEmpleado'],
						 'status'=>$enco['status']);

		// Release memory from data query again
		mysqli_free_result($regis);

        //display user data in json format and http request proccess successfully
		deliver_response(201,"Datas was created",$encomienda);
	}
?>