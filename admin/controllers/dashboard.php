<?php

namespace MavenEngage\Admin\Controllers;

class Dashboard extends \MavenEngage\Admin\Controllers\EngageAdminController {

	public function __construct() {
		parent::__construct();
	}

	public function admin_init() {
		
	}

	public function showForm() {


		$this->getOutput()->setTitle( $this->__( "Dashboard" ) );

		$this->getOutput()->loadAdminView( "dashboard" );
	}

	public function cancel() {
		
	}

	public function save() {
		
	}

	public function showList() {
		
	}

	public function entryPoint() {

		$event = $this->getRequest()->getProperty( "event" );
		$data = $this->getRequest()->getProperty( "data" );

		$campaignScheduleManager = new \MavenEngage\Core\CampaignScheduleManager();

		switch ( $event ) {

			case "read":

				$data = $this->getRequest()->getProperty( 'data' );

				$filter = new \MavenEngage\Core\Domain\CampaignStatisticsFilter();

				if ( key_exists( 'fromDate', $data ) && $data[ 'fromDate' ] ) {
					$filter->setFromDate( $data[ 'fromDate' ] );
				}

				if ( key_exists( 'toDate', $data ) && $data[ 'toDate' ] ) {
					$filter->setToDate( $data[ 'toDate' ] );
				}

				if ( key_exists( 'campaignId', $data ) && $data[ 'campaignId' ] ) {
					$filter->setCampaignId( $data[ 'campaignId' ] );
				}

				$stats = $campaignScheduleManager->getStatistics( $filter );

				$this->getOutput()->sendData( $stats );

				break;
		}
	}

}
