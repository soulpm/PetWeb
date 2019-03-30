<?php
	require_once($_SERVER["DOCUMENT_ROOT"]."/constants-maskotaweb.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/dao/dao_configuration.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/entity/entity_cfg.php");

	class ControllerConfiguration
	{
		public $IMAGE_CREATE_VALUE 		= "";	
        public $MESSAGE_TRANSACTION     = "";
		public function listConfiguration($d1,$d2)
		{
			$dao = new DAOConfiguration();
			$data = $dao->getConfiguration($d1,$d2);	
			if(count($data)>0){
				return $data;
			}			
		}
		
		//---------------- SERVIDORES X USER
		public function listServers($p1,$p2)
		{
			$dao = new DAOConfiguration();
			$data = $dao->getServers($p1,$p2);
			if(count($data)>0){
				return $data;
			}			
		}
		//---------------- SERVIDORES X NAME
		public function listServersData($p1,$p2)
		{
			$dao = new DAOConfiguration();
			$data = $dao->getServersData($p1,$p2);
			if(count($data)>0){
				return $data;
			}			
		}
		//---------------- PERFILES
		public function listPerfil()
		{
			$dao = new DAOConfiguration();
			$data = $dao->getPerfil();
			if(count($data)>0){
				return $data;
			}			
		}
		//---------------- PERFILES
		public function listUserPerfil($prf)
		{
			$dao = new DAOConfiguration();
			$data = $dao->getUserPerfil($prf);
			if(count($data)>0){
				return $data;
			}			
		}
		//---------------- TIPO NIF
		public function listTipoNif()
		{
			$dao = new DAOConfiguration();
			$data = $dao->getTipoNif();
			if(count($data)>0){
				return $data;
			}			
		}
		//---------------- ESTADO
		public function listEstado()
		{
			$dao = new DAOConfiguration();
			$data = $dao->getEstado();
			if(count($data)>0){
				return $data;
			}			
		}
		//---------------- CREAR
		public function create($entity)
		{
			$this->MESSAGE_TRANSACTION ="";
			$dao_server = new DAOConfiguration();
			$result = $dao_server->Create($entity);
			$this->MESSAGE_TRANSACTION = $dao_server->MESSAGE_TRANSACTION;
			return $result;
		}
		//---------------- MODIFICAR
		public function update($ent)
		{
			$this->MESSAGE_TRANSACTION ="";
			$dao_server = new DAOConfiguration();
			$result = $dao_server->Update($ent);
			$this->MESSAGE_TRANSACTION = $dao_server->MESSAGE_TRANSACTION;
            return $result;
		}
		//---------------- MODIFICAR IP
		public function updateIP($server,$ip)
		{
			$this->MESSAGE_TRANSACTION ="";
			$dao_server = new DAOConfiguration();
			$result = $dao_server->UpdateIP($server,$ip);
			return $result;
		}
		//---------------- ELIMINAR
		public function delete($ent)
		{
			$this->MESSAGE_TRANSACTION ="";
			$dao_server = new DAOConfiguration();
			$result = $dao_server->Delete($ent);
			$this->MESSAGE_TRANSACTION = $dao_server->MESSAGE_TRANSACTION;
			return $result;
		}
		
		
		
	}
	//$c = new ControllerConfiguration();
	//print_r($c->listConfiguration('',''));

?>