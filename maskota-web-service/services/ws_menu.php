<?php
	header("Access-Control-Allow-Origin:*");
	header("Access-Control-Allow-Headers:Content-Type");
	header("Access-Control-Allow-Methods:Get,Post");
	require_once($_SERVER["DOCUMENT_ROOT"]."/constants-maskotaweb.php");
	//require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/services/ws_basic_autenticate.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/services/helper_service.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/controller/controller_menu.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/entity/entity_exception.php");
	
	validatePostParameters(array("role"));
	$role  		= $_REQUEST["role"];
	try{	
		$ctrl 		= new ControllerMenu();
		$listMenu 	=  $ctrl->listMenu($role);
		setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$listMenu),"POST");
	}
	catch(Exception $e)
	{
		setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION." , ".$e->getMessage()),"POST");
	}
	

?>