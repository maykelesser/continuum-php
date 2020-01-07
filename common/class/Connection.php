<?php

	/**
	* @author Maykel Esser
	* @copyright (C)2019, Maykel Esser.
	* @version 0.0.1
	* @since 0.0.1
	* @package Continuum 1.0.0.
	*/
	 
	class Connection
	{
		/**
		* connect()
		* Usada para conectar  ao banco de dados.
		* @author Maykel Esser
		* @return Object
		*/
 		function connect(){

 			// * Efetua a conexão
			try{
				$obj = new PDO(
					"mysql:host=".DATABASE_HOST.";dbname=".DATABASE_NAME, 
					DATABASE_LOGIN, 
					DATABASE_PASS, 
					array(
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
						PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
					)
				);
			}

			// * Em caso de falha, verificamos o motivo através da exceção.
			catch (PDOException $e){
				die("Erro na conexão ao banco: (".$e->getMessage().")");
			}
			
			return $obj;
		}
	}
