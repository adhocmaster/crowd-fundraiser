<?php

/**
 * Registering custom posts
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
class Crowd_Fundraiser_Custom_Post {

	/**
	 * Function which registers all the custom posts needed.
	 *
	 * All the non-hidden custom posts are registered through Crowd_Fundraiser_Custom_Post function. 
	 * If you add a new custom post which needs to be shown in public add in the function.
	 *
	 * @since    1.0.0
	 */
	public static function register() {

		Crowd_Fundraiser_Campaign_Controller::register_custom_post();

		Crowd_Fundraiser_Cart_Controller::register_custom_post();


	}

}
