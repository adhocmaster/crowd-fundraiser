<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/adhocmaster/crowd-fundraiser
 * @since      1.0.0
 *
 * @package    Crowd_Fundraiser
 * @subpackage Crowd_Fundraiser/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Crowd_Fundraiser
 * @subpackage Crowd_Fundraiser/includes
 * @author     AdhocMaster <adhocmaster@live.com>
 */
class Crowd_Fundraiser_Activator {

	/**
	 * Performs tasks on plugin activation
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		static::install();

		add_option(CROWD_FUNDRAISER_VERSION_KEY, CROWD_FUNDRAISER_VERSION_NUM); //it does not update the options. So, we can do upgrade logic here

		// static::upgrade();

		Crowd_Fundraiser_Custom_Post::register();

		// Clear the permalinks after the post types has been registered
    	flush_rewrite_rules();

	}

	/**
	 * Installion, run only first time of plugin's lifetime.
	 *
	 * @since    1.0.0
	 * @access   private
	 */

	public static function install() {

		if( 0 !== get_option(CROWD_FUNDRAISER_VERSION_KEY, 0 )) {

			return;

		}

		global $wpdb;
		
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "
			CREATE TABLE {$wpdb->prefix}donor (
			) $charset_collate;
			
		
		";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );




	}

	/**
	 * Checks if plugin has newer version than previously installed, and upgrades if needed.
	 *
	 * @since    1.0.0
	 * @access   private
	 */

	public static function upgrade() {

		if (get_option(CROWD_FUNDRAISER_VERSION_KEY) != CROWD_FUNDRAISER_VERSION_NUM) {

		    // Execute your upgrade logic here

		    // Then update the version value
		    update_option(CROWD_FUNDRAISER_VERSION_KEY, CROWD_FUNDRAISER_VERSION_NUM);

		}	
	}

}
