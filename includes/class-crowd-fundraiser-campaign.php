<?php

/**
 * Campaign management
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
class Crowd_Fundraiser_Campaign {


	/**
	 * id of the custom post type
	 *
	 * @since    1.0.0
	 * @access   public
	 */


	const CUSTOM_POST_TYPE = "campaign";
	const META_NAME_CAUSE_ID = "cause_id";


	public function __construct() {


	}

}
