<?php

namespace MavenEngage\Admin\Controllers;

class Dashboard extends \MavenEngage\Admin\Controllers\EngageAdminController {

	public function __construct() {
		parent::__construct();
	}
   
	public function admin_init(){

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
	
	public function entryPoint(){
		
		$event = $this->getRequest()->getProperty( "event" );
		$data  = $this->getRequest()->getProperty( "data" );

		switch ( $event ) {

			case "update":

				break;
		}
	}
	

}
