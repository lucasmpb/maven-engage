<?php

namespace MavenEngage\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class CampaignScheduleManager {

	private $mapper;

	public function __construct() {

		$this->mapper = new Mappers\CampaignScheduleMapper();
	}

	public static function init() {

		$campaignScheduleManager = new CampaignScheduleManager();

		\Maven\Core\HookManager::instance()->addAction( 'maven/cart/newOrder', array( $campaignScheduleManager, 'registerNewOrder' ) );
	}

	/**
	 * 
	 * @param \MavenEngage\Core\Domain\CampaignSchedule $campaignSchedule
	 * @return \MavenEngage\Core\Domain\CampaignSchedule
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function addCampaignSchedule( \MavenEngage\Core\Domain\CampaignSchedule $campaignSchedule ) {

		$campaignScheduleToUpdate = new Domain\CampaignSchedule();

		if ( is_array( $campaignSchedule ) ) {
			\Maven\Core\FillerHelper::fillObject( $campaignScheduleToUpdate, $campaignSchedule );
		} else {
			$campaignScheduleToUpdate = $campaignSchedule;
		}

		$mapper = new Mappers\CampaignScheduleMapper();

		return $mapper->save( $campaignScheduleToUpdate );
	}

	public function registerNewOrder( \Maven\Core\Domain\Order $order ) {

		$campaignManager = new CampaignManager();

		//Get alla campaigns
		$campaigns = $campaignManager->getAll();

		foreach ( $campaigns as $campaign ) {
			$schedule = new Domain\CampaignSchedule();

			$schedule->setCampaignId( $campaign->getId() );
			$schedule->setOrderId( $order->getId() );
			$schedule->setCode( wp_create_nonce() );

			//Schedule every campaign
			$this->addCampaignSchedule( $schedule );
		}
	}

	/**
	 * 
	 * @param mixed $campaignScheduleId
	 * @return \MavenEngage\Core\Domain\CampaignSchedule
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function get( $campaignScheduleId ) {

		if ( ! $campaignScheduleId ) {
			throw new \Maven\Exceptions\MissingParameterException( "Id is required" );
		}

		$campaignSchedule = $this->mapper->get( $campaignScheduleId );

		return $campaignSchedule;
	}

	/**
	 * 
	 * @param int $orderId
	 * @param int $campaignId
	 * @return \MavenEngage\Core\Domain\CampaignSchedule
	 */
	public function getOrderSchedule( $orderId, $campaignId ) {

		$campaignSchedule = $this->mapper->getOrderSchedule( $orderId, $campaignId );

		return $campaignSchedule;
	}

	/**
	 * Get campaigns Schedule
	 * @return \MavenEngage\Core\Domain\CampaignSchedule[]
	 */
	public function getAll( $orderBy = "id", $orderType = 'asc', $start = "0", $limit = "1000" ) {

		return $this->mapper->getAll( $filter, $orderBy, $orderType, $start, $limit );
	}

	public function getCount() {

		return $this->mapper->getCount();
	}
	
	/**
	 * 
	 * @return Domain\CampaignSchedule[]
	 */
	public function getPendingSchedules(){
		return $this->mapper->getPendingSchedules();
	}

	public function delete( $campaignScheduleId ) {

		$this->mapper->delete( $campaignScheduleId );

		return true;
	}

}
