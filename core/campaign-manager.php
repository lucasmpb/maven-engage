<?php

namespace MavenEngage\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class CampaignManager {

	private $mapper;

	public function __construct() {

		$this->mapper = new Mappers\CampaignMapper();
	}

	/**
	 * 
	 * @param \MavenEngage\Core\Domain\Campaign $campaign
	 * @return \MavenEngage\Core\Domain\Campaign
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function addCampaign( \MavenEngage\Core\Domain\Campaign $campaign ) {

		$campaignToUpdate = new Domain\Campaign();

		if ( is_array( $campaign ) ) {
			\Maven\Core\FillerHelper::fillObject( $campaignToUpdate, $campaign );
		} else {
			$campaignToUpdate = $campaign;
		}

		$mapper = new Mappers\CampaignMapper();

		return $mapper->save( $campaignToUpdate );
	}

	/**
	 * 
	 * @param mixed $campaignId
	 * @return \MavenEngage\Core\Domain\Campaign
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function get( $campaignId ) {

		if ( ! $campaignId ) {
			throw new \Maven\Exceptions\MissingParameterException( "Event id is required" );
		}

		$campaign = $this->mapper->get( $campaignId );

		return $campaign;
	}

	/**
	 * Get campaigns
	 * @return \MavenEngage\Core\Domain\Campaign[]
	 */
	public function getAll( $orderBy = "id", $orderType = 'asc', $start = "0", $limit = "1000" ) {

		return $this->mapper->getAll( $orderBy, $orderType, $start, $limit );
	}

	public function getCount() {

		return $this->mapper->getCount();
	}

	public function delete( $orderId ) {

		$this->mapper->delete( $orderId );

		return true;
	}

	/**
	 * Send campaign email
	 * @param int $orderId
	 * @param int $campaignId
	 */
	public function sendCampaignEmail( $orderId, $campaignId ) {

		$scheduleManager = new CampaignScheduleManager();

		$orderManager = new \MavenShop\Core\OrderManager();

		$order = $orderManager->get( $orderId );

		//Get schedule
		$campaignSchedule = $scheduleManager->getOrderSchedule( $orderId, $campaignId );

		if ( ! $campaignSchedule->getSendDate() ) {

			//send mail
			
			
			//update schedule
			$campaignSchedule->setSendDate('');
			$scheduleManager->addCampaignSchedule($campaignSchedule);
		}
	}

	//This function will search for abandoned carts and add a schedule to send the corresponding email
	public function prepareAbandonedCartEmail() {

		//get Active Campaigns
		$campaigns = $this->getAll();

		foreach ( $campaigns as $campaign ) {
			$interval = $campaign->getScheduleString();
			// Campaign Limit
			$today = MavenDateTime::getWPCurrentDateTime();

			$toDate = new \Maven\Core\MavenDateTime( $today );
			$toDate->subFromIntervalString( $interval );
			
			//First Process Received Orders
			
			
			
			
		}
	}

}
