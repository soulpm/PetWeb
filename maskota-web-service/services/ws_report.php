<?php
	header("Access-Control-Allow-Origin:*");
	header("Access-Control-Allow-Headers:Content-Type");
	header("Access-Control-Allow-Methods:Get,Post");
	
	
	require_once($_SERVER["DOCUMENT_ROOT"]."/constants-maskotaweb.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/services/helper_service.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/entity/entity_report.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/controller/controller_report.php");
			
	validatePostParameters(array("type"));
	$type  		= $_REQUEST["type"];
	switch($type)		
	{
		case "report_attention":
			validatePostParameters(array("token","patient","date_ini","date_end"));
			$token	                = $_REQUEST["token"];
            $patient	            = $_REQUEST["patient"];
            $dateInitial	        = $_REQUEST["date_ini"];
            $dateEnd		        = $_REQUEST["date_end"];
            $ctrl 			        = new ControllerReport();
            $data  		            = $ctrl->getAttentionReport($token,$patient,$dateInitial,$dateEnd);
			if(isset($data)){
				setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$data),"POST");
			}
			else{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,""),"POST");
			}
		break;
	}



?>


