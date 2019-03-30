<?php
	require_once($_SERVER["DOCUMENT_ROOT"]."/constants-maskotaweb.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/dao/dao.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/entity/entity_cfg.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/entity/entity_autocomplete.php");
	
	class DAOConfiguration
	{	
		const SP_LIST 					= "SP_LST_CFG";
		const SP_LIST_SERVER 			= "SP_LST_SPC_APP";
		const SP_LIST_USER 				= "SP_LST_USR";
		const SP_INSERT					= "SP_INS_CFG";
		const SP_UPDATE 				= "SP_UPD_CFG";
		const SP_UPDATE_IP 				= "SP_UPD_CFG_IP_SRV";
		const SP_DELETE 				= "SP_DEL_CFG";

		public $IMAGE_CREATE_VALUE      = "";
        public $MESSAGE_TRANSACTION     = "";
		

		

		/*
		//-------------------- RETURN CONFIGURATION
		public function getConfiguration($p1,$p2,$p3)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOConfiguration::SP_LIST."(?,?,?)";
			$data =  $dao->getDataTable($querie,array($p1,$p2,$p3));
			$array_entity = array();
			for($j=0;$j<count($data);$j++)
			{  
				$data_ent = $data[$j];
				$entity = new EntityConfiguration();			
				$entity->configId				= $data_ent[0];
				$entity->type					= $data_ent[1];
				$entity->subtype				= $data_ent[2];
				$entity->name 					= utf8_decode($data_ent[3]);
				$entity->description			= $data_ent[4];
				$array_entity[$j] = $entity;
			}
			return $array_entity;
		}
		//-------------------- RETURN USERS
		public function getUserPerfil($p1)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOUser::SP_LIST."(?,?,?,?)";
			$data =  $dao->getDataTable($querie,array("",$p1,"",""));
			$array_users 	= array();
			$array_entity 	= array();
			for($j=0;$j<count($data);$j++)
			{  
				$data_ent = $data[$j];
				$array_entity[$j]  = utf8_decode($data_ent[1])." ".utf8_decode($data_ent[2]);
			}
			return $array_entity;
		}
		//-------------------- RETURN SERVERS X USER OR SERVER OR IP
		public function getServers($p1,$p2)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOConfiguration::SP_LIST_SERVER."(?,?)";
			$data =  $dao->getDataTable($querie,array($p1,$p2));
			$array_entity = array();
			for($j=0;$j<count($data);$j++)
			{  
				$data_ent = $data[$j];
				$entity = new EntityConfiguration();			
				$entity->configId				= $data_ent[0];
				$entity->description 			= utf8_decode($data_ent[1]);
				$entity->name1					= $data_ent[2];
				$entity->name2					= $data_ent[3];
				$entity->type					= $data_ent[4];
				$array_entity[$j] = $entity;
			}
			return $array_entity;
		}
		//-------------------- RETURN SERVERS x NAME
		public function getServersData($p1,$p2)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOConfiguration::SP_LIST_SERVER."(?,?)";
			$data =  $dao->getDataTable($querie,array($p1,$p2));
			$array_entity = array();
			for($j=0;$j<count($data);$j++)
			{  
				$data_ent = $data[$j];
				$entity = new EntityConfiguration();			
				$entity->configId				= $data_ent[0];
				$entity->description			= utf8_decode($data_ent[1]);
				$entity->name1					= $data_ent[2];
				$entity->name2 					= $data_ent[3];
				$entity->type					= $data_ent[4];
				$array_entity[$j] = $entity;
			}
			return $array_entity;
		}
		//-------------------- RETURN PERFILES
		public function getPerfil()
		{
			$dao = new DAO();
			$querie = "CALL ".DAOConfiguration::SP_LIST."(?,?,?)";
			$data =  $dao->getDataTable($querie,array("PRF","",""));
			$array_entity = array();
			for($j=0;$j<count($data);$j++)
			{  
				$data_ent = $data[$j];
				$array_entity[$j] 		= utf8_decode($data_ent[3]);
			}
			return $array_entity;
		}
		//-------------------- RETURN TIPO NIF
		public function getTipoNif()
		{
			$dao = new DAO();
			$querie = "CALL ".DAOConfiguration::SP_LIST."(?,?,?)";
			$data =  $dao->getDataTable($querie,array("NIF","",""));
			for($j=0;$j<count($data);$j++)
			{  
				$data_ent = $data[$j];
				$entity = new EntityAutoComplete();
				$array_entity[$j] 			= utf8_decode($data_ent[3]);
			}
			return $array_entity;
		}
		//-------------------- RETURN ESTADO
		public function getEstado()
		{
			$dao = new DAO();
			$querie = "CALL ".DAOConfiguration::SP_LIST."(?,?,?)";
			$data =  $dao->getDataTable($querie,array("STD","",""));
			for($j=0;$j<count($data);$j++)
			{  
				$data_ent = $data[$j];
				$entity = new EntityAutoComplete();
				$array_entity[$j] 			= utf8_decode($data_ent[3]);
			}
			return $array_entity;
		}
		//-------------------- INSERT 
		public function Create($entity)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOConfiguration::SP_INSERT."(?,?,?,?,@msg_db_transaccion)";
			$dao->prepareSP($querie);
			$dao->addParameter(1,$entity->type);
			$dao->addParameter(2,$entity->description);
			$dao->addParameter(3,$entity->name1);
			$dao->addParameter(4,$entity->name2);
			$result = $dao->execute();
			if($dao->_ERROR_COMAND!=""){$this->MESSAGE_TRANSACTION = $dao->formatMensajeError("Error, ");}
			else{
				$this->IMAGE_CREATE_VALUE   = $dao->getFirstValue("select @img_db_name",null);
				$this->MESSAGE_TRANSACTION  = $dao->getFirstValue("select @msg_db_transaccion",null);
            }
			return $result;
		}
		//-------------------- UPDATE 
		public function Update($entity)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOConfiguration::SP_UPDATE."(?,?,?,?,?,@msg_db_transaccion)";
			$dao->prepareSP($querie);
			$dao->addParameter(1,$entity->configId);
			$dao->addParameter(2,$entity->type);
			$dao->addParameter(3,$entity->name1);
			$dao->addParameter(4,$entity->name2);
			$dao->addParameter(5,$entity->description);
			$result = $dao->execute();
			if($dao->_ERROR_COMAND!=""){$this->MESSAGE_TRANSACTION = $dao->formatMensajeError("Error, ");}
			else{
				$this->MESSAGE_TRANSACTION  = $dao->getFirstValue("select @msg_db_transaccion",null);
			}
			return $result;
		}
		
		//-------------------- UPDATE IP
		public function UpdateIP($server,$ip1,$ip2)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOConfiguration::SP_UPDATE_IP."(?,?,?)";
			$dao->prepareSP($querie);
			$dao->addParameter(1,$server);
			$dao->addParameter(2,$ip1);
			$dao->addParameter(3,$ip2);
			$result = $dao->execute();
			return $result;
		}
		//-------------------- DELETE 
		public function Delete($idval)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOConfiguration::SP_DELETE."(?,@msg_db_transaccion)";
			$dao->prepareSP($querie);
			$dao->addParameter(1,$idval);
			$result = $dao->execute();
			if($dao->_ERROR_COMAND!=""){$this->MESSAGE_TRANSACTION = $dao->formatMensajeError("Error, ");}
			else{
				$this->MESSAGE_TRANSACTION  = $dao->getFirstValue("select @msg_db_transaccion",null);
			}
			return $result;	
		}*/			
	}
	
?>