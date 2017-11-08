<?php

use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode,
    WP_CLI\Process;

$steps->Given( '/^a BP install$/',
	function ( $world ) {
		$world->install_wp();
		$dest_dir = $world->variables['RUN_DIR'] . '/wp-content/plugins/buddypress/';
		if ( ! is_dir( $dest_dir ) ) {
			mkdir( $dest_dir );
		}

		$bp_src_dir = getenv( 'BP_SRC_DIR' );
		try {
			$world->copy_dir( $bp_src_dir, $dest_dir );
			$world->proc( 'wp plugin activate buddypress' )->run_check();

			$components = array( 'friends', 'groups', 'xprofile', 'activity' );
			foreach ( $components as $component ) {
				$world->proc( "wp bp core activate $component" )->run_check();
			}
		} catch ( Exception $e ) {};
	}
);
