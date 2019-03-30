<?php
	require_once($_SERVER["DOCUMENT_ROOT"]."/constants-grifos-peruanos.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/dao/dao.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/entity/entity_patient.php");
			
	class DAOPatient 
	{
		const SP_INSERT					= "SP_VET_PAT_INS";
		const SP_UPDATE 				= "SP_VET_PAT_UPD";
		const SP_INSERT_ATTENTION		= "SP_VET_HIS_CLI_INS";
		const SP_UPDATE_ATTENTION		= "SP_VET_HIS_CLI_UPD";
		const SP_LIST_ATTENTION			= "SP_VET_HIS_CLI_LST";
		const SP_INSERT_OWNER_PATIENT	= "SP_VET_PAT_OWN_INS";
		const SP_DELETE_OWNER_PATIENT	= "SP_VET_PAT_OWN_DEL";
		const SP_LIST 					= "SP_VET_PAT_LST"	 ;
		const SP_LIST_CLINIC_SIGN		= "SP_CLIN_SIGN_LST";
		public $MESSAGE_TRANSACTION     = "";

		//------------------------------------------------------------------------------------
		// LIST PATIENTS
		//------------------------------------------------------------------------------------
		public function getListPatients($pToken,$pId,$pKind,$pName)
		{
			$dao 	= new DAO();
			$querie = "CALL ".DAOPatient::SP_LIST."(?,?,?,?)";
			$data 	=  $dao->getDataTable($querie,array($pToken,$pId,$pKind,$pName));
			$array 	= array();
			if($data!=null){
				for($j=0;$j<count($data);$j++)
				{  
					$data_ent = $data[$j];
					$entity = new EntityPatient();			
					$entity->idPatient				= $data_ent[0];
					$entity->kindPatient->idKind	= $data_ent[1];
					$entity->kindPatient->name		= $data_ent[2];
					$entity->names					= $data_ent[3];
					$entity->sex->idSex 	 		= $data_ent[4];
					switch($data_ent[4]){
						case 1:
							$entity->sex->name 	 			= "Macho";
						break;
						case 2:
							$entity->sex->name 	 			= "Hembra";
						break;
					}
					$entity->breed					= $data_ent[5];
					$entity->color					= $data_ent[6];
					$entity->dateBorn				= $data_ent[7];
					$entity->year					= $data_ent[8];
					$entity->month					= $data_ent[9];
					$array[$j] = $entity;
				}
			}
			return $array;
		}
		//---------------------------------------------------------------------------------------
		// INSERT
		//---------------------------------------------------------------------------------------
		public function Create($entity,$pToken)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOPatient::SP_INSERT."(?,?,?,?,?,?,?,?,?,@msg_db_transaccion)";
			$dao->prepareSP($querie);
			$dao->addParameter(1,$pToken);
			$dao->addParameter(2,$entity->kindPatient->idKind);
			$dao->addParameter(3,$entity->names);
			$dao->addParameter(4,$entity->sex);
			$dao->addParameter(5,$entity->breed);
			$dao->addParameter(6,$entity->color);
			$dao->addParameter(7,$entity->dateBorn);
			$dao->addParameter(8,$entity->year);
			$dao->addParameter(9,$entity->month);
			
			$result = $dao->execute();
			if($dao->_ERROR_COMAND!=""){$this->MESSAGE_TRANSACTION = $dao->formatMensajeError("Error, ");}
			else{
				$this->MESSAGE_TRANSACTION  = $dao->getFirstValue("select @msg_db_transaccion",null);
            }
			return $result;
		}
		//--------------------------------------------------------------------------------------------
		// UPDATE
		//-------------------------------------------------------------------------------------------
		public function Update($entity,$pToken)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOPatient::SP_UPDATE."(?,?,?,?,?,?,?,?,?,?,@msg_db_transaccion)";
			$dao->prepareSP($querie);
			$dao->addParameter(1,$pToken);
			$dao->addParameter(2,$entity->idPatient);
			$dao->addParameter(3,$entity->kindPatient->idKind);
			$dao->addParameter(4,$entity->names);
			$dao->addParameter(5,$entity->sex);
			$dao->addParameter(6,$entity->breed);
			$dao->addParameter(7,$entity->color);
			$dao->addParameter(8,$entity->dateBorn);
			$dao->addParameter(9,$entity->year);
			$dao->addParameter(10,$entity->month);
			$result = $dao->execute();
			if($dao->_ERROR_COMAND!=""){$this->MESSAGE_TRANSACTION = $dao->formatMensajeError("Error, ");}
			else{
				$this->MESSAGE_TRANSACTION  = $dao->getFirstValue("select @msg_db_transaccion",null);
            }
			return $result;
		}
		//---------------------------------------------------------------------------------------
		// INSERT OWNER
		//---------------------------------------------------------------------------------------
		public function CreateOwner($pToken,$pUser,$pPatient)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOPatient::SP_INSERT_OWNER_PATIENT."(?,?,?,@msg_db_transaccion)";
			$dao->prepareSP($querie);
			$dao->addParameter(1,$pToken);
			$dao->addParameter(2,$pUser);
			$dao->addParameter(3,$pPatient);
			$result = $dao->execute();
			if($dao->_ERROR_COMAND!=""){$this->MESSAGE_TRANSACTION = $dao->formatMensajeError("Error, ");}
			else{
				$this->MESSAGE_TRANSACTION  = $dao->getFirstValue("select @msg_db_transaccion",null);
            }
			return $result;
		}
		//---------------------------------------------------------------------------------------
		// DELETE OWNER BY PATIENT
		//---------------------------------------------------------------------------------------
		public function DeleteOwner($pToken,$pUser,$pPatient)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOPatient::SP_DELETE_OWNER_PATIENT."(?,?,?,@msg_db_transaccion)";
			$dao->prepareSP($querie);
			$dao->addParameter(1,$pToken);
			$dao->addParameter(2,$pUser);
			$dao->addParameter(3,$pPatient);
			$result = $dao->execute();
			if($dao->_ERROR_COMAND!=""){$this->MESSAGE_TRANSACTION = $dao->formatMensajeError("Error, ");}
			else{
				$this->MESSAGE_TRANSACTION  = $dao->getFirstValue("select @msg_db_transaccion",null);
            }
			return $result;
		}
		//----------------------------------------------------------------------------
		// SIGNINC CLINICS 
		//----------------------------------------------------------------------------
		public function getSignClinic($pToken,$pId,$pName)
		{
			$dao 	= new DAO();
			$querie = "CALL ".DAOPatient::SP_LIST_CLINIC_SIGN."(?,?,?,?,?)";
			$data 	=  $dao->getDataTable($querie,array($pToken,$pId,$pName,1,0));
			$array 	= array();
			if($data!=null){
				for($j=0;$j<count($data);$j++)
				{  
					$data_ent 					= $data[$j];
					$entity 					= new EntitySignClinic();			
					$entity->idClinicSign		= $data_ent[0];
					$entity->name				= utf8_encode($data_ent[1]);
					$array[$j] = $entity;
				}
			}
			return $array;
		}
		//----------------------------------------------------------------------------
		// DIAGNOSTICOS 
		//----------------------------------------------------------------------------
		public function getDiagonostics($pToken,$pId,$pName)
		{
			$dao 	= new DAO();
			$querie = "CALL ".DAOPatient::SP_LIST_CLINIC_SIGN."(?,?,?,?,?)";
			$data 	=  $dao->getDataTable($querie,array($pToken,$pId,$pName,2,0));
			$array 	= array();
			if($data!=null){
				for($j=0;$j<count($data);$j++)
				{  
					$data_ent 					= $data[$j];
					$entity 					= new EntitySignClinic();			
					$entity->idClinicSign		= $data_ent[0];
					$entity->name				= utf8_encode($data_ent[1]);
					$array[$j] = $entity;
				}
			}
			return $array;
		}
		//----------------------------------------------------------------------------
		// TREATMENTS
		//----------------------------------------------------------------------------
		public function getTreatments($pToken,$pId,$pName,$pDiagnostic)
		{
			$dao 	= new DAO();
			$querie = "CALL ".DAOPatient::SP_LIST_CLINIC_SIGN."(?,?,?,?,?)";
			$data 	=  $dao->getDataTable($querie,array($pToken,$pId,$pName,3,$pDiagnostic));
			$array 	= array();
			if($data!=null){
				for($j=0;$j<count($data);$j++)
				{  
					$data_ent 					= $data[$j];
					$entity 					= new EntitySignClinic();			
					$entity->idClinicSign		= $data_ent[0];
					$entity->name				= utf8_encode($data_ent[1]);
					$array[$j] = $entity;
				}
			}
			return $array;
		}
		//----------------------------------------------------------------------------
		// VACCINES 
		//----------------------------------------------------------------------------
		public function getVaccines($pToken,$pId,$pName)
		{
			$dao 	= new DAO();
			$querie = "CALL ".DAOPatient::SP_LIST_CLINIC_SIGN."(?,?,?,?,?)";
			$data 	=  $dao->getDataTable($querie,array($pToken,$pId,$pName,4,0));
			$array 	= array();
			if($data!=null){
				for($j=0;$j<count($data);$j++)
				{  
					$data_ent 					= $data[$j];
					$entity 					= new EntitySignClinic();			
					$entity->idClinicSign		= $data_ent[0];
					$entity->name				= utf8_encode($data_ent[1]);
					$array[$j] = $entity;
				}
			}
			return $array;
		}
		
		//---------------------------------------------------------------------------------------
		// INSERT ATTENTION
		//---------------------------------------------------------------------------------------
		public function CreateAttention($entity,$pToken)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOPatient::SP_INSERT_ATTENTION."(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,@msg_db_transaccion)";
			$dao->prepareSP($querie);
			$dao->addParameter(1,$pToken);
			$dao->addParameter(2,$entity->patient->idPatient);
			$dao->addParameter(3,$entity->idClinicSign);
			$dao->addParameter(4,$entity->medic->userId);
			$dao->addParameter(5,$entity->dateRegister);
			$dao->addParameter(6,$entity->stature);
			$dao->addParameter(7,$entity->weight);
			$dao->addParameter(8,$entity->temperature);
			$dao->addParameter(9,$entity->recommend);
			$dao->addParameter(10,$entity->diagnostic);
			$dao->addParameter(11,$entity->nextDate);
			$dao->addParameter(12,$entity->treatment);
			$dao->addParameter(13,$entity->vaccine);
			$dao->addParameter(14,$entity->chemotherapy);
			$dao->addParameter(15,$entity->payment);
			$dao->addParameter(16,$entity->vaccineCompleted);
			$dao->addParameter(17,$entity->desparacitado);
			$dao->addParameter(18,$entity->withOperation);
			$dao->addParameter(19,$entity->socialKnown);
			$dao->addParameter(20,$entity->itvePulgas);
			$dao->addParameter(21,$entity->itveGarrapata);
			$dao->addParameter(22,$entity->itveHongos);
			$dao->addParameter(23,$entity->itveOtitis);
			$dao->addParameter(24,$entity->itveBanioStandar);
			$dao->addParameter(25,$entity->itveBanioMedicado);
			$dao->addParameter(26,$entity->itveCorte);
			$dao->addParameter(27,$entity->itvePromoGratis);
			$result = $dao->execute();
			if($dao->_ERROR_COMAND!=""){$this->MESSAGE_TRANSACTION = $dao->formatMensajeError("Error, ");}
			else{
				$this->MESSAGE_TRANSACTION  = $dao->getFirstValue("select @msg_db_transaccion",null);
            }
			return $result;
		}
		//--------------------------------------------------------------------------------------------
		// UPDATE ATTENTION
		//-------------------------------------------------------------------------------------------
		public function UpdateAttention($entity,$pToken)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOPatient::SP_UPDATE_ATTENTION."(?,?,?,?,?,?,?,?,?,?,?,?,?,?,@msg_db_transaccion)";
			$dao->prepareSP($querie);
			$dao->addParameter(1,$pToken);
			$dao->addParameter(2,$idHistory);
			$dao->addParameter(3,$entity->patient->idPatient);
			$dao->addParameter(4,$entity->idClinicSign);
			$dao->addParameter(5,$entity->medic->userId);
			$dao->addParameter(6,$entity->dateRegister);
			$dao->addParameter(7,$entity->stature);
			$dao->addParameter(8,$entity->weight);
			$dao->addParameter(9,$entity->diagnostic);
			$dao->addParameter(10,$entity->nextDate);
			$dao->addParameter(11,$entity->treatment);
			$dao->addParameter(12,$entity->vaccine);
			$dao->addParameter(13,$entity->chemotherapy);
			$dao->addParameter(14,$entity->payment);
			$result = $dao->execute();
			if($dao->_ERROR_COMAND!=""){$this->MESSAGE_TRANSACTION = $dao->formatMensajeError("Error, ");}
			else{
				$this->MESSAGE_TRANSACTION  = $dao->getFirstValue("select @msg_db_transaccion",null);
            }
			return $result;
		}
		//------------------------------------------------------------------------------------
		// LIST ATTENTION
		//------------------------------------------------------------------------------------
		public function getListAttention($pToken,$pIdHistory,$pIdPatient)
		{
			$dao 	= new DAO();
			$querie = "CALL ".DAOPatient::SP_LIST_ATTENTION."(?,?,?)";
			$data 	=  $dao->getDataTable($querie,array($pToken,$pIdHistory,$pIdPatient));
			$array 	= array();
			if($data!=null){
				for($j=0;$j<count($data);$j++)
				{  
					$data_ent = $data[$j];
					$entity = new EntityHistoryClinic();			
					$entity->idHistory				= $data_ent[0];
					$entity->patient->idPatient		= $data_ent[1];
					$entity->patient->names			= utf8_encode($data_ent[2]);
					$entity->idClinicSign			= $data_ent[3];
					$entity->medic->userId			= $data_ent[4];
					$entity->medic->names  		    = utf8_encode($data_ent[5]);
					$entity->dateRegister 			= $data_ent[6];
					$entity->stature				= $data_ent[7];
					$entity->weight					= $data_ent[8];
					$entity->temperature			= $data_ent[9];
					$entity->recommend				= $data_ent[10];
					$entity->diagnostic				= utf8_encode($data_ent[11]);
					$entity->nextDate				= $data_ent[12];
					$entity->treatment				= utf8_encode($data_ent[13]);
					$entity->vaccine				= utf8_encode($data_ent[14]);
					$entity->chemotherapy			= utf8_encode($data_ent[15]);
					$entity->payment				= $data_ent[16];
					$entity->vaccineCompleted		= $data_ent[17];
					$entity->desparacitado			= $data_ent[18];
					$entity->withOperation			= $data_ent[19];
					$entity->itvePulgas				= $data_ent[20];
					$entity->itveGarrapata			= $data_ent[21];
					$entity->itveHongos				= $data_ent[22];
					$entity->itveOtitis				= $data_ent[23];
					$entity->itveBanioStandar		= $data_ent[24];
					$entity->itveBanioMedicado		= $data_ent[25];
					$entity->itveCorte				= $data_ent[26];
					$entity->itvePromoGratis		= $data_ent[27];
					$entity->socialKnown			= $data_ent[28];
					$array[$j] = $entity;
				}
			}
			return $array;
		}
	}
?>