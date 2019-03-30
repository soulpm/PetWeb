<?php
	header("Access-Control-Allow-Origin:*");
	header("Access-Control-Allow-Headers:Content-Type");
	header("Access-Control-Allow-Methods:Get,Post");
	
	require_once($_SERVER["DOCUMENT_ROOT"]."/constants-maskotaweb.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/services/helper_service.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/entity/entity_patient.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/controller/controller_patient.php");
			
	validatePostParameters(array("type"));
	$type  		= $_REQUEST["type"];
	switch($type)		
	{
		case "list":
			validatePostParameters(array("token_session","id_patient","id_kind","name"));
			$pToken			= $_REQUEST["token_session"];
			$pId 			= $_REQUEST["id_patient"];
			$pKind 			= $_REQUEST["id_kind"];
			$pName 			= $_REQUEST["name"];
			try{
				$ctrl 			= 	new ControllerPatient();
				$listPatient  	= 	$ctrl->getListPatients($pToken,$pId,$pKind,$pName);
				setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$listPatient),"POST");
			}
			catch(Exception $e)
			{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION." , ".$e->getMessage()),"POST");
			}
		break;
		case "create":
			validatePostParameters(array("token_session","kind","names","sex","breed","color","date_born","year","month"));
			$pToken							= $_REQUEST["token_session"];
			$entity						 	= new EntityPatient();
			$entity->kindPatient->idKind 	= $_REQUEST["kind"];
			$entity->names 					= $_REQUEST["names"];
			$entity->sex 					= $_REQUEST["sex"];
			$entity->breed 					= $_REQUEST["breed"];
			$entity->color 					= $_REQUEST["color"];
			$entity->dateBorn 				= $_REQUEST["date_born"];
			$entity->year 					= $_REQUEST["year"];
			$entity->month 					= $_REQUEST["month"];
			if($entity->dateBorn==""){$entity->dateBorn = null;}
			try{
				$ctrl 		= new ControllerPatient();
				$result  	=  $ctrl->create($entity,$pToken);
				setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$ctrl->MESSAGE_TRANSACTION),"POST");
			}
			catch(Exception $e)
			{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION." , ".$e->getMessage()),"POST");
			}
		break;
		case "edit":
			validatePostParameters(array("token_session","id_patient","kind","names","sex","breed","color","date_born","year","month"));
			$pToken							= $_REQUEST["token_session"];
			$entity						 	= new EntityPatient();
			$entity->idPatient 				= $_REQUEST["id_patient"];
			$entity->kindPatient->idKind 	= $_REQUEST["kind"];
			$entity->names 					= $_REQUEST["names"];
			$entity->sex 					= $_REQUEST["sex"];
			$entity->breed 					= $_REQUEST["breed"];
			$entity->color 					= $_REQUEST["color"];
			$entity->dateBorn 				= $_REQUEST["date_born"];
			$entity->year 					= $_REQUEST["year"];
			$entity->month 					= $_REQUEST["month"];
			try{	
				$ctrl 		= new ControllerPatient();
				$result  	=  $ctrl->update($entity,$pToken);
				setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$ctrl->MESSAGE_TRANSACTION),"POST");
			}
			catch(Exception $e)
			{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION." , ".$e->getMessage()),"POST");
			}
		break;
		case "create_owners":
			validatePostParameters(array("token_session","users","patient"));
			$pToken							= $_REQUEST["token_session"];
			$pUser 							= $_REQUEST["users"];
			$pUserDrop						= $_REQUEST["user_drop"];
			$pPatient 						= $_REQUEST["patient"];
			try{
				$ctrl 		= new ControllerPatient();
				$result  	=  $ctrl->createOwners($pToken,$pUser,$pUserDrop,$pPatient);
				setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$ctrl->MESSAGE_TRANSACTION),"POST");
			}
			catch(Exception $e)
			{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION." , ".$e->getMessage()),"POST");
			}
		break;
		case "list_sign_clinic":
			validatePostParameters(array("token_session","id","name"));
			$pToken			= $_REQUEST["token_session"];
			$pId 			= $_REQUEST["id"];
			$pName 			= $_REQUEST["name"];
			try{
				$ctrl 			= 	new ControllerPatient();
				$list 			= 	$ctrl->getSignClinic($pToken,$pId,$pName);
				setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$list),"POST");
			}
			catch(Exception $e)
			{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION." , ".$e->getMessage()),"POST");
			}
		break;
		case "list_diagnostic":
			validatePostParameters(array("token_session","id","name"));
			$pToken			= $_REQUEST["token_session"];
			$pId 			= $_REQUEST["id"];
			$pName 			= $_REQUEST["name"];
			try{
				$ctrl 			= 	new ControllerPatient();
				$list 			= 	$ctrl->getDiagonostics($pToken,$pId,$pName);
				setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$list),"POST");
			}
			catch(Exception $e)
			{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION." , ".$e->getMessage()),"POST");
			}
		break;
		case "list_vaccine":
			validatePostParameters(array("token_session","id","name"));
			$pToken			= $_REQUEST["token_session"];
			$pId 			= $_REQUEST["id"];
			$pName 			= $_REQUEST["name"];
			try{
				$ctrl 			= 	new ControllerPatient();
				$list 			= 	$ctrl->getVaccines($pToken,$pId,$pName);
				setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$list),"POST");
			}
			catch(Exception $e)
			{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION." , ".$e->getMessage()),"POST");
			}
		break;
		case "list_treatments":
			validatePostParameters(array("token_session","id","name","diagnostic"));
			$pToken			= $_REQUEST["token_session"];
			$pId 			= $_REQUEST["id"];
			$pName 			= $_REQUEST["name"];
			$pDiagnostic 	= $_REQUEST["diagnostic"];
			
			try{
				$ctrl 			= 	new ControllerPatient();
				$list 			= 	$ctrl->getTreatments($pToken,$pId,$pName,$pDiagnostic);
				setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$list),"POST");
			}
			catch(Exception $e)
			{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION." , ".$e->getMessage()),"POST");
			}
		break;
		case "create_attention":
			validatePostParameters(array("token_session",
			"patient",
			"sign_clinic",
			"medic",
			"date_register",
			"stature",
			"weight",
			"temperature",
			"recommend",
			"diagnostic",
			"next_date",
			"treatment",
			"vaccine",
			"chemotherapy",
			"payment",
			"vaccine_completed",
			"desparacitado",
			"with_operation",
			"social_known",
			"itve_pulgas",
			"itve_garrapata",
			"itve_hongos",
			"itve_otitis",
			"itve_banio_std",
			"itve_banio_med",
			"itve_corte",
			"itve_promo_success"
			));
			$entity						 	= new EntityHistoryClinic();
			$pToken							= $_REQUEST["token_session"];
			$entity->patient->idPatient		= $_REQUEST["patient"];
			$entity->idClinicSign 			= $_REQUEST["sign_clinic"];
			$entity->medic->userId			= $_REQUEST["medic"];
			$entity->dateRegister			= $_REQUEST["date_register"];
			$entity->stature 				= $_REQUEST["stature"];
			$entity->weight 				= $_REQUEST["weight"];
			$entity->temperature			= $_REQUEST["temperature"];
			$entity->recommend 				= $_REQUEST["recommend"];
			$entity->diagnostic 			= $_REQUEST["diagnostic"];
			$entity->nextDate 				= $_REQUEST["next_date"];
			$entity->treatment 				= $_REQUEST["treatment"];
			$entity->vaccine 				= $_REQUEST["vaccine"];
			$entity->chemotherapy 			= $_REQUEST["chemotherapy"];
			$entity->payment 				= $_REQUEST["payment"];
			$entity->vaccineCompleted		= $_REQUEST["vaccine_completed"];
			$entity->desparacitado			= $_REQUEST["desparacitado"];
			$entity->withOperation			= $_REQUEST["with_operation"];
			$entity->socialKnown			= $_REQUEST["social_known"];
			$entity->itvePulgas 			= $_REQUEST["itve_pulgas"];
			$entity->itveGarrapata			= $_REQUEST["itve_garrapata"];
			$entity->itveHongos 			= $_REQUEST["itve_hongos"];
			$entity->itveOtitis 			= $_REQUEST["itve_otitis"];
			$entity->itveBanioStandar		= $_REQUEST["itve_banio_std"];
			$entity->itveBanioMedicado		= $_REQUEST["itve_banio_med"];
			$entity->itveCorte 				= $_REQUEST["itve_corte"];
			$entity->itvePromoGratis		= $_REQUEST["itve_promo_success"];
			
			try{
				$ctrl 		= new ControllerPatient();
				$result  	=  $ctrl->createAttention($entity,$pToken);
				setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$ctrl->MESSAGE_TRANSACTION),"POST");
			}
			catch(Exception $e)
			{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION." , ".$e->getMessage()),"POST");
			}
		break;
		case "edit_attention":
			validatePostParameters(array("token_session",
			"id_history",
			"patient",
			"sign_clinic",
			"medic",
			"date_register",
			"stature",
			"weight",
			"diagnostic",
			"next_date",
			"treatment",
			"vaccine",
			"chemotherapy",
			"payment"));
			$entity						 	= new EntityHistoryClinic();
			$pToken							= $_REQUEST["token_session"];
			$entity->idHistory 				= $_REQUEST["id_history"];
			$entity->patient->idPatient		= $_REQUEST["patient"];
			$entity->idClinicSign 			= $_REQUEST["sign_clinic"];
			$entity->medic->userId			= $_REQUEST["medic"];
			$entity->dateRegister			= $_REQUEST["date_register"];
			$entity->stature 				= $_REQUEST["stature"];
			$entity->weight 				= $_REQUEST["weight"];
			$entity->diagnostic 			= $_REQUEST["diagnostic"];
			$entity->nextDate 				= $_REQUEST["next_date"];
			$entity->treatment 				= $_REQUEST["treatment"];
			$entity->vaccine 				= $_REQUEST["vaccine"];
			$entity->chemotherapy 			= $_REQUEST["chemotherapy"];
			$entity->payment 				= $_REQUEST["payment"];
			try{
				$ctrl 		= new ControllerPatient();
				$result  	=  $ctrl->updateAttention($entity,$pToken);
				setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$ctrl->MESSAGE_TRANSACTION),"POST");
			}
			catch(Exception $e)
			{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION." , ".$e->getMessage()),"POST");
			}
		break;
		case "list_attention":
			validatePostParameters(array("token_session","id_history","id_patient"));
			$pToken			= $_REQUEST["token_session"];
			$pIdHistory		= $_REQUEST["id_history"];
			$pIdPatient		= $_REQUEST["id_patient"];
			try{
				$ctrl 			= 	new ControllerPatient();
				$listAttention	= 	$ctrl->getListAttention($pToken,$pIdHistory,$pIdPatient);
				//print_r($listAttention);
				setResponse(formatResponse(VarConstantsMaskotaWeb::_CODE_TRANSACTION_OK,$listAttention),"POST");
			}
			catch(Exception $e)
			{
				setResponse(formatResponse(VarConstantsMaskotaWeb::_ERROR_VALIDATE_DATA_DB,$ctrl->MESSAGE_TRANSACTION." , ".$e->getMessage()),"POST");
			}
		break;
		
	}
?>


