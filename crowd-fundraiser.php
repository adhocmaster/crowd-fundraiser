<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/adhocmaster/crowd-fundraiser
 * @since             1.0.0
 * @package           Crowd_Fundraiser
 *
 * @wordpress-plugin
 * Plugin Name:       Crowd Fundraiser
 * Plugin URI:        https://github.com/adhocmaster/crowd-fundraiser
 * Description:       A well-supported plugin to raise funds in your Wordpress site. Requires php 5.3.+
 * Version:           1.0.0
 * Author:            AdhocMaster
 * Author URI:        https://adhocmaster.wordpress.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       crowd-fundraiser
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if (!defined('CROWD_FUNDRAISER_PATH'))
	define( 'CROWD_FUNDRAISER_PATH', plugin_dir_path( __FILE__ ) );

if (!defined('CROWD_FUNDRAISER_URL'))
	define( 'CROWD_FUNDRAISER_URL', plugin_dir_url( __FILE__ ) );

require_once plugin_dir_path( __FILE__ ) . 'includes/bootstrap.php';

register_activation_hook(__FILE__, array('Crowd_Fundraiser_Activator', 'activate'));

register_deactivation_hook( __FILE__, array('Crowd_Fundraiser_Deactivator', 'deactivate') );


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_crowd_fundraiser() {

	$plugin = new Crowd_Fundraiser();
	$plugin->run();

}
run_crowd_fundraiser();
