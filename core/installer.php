<?php

namespace MavenEngage\Core;

class Installer {

	public function __construct() {
		;
	}

	public function install() {

		global $wpdb;

		$create = array(
		    "CREATE TABLE IF NOT EXISTS `mvneg_campaign` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `schedule_value` int(11) DEFAULT NULL,
			  `schedule_unit` varchar(50) DEFAULT NULL,
			  `subject` varchar(1024) DEFAULT NULL,
			  `body` text,
			  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,				
			  PRIMARY KEY (`id`)
			)",
		    "CREATE TABLE IF NOT EXISTS `mvneg_campaign_schedule` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `order_id` int(11) DEFAULT NULL,
			  `campaign_id` int(11) DEFAULT NULL,
			  `code` varchar(32) DEFAULT NULL,
			  `scheduled_date` datetime DEFAULT NULL,
			  `send_date` datetime DEFAULT NULL,
			  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,					  
			  PRIMARY KEY (`id`)
			) "
		);

		foreach ( $create AS $sql ) {
			$wpdb->query( $sql );
		}
	}

	public function uninstall() {

		global $wpdb;

		$settings = \MavenEngage\Settings\EngageRegistry::instance();
		$settings->reset();
		//To danger to remove the tables in the uninstall process
		$drop = array(
//			"mvnse_campaign",
//			"mvnse_campaign_send"
		);


		foreach ( $drop AS $sql ) {
			if ( $wpdb->query( $sql ) === false )
				return false;
		}
		
		//Remove scheduled jobs
		//CronJobs::removeCronJobs();
	}

}
