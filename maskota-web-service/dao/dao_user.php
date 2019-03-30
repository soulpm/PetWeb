<?php
	require_once($_SERVER["DOCUMENT_ROOT"]."/constants-grifos-peruanos.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/dao/dao.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/entity/entity_user.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/entity/entity_document.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/entity/entity_state.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/entity/entity_role.php");
			
	class DAOUser 
	{
		const SP_LOGIN 					= "SP_USR_LOGIN";
		const SP_LOGOUT 				= "SP_USR_LOGOUT";
		const SP_ACCOUNT_USER			= "SP_USR_ACCOUNT";
		const SP_INSERT					= "SP_USR_INS";
		const SP_UPDATE 				= "SP_USR_UPD";
		const SP_ASG_LAST_OWNER			= "SP_USR_PAT_LAST_OWNER";
		const SP_LIST 					= "SP_USR_LST"	 ;
		const SP_LIST_CLIENT_PAT		= "SP_USR_LST_CLI_PAT"	 ;
		const SP_LIST_CLIENT_OWN		= "SP_USR_LST_CLI_PAT_OWN";
		const SP_LIST_ROL				= "SP_USR_ROL";
		const SP_LOGIN_APP 				= "SP_LOGIN_USR_APP";
		const SP_SERVER_USER 			= "SP_USR_LST_SRV";
		const SP_INSERT_SERVER_USER		= "SP_USR_INS_SRV";
		const SP_DELETE_SERVER_USER		= "SP_USR_DEL_SRV";
		const SP_INSERT_USER_CHILD		= "SP_USR_INS_USR_CHILD";
		const SP_DELETE_USER_CHILD		= "SP_USR_DEL_USR_CHILD";
		const SP_LIST_USER_CHILD		= "SP_USR_LST_USR_CHILD";
		const SP_DELETE 				= "SP_DEL_USR";
		const SP_LIST_CLI 				= "SP_LST_USR_BY_CLI";
		const SP_USR_MED_LST			= "SP_USR_MED_LST";
		const SP_CRED_USR 				= "SP_CREDENCIAL_USR";
		const SP_UPD_CREDENTIAL 		= "SP_USR_UPD_PWD";
		const SP_STATE 					= "SP_STD_USR";
		public $IMAGE_CREATE_VALUE      = "";
        public $MESSAGE_TRANSACTION     = "";
		
		//---------------------------------------------------------------------------------
		// LOGIN USER
		//---------------------------------------------------------------------------------
		public function signIn($usr,$pwd)
		{
			$dao = new Dao();
			$querie = "CALL ".DAOUser::SP_LOGIN."(?,?,@msg_db_transaccion)";
			$dao->prepareSP($querie);
			$dao->addParameter(1,$usr);
			$dao->addParameter(2,$pwd);
			/*$dao->addParameter(3,VarConstantsMaskotaWeb::DEVICE_PC);
			$dao->addParameter(4,$dao->getRealIP());*/
			if($dao->_ERROR_COMAND==""){	
				$dato = $dao->getDataTableSP();
				$this->MESSAGE_TRANSACTION  = $dao->getFirstValue("select @msg_db_transaccion",null);
				if(strpos($this->MESSAGE_TRANSACTION,'Error')=== false)
				{
					$data_ent = $dato[0];
					return $data_ent[0];
				}
			}
			else { $this->MESSAGE_TRANSACTION = VarConstantsMaskotaWeb::_ERROR_DB_TRANSACTION; }
		}
		//-------------------------------------------------------------------------------------- 
		// LOGOUT USER 
		//--------------------------------------------------------------------------------------
		public function signOut($ptoken)
		{
			$dao = new Dao();
			$querie = "CALL ".DAOUser::SP_LOGOUT."(?)";
			$dao->prepareSP($querie);
			$dao->addParameter(1,$ptoken);
			if($dao->_ERROR_COMAND==""){	
				$result = $dao->execute();
				return $result;
			}
			else { $this->MESSAGE_TRANSACTION = 
			   $dao->formatMensajeError("Error, no se efectuo la operacion con la Base Datos");
			}
		}
		//---------------------------------------------------------------------------------
		// USER ACCOUNT DATA
		//---------------------------------------------------------------------------------
		public function userAccount($token)
		{
			$dao = new Dao();
			$querie = "CALL ".DAOUser::SP_ACCOUNT_USER."(?)";
			$dao->prepareSP($querie);
			$dao->addParameter(1,$token);
			if($dao->_ERROR_COMAND==""){	
				$dato = $dao->getDataTableSP();
				$entity = null;
				if($dato!=null){
					$data_ent = $dato[0];
					$entity = new EntityUser();			
					$entity->tokenSession		= $data_ent[0];
					$entity->role->idRole		= $data_ent[1];
					$entity->role->name			= utf8_encode($data_ent[2]);
					$entity->names 	 			= utf8_encode($data_ent[3]);
					$entity->email	 			= $data_ent[4];
					$entity->typeNif 			= $data_ent[5];
					$entity->nif 				= $data_ent[6];
					$entity->photo 				= $data_ent[7];
				}
				return $entity;
			}
			else { $this->MESSAGE_TRANSACTION = VarConstantsMaskotaWeb::_ERROR_DB_TRANSACTION; }
		}
		//------------------------------------------------------------------------------------
		// LIST ROL
		//------------------------------------------------------------------------------------
		public function getListRol($pToken)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOUser::SP_LIST_ROL."(?)";
			$data =  $dao->getDataTable($querie,array($pToken));
			$array_rol = array();
			for($j=0;$j<count($data);$j++)
			{  
				$data_ent = $data[$j];
				$entity = new EntityRole();			
				$entity->idRole					= $data_ent[0];
				$entity->name					= utf8_encode($data_ent[1]);
				$array_rol[$j] = $entity;
			}
			return $array_rol;
		}
		//------------------------------------------------------------------------------------
		// LIST USERS
		//------------------------------------------------------------------------------------
		public function getListUsers($pToken,$pId,$pName,$pRole,$pTypeDoc,$pState)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOUser::SP_LIST."(?,?,?,?,?,?)";
			$data =  $dao->getDataTable($querie,array($pToken,$pId,$pName,$pRole,$pTypeDoc,$pState));
			$array_users = array();
			if($data!=null){
				for($j=0;$j<count($data);$j++)
				{  
					$data_ent = $data[$j];
					$entity = new EntityUser();			
					$entity->userId					= $data_ent[0];
					$entity->role 					= new EntityRole();
					$entity->role->idRole			= $data_ent[1];
					$entity->role->name				= utf8_encode($data_ent[2]);
					$entity->typeNif		 	 	= new EntityDocument;
					$entity->typeNif->idDocument 	= $data_ent[3];
					$entity->typeNif->name 			= $data_ent[4];
					$entity->numberNif				= $data_ent[5];
					$entity->names  				= utf8_encode($data_ent[6]);
					$entity->email 	 				= $data_ent[7];
					$entity->estate					= new EntityState;
					$entity->estate->idState		= $data_ent[8];
					$entity->estate->name			= $data_ent[9];
					$entity->sex 	 				= $data_ent[10];
					$entity->address 				= $data_ent[11];
					$entity->movilNumber			= $data_ent[12];
					$entity->landLine 				= $data_ent[13];
					$entity->photo					= $data_ent[10];
					$array_users[$j] = $entity;
				}
			}
			return $array_users;
		}
		//------------------------------------------------------------------------------------
		// LIST USERS CLIENTS
		//------------------------------------------------------------------------------------
		public function getListClientPatientUsers($pToken,$pName,$pNif,$pPatient)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOUser::SP_LIST_CLIENT_PAT."(?,?,?,?)";
			$data =  $dao->getDataTable($querie,array($pToken,$pName,$pNif,$pPatient));
			$array_users = array();
			if($data!=null){
				for($j=0;$j<count($data);$j++)
				{  
					$data_ent = $data[$j];
					$entity = new EntityUserClientPatient();			
					$entity->userId					= $data_ent[0];
					$entity->typeNif		 	 	= new EntityDocument;
					$entity->typeNif->idDocument 	= $data_ent[1];
					$entity->typeNif->name 			= $data_ent[2];
					$entity->numberNif				= $data_ent[3];
					$entity->names  				= utf8_encode($data_ent[4]);
					$entity->email 	 				= $data_ent[5];
					$entity->isOwner 				= $data_ent[6];
					$entity->sex 	 				= $data_ent[7];
					$entity->address 				= $data_ent[8];
					$entity->movilNumber			= $data_ent[9];
					$entity->landLine 				= $data_ent[10];
					$entity->photo					= $data_ent[11];
					$array_users[$j] = $entity;
				}
			}
			return $array_users;
		}
		//------------------------------------------------------------------------------------
		// LIST USERS CLIENTS OWNERS
		//------------------------------------------------------------------------------------
		public function getListClientPatientOwner($pToken,$pPatient)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOUser::SP_LIST_CLIENT_OWN."(?,?)";
			$data =  $dao->getDataTable($querie,array($pToken,$pPatient));
			$array_users = array();
			if($data!=null){
				for($j=0;$j<count($data);$j++)
				{  
					$data_ent = $data[$j];
					$entity = new EntityUserClientPatient();			
					$entity->userId					= $data_ent[0];
					$entity->typeNif		 	 	= new EntityDocument;
					$entity->typeNif->idDocument 	= $data_ent[1];
					$entity->typeNif->name 			= $data_ent[2];
					$entity->numberNif				= $data_ent[3];
					$entity->names  				= utf8_encode($data_ent[4]);
					$entity->email 	 				= $data_ent[5];
					$entity->isOwner 				= $data_ent[6];
					$entity->sex 	 				= $data_ent[7];
					$entity->address 				= $data_ent[8];
					$entity->movilNumber			= $data_ent[9];
					$entity->landLine 				= $data_ent[10];
					$entity->photo					= $data_ent[11];
					$array_users[$j] = $entity;
				}
			}
			return $array_users;
		}
		//---------------------------------------------------------------------------------------
		// INSERT USERS
		//---------------------------------------------------------------------------------------
		public function Create($usr,$img)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOUser::SP_INSERT."(?,?,?,?,?,?,?,?,?,?,?,@img_db_name,@msg_db_transaccion)";
			$dao->prepareSP($querie);
			$dao->addParameter(1,$usr->tokenSession);
			$dao->addParameter(2,$usr->role->roleId);
			$dao->addParameter(3,$usr->names);
			$dao->addParameter(4,$usr->email);
			$dao->addParameter(5,$usr->typeNif->idDocument);
			$dao->addParameter(6,$usr->nif);
			$dao->addParameter(7,$usr->sex);
			$dao->addParameter(8,$usr->address);
			$dao->addParameter(9,$usr->movilNumber);
			$dao->addParameter(10,$usr->landLine);
			$dao->addParameter(11,$img);
			$result = $dao->execute();
			if($dao->_ERROR_COMAND!=""){$this->MESSAGE_TRANSACTION = $dao->formatMensajeError("Error, ");}
			else{
				$this->IMAGE_CREATE_VALUE   = $dao->getFirstValue("select @img_db_name",null);
				$this->MESSAGE_TRANSACTION  = $dao->getFirstValue("select @msg_db_transaccion",null);
            }
			return $result;
		}
		//--------------------------------------------------------------------------------------------
		// UPDATE USERS
		//-------------------------------------------------------------------------------------------
		public function Update($usr,$img)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOUser::SP_UPDATE."(?,?,?,?,?,?,?,?,?,?,?,?,?,@img_db_name,@msg_db_transaccion)";
			$dao->prepareSP($querie);
			$dao->addParameter(1,$usr->tokenSession);
			$dao->addParameter(2,$usr->userId);
			$dao->addParameter(3,$usr->role->roleId);
			$dao->addParameter(4,$usr->names);
			$dao->addParameter(5,$usr->email);
			$dao->addParameter(6,$usr->typeNif->idDocument);
			$dao->addParameter(7,$usr->nif);
			$dao->addParameter(8,$usr->estate->idState);
			$dao->addParameter(9,$usr->sex);
			$dao->addParameter(10,$usr->address);
			$dao->addParameter(11,$usr->movilNumber);
			$dao->addParameter(12,$usr->landLine);
			$dao->addParameter(13,$img);
			$result = $dao->execute();
			if($dao->_ERROR_COMAND!=""){$this->MESSAGE_TRANSACTION = $dao->formatMensajeError("Error, ");}
			else{
				$this->IMAGE_CREATE_VALUE   = $dao->getFirstValue("select @img_db_name",null);
				$this->MESSAGE_TRANSACTION  = $dao->getFirstValue("select @msg_db_transaccion",null);
            }
			return $result;
		}
		//-------------------------------------------------------------------------------------------
		// REFERENCE USER AND PATIENT LAST INSERTED
		//-------------------------------------------------------------------------------------------
		public function assignPatientUserLastOwner()
		{
			$dao = new DAO();
			$querie = "CALL ".DAOUser::SP_ASG_LAST_OWNER."(@msg_db_transaccion)";
			$result = $dao->executeSP($querie,null);
			if($dao->_ERROR_COMAND!=""){$this->MESSAGE_TRANSACTION = $dao->formatMensajeError("Error, ");}
			else{
				$this->MESSAGE_TRANSACTION  = utf8_encode($dao->getFirstValue("select @msg_db_transaccion",null));
			}
			return $result;
		}
		
		//--------------------------------------------------------------------------------------------
		// UPDATE CREDENTIALS USERS
		//--------------------------------------------------------------------------------------------
		public function UpdateCredential($iduser,$newPassword)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOUser::SP_UPD_CREDENTIAL."(?,?,@msg_db_transaccion)";
			$result = $dao->executeSP($querie,array($iduser,$newPassword));
			if($dao->_ERROR_COMAND!=""){$this->MESSAGE_TRANSACTION = $dao->formatMensajeError("Error, ");}
			else{
				$this->MESSAGE_TRANSACTION  = $dao->getFirstValue("select @msg_db_transaccion",null);
			}
			return $result;	
		}
		//--------------------------------------------------------------------------------------------
		// GET PARAMETERS FOR DATABASE FROM USER LOGIN
		//--------------------------------------------------------------------------------------------
		public function getServerUser($usr)
		{
			$dao = new Dao();
			$querie = "CALL ".DAOUser::SP_SERVER_USER."(?)";
			$dao->prepareSP($querie);
			$dao->addParameter(1,$usr);
			if($dao->_ERROR_COMAND==""){	
				$data_ent = $dao->getDataTableSP();
				$array_servers = array();
				$this->MESSAGE_TRANSACTION = "OK"; 
				for($j=0;$j<count($data_ent);$j++)
				{
					$data 	= $data_ent[$j];
					$entity = new EntityServerUserAssign();
					$entity->idServer 			= $data[0];
					$entity->nameServer 		= $data[1];
					$entity->isAssign 		= $data[2];
					$array_servers[$j] = $entity;
				}
				return $array_servers;	
			}
			else { $this->MESSAGE_TRANSACTION = 
				   $dao->formatMensajeError("Error, no se efectuo la operacion con la Base Datos");
			}
		}
		//--------------------------------------------------------------------------------------------
		// ASSIGN SERVER FOR USER
		//--------------------------------------------------------------------------------------------
		public function AssignServer($iduser,$idserver)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOUser::SP_INSERT_SERVER_USER."(?,?,@msg_db_transaccion)";
			$dao->prepareSP($querie);
			$dao->addParameter(1,$iduser);
			$dao->addParameter(2,$idserver);
			$result = $dao->execute();
			if($dao->_ERROR_COMAND!=""){$this->MESSAGE_TRANSACTION = $dao->formatMensajeError("Error, ");}
			else{
				$this->MESSAGE_TRANSACTION  = $dao->getFirstValue("select @msg_db_transaccion",null);
			}
			return $result;	
		}		
		//--------------------------------------------------------------------------------------------
		// DELETE ASIGN SERVER FOR USER
		//--------------------------------------------------------------------------------------------
		public function DeleteAssignServer($iduser)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOUser::SP_DELETE_SERVER_USER."(?,@msg_db_transaccion)";
			$dao->prepareSP($querie);
			$dao->addParameter(1,$iduser);
			$result = $dao->execute();
			if($dao->_ERROR_COMAND!=""){$this->MESSAGE_TRANSACTION = $dao->formatMensajeError("Error, ");}
			else{
				$this->MESSAGE_TRANSACTION  = $dao->getFirstValue("select @msg_db_transaccion",null);
			}
			return $result;	
		}
		//--------------------------------------------------------------------------------------------
		// ASSIGN USERS FOR USER
		//--------------------------------------------------------------------------------------------
		public function AssignUserChild($iduser,$idUserChild)
		{
			$dao = new DAO();		
			$querie = "CALL ".DAOUser::SP_INSERT_USER_CHILD."(?,?,@msg_db_transaccion)";
			$dao->prepareSP($querie);
			$dao->addParameter(1,$iduser);
			$dao->addParameter(2,$idUserChild);
			$result = $dao->execute();
			if($dao->_ERROR_COMAND!=""){$this->MESSAGE_TRANSACTION = $dao->formatMensajeError("Error, ");}
			else{
				$this->MESSAGE_TRANSACTION  = $dao->getFirstValue("select @msg_db_transaccion",null);
			}
			return $result;	
		}		
		//--------------------------------------------------------------------------------------------
		// DELETE ASIGN SERVER FOR USER
		//--------------------------------------------------------------------------------------------
		public function deleteAssignUserChild($iduser)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOUser::SP_DELETE_USER_CHILD."(?,@msg_db_transaccion)";
			$dao->prepareSP($querie);
			$dao->addParameter(1,$iduser);
			$result = $dao->execute();
			if($dao->_ERROR_COMAND!=""){$this->MESSAGE_TRANSACTION = $dao->formatMensajeError("Error, ");}
			else{
				$this->MESSAGE_TRANSACTION  = $dao->getFirstValue("select @msg_db_transaccion",null);
			}
			return $result;	
		}
		//------------------------------------------------------------------------------------
		// LIST USERS ASSIGN TO USER
		//------------------------------------------------------------------------------------
		public function getListUsersChild($pUser)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOUser::SP_LIST_USER_CHILD."(?)";
			$data =  $dao->getDataTable($querie,array($pUser));
			$array_users = array();
			for($j=0;$j<count($data);$j++)
			{  
				$data_ent = $data[$j];
				$entity = new EntityUserChild();			
				$entity->userId					= $data_ent[0];
				$entity->userChildId 			= $data_ent[1];
				$array_users[$j] = $entity;
			}
			return $array_users;
		}
		/*
		//-------------------- DELETE USERS
		public function Delete($iduser)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOUser::SP_DELETE."(?,@msg_db_transaccion)";
			$dao->prepareSP($querie);
			$dao->addParameter(1,$iduser);
			$result = $dao->execute();
			if($dao->_ERROR_COMAND!=""){$this->MESSAGE_TRANSACTION = $dao->formatMensajeError("Error, ");}
			else{
				$this->MESSAGE_TRANSACTION  = $dao->getFirstValue("select @msg_db_transaccion",null);
			}
			return $result;	
		}		
		*/
	}
	/*
	$dao = new DAOUser();
	print_r($dao->getUsers('','','','','',''));
	$dao = new DAOUser();
	print_r($dao->getServerUser("USR00004",""));
	*/
?>