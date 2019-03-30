<?php
	require_once("paramsMSQL.php");
/*	
	echo 			Params::PROVIDER_DB.
					'Server:'.Params::HOST_DB.
					';Database='.Params::NAME_DB;
	*/
	class DatabaseMSQL
	{
		private $connection = null;
		private $command	= null;
		public  $_ERROR_COMMAND = "";
		public function getConnection()
		{
			$this->_ERROR_COMMAND = "";
			try {
				$this->connection = new PDO(
					ParamsMSQL::PROVIDER_DB.
					':Server='.ParamsMSQL::HOST_DB.','.ParamsMSQL::PORT_DB.
					';Database='.ParamsMSQL::NAME_DB,
					ParamsMSQL::USER_DB,ParamsMSQL::PASSWORD_DB,
					array(PDO::ATTR_PERSISTENT => true));
				$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (Exception $ex) {
				// echo $ex->getMessage();
				$this->closeConnection();
				$this->_ERROR_COMMAND = $ex->getMessage();
			}
			return $this->connection;
		}
		
		
		/***** TEMP ***************/
		public function getConnection2()
		{
			$this->_ERROR_COMMAND = "";
			try {
				$this->connection = new PDO(
					ParamsMSQL::PROVIDER_DB2.
					':Server='.ParamsMSQL::HOST_DB2.','.ParamsMSQL::PORT_DB2.
					';Database='.ParamsMSQL::NAME_DB2,
					ParamsMSQL::USER_DB2,ParamsMSQL::PASSWORD_DB2,
					array(PDO::ATTR_PERSISTENT => true));
				$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (Exception $ex) {
				// echo $ex->getMessage();
				$this->closeConnection();
				$this->_ERROR_COMMAND = $ex->getMessage();
			}
			return $this->connection;
		}
		
		
		public function getConnection3()
		{
			$this->_ERROR_COMMAND = "";
			try {
				$this->connection = new PDO(
					ParamsMSQL::PROVIDER_DB3.
					':Server='.ParamsMSQL::HOST_DB3.','.ParamsMSQL::PORT_DB3.
					';Database='.ParamsMSQL::NAME_DB3,
					ParamsMSQL::USER_DB,ParamsMSQL::PASSWORD_DB3,
					array(PDO::ATTR_PERSISTENT => true));
				$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (Exception $ex) {
				// echo $ex->getMessage();
				$this->closeConnection();
				$this->_ERROR_COMMAND = $ex->getMessage();
			}
			return $this->connection;
		}
		
		
		public function getConnection4()
		{
			$this->_ERROR_COMMAND = "";
			try {
				$this->connection = new PDO(
					ParamsMSQL::PROVIDER_DB4.
					':Server='.ParamsMSQL::HOST_DB4.','.ParamsMSQL::PORT_DB4.
					';Database='.ParamsMSQL::NAME_DB4,
					ParamsMSQL::USER_DB,ParamsMSQL::PASSWORD_DB4,
					array(PDO::ATTR_PERSISTENT => true));
				$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch (Exception $ex) {
				// echo $ex->getMessage();
				$this->closeConnection();
				$this->_ERROR_COMMAND = $ex->getMessage();
			}
			return $this->connection;
		}
		
		public function getConnection5()
		{
			$this->_ERROR_COMMAND = "";
			try {
				$this->connection = new PDO(
					ParamsMSQL::PROVIDER_DB5.
					':Server='.ParamsMSQL::HOST_DB5.','.ParamsMSQL::PORT_DB5.
					';Database='.ParamsMSQL::NAME_DB5,
					ParamsMSQL::USER_DB,ParamsMSQL::PASSWORD_DB5,
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