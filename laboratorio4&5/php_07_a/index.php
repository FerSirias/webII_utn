<?php
	header("Content-Type:application/json");
	header("Accept:application/json");

	$method = $_SERVER['REQUEST_METHOD'];
	$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

	include_once('code/conexion.inc');
	switch ($method){
		case 'PUT':
			if(sizeof($request)==2){
				// update encomienda
				if($request[1]=='update'){
					include_once("code/updEnco.inc");
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
				if($request[0]=='register'){
					// registrar una nueva encomienda
					include_once("code/registerEnco.inc");
				}
			}else if(sizeof($request)==2){
				if($request[1]=='add'){
					// registrar una nueva escala
					include_once("code/registerEsca.inc");
				}
			}else if(sizeof($request)==3){
				if($request[2]=='person'){
					// registrar una nueva escala
					include_once("code/registerPer.inc");
				}
			}
			break;
	  	case 'GET':
			if(sizeof($request)==3){
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
			if(sizeof($request)==2){
				if($request[1]=='task'){
					// remove task from todo list
					include_once("code/deltarea.inc");
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
		$response["author"]="fer";

		$json_response=json_encode($response);
		echo $json_response;
	}
?>









