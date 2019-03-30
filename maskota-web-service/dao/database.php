<?php
	require_once("params.php");
	class Database
	{
		private $connection = null;
		private $command	= null;
		public  $_ERROR_COMMAND = "";
		public function getConnection()
		{
			$this->_ERROR_COMMAND = "";
			try {
				$this->connection = new PDO(
					Params::PROVIDER_DB.
					':dbname='.Params::NAME_DB.
					';host:'.Params::HOST_DB,
					Params::USER_DB,Params::PASSWORD_DB,
					array(PDO::ATTR_PERSISTENT => true));
				$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (Exception $ex) {
				// echo $ex->getMessage();
				$this->closeConnection();
				$this->_ERROR_COMMAND = $ex->getMessage();
			}
			return $this->connection;
		}
		
		public function closeConnection()
		{
			$this->connection = null;
			
		}
		
	}
	


?>