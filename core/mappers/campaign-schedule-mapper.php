<?php

namespace MavenEngage\Core\Mappers;

use MavenEngage\Core\EngageConfig;

class CampaignScheduleMapper extends \Maven\Core\Db\WordpressMapper {

	public function __construct() {

		parent::__construct( \MavenEngage\Core\EngageConfig::campaignScheduleTableName );
	}

	public function getAll( $orderBy = "id", $orderType = 'desc', $start = 0, $limit = 1000 ) {
		$where = '';
		$values = array();

		if ( ! $orderBy )
			$orderBy = 'id';

		$values[] = $start;
		$values[] = $limit;

		$query = "select	{$this->tableName}.*
					from {$this->tableName} 
					where 1=1 
					{$where} order by {$orderBy} {$orderType}
					LIMIT %d , %d;";

		$query = $this->prepare( $query, $values );

		$results = $this->getQuery( $query );

		$campaigns = array();

		foreach ( $results as $row ) {
			$campaign = new \MavenEngage\Core\Domain\Campaign();
			$this->fillObject( $campaign, $row );

			$campaigns[] = $campaign;
		}

		return $campaigns;
	}

	public function getCount() {

		$query = "select count(*)
				from {$this->tableName} 
				where 1=1";

		$query = $this->prepare( $query );

		return $this->getVar( $query );
	}

	/**
	 * Return a Campaign Schedule object
	 * @param int $id
	 * @return \MavenEngage\Core\Domain\CampaignSchedule
	 */
	public function get( $id ) {

		if ( ! $id || ! is_numeric( $id ) ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Id: is required' );
		}

		$campaignSchedule = new \MavenEngage\Core\Domain\CampaignSchedule();

		$row = $this->getRowById( $id );

		if ( ! $row ) {
			throw new \Maven\Exceptions\NotFoundException();
		}

		$this->fillObject( $campaignSchedule, $row );

		return $campaignSchedule;
	}

	
	/**
	 * 
	 * @param int $orderId
	 * @param int $campaingId
	 * @return \MavenEngage\Core\Domain\CampaignSchedule
	 * @throws \Maven\Exceptions\MissingParameterException
	 */
	public function getOrderSchedule( $orderId, $campaingId ) {
		if ( ! $orderId || ! is_numeric( $orderId ) ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Order Id: is required' );
		}
		if ( ! $campaingId || ! is_numeric( $campaingId ) ) {
			throw new \Maven\Exceptions\MissingParameterException( 'Campaign Id: is required' );
		}

		$query = "select	{$this->tableName}.*
					from {$this->tableName} 
					where order_id = %d AND campaign_id = %d";

		$query = $this->prepare( $query, array( $orderId, $campaingId ) );

		$row = $this->getQueryRow( $query );

		$campaignSchedule = new \MavenEngage\Core\Domain\CampaignSchedule();

		$this->fillObject( $campaignSchedule, $row );

		return $campaignSchedule;
	}

	/** Create or update the campaign schedule to the database
	 * 
	 * @param \MavenEngage\Core\Domain\CampaignSchedule $campaignSchedule
	 * @return \MavenEngage\Core\Domain\CampaignSchedule
	 */
	public function save( \MavenEngage\Core\Domain\CampaignSchedule $campaignSchedule ) {

		$campaignSchedule->sanitize();

		$data = array(
		    'order_id' => $campaignSchedule->getOrderId(),
		    'campaign_id' => $campaignSchedule->getCampaignId(),
		    'code' => $campaignSchedule->getCode(),
		    'send_date' => $campaignSchedule->getSendDate(),
		    'return_date' => $campaignSchedule->getReturnDate(),
		    'completed_date' => $campaignSchedule->getCompletedDate()
		);

		$format = array(
		    '%d', //order_id
		    '%d', //campaign_id
		    '%s', //code
		    '%s', //send_date
		    '%s', //return_date
		    '%s' //completed_date
		);

		if ( ! $campaignSchedule->getId() ) {
			try {

				$campaignScheduleId = $this->insert( $data, $format );
			} catch ( \Exception $ex ) {

				return \Maven\Core\Message\MessageManager::createErrorMessage( $ex->getMessage() );
			}

			$campaignSchedule->setId( $campaignScheduleId );
		} else {
			$this->updateById( $campaignSchedule->getId(), $data, $format );
		}

		return $campaignSchedule;
	}

	public function fill( $object, $row ) {
		$this->fillObject( $object, $row );
	}

}