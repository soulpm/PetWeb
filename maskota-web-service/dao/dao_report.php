<?php
	require_once($_SERVER["DOCUMENT_ROOT"]."/constants-grifos-peruanos.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/dao/dao.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/entity/entity_report.php");
			
	class DAOReport 
	{
		const SP_ATTENTION_REP			= "SP_REP_ATTENTION"	 ;
		public $MESSAGE_TRANSACTION     = "";

		//------------------------------------------------------------------------------------
		// REPORT ATTENTION
		//------------------------------------------------------------------------------------
		public function getAttentionReport($pToken,$pPatient,$pDateIni,$pDateEnd)
		{
			$dao 	= new DAO();
			$querie = "CALL ".DAOReport::SP_ATTENTION_REP."(?,?,?,?)";
			$data 	=  $dao->getDataTable($querie,array($pToken,$pPatient,$pDateIni,$pDateEnd));
			$array 	= array();
			if($data!=null){
				for($j=0;$j<count($data);$j++)
				{  
					$data_ent = $data[$j];
					$entity = new EntityReportAttention();			
					$entity->owner					= utf8_encode($data_ent[0]);
					$entity->kind					= utf8_encode($data_ent[1]);
					$entity->patient				= utf8_encode($data_ent[2]);
					$entity->sex					= $data_ent[3];
					$entity->breed					= $data_ent[4];
					$entity->color					= utf8_encode($data_ent[5]);
					$entity->dateBorn				= $data_ent[6];
					$entity->year					= $data_ent[7];
					$entity->month					= $data_ent[8];
					$entity->dateAttention			= $data_ent[9];
					$entity->diagnostic				= utf8_encode($data_ent[10]);
					$entity->payment				= $data_ent[11];
					$entity->state					= $data_ent[12];
					$array[$j] = $entity;
				}
			}
			return $array;
		}
	}
?>