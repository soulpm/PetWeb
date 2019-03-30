<?php
	require_once($_SERVER["DOCUMENT_ROOT"]."/constants-maskotaweb.php");
	require_once($_SERVER["DOCUMENT_ROOT"].VarConstantsMaskotaWeb::PATH_ROOT_APP."/dao/databaseMSQL.php");

	class DAOMSQL
	{	
		private $database	 		= null;
		private $connection	 		= null;
		private $connection2	 	= null;
		private $connection3	 	= null;
		private $connection4	 	= null;
		private $connection5	 	= null;
		private $command 	 		= null;
		public  $_ERROR_COMAND 		= "";
		public  $_ERROR_CONNECTION 	= "";
		
		public function __construct($type){
			$connection = "";
			if($this->database == null)
			{
				try{
					$this->database = new DatabaseMSQL();
					switch($type){
						case 1: $connection = $this->database->getConnection(); break;
						case 2: $connection = $this->database->getConnection2(); break;
						case 3: $connection = $this->database->getConnection3(); break;
						case 4: $connection = $this->database->getConnection4(); break;
						case 5: $connection = $this->database->getConnection5(); break;
					}
					$this->connection = $connection;
					if($this->database->_ERROR_COMMAND!=""){
						$this->_ERROR_CONNECTION = $this->database->_ERROR_COMMAND;	
					}
				}
				catch(Exception $ex)
				{
					$this->_ERROR_COMAND = "Error, no se establecion conexion a la base de datos, ".$ex->getMessage();	
				}
			}
			
			
		}
		
		//-------------------------------------------------------------------------
		// EXECUTE STORE PROCEDURE
		//-------------------------------------------------------------------------
		public function executeSP($querie,$params)
		{
			$result = -2;
			$this->_ERROR_COMAND = "";
			try
			{
				if($this->_ERROR_CONNECTION == ""){
					$this->command = $this->connection->prepare($querie);
					if($params!=null)
					{
						for($i=0;$i<count($params);$i++)
						{
							$this->command->bindParam($i+1,$params[$i],PDO::PARAM_STR);
						}
					}
					$result = $this->command->execute();
				}
				else{
					$this->_ERROR_COMAND = $this->_ERROR_CONNECTION;
				}
				
			}
			catch(Exception $ex)
			{
				$result = -1;
				$this->_ERROR_COMAND = $ex->getMessage();
				error_log("Error, no se ejecutar la operacion, ".$this->command->queryString, 0);
				//echo $ex->getMessage();
			}
			return $result;
		}
		
		//-------------------------------------------------------------------------
		// EXECUTE STORE PROCEDURE
		//-------------------------------------------------------------------------
		public function prepareSP($querie)
		{
			$this->_ERROR_COMAND = "";
			if($this->_ERROR_CONNECTION == "")
			{
				try
				{
					$this->command = $this->connection->prepare($querie);
				}
				catch(Exception $ex)
				{
					$this->_ERROR_COMAND = $ex->getMessage();
					error_log("Error, no se pudo preparar el comando, ".$this->command->queryString, 0);
					///echo $ex->getMessage();
				}
			}
			else{
				$this->_ERROR_COMAND = $this->_ERROR_CONNECTION;
			}
		}
		
		public function addParameter($index,$value)
		{
			if($this->_ERROR_CONNECTION == "")
			{$this->command->bindParam($index,$value,PDO::PARAM_STR);}
			else{$this->_ERROR_COMAND = $this->_ERROR_CONNECTION;}
		}	

		public function addOutParameter($index,$value)
		{
			if($this->_ERROR_CONNECTION == "")
			{$this->command->bindParam($index,$value,PDO::PARAM_OUT);}
			else{$this->_ERROR_COMAND = $this->_ERROR_CONNECTION;}
		}	
		
		public function execute()
		{
			if($this->_ERROR_CONNECTION == "")
			{
				$this->_ERROR_COMAND = "";
				$result = -1;
				try{
					$result = $this->command->execute();
				}
				catch(Exception $ex)
				{
					$result = -1;
					//echo $ex->getMessage();
					$this->_ERROR_COMAND = $ex->getMessage();
					error_log("Error, no se pudo ejecutar la operacion, ".$this->command->queryString, 0);
				}
				return $result;
			}
			else{$this->_ERROR_COMAND = $this->_ERROR_CONNECTION;}
		}
		
		
		//-------------------------------------------------------------------------
		// GET CURSOR  SP WITH STRING PARAMETERS
		//-------------------------------------------------------------------------
		public function getDataTable($querie,$params)
		{
			$this->_ERROR_COMAND = "";
			//echo "::: ".$this->_ERROR_CONNECTION;
			if($this->_ERROR_CONNECTION == "")
			{
				try
				{
					if($this->database == null)
					{
						$this->database = new Database();
						$this->connection = $this->database->getConnection();
					}
					$this->command = $this->connection->prepare($querie);
					if($params!=null)
					{
						for($i=0;$i<count($params);$i++)
						{
							//echo ":: ".$params[$i];
							$this->command->bindParam($i+1,$params[$i],PDO::PARAM_STR);
						}
					}
					$this->command->execute();
					$data = $this->command->fetchAll();
					return $data;
				}
				catch(Exception $ex)
				{
					//echo $ex->getMessage();
					error_log("Error, no obtuvo valor de la consulta, ".$this->command->queryString, 0);
					$this->_ERROR_COMAND = $ex->getMessage();
				}
			}
			else{$this->_ERROR_COMAND = $this->_ERROR_CONNECTION;}
		}
		
		//-------------------------------------------------------------------------
		// GET CURSOR  SP WITH PREPARE SP
		//-------------------------------------------------------------------------
		public function getDataTableSP()
		{
			$this->_ERROR_COMAND = "";
			if($this->_ERROR_CONNECTION == "")
			{	
				try
				{
					$this->_ERROR_COMAND = "";
					$result = $this->command->execute();
					$data = $this->command->fetchAll();
					return $data;
				}
				catch(Exception $ex)
				{
					//echo $ex->getMessage();
					error_log("Error, no obtuvo valor de la consulta, ".$this->command->queryString, 0);
					$this->_ERROR_COMAND = $ex->getMessage();
				}
			}
			else{$this->_ERROR_COMAND = $this->_ERROR_CONNECTION;}
		}
		
		//-------------------------------------------------------------------------
		// GET CURSOR  SP WITH PARAMETERS
		//-------------------------------------------------------------------------
		public function getDataTableParam($querie,$params)
		{
			$this->_ERROR_COMAND = "";
			if($this->_ERROR_CONNECTION == "")
			{	
				//--- PARAMETER:  VALUE - TYPE 
				try
				{
					if($this->database == null)
					{
						$this->database = new Database();
						$this->connection = $this->database->getConnection();
					}
					$this->command = $this->connection->prepare($querie);
					if($params!=null)
					{
						for($i=0;$i<count($params);$i++)
						{
							//echo ":: ".$params[$i];
							$this->command->bindParam($i+1,$params[$i][0],$this->_EVALUE_TYPE_PARAM($params[$i][1]));
						}
					}
					$this->command->execute();
					$data = $this->command->fetchAll();
					return $data;
				}
				catch(Exception $ex)
				{
					//echo $ex->getMessage();
					error_log("Error, no obtuvo valor de la consulta, ".$this->command->queryString, 0);
					$this->_ERROR_COMAND = $ex->getMessage();
				}
			}
			else{$this->_ERROR_COMAND = $this->_ERROR_CONNECTION;}
		}
		
		function _EVALUE_TYPE_PARAM($va)
		{
			$TYPE_PARAM = "";
			switch($va)
			{
				case 1: $TYPE_PARAM = PDO::PARAM_STR; break;
				case 2: $TYPE_PARAM = PDO::PARAM_INT; break;
				case 3: $TYPE_PARAM = PDO::PARAM_BOOL; break;
				case 4: $TYPE_PARAM = PDO::PARAM_LOB ; break;
				case 5: $TYPE_PARAM = PDO::PARAM_INPUT_OUTPUT; break;
				 	
			}
			return $TYPE_PARAM;
		}
		
		public function getFirstValue($querie,$params)
		{
			$this->_ERROR_COMAND = "";
			if($this->_ERROR_CONNECTION == "")
			{		
				try
				{
					if($this->database == null)
					{
						$this->database = new Database();
						$this->connection = $this->database->getConnection();
					}
					$this->command = $this->connection->prepare($querie);
					if($params!=null)
					{
						for($i=0;$i<count($params);$i++)
						{
							$this->command->bindParam($i+1,$params[$i],PDO::PARAM_STR);
						}
					}
					$this->command->execute();
					$data = $this->command->fetchAll();
					return $data[0][0];
				}
				catch(Exception $ex)
				{
					//echo $ex->getMessage();
					error_log("Error, no obtuvo valor de la consulta, ".$this->command->queryString, 0);
					$this->_ERROR_COMAND = $ex->getMessage();
				}
			}
			else{$this->_ERROR_COMAND = $this->_ERROR_CONNECTION;}
		}
		
		public function getRealIP() {
			if (!empty($_SERVER["HTTP_CLIENT_IP"]))
			return $_SERVER["HTTP_CLIENT_IP"];
			if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
			return $_SERVER["HTTP_X_FORWARDED_FOR"];
			return $_SERVER["REMOTE_ADDR"];
		}
		
		//------------ FORMATO DE MENSAJE ERROR GENERAL DE MODEL BASE DATOS
		public function formatMensajeError($msg){
			 $val = 
		    //"::::::::::::::::::::::::::::::::::::::::::::::::".
			//"<br />".
			$msg.
			"<br />".
			$this->_ERROR_COMAND."<br />";
		    //"::::::::::::::::::::::::::::::::::::::::::::::::".
			//"<br />";
			return trim($val);
		}
		
		
		
		

	}

?>


