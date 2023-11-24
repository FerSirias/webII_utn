<?php
	// Get data user from server.
	$auxSql = "select * from encomiendas where num_tracking = '".$request[0]."'";
	$regis = mysqli_query($conex, $auxSql);

	if(!$regis) {
		// Display error information in json format
		deliver_response(500,"Internal Server Error",mysqli_error($conex));
	}else{
        // Validate if user exist
		if(mysqli_num_rows($regis)==0){
            // Display error information about unregistered user
			deliver_response(401,"Unauthorized","Wrong user data.");
		}else{
			// Get data written in json format
            $data = file_get_contents("php://input");

            // Convert json data into data array
            $valores = json_decode($data,TRUE);

            // Prepare and execute SQL update instruction on task table
			$auxSql = "call updencomienda('".$request[0]."','".$valores['status']."')";
			$regis = mysqli_query($conex, $auxSql);

			if(!$regis) {
				// Display error information in json format
                deliver_response(500,"Internal Server Error",mysqli_error($conex));
			}else{
				// Get data task user from server and placed into sub structure.
                $auxSql = "select * from encomiendas where num_tracking = '".$request[0]."'";
                $regis = mysqli_query($conex, $auxSql);
				$enco = mysqli_fetch_array($regis);

                // Prepare data package like associative array
				$datos= array('num_tracking'=>$enco['num_tracking'],
						      'status'=>$enco['status']);

				// Release memory from data query
                mysqli_free_result($regis);

                // Display task data in json format and http request proccess successfully
				deliver_response(200,"OK",$datos);
			}
		}
	}
?>