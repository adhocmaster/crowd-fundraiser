<?php

/**
 * Blank Class
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
class Adhocmaster_Cart {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public $ID = 0;


	/**
	 * Map between cart model and post model
	 *
	 * @var admin_id is set when cart made by an admin in lieu of another account or person. In case of a person who is not an user, 
	 * payer_id is 0. post name has a index, so, it's used. As usually index is not create for 3 or less charactersm we will save data as admin-ID
	 *
	 * @since    1.0.0
	 */

	protected static $map = array( 
		
		'order_id' 		=> 'post_parent',
		'amount' 		=> 'menu_order',
		'currency_code' => 'ping_status',
		'message'		=> 'post_title',
		'txn_id'		=> 'post_password',
		'payer_id'		=> 'post_author',
		'admin_id'		=> 'post_name',
		'address'		=> 'post_content',
		'guest_info'	=> 'post_excerpt',
		'date_added'	=> 'post_date',
		'date_completed'=> 'post_modified'

		);

 	// used for saving and manipulating data> Lazily loaded when accessed from post data.

	protected $data;

	// public $amount;

	// public $currency_code;

	// public $status;

	// public $date;

	protected $wp_post;

	/**
	 * Magic function to access data from a post row
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function __get($name) {

		if(isset($this->data[$name])) {

			return $this->data[$name];

		}

		if(in_array($name, $map)) {

			$this->data[$name] = $wp_post[$map[$name]];

			return $wp_post[$map[$name]];

		}

		$post_key = 'post_' . $name;

		if( array_key_exists( $post_key, $wp_post ) ) {

			$this->data[$name] = $wp_post[$post_key];

			return $wp_post[$post_key];

		}

		if ( array_key_exists( $name, $wp_post ) ) {

			$this->data[$name] = $wp_post[$name];

			return $wp_post[$name];

		}

		//search meta

		$this->data[$name] = get_post_meta( $this->ID, $name, true ); //true is not default value, it means singular value

		return $this->data[$name];

	}

	public function __set( $name, $val ) {

		$this->$name = $val; // is this okay?

	}

	public function __construct($post_id = 0) {

		if( $post_id >0 ) {

			//load $wp_post and forget everything

			$this->wp_post = get_post( $post_id, ARRAY_A );

			$this->data = array( 'ID' => $post_id ); //pre loaded because will be used for sure


		} else {

			$this->data = array( 'ID' => 0);

		}

	}

	public function save() {

		//validate data?

		$reverse_map = array_flip( self::$map );

		if( $this->ID > 0 ) {

			//update
			



		} else {

			//insert

		}

	}


}
