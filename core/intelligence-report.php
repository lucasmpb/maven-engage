<?php

namespace MavenEngage\Core;


// Exit if accessed directly 
if ( ! defined( 'ABSPATH' ) ) exit;


class IntelligenceReport {

	public static function generateData( $data, $lastRun ){
		
		/*$table = new \Maven\Core\Domain\IntelligenceReport\Table();
		
		$table->setTitle( 'Event Cart Activity');
		
		$table->addColumn( "# of Carts" );
		$table->addColumn( "# of Carts Received" );
		$table->addColumn( "# of Carts Completed" );
		$table->addColumn( "# of Carts with Error" );
		
		$orderManager = new OrderManager();
		
		$countTotal = $orderManager->getCount('total');
		$countError = $orderManager->getCount('error');
		$countCompleted = $orderManager->getCount('completed');
		$countReceived = $orderManager->getCount('received');
		
		$table->addRow( array( $countTotal, $countReceived, $countCompleted, $countError) );
		
		
		$data[] = $table;
		
		
		$gGraph = new \Maven\Core\Domain\IntelligenceReport\GoogleGraph();
		$gGraph->setTitle( 'Sales' );
		$gGraph->setUrl("http://chart.googleapis.com/chart?chs=300x225&cht=p&chco=00A2FF|80C65A|FF0000&chd=t:{$countReceived},{$countCompleted},{$countError}&chdl=Received|Completed|Error");
		
		
		$data[] = $gGraph;
		
		return $data;*/
		
	}
}
