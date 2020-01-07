<?php

	/**
	* @author Maykel Esser
	* @copyright (C)2019, Maykel Esser.
	* @version 0.0.1
	* @since 0.0.1
	* @package Continuum 1.0.0.
	*/
	
	// * Muda diretrizes do PHP
	ini_set("display_errors", true);
	error_reporting(E_ALL);
	date_default_timezone_set('America/Sao_Paulo');

	// * Inicia a sessão
	session_start();

	// * Inicia algumas variáveis
	$divAlert = "";

	// * Requires
	require_once($_SERVER['DOCUMENT_ROOT']."/common/class_autoload.php");
	require_once($_SERVER['DOCUMENT_ROOT'].'/common/lib/vendor/autoload.php');

	// * Identifica o protocolo
	$protocoloHTTP = (isset($_SERVER['HTTPS'])) ? "https" : "http";

	// * Automatiza a captura das URLs de subdomínios
	$info = parse_url($_SERVER["SERVER_NAME"]);
	$info = explode(".", $info['path']);
	$info = array_values($info);
	$infoPath = implode(".",$info);

	// * Banco de dados
	switch($_SERVER["SERVER_NAME"]){
		case "localhost":
			define("DATABASE_HOST", "localhost");
			define("DATABASE_LOGIN", "root");
			define("DATABASE_PASS", "");
			define("DATABASE_NAME", "");
			define("DATABASE_PORT", "");
		break;
	}

	// * Domínios
	switch($_SERVER["SERVER_NAME"]){
		case "localhost":
			define("PATH_BASE", "panel");
		break;
	}

	// * Definições de tokens e diretivas
	define("PATH_COMMON", "common/assets");
	define("PROTOCOL", $protocoloHTTP);
	define("TOKEN_JWT", "continuum2020");
	define("PATH_DOMAIN", $infoPath);
	