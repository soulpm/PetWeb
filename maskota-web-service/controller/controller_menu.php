<?php
	require_once($_SERVER["DOCUMENT_ROOT"]."/constants-maskotaweb.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/dao/dao_menu.php");
	class ControllerMenu	
	{
		public $MESSAGE_TRANSACTION     = "";
		
		public function listMenu($role)
		{
			$dao_server = new DAOMenu();
			$data = $dao_server->getListMenu($role);
			if(count($data)>0){
				return $data;
			}			
		}
	}
?>