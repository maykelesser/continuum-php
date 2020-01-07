<?php

	/**
	* @author Maykel Esser
	* @copyright (C)2019, Maykel Esser.
	* @version 0.0.1
	* @since 0.0.1
	* @package Continuum 1.0.0.
	*/
	
	require_once($_SERVER['DOCUMENT_ROOT']."/common/config.php");
	
	// * Gerencia as URLs amigáveis
	$url = strip_tags($_SERVER["REQUEST_URI"]);

	// * Retira os parametros get
	$newUrl = strtok($url, '?');

	// * Verifica se é a index
	if(empty($newUrl) || $newUrl == "/"){
		include(PATH_BASE."/index.php");
		exit();
	}

	// * Caso não seja
	else{

		// * Verifica se é a index.php
		if($newUrl == "/index.php" || $newUrl == "/index.html"){
			header("Location: /");
		}
		else{

			$url_array = explode("/", $newUrl);
			array_shift($url_array);

			// * Verifica a existencia do arquivo físico
			if(file_exists(PATH_BASE."/".$url_array[0].".php")){
				include(PATH_BASE."/".$url_array[0].".php");
				exit();
			}

			// * Caso não tenha arquivo
			else{
				http_response_code(404);
				include(PATH_BASE."/404.php");
			}
		}
	}
