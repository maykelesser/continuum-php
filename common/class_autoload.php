<?php

	/**
	* @author Maykel Esser
	* @copyright (C)2019, Maykel Esser.
	* @version 0.0.1
	* @since 0.0.1
	* @package Continuum 1.0.0.
	*/

	spl_autoload_register(function($class) {
		if(file_exists($_SERVER['DOCUMENT_ROOT'].'/common/class/'.$class.'.php')){
			include($_SERVER['DOCUMENT_ROOT'].'/common/class/'.$class.'.php');
		}
	});
