<?php
	header("Content-Type:application/json");
	header("Accept:application/json");

	$method = $_SERVER['REQUEST_METHOD'];
	$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

	include_once('code/conexion.inc');
	switch ($method){
		case 'PUT':
			if(sizeof($request)==2){
				// para actualizar datos de una cita
				if($request[1]=='modificar'){
					include_once("code/updateCita.inc");
				}
			}else if(sizeof($request)==3){
				if($request[2]=='task'){
					// update task 
					include_once("code/modtarea.inc");
				}
			}
			break;
	  	case 'POST':
			if(sizeof($request)==1){
				if($request[0]=='estudiante'){
					// insertar datos de un estudiante
					include_once("code/insertEstudiante.inc");
				}else if($request[0]=='funcionario'){
					// insertar datos de un funcionario
					include_once("code/insertFuncionario.inc");
				}else if($request[0]=='solicitud'){
					// insertar datos de una solicitud
					include_once("code/insertSolicitud.inc");
				}else if($request[0]=='cita'){
					// insertar datos de una cita
					include_once("code/insertCita.inc");
				}else if($request[0]=='informe'){
					// insertar datos de un informe
					include_once("code/insertInforme.inc");
				}
			}
			break;
	  	case 'GET':
			if(sizeof($request)==1){
				if($request[0]=='solicitud'){
					// para consultar una solicitud específica
					include_once("code/selectSolicitud.inc");
				}else if($request[0]=='cita'){
					// para consultar una cita específica
					include_once("code/selectCita.inc");
				}else if($request[0]=='informe'){
					// para consultar un informe específico
					include_once("code/selectInforme.inc");
				}
			}else if(sizeof($request)==3){
				if($request[0]=='login'){
					// user authentication retrieves token
					include_once("code/login.inc");
				}
			}else if(sizeof($request)==2){
				if($request[1]=='me'){
					// user data query
					include_once("code/me.inc");
				}else if($request[1]=='task'){
					// task data query, using token user
					include_once("code/lsttarea.inc");
				}
			}else{
				deliver_response(204,"No Content","Your request is empty.");
			}
			break;
	   	case 'DELETE':
			if(sizeof($request)==1){
				if($request[0]=='informe'){
					// para borrar un informe de la BD
					include_once("code/deleteInforme.inc");
				}else if($request[0]=='cita'){
					// para borrar una cita de la BD
					include_once("code/deleteCita.inc");
				}else if($request[0]=='solicitud'){
					// para borrar una solicitud de la BD
					include_once("code/deleteSolicitud.inc");
				}
			}
			break;
	    default:
			deliver_response(405,"Method not allowed","");
			break;
	}// switch end

	/*----------------------------------------------------------------------*/
	/*Declarate http response functions or methods
	/*----------------------------------------------------------------------*/
	function deliver_response($status, $status_message,$data){
		header("HTTP/1.1 $status $status_message");

		$response["status"]=$status;
		$response["status_message"]=$status_message;
		$response["data"]=$data;
		$response["author"]="Fernanda Sirias, Admin";

		$json_response=json_encode($response);
		echo $json_response;
	}
?>