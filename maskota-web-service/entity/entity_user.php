<?php
	require_once($_SERVER["DOCUMENT_ROOT"]."/constants-maskotaweb.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/entity/entity_role.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/entity/entity_document.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/entity/entity_state.php");
	
	//sys_usr
	class EntityUser		
	{
		//token_user_session
		public $tokenSession;
		//id_usr
		public $userId;
		//Role Entity;
		public $role;
		//names
		public $names;
		//email
		public $email;
		//typ_nif
		public $typeNif;
		//NIF
		public $numberNif;
		//state
		public $state;
		//SEX
		public $sex;
		//ADDRESS	
		public $address;
		//MOVIL NUMBER
		public $movilNumber;
		//LINE NUMBER
		public $landLine;
		//PHOTO
		public $photo;
		
		//CONSTRUCT
		function __construct() {
        	$this->role 		= new EntityRole();
			$this->typeNif 		= new EntityDocument();
			$this->estate 		= new EntityState();
			
		}
	}

	class EntityUserChild{
		//id_usr
		public $userId;
		//id_usr_child
		public $userChildId;

	}

	class EntityUserClientPatient{
		//token_user_session
		public $tokenSession;
		//id_usr
		public $userId;
		//names
		public $names;
		//email
		public $email;
		//typ_nif
		public $typeNif;
		//NIF
		public $numberNif;
		
		public $isOwner;
		//PHOTO
		public $photo;
		
		//CONSTRUCT
		function __construct() {
        	$this->typeNif 		= new EntityDocument();
		}
	}
	
?>