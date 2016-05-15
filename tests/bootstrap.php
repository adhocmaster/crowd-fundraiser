<?php
/**
 * PHPUnit bootstrap file
 *
 * @package crowd-fundraiser
 */


$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	
	if(file_exists('/tmp/wordpress-tests-lib')) {
		
		$_tests_dir = '/tmp/wordpress-tests-lib';

	} else {

		$_tests_dir = 'G:\wamp\www\wp452';

	}
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	require dirname( dirname( __FILE__ ) ) . '/crowd-fundraiser.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
