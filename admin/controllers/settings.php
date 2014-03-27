<?php

namespace MavenEngage\Admin\Controllers;

class Settings extends \MavenEngage\Admin\Controllers\EngageAdminController {

	public function __construct() {
		parent::__construct();
	}

	public function admin_init() {
		
	}

	public function showForm() {

		$options = $this->getRegistry()->getOptions();

		$this->addJSONData( 'savedSettings', $options );

		$this->addJSONData( 'actions', $this->getActions() );

		$this->getOutput()->setTitle( "Settings" );

		$this->getOutput()->loadAdminView( "settings" );
	}

	private function getActions() {

		$savedActions = $this->getRegistry()->getActions();

		//Get the action class, and its methods
		$actionClass = new \ReflectionClass( '\MavenEngage\Core\Actions' );

		$methods = $actionClass->getMethods();
		$actions = array();

		foreach ( $methods as $method ) {


			$comment = $method->getDocComment();

			if ( $comment ) {

				// Check if it is an action
				preg_match_all( '/@action:?\s+(.*)/', $comment, $matches );

				if ( ! empty( $matches[ 1 ] ) ) {
					$actionName = $matches[ 1 ][ 0 ];

					if ( $actionName ) {

						//Get the description
						preg_match_all( '/@description:?\s+(.*)/', $comment, $matches );
						$description = "";

						if ( ! empty( $matches[ 1 ] ) )
							$description = $matches[ 1 ][ 0 ];

						//Get the description
						preg_match_all( '/@label:?\s+(.*)/', $comment, $matches );
						$label = "";

						if ( ! empty( $matches[ 1 ] ) )
							$label = $matches[ 1 ][ 0 ];


						if ( $label )
							$actions[] = array( 'action' => $method->name, 'name' => $label, 'description' => $description, 'enabled' => in_array( $method->name, $savedActions ) );
					}
				}
			}
		}


		return $actions;
	}

	public function entryPoint() {

		$event = $this->getRequest()->getProperty( "event" );
		$data = $this->getRequest()->getProperty( "data" );

		switch ( $event ) {

			case "update":

				$this->updateOption( $data );

				// We need to flush the re-write rulles in case that events Prefix or any other setting has change them

				delete_option( 'rewrite_rules' );

				$this->getOutput()->sendData( 'Success' );

				break;

			case "read":
				$options = $this->getRegistry()->getOptions();
				$this->getOutput()->sendData( $options );
				break;
			case "updateCollection":
				if ( is_array( $data ) ) {


					$this->updateOption( $data );

					$this->getOutput()->sendData( 'Success' );
				} else
					$this->getOutput()->sendError( 'Invalid collection' );
				break;
		}
	}

	public function updateOption( $optionToUpdate ) {

		if ( ! is_array( $optionToUpdate ) )
			return;

		// Get all the settings 
		$options = $this->getRegistry()->getOptions();

		$flushUrls = false;

		foreach ( $options as $option ) {
			//$properties = $optionToUpdate[ 'properties' ];
			//$values = array_flip($optionToUpdate[ 'properties' ]);

			if ( ! isset( $optionToUpdate[ $option->getId() ] ) )
				continue;

			$value = $optionToUpdate[ $option->getId() ];


			switch ( $option->getType() ) {
				case \Maven\Settings\OptionType::CheckBox:
					if ( $value === 'false' || $value === false || $value === '' ) {
						$option->setValue( FALSE );
					} else {
						$option->setValue( TRUE );
					}
					break;
				default:$option->setValue( $value );
					break;
			}
		}


		$this->getRegistry()->saveOptions( $options );

		if ( $flushUrls ) {
			//This is maybe the not best option to "flush" the urls, but it's the only I found without using any other hook
			delete_option( 'rewrite_rules' );
		}
	}

	public function showList() {
		
	}

}
