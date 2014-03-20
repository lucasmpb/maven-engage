<?php

namespace MavenEngage\Core;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class CampaignManager {

	private $mapper;
	private $mailVariables = array( 'first_name', 'last_name', 'link' );

	/**
	 *
	 * @var \Maven\Core\Domain\Profile 
	 */
	private $currentProfile = null;
	private $currentLink = null;

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
	 * 
	 * @param \Maven\Core\Domain\Order $order
	 * @param \MavenEngage\Core\Domain\Campaign $campaign
	 * @param \MavenEngage\Core\Domain\CampaignSchedule $schedule
	 */
	public function sendCampaignEmail( \Maven\Core\Domain\Order $order, Domain\Campaign $campaign, Domain\CampaignSchedule $schedule = NULL ) {
		$campaignScheduleManager = new CampaignScheduleManager();
		$engageRegistry = \MavenEngage\Settings\EngageRegistry::instance();
		if ( ! $schedule ) {
			$schedule = $campaignScheduleManager->getOrderSchedule( $order->getId(), $campaign->getId() );
		}

		$email = false;
		$profile = false;
		if ( $order->hasUserInformation() && $order->getUser()->hasProfile() ) {
			$profile = $order->getUser()->getProfile();
			$email = $profile->getEmail();
		}
		//If we dont have an email, we look in the order contact
		if ( ! $email && $order->hasContactInformation() ) {
			$profile = $order->getContact();
			$email = $profile->getEmail();
		}
		//Check Billing Contact
		if ( ! $email && $order->hasBillingInformation() ) {
			$profile = $order->getBillingContact();
			$email = $profile->getEmail();
		}
		if ( ! $email && $order->hasShippingInformation() ) {
			$profile = $order->getShippingContact();
			$email = $profile->getEmail();
		}

		if ( $email ) {
			//Now we send the email
			$mavenSettings = \Maven\Settings\MavenRegistry::instance();

			$this->currentLink = $engageRegistry->getRecoverOrderUrl() . $schedule->getCode();
			$this->currentProfile = $profile;

			$this->addVariables( $this->mailVariables );

			$message = do_shortcode( $campaign->getBody() );

			$this->removeVariables( $this->mailVariables );

			$mail = \Maven\Mail\MailFactory::build();

			$mail->to( $email )
				->message( $message )
				->subject( $campaign->getSubject() )
				->fromAccount( $mavenSettings->getSenderEmail() )
				->fromMessage( $mavenSettings->getSenderName() )
				->send();

			//set the send_date
			$date = new MavenDateTime();
			$schedule->setSendDate( $date->mySqlFormatDateTime() );
			$campaignScheduleManager->addCampaignSchedule( $schedule );
		} else {
			//We dont have a valid email address, delete the schedule
			$campaignScheduleManager->delete( $schedule->getId() );
		}
	}

	function addVariables( $array ) {
		foreach ( ( array ) $array as $shortcode ) {
			add_shortcode( $shortcode, array( &$this, "processTemplate" ) );
		}
	}

	function removeVariables( $array ) {
		foreach ( ( array ) $array as $shortcode ) {
			remove_shortcode( $shortcode );
		}
	}

	function processTemplate( $atts, $content, $tag ) {

		$profile = $this->currentProfile;

		switch ( $tag ) {
			case "first_name":
				return $profile->getFirstName();
				break;
			case "last_name":
				return $profile->getLastName();
				break;
			case "link":
				return $this->currentLink;
				break;
			default:
				return "";
		}
	}

	//This function will...
	public function prepareAbandonedCartEmail() {

		$orderManager = new \Maven\Core\OrderManager();
		//get Pending schedules
		$campaignScheduleManager = new CampaignScheduleManager();

		$schedules = $campaignScheduleManager->getPendingSchedules();

		foreach ( $schedules as $schedule ) {
			//get campaign
			$campaign = $this->get( $schedule->getCampaignId() );

			if ( $campaign->isEnabled() ) {
				//get Order
				$order = $orderManager->get( $schedule->getOrderId() );

				//Check if we have some email address to use
				if ( ! $order->hasBillingInformation() &&
					! $order->hasShippingInformation() &&
					! $order->hasContactInformation() &&
					! $order->hasUserInformation() ) {
					//TODO: We dont have any contact information, delete the schedule
					$campaignScheduleManager->delete( $schedule->getId() );
				} else {

					//Get order last update
					$orderLastUpdate = new \Maven\Core\MavenDateTime( $order->getLastUpdate() );

					$interval = $campaign->getScheduleString();
					// Campaign Limit
					$today = MavenDateTime::getWPCurrentDateTime();

					$toDate = new \Maven\Core\MavenDateTime( $today );
					$toDate->subFromIntervalString( $interval );

					if ( $orderLastUpdate < $toDate ) {
						//Schedule time has passed, send email
						$this->sendCampaignEmail( $order, $campaign, $schedule );
					}
				}
			} else {
				//TODO: If disabled, maybe we should delete the schedule
				$campaignScheduleManager->delete( $schedule->getId() );
			}
		}
	}

}
