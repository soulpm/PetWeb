<?php
	require_once($_SERVER["DOCUMENT_ROOT"]."/constants-maskotaweb.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/entity/entity_user.php");
		
	//vet_patient
	class EntityPatient
	{
		//ID PATIENT
		public $idPatient;
		//KIND;
		public $kindPatient;
		//NAMES
		public $names;
		//SEX
		public $sex;
		//BREED
		public $breed;
		//COLOR
		public $color;
		//DATE BORN
		public $dateBorn;
		//YEARS
		public $year;
		//MONTHS
		public $month;
		
		
		//CONSTRUCT
		function __construct() {
			$this->kindPatient 	= new EntityKindPatient();
			$this->sex 			= new EntitySexPatient();
			
		}
	}
	class EntitySexPatient{
		//id_kind
		public $idSex;
		//name
		public $name;
	}

	class EntityKindPatient{
		//id_kind
		public $idKind;
		//name
		public $name;
	}

	class EntitySignClinic {
		public $idClinicSign;
		public $name;
	}
	class EntityMedic{
		public $idMedic;
    	public $names;
	}
	class EntityHistoryClinic{
		public $idHistory;
		public $patient;
		public $idClinicSign;
		public $medic;
		public $dateRegister;
		public $stature;
		public $weight;
		public $diagnostic;
		public $nextDate;
		public $treatment;
		public $vaccine;
		public $chemotherapy;
		public $payment;
		public $temperature;
		public $recommend;
		public $vaccineCompleted;
		public $desparacitado;
		public $withOperation;
		public $socialKnown;
		public $itvePulgas;
		public $itveGarrapata;
		public $itveHongos;
		public $itveOtitis;
		public $itveBanioStandar;
		public $itveBanioMedicado;
		public $itveCorte;
		public $itvePromoGratis;

		function __construct() {
			$this->patient 	= new EntityPatient();
			$this->medic 	= new EntityUser();
		}
	}
?>