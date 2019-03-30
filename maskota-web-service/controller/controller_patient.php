<?php
	require_once($_SERVER["DOCUMENT_ROOT"]."/constants-maskotaweb.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/dao/dao_patient.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/entity/entity_patient.php");
		
	class ControllerPatient
	{
		public $MESSAGE_TRANSACTION     = "";
		
		//--------------------------------------------------------------------------------------
		// LIST PATIENTES
		//--------------------------------------------------------------------------------------
		public function getListPatients($pToken,$pId,$pKind,$pName)
		{
			$dao_entity = new DAOPatient();
			$data = $dao_entity->getListPatients($pToken,$pId,$pKind,$pName);
			if(count($data)>0){
				return $data;
			}			
		}
		//--------------------------------------------------------------------------------------
		// REGISTER NEW
		//--------------------------------------------------------------------------------------
		public function create($entity,$pToken)
		{
			$this->MESSAGE_TRANSACTION ="";
			$dao_entity = new DAOPatient();
			$result = $dao_entity->Create($entity,$pToken);
			$this->MESSAGE_TRANSACTION = $dao_entity->MESSAGE_TRANSACTION;
			return $result;
		}
		//-------------------------------------------------------------------------------------------
		// UPDATE REGISTER
		//-------------------------------------------------------------------------------------------
		public function update($entity,$pToken)
		{
			$this->MESSAGE_TRANSACTION ="";
			$dao_entity = new DAOPatient();
			$result   	= $dao_entity->Update($entity,$pToken);
			$this->MESSAGE_TRANSACTION = $dao_entity->MESSAGE_TRANSACTION;
            return $result;
		}
		//--------------------------------------------------------------------------------------
		// REGISTER NEW OWNERS
		//--------------------------------------------------------------------------------------
		public function createOwners($pToken,$pUser,$pUserDrop,$pPatient)
		{
			$this->MESSAGE_TRANSACTION ="";
			$dao_entity = new DAOPatient();
			$users = explode("||",$pUser);
			$userDropList = explode("||",$pUserDrop);
			for($i=0;$i<count($userDropList);$i++){
				if($userDropList[$i]!=""){
					$result = $dao_entity->DeleteOwner($pToken,$userDropList[$i],$pPatient);
				}
			}
			for($i=0;$i<count($users);$i++){
				if($users[$i]!=""){
					$result = $dao_entity->CreateOwner($pToken,$users[$i],$pPatient);
					if(!$result){break;}
				}
			}
			if($result){
				$this->MESSAGE_TRANSACTION = "Se realizo la transaccion correctamente";
			}
			else{ $this->MESSAGE_TRANSACTION = $dao_entity->MESSAGE_TRANSACTION;}
			return $result;
		}
		//--------------------------------------------------------------------------------------
		// LIST SIGNINC CLINICS
		//--------------------------------------------------------------------------------------
		public function getSignClinic($pToken,$pId,$pName)
		{
			$dao_entity = new DAOPatient();
			$data = $dao_entity->getSignClinic($pToken,$pId,$pName);
			if(count($data)>0){
				return $data;
			}			
		}		
		//--------------------------------------------------------------------------------------
		// LIST DIAGNOSTICS
		//--------------------------------------------------------------------------------------
		public function getDiagonostics($pToken,$pId,$pName)
		{
			$dao_entity = new DAOPatient();
			$data = $dao_entity->getDiagonostics($pToken,$pId,$pName);
			if(count($data)>0){
				return $data;
			}			
		}		
		//--------------------------------------------------------------------------------------
		// LIST TREATMENTS
		//--------------------------------------------------------------------------------------
		public function getTreatments($pToken,$pId,$pName,$pDiagnostic)
		{
			$dao_entity = new DAOPatient();
			$data = $dao_entity->getTreatments($pToken,$pId,$pName,$pDiagnostic);
			if(count($data)>0){
				return $data;
			}			
		}		
		//--------------------------------------------------------------------------------------
		// LIST VACCINES
		//--------------------------------------------------------------------------------------
		public function getVaccines($pToken,$pId,$pName)
		{
			$dao_entity = new DAOPatient();
			$data = $dao_entity->getVaccines($pToken,$pId,$pName);
			if(count($data)>0){
				return $data;
			}			
		}		
		
		//--------------------------------------------------------------------------------------
		// REGISTER NEW ATTENTION
		//--------------------------------------------------------------------------------------
		public function createAttention($entity,$pToken)
		{
			$this->MESSAGE_TRANSACTION ="";
			$dao_entity = new DAOPatient();
			$result = $dao_entity->CreateAttention($entity,$pToken);
			$this->MESSAGE_TRANSACTION = $dao_entity->MESSAGE_TRANSACTION;
			return $result;
		}
		//-------------------------------------------------------------------------------------------
		// UPDATE REGISTER ATTENTION
		//-------------------------------------------------------------------------------------------
		public function updateAttention($entity,$pToken)
		{
			$this->MESSAGE_TRANSACTION ="";
			$dao_entity = new DAOPatient();
			$result   	= $dao_entity->UpdateAttention($entity,$pToken);
			$this->MESSAGE_TRANSACTION = $dao_entity->MESSAGE_TRANSACTION;
            return $result;
		}
		//--------------------------------------------------------------------------------------
		// LIST ATTENTION
		//--------------------------------------------------------------------------------------
		public function getListAttention($pToken,$pIdHistory,$pIdPatient)
		{
			$dao_entity = new DAOPatient();
			$data = $dao_entity->getListAttention($pToken,$pIdHistory,$pIdPatient);
			if(count($data)>0){
				return $data;
			}			
		}		
		
		
	}

	
?>