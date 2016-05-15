<?php


require_once CROWD_FUNDRAISER_PATH . 'includes/' . 'constants.php';

// Load must classes

function crowd_fundraiser_autoloader($class_name) {

	if( false !== strpos($class_name, 'Crowd_Fundraiser') || false !== strpos($class_name, 'Adhocmaster')   ) {

		$class_filename = 'class-' . strtolower(str_replace('_', '-', $class_name)) . '.php';

		if( false !== strpos($class_name, 'Admin') ) {

			require_once CROWD_FUNDRAISER_PATH . 'admin/' . $class_filename;

		} elseif ( false !== strpos($class_name, 'Public') ) {

			require_once CROWD_FUNDRAISER_PATH . 'public/' . $class_filename;

		} else {

			require_once CROWD_FUNDRAISER_PATH . 'includes/' . $class_filename;

		}

	} 

}

spl_autoload_register('crowd_fundraiser_autoloader');

/**
 * Must classes or add in Crowd_Fundraiser::load_dependencies
 */

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once CROWD_FUNDRAISER_PATH . 'includes/class-crowd-fundraiser.php';