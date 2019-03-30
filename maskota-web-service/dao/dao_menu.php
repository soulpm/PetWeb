<?php
	require_once($_SERVER["DOCUMENT_ROOT"]."/constants-maskotaweb.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/dao/dao.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/entity/entity_menu.php");
		
	class DAOMenu
	{
		const SP_LIST_MENU				= "SP_MNU_LST_CFG";
		public $MESSAGE_TRANSACTION     = "";
		
		//-------------------- RETURN LIST MENUS
		public function getListMenu($role)
		{
			$dao = new DAO();
			$querie = "CALL ".DAOMENU::SP_LIST_MENU."(?)";
			$data =  $dao->getDataTable($querie,array($role));
			$array_entity = array();
			for($j=0;$j<count($data);$j++)
			{  
				$data_ent = $data[$j];
				$entity = new EntityMenu();			
				$entity->idMenu					= $data_ent[0];
				$entity->nameOption				= utf8_decode($data_ent[1]);
				$entity->urlLink				= $data_ent[2];
				$entity->iconOption				= $data_ent[3];
				$array_entity[$j] = $entity;
			}
			return $array_entity;
		}
		
	}
	
?>