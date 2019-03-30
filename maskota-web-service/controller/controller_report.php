<?php
	require_once($_SERVER["DOCUMENT_ROOT"]."/constants-maskotaweb.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/dao/dao_report.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/entity/entity_report.php");
		
	class ControllerReport
	{
		public $MESSAGE_TRANSACTION     = "";
		//--------------------------------------------------------------------------------------
		// REPORTE ATENCIONES
		//--------------------------------------------------------------------------------------
		public function getAttentionReport($pToken,$pPatient,$pDateIni,$pDateEnd)
		{
			$dao_entity = new DAOReport();
			$data = $dao_entity->getAttentionReport($pToken,$pPatient,$pDateIni,$pDateEnd);
			if(count($data)>0){
				return $data;
			}			
		}
	}

	
?>