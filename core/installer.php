<?php

namespace MavenEngage\Core;

class Installer {
	public function __construct() {
		;
	}
	
	public  function install(){
		
		global $wpdb;

		$create = array(
			
		);

		foreach ( $create AS $sql ) {
			$wpdb->query($sql);
		}
	}
	
	public function uninstall(){
		
		global $wpdb;
		
		$settings = \MavenEngage\Settings\EngageRegistry::instance();
		$settings->reset();
		//To danger to remove the tables in the uninstall process
		$drop = array(
//			"mvns_products"
			);
		
		
		foreach ( $drop AS $sql ) {
			if ( $wpdb->query( $sql ) === false )
				return false;
		}
	}
	
}
