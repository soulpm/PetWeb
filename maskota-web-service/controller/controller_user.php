<?php
	require_once($_SERVER["DOCUMENT_ROOT"]."/constants-maskotaweb.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/dao/dao_user.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/entity/entity_user.php");
		
	class ControllerUser
	{
		public $IMAGE_CREATE_VALUE 		= "";
        public $MESSAGE_TRANSACTION     = "";
		
		//--------------------------------------------------------------------------------------
		// LOGIN USER
		//--------------------------------------------------------------------------------------
		public function signIn($user,$password)
		{
			$valReturn  				= null;
			$this->MESSAGE_TRANSACTION 	= "";
			$dao_user = new DAOUser();
			$request_user = $dao_user->signIn($user,$password);
            $this->MESSAGE_TRANSACTION = $dao_user->MESSAGE_TRANSACTION;
			if($this->MESSAGE_TRANSACTION!="")
			{
				if(gettype($request_user)!="NULL")
				{
					$valReturn = $request_user;
				}	
			}
			return $valReturn;
		}
		//----------------------------------------------------------------------------------------
		// SIGN OUT 
		//----------------------------------------------------------------------------------------
		public function signOut($ptoken)
		{
			$dao_user = new DAOUser();
			$result =  $dao_user->signOut($ptoken);
			return $result;
		}
		
		//--------------------------------------------------------------------------------------
		// ACCOUNT USER LOGIN
		//--------------------------------------------------------------------------------------
		public function userAccount($token)
		{
			$valReturn  				= null;
			$this->MESSAGE_TRANSACTION 	= "";
			$dao_user = new DAOUser();
			$request_user = $dao_user->userAccount($token);
			$this->MESSAGE_TRANSACTION = $dao_user->MESSAGE_TRANSACTION;
			return $request_user;
		}
		
		//--------------------------------------------------------------------------------------
		// LIST ROL
		//--------------------------------------------------------------------------------------
		public function getListRol($pToken)
		{
			$dao_user = new DAOUser();
			$data = $dao_user->getListRol($pToken);
			if(count($data)>0){
				return $data;
			}			
		}

		//--------------------------------------------------------------------------------------
		// LIST USERS
		//--------------------------------------------------------------------------------------
		public function getListUsers($pToken,$pId,$pName,$pRole,$pTypeDoc,$pState)
		{
			$dao_user = new DAOUser();
			$data = $dao_user->getListUsers($pToken,$pId,$pName,$pRole,$pTypeDoc,$pState);
			if(count($data)>0){
				return $data;
			}			
		}
		//--------------------------------------------------------------------------------------
		// LIST CLIENT USERS
		//--------------------------------------------------------------------------------------
		public function getListClientPatientUsers($pToken,$pName,$pNif,$pPatient)
		{
			$dao_user = new DAOUser();
			$data = $dao_user->getListClientPatientUsers($pToken,$pName,$pNif,$pPatient);
			if(count($data)>0){
				return $data;
			}			
		}
		//--------------------------------------------------------------------------------------
		// LIST CLIENT USERS OWNERS
		//--------------------------------------------------------------------------------------
		public function getListClientPatientOwner($pToken,$pPatient)
		{
			$dao_user = new DAOUser();
			$data = $dao_user->getListClientPatientOwner($pToken,$pPatient);
			if(count($data)>0){
				return $data;
			}			
		}
		//--------------------------------------------------------------------------------------
		// REGISTER NEW USER
		//--------------------------------------------------------------------------------------
		public function create($usr,$img)
		{
			$this->MESSAGE_TRANSACTION ="";
			$this->IMAGE_CREATE_VALUE = "";
            $dao_user = new DAOUser();
			$result = $dao_user->Create($usr,$img);
			$this->IMAGE_CREATE_VALUE = $dao_user->IMAGE_CREATE_VALUE;
            $this->MESSAGE_TRANSACTION = $dao_user->MESSAGE_TRANSACTION;
			return $result;
		}
		//-------------------------------------------------------------------------------------------
		// UPDATE USER
		//-------------------------------------------------------------------------------------------
		public function update($usr,$img)
		{
			$this->MESSAGE_TRANSACTION ="";
			$this->IMAGE_CREATE_VALUE = "";
            $dao_user = new DAOUser();
			$result   = $dao_user->Update($usr,$img);
			$this->IMAGE_CREATE_VALUE  = $dao_user->IMAGE_CREATE_VALUE;
			$this->MESSAGE_TRANSACTION = $dao_user->MESSAGE_TRANSACTION;
            return $result;
		}
		//-------------------------------------------------------------------------------------------
		// REFERENCE USER AND PATIENT LAST INSERTED
		//-------------------------------------------------------------------------------------------
		public function assignPatientUserLastOwner()
		{
			$this->MESSAGE_TRANSACTION ="";
			$dao_user = new DAOUser();
			$result   = $dao_user->assignPatientUserLastOwner();
			$this->MESSAGE_TRANSACTION = $dao_user->MESSAGE_TRANSACTION;
            return $result;
		}
		
		//-------------------------------------------------------------------------------------------
		// CHANGE CREDENT
		//-------------------------------------------------------------------------------------------
		public function updateCredential($iduser,$password)
		{
			$dao_user = new DAOUser();
			$result = $dao_user->UpdateCredential($iduser,$password);
			$this->MESSAGE_TRANSACTION = $dao_user->MESSAGE_TRANSACTION;
			return $result;
		}
		//-------------------------------------------------------------------------------------------
		// SERVERS BY USERS
		//-------------------------------------------------------------------------------------------
		public function getServerUser($user)
		{
			$dao_user = new DAOUser();
			$request_server_user = $dao_user->getServerUser($user);
			$this->MESSAGE_TRANSACTION = $dao_user->MESSAGE_TRANSACTION;
			return $request_server_user;			
		}
		//-------------------------------------------------------------------------------------------
		// ASSIGN SERVERS FOR USERS
		//-------------------------------------------------------------------------------------------
		public function assignServer($user,$server)
		{
			$this->MESSAGE_TRANSACTION ="";
			$this->IMAGE_CREATE_VALUE = "";
            $dao_user = new DAOUser();
			$result = $dao_user->AssignServer($user,$server);
			$this->MESSAGE_TRANSACTION = $dao_user->MESSAGE_TRANSACTION;
			return $result;
		}
		//-------------------------------------------------------------------------------------------
		// DELETE SERVERS ASSIGN TO USER
		//-------------------------------------------------------------------------------------------
		public function deleteAssignServer($user)
		{
			$this->MESSAGE_TRANSACTION ="";
			$this->IMAGE_CREATE_VALUE = "";
            $dao_user = new DAOUser();
			$result = $dao_user->DeleteAssignServer($user);
			$this->MESSAGE_TRANSACTION = $dao_user->MESSAGE_TRANSACTION;
			return $result;
		}
		//-------------------------------------------------------------------------------------------
		// ASSIGN USERS FOR USER
		//-------------------------------------------------------------------------------------------
		public function assignUserChild($user,$userChild)
		{
			$this->MESSAGE_TRANSACTION ="";
			$this->IMAGE_CREATE_VALUE = "";
            $dao_user = new DAOUser();
			$result = $dao_user->AssignUserChild($user,$userChild);
			$this->MESSAGE_TRANSACTION = $dao_user->MESSAGE_TRANSACTION;
			return $result;
		}
		//-------------------------------------------------------------------------------------------
		// DELETE USERS ASSIGN TO USER
		//-------------------------------------------------------------------------------------------
		public function deleteAssignUserChild($user)
		{
			$this->MESSAGE_TRANSACTION ="";
			$this->IMAGE_CREATE_VALUE = "";
            $dao_user = new DAOUser();
			$result = $dao_user->deleteAssignUserChild($user);
			$this->MESSAGE_TRANSACTION = $dao_user->MESSAGE_TRANSACTION;
			return $result;
		}
		//--------------------------------------------------------------------------------------
		// LIST ROL
		//--------------------------------------------------------------------------------------
		public function getListUsersChild($pUser)
		{
			$dao_user = new DAOUser();
			$data = $dao_user->getListUsersChild($pUser);
			if(count($data)>0){
				return $data;
			}			
		}
		
		/*
		public function signInWs($user,$password)
		{
			$dao_user = new DAOUser();
			$request_user = $dao_user->signInApp($user,$password);
			$this->MESSAGE_TRANSACTION = $dao_user->MESSAGE_TRANSACTION;
			if($this->MESSAGE_TRANSACTION == "OK")
			{
				$dao_server = new DAOUser();
				$data =  $dao_server->getServerUser($request_user->userId,"");
				if(count($data)==0){$data = array();}
				$credentials = array(
					"ID"			=>$request_user->userId,
					"FIRST_NAME"	=>utf8_encode($request_user->firstName),
					"LAST_NAME"		=>utf8_encode($request_user->lastName),
					"EMAIL"			=>utf8_encode($request_user->email),
					"PHOTO"			=>utf8_encode($request_user->photo),
					"PROFILE"		=>utf8_encode($request_user->profile->name),
					"NIF"			=>utf8_encode($request_user->nif),
					"SERVERS"		=>$data,
				);
				return $credentials;				
			}
		}
		
		
		public function getCredentialUser($p1)
		{
			$dao_user = new DAOUser();
			$request_user = $dao_user->getCredentialUser($p1);
			$this->MESSAGE_TRANSACTION = $dao_user->MESSAGE_TRANSACTION;
			return $request_user;			
		}
		public function listUserByClient($d1,$d2,$d3,$d4)
		{
			$dao_user = new DAOUser();
			$data = $dao_user->getUsersByClient($d1,$d2,$d3,$d4);
			if(count($data)>0){
				return $data;
			}			
		}
		
		public function delete($user)
		{
			$this->MESSAGE_TRANSACTION ="";
			$this->IMAGE_CREATE_VALUE = "";
            $dao_user = new DAOUser();
			$result = $dao_user->Delete($user);
			$this->MESSAGE_TRANSACTION = $dao_user->MESSAGE_TRANSACTION;
			return $result;
		}
		*/
	}

	
?>