<?php

namespace MavenEngage\Core\Domain;

// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) )
	exit;

class Campaign extends \Maven\Core\DomainObject {

	private $schedule_value;
	private $schedule_unit;
	private $subject;
	private $body;
	private $enabled;

	public function __construct( $id = false ) {

		parent::__construct( $id );

		$rules = array(
		    'schedule_value' => \Maven\Core\SanitizationRule::Integer,
		    'schedule_unit' => \Maven\Core\SanitizationRule::Text,
		    'subject' => \Maven\Core\SanitizationRule::Text,
		    'body' => \Maven\Core\SanitizationRule::TextWithHtml,
		    'enabled' => \Maven\Core\SanitizationRule::Boolean
		);

		$this->setSanitizationRules( $rules );
	}

	public function getSchedule_value() {
		return $this->schedule_value;
	}

	public function getSchedule_unit() {
		return $this->schedule_unit;
	}

	public function setSchedule_value( $schedule_value ) {
		$this->schedule_value = $schedule_value;
	}

	public function setSchedule_unit( $schedule_unit ) {
		$this->schedule_unit = $schedule_unit;
	}
	
	public function getScheduleString(){
		return "{$this->getSchedule_value()} {$this->getSchedule_unit()}";
	}

	public function getBody() {
		return $this->body;
	}

	public function setSchedule( $schedule ) {
		$this->schedule = $schedule;
	}

	public function setSubject( $subject ) {
		$this->subject = $subject;
	}

	public function setBody( $body ) {
		$this->body = $body;
	}
	
	public function setEnabled($enabled){
		if ( $enabled === 'false' || $enabled === false ) {
			$this->enabled = FALSE;
		} else {
			$this->enabled = $enabled;
		}
	}
	
	public function isEnabled(){
		return $this->enabled;
	}

}
