<?php


/**
 * Description of maven-validation
 *
 * @author mustela
 */
class MavenValidation {

	public function __construct() {
		;
	}
	
	public static function isMavenMissing(){
		
		$result = class_exists('\Maven\Settings\MavenRegistry');
		
		// If the common plugin isn't activate, lets add a default option.
		if ( ! $result )
			self::addMenu();
			
		return ! $result;
	}
	
	public static function addMenu(){
		add_action('admin_menu','\MavenValidation::init');
		
	}
	
	public static function init(){
		add_menu_page('Maven', 'Maven', 'manage_options', 'maven', '\MavenValidation::showHelp' );
		
	}
	

	public static function showHelp() {
		?>

		<div class="wrap">
			<div id="icon-index" class="icon32"><br></div><h2>Maven</h2>

			<div id="welcome-panel" class="welcome-panel">
				<div class="welcome-panel-content">
					<h3>Welcome to Maven!</h3>
					<p class="about-description">Remember, you need to have Maven plugin, in order to have Maven Engage</p>
<!--					<div class="welcome-panel-column-container">
						<div class="welcome-panel-column">
							<h4>Download / Activate Maven</h4>
							<a class="button button-primary button-hero hide-if-customize" href="">Install Maven</a>
						</div>
						
					</div>-->
				</div>
			</div>

			

		</div>

		<?php
	}

}
 
