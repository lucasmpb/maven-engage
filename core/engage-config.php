<?php

namespace MavenEngage\Core;

class EngageConfig {

	const objectTypeName = 'mvnse_object';
	const objectTableName = 'mvnse_object';
	
	public static function init() {

		add_action( 'init', array( __CLASS__, 'registerTypes' ) );

	}


	static function  registerTypes() {

		$showInMenu = WP_DEBUG;
		
	}

}


EngageConfig::init();
 
