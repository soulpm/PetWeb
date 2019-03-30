<?php
	header("Access-Control-Allow-Origin:*");
	header("Access-Control-Allow-Headers:Content-Type");
	header("Access-Control-Allow-Methods:Get,Post");
	
	require_once($_SERVER["DOCUMENT_ROOT"]."/constants-maskotaweb.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/services/helper_service.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/entity/entity_user.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/controller/controller_user.php");
			
	validatePostParameters(array("type"));
	$type  		= $_REQUEST["type"];
	switch($type)		
	{
		case "account":
			/*$myfile 		=   fopen(VarConstantsMaskotaWeb::_FILE_USER_ACCOUNT, "r") or die("Unable to open file!");
			$userAccount 	=   fread($myfile,filesize(VarConstantsMaskotaWeb::_FILE_USER_ACCOUNT));
			fclose($myfile);
			*/
			validatePostParameters(array("user_session"));
			$pToken			= $_REQUEST["user_session"];
			$ctrl 			= new ControllerUser();
			$userAccount 	= $ctrl->userAccount($pToken);			
			if(isset($userAccount)){
				setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$userAccount),"POST");
			}
			else{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,""),"POST");
			}
		break;
		case "list_role":
			validatePostParameters(array("token_session"));
			$pToken			= $_REQUEST["token_session"];
			try{
				$ctrl 		= new ControllerUser();
				$listRole  =  $ctrl->getListRol($pToken);
				setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$listRole),"POST");
			}
			catch(Exception $e)
			{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION." , ".$e->getMessage()),"POST");
			}
		break;
		case "list":
			validatePostParameters(array("token_session","id","name","role","type_doc","state"));
			$pToken			= $_REQUEST["token_session"];
			$pId 			= $_REQUEST["id"];
			$pName 			= $_REQUEST["name"];
			$pRole 			= $_REQUEST["role"];
			$pTypeDoc		= $_REQUEST["type_doc"];
			$pState			= $_REQUEST["state"];
			try{
				$ctrl 		= new ControllerUser();
				$listUsers  =  $ctrl->getListUsers($pToken,$pId,$pName,$pRole,$pTypeDoc,$pState);
				setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$listUsers),"POST");
			}
			catch(Exception $e)
			{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION." , ".$e->getMessage()),"POST");
			}
		break;
		case "list_medics":
			validatePostParameters(array("token_session","id","name"));
			$pToken			= $_REQUEST["token_session"];
			$pId 			= $_REQUEST["id"];
			$pName 			= $_REQUEST["name"];
			$pRole 			= 2;	//ROL MEDIC
			$pTypeDoc		= 0;
			$pState			= 1;
			try{
				$ctrl 		= new ControllerUser();
				$listUsers  =  $ctrl->getListUsers($pToken,$pId,$pName,$pRole,$pTypeDoc,$pState);
				setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$listUsers),"POST");
			}
			catch(Exception $e)
			{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION." , ".$e->getMessage()),"POST");
			}
		break;
		case "list_client":
			validatePostParameters(array("token_session","name","nif","patient"));
			$pToken			= $_REQUEST["token_session"];
			$pName 			= $_REQUEST["name"];
			$pNif 			= $_REQUEST["nif"];
			$pPatient		= $_REQUEST["patient"];
			try{
				$ctrl 		= new ControllerUser();
				$listUsers  =  $ctrl->getListClientPatientUsers($pToken,$pName,$pNif,$pPatient);
				setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$listUsers),"POST");
			}
			catch(Exception $e)
			{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION." , ".$e->getMessage()),"POST");
			}
		break;
		case "list_client_owner":
		validatePostParameters(array("token_session","patient"));
		$pToken			= $_REQUEST["token_session"];
		$pPatient		= $_REQUEST["patient"];
		try{
			$ctrl 		= new ControllerUser();
			$listUsers  =  $ctrl->getListClientPatientOwner($pToken,$pPatient);
			setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$listUsers),"POST");
		}
		catch(Exception $e)
		{
			setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION." , ".$e->getMessage()),"POST");
		}
		case "logout":
			validatePostParameters(array("user_session"));
			$pToken			= $_REQUEST["user_session"];
			$ctrl 			= new ControllerUser();
			$result 		= $ctrl->signOut($pToken);
			
			if(isset($result)){
				setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$result),"POST");
			}
			else{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,""),"POST");
			}
			/*
				$myfile = fopen(VarConstantsMaskotaWeb::_FILE_USER_ACCOUNT, "w") or die("Unable to open file!");
				$txt 	= "";
				fwrite($myfile, $txt);
				fclose($myfile);
			*/
		break;
		case "create":
			validatePostParameters(array("token_session","role","names","email","type_doc","nif","sex","address","movilNumber","landLine","image","w_owner"));
			$pToken			= $_REQUEST["token_session"];
			$pRole 			= $_REQUEST["role"];
			$pNames 		= $_REQUEST["names"];
			$pEmail 		= $_REQUEST["email"];
			$pTypeDoc 		= $_REQUEST["type_doc"];
			$pNif 			= $_REQUEST["nif"];
			$pSex 			= $_REQUEST["sex"];
			$pAddress		= $_REQUEST["address"];
			$pMovilNumber	= $_REQUEST["movilNumber"];
			$pLandLine		= $_REQUEST["landLine"];
			$pImage 		= $_REQUEST["image"];
			$pWOwner		= $_REQUEST["w_owner"];
			try{
				$ctrl 		= new ControllerUser();
				$usr		= new EntityUser();
				$usr->tokenSession			= $pToken;
				$usr->role->roleId 			= $pRole;
				$usr->names					= $pNames;
				$usr->email					= $pEmail;
				$usr->typeNif->idDocument 	= $pTypeDoc;
				$usr->nif					= $pNif;
				$usr->sex					= $pSex;
				$usr->address				= $pAddress;
				$usr->movilNumber			= $pMovilNumber;
				$usr->landLine				= $pLandLine;
				$result  	=  $ctrl->create($usr,$pImage);
				if($result != -1){
					//grabar imagen con nombre obtenido
					// $ctrl->IMAGE_CREATE_VALUE;
				}
				if($pWOwner!=null){
					if($pWOwner==1){
						$ctrl->assignPatientUserLastOwner();
					}
				}
				setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$ctrl->MESSAGE_TRANSACTION),"POST");
			}
			catch(Exception $e)
			{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION." , ".$e->getMessage()),"POST");
			}
		break;
		case "edit":
			validatePostParameters(array("token_session","id_user","role","names","email","type_doc","nif","sex","address","movilNumber","landLine","image","estate"));
			$pToken						= $_REQUEST["token_session"];
			$pIdUser					= $_REQUEST["id_user"];
			$pRole 						= $_REQUEST["role"];
			$pNames 					= $_REQUEST["names"];
			$pEmail 					= $_REQUEST["email"];
			$pTypeDoc 					= $_REQUEST["type_doc"];
			$pNif 						= $_REQUEST["nif"];
			$pSex 						= $_REQUEST["sex"];
			$pAddress					= $_REQUEST["address"];
			$pMovilNumber				= $_REQUEST["movilNumber"];
			$pLandLine					= $_REQUEST["landLine"];
			$pImage 					= $_REQUEST["image"];
			$pEstate 					= $_REQUEST["estate"];
			try{
				$ctrl 		= new ControllerUser();
				$usr		= new EntityUser();
				$usr->tokenSession			= $pToken;
				$usr->userId 				= $pIdUser;
				$usr->role->roleId 			= $pRole;
				$usr->names					= $pNames;
				$usr->email					= $pEmail;
				$usr->typeNif->idDocument 	= $pTypeDoc;
				$usr->nif					= $pNif;
				$usr->sex					= $pSex;
				$usr->address				= $pAddress;
				$usr->movilNumber			= $pMovilNumber;
				$usr->landLine				= $pLandLine;
				$usr->estate->idState		= $pEstate;
				$result  	=  $ctrl->update($usr,$pImage);
				if($result != -1){
					//grabar imagen con nombre obtenido
					// $ctrl->IMAGE_CREATE_VALUE;
				}
				setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$ctrl->MESSAGE_TRANSACTION),"POST");
			}
			catch(Exception $e)
			{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION." , ".$e->getMessage()),"POST");
			}
		break;
		case "change_credential":
		{
			validatePostParameters(array("id_user","password"));
			$pIdUser 	= $_REQUEST["id_user"];
			$pPassword 	= sha1(sha1($_REQUEST["password"]));
			try{
				$ctrl 		= new ControllerUser();
				$result  	=  $ctrl->updateCredential($pIdUser,$pPassword);
				setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$ctrl->MESSAGE_TRANSACTION),"POST");
			}
			catch(Exception $e)
			{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION." , ".$e->getMessage()),"POST");
			}
		}
		case "list_server_user":
			validatePostParameters(array("id_user"));
			$pUser 			= $_REQUEST["id_user"];
			if($pUser!=""){
				try{
					$ctrl 				= new ControllerUser();
					$listServerUser  	=  $ctrl->getServerUser($pUser);
					setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$listServerUser),"POST");
				}
				catch(Exception $e)
				{
					setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION." , ".$e->getMessage()),"POST");
				}
			}
			else{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_PARAMETERS_EXPECTED,"id_user"),"POST");
			}
		break;
		case "assign_server":
			validatePostParameters(array("id_user","list_server"));
			$pUser 			= $_REQUEST["id_user"];
			$pListServer	= $_REQUEST["list_server"];
			if($pUser!=""){
				if($pListServer!=""){
					try{
						$ctrl 				= new ControllerUser();
						$listServer 		= explode("||",$pListServer);
						$ctrl->deleteAssignServer($pUser);
						for($i=0;$i<count($listServer);$i++){
							$server = $listServer[$i];
							if($server!=""){
								$ctrl->assignServer($pUser,$server);
							}
						}
						setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$ctrl->MESSAGE_TRANSACTION),"POST");
					}
					catch(Exception $e)
					{
						setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION." , ".$e->getMessage()),"POST");
					}
				}
				else{
					setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_PARAMETERS_EXPECTED,"list_server"),"POST");	
				}
			}
			else{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_PARAMETERS_EXPECTED,"id_user"),"POST");
			}
		break;
		case "assign_user_child":
			validatePostParameters(array("id_user","list_user_child"));
			$pUser 			= $_REQUEST["id_user"];
			$pListUserChild	= $_REQUEST["list_user_child"];
			if($pUser!=""){
				if($pListUserChild!=""){
					try{
						$ctrl 				= new ControllerUser();
						$listUserAssign		= explode("||",$pListUserChild);
						$ctrl->deleteAssignUserChild($pUser);
						for($i=0;$i<count($listUserAssign);$i++){
							$userChild = $listUserAssign[$i];
							if($userChild!=""){
								$ctrl->assignUserChild($pUser,$userChild);
							}
						}
						setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$ctrl->MESSAGE_TRANSACTION),"POST");
					}
					catch(Exception $e)
					{
						setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION." , ".$e->getMessage()),"POST");
					}
				}
				else{
					setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_PARAMETERS_EXPECTED,"list_server"),"POST");	
				}
			}
			else{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_PARAMETERS_EXPECTED,"id_user"),"POST");
			}
		break;
		case "list_user_child":
			validatePostParameters(array("id_user"));
			$pUser 			= $_REQUEST["id_user"];
			if($pUser!=""){
				try{
					$ctrl 				= new ControllerUser();
					$lisetUsers		  	=  $ctrl->getListUsersChild($pUser);
					setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$lisetUsers),"POST");
				}
				catch(Exception $e)
				{
					setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION." , ".$e->getMessage()),"POST");
				}
			}
			else{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_PARAMETERS_EXPECTED,"id_user"),"POST");
			}
		break;

		
		
	}



?>


