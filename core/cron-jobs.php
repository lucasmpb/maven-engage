<?php

namespace MavenEngage\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class CronJobs {

	public static function init() {

		$campaignManager = new CampaignManager();

		add_action( 'wp', array( '\MavenEngage\Core\CronJobs', 'setupAbandonedCartsSchedule' ) );

		add_action( 'maven/orders/prepareAbandonedCartEmail', array( $campaignManager, 'prepareAbandonedCartEmail' ) );

		add_filter( 'cron_schedules', array( '\MavenEngage\Core\CronJobs', 'addScheduleInterval' ) );
	}

	public static function setupAbandonedCartsSchedule() {

		if ( ! wp_next_scheduled( 'maven/orders/prepareAbandonedCartEmail' ) ) {
			wp_schedule_event( time(), 'every5minutes', 'maven/orders/prepareAbandonedCartEmail' );
		}
	}

	function addScheduleInterval( $schedules ) {

		// add a '5 minutes' schedule to the existing set
		$schedules[ 'every5minutes' ] = array(
		    'interval' => 5 * 60,
		    'display' => __( 'Every 5 minutes' )
		);

		return $schedules;
	}

	public static function removeCronJobs() {

		wp_clear_scheduled_hook( 'maven/orders/prepareAbandonedCartEmail' );

		//TODO: deberiamos limpiar los eventos individuales de cada cart
	}

}
