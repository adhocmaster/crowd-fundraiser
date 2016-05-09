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
class Crowd_Fundraiser_Log {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function log($message) {

	    if (WP_DEBUG === true) {

	        if (is_array($message) || is_object($message)) {

	            error_log(print_r($message, true));

	        } else {

	            error_log($message);
	            
	        }
	    }

	}

	public static function echo_admin($error, $type = 'error') {

        if ( $error ) {
            echo "<div class='notice notice-{$type} is-dismissible'><p><b style='color:#00a0d2'>Crowd Fundraiser:</b> {$error}</p></div>";
        }

	}

}
