<?php
	//$allowed_domains = array('http://localhost:4200','http://domain2.com');
	$allowed_domains = 'http://localhost:4210';
	header("Access-Control-Allow-Origin:*");
	header("Access-Control-Allow-Headers:Content-Type");
	header("Access-Control-Allow-Methods:Get,Post");
	
	require_once($_SERVER["DOCUMENT_ROOT"]."/constants-maskotaweb.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/services/helper_service.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/controller/controller_user.php");
				
	validatePostParameters(array("usr","pwd"));	
	$user 		= sha1($_REQUEST["usr"]);
	$password 	= sha1($_REQUEST["pwd"]);
	$ctrl 		= new ControllerUser();
	$userOnSign =  $ctrl->signIn($user,$password);
	
	if($ctrl->MESSAGE_TRANSACTION == "OK")		
	{
		/*$myfile = fopen(VarConstantsMaskotaWeb::_FILE_USER_ACCOUNT, "w") or die("Unable to open file!");
		$txt 	= json_encode($userOnSign);
		fwrite($myfile, $txt);
		fclose($myfile);*/
		setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$userOnSign),"POST");
	}
	else{setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION),"POST");}


?>


