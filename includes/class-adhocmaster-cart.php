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
	 * Map between cart model and post model
	 *
	 * @var admin_id is set when cart made by an admin in lieu of another account or person. In case of a person who is not an user, 
	 * payer_id is 0. post name has a index, so, it's used. As usually index is not create for 3 or less charactersm we will save data as admin-ID
	 *
	 * @since    1.0.0
	 */

	protected static $post_type = 'adhocmaster_cart';

	protected static $map = array( 
		
		'order_id' 		=> 'post_parent',
		'amount' 		=> 'menu_order',
		'currency_code' => 'pinged',
		'message'		=> 'post_title',
		'txn_id'		=> 'post_password',
		'payer_id'		=> 'post_author',
		'admin_id'		=> 'post_name',
		'address'		=> 'post_content',
		'guest_info'	=> 'post_excerpt',
		'date_added'	=> 'post_date',
		'date_completed'=> 'post_modified',
		'status'		=> 'post_status'

		);

 	// used for saving and manipulating data> Lazily loaded when accessed from post data.

	protected $data;


	protected $wp_post;

	/**
	 * Magic function to access data from a post row
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function __get($name) {

		if( isset( $this->data[$name] ) ) {

			return $this->data[$name];

		}

		if( in_array( $name, self::$map ) ) {

			$this->data[$name] = $this->wp_post[self::$map[$name]];

			return $this->wp_post[self::$map[$name]];

		}

		$post_key = 'post_' . $name;

		if( array_key_exists( $post_key, $this->wp_post ) ) {

			$this->data[$name] = $this->wp_post[$post_key];

			return $this->wp_post[$post_key];

		}

		if ( array_key_exists( $name, $this->wp_post ) ) {

			$this->data[$name] = $this->wp_post[$name];

			return $this->wp_post[$name];

		}

		//search meta

		$this->data[$name] = get_post_meta( $this->ID, $name, true ); //true is not default value, it means singular value

		return $this->data[$name];

	}

	/**
	 * Magic function to access data from a post row
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function __set( $name, $val ) {

		$this->data[$name] = $val;

	}

	/**
	 * create new object, optionally from database
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function __construct($post_id = 0) {

		if( $post_id > 0 ) {

			//load $this->wp_post and forget everything

			$this->wp_post = get_post( $post_id, ARRAY_A );

			$this->data = array( 'ID' => $post_id ); //pre loaded because will be used for sure


		} else {



			$this->data = array( 'ID' => 0);

			$this->status = 'payment_waiting';

		}

	}

	/**
	 * Saves object data into database
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function save() {

		//validate data?

		// $reverse_map = array_flip( self::$map );

		//we save only mapped fields
		
		$post_arr = array();

		foreach ( $this->data as $key => $value ) {

			if( isset( self::$map[$key] ) ) {

				$post_arr[self::$map[$key]] = $value;

			}

		}

		$errorText = '';

		// if( $this->ID > 0 ) {

		// 	//update

		// 	$post_arr['ID'] = $this->ID;

		// 	$post_id = wp_update_post( $post_arr, true );		

		// 	if ( is_wp_error($post_id) ) {

		// 		$errors = $post_id->get_error_messages();

		// 		foreach ($errors as $error) {

		// 			$$errorText .= $error;

		// 		}

		// 		return $errorText;

		// 	}
						


		// } else {

		// 	//insert

		// }


		if( $this->ID > 0 ) {

			$post_arr['ID'] = $this->ID;

		} else {

			$post_arr['post_type'] = self::$post_type;

		}

		print_r($post_arr);

		$post_id = wp_insert_post( $post_arr, true );		

		if ( is_wp_error($post_id) ) {

			$errors = $post_id->get_error_messages();

			foreach ($errors as $error) {

				$$errorText .= $error;

			}

			return $errorText;

		}

		$this->ID =$post_id;

		$this->refresh_from_db();

		return $post_id;

	}

	public function refresh_from_db() {

		// print_r($this);

		if( $this->ID >0 ) {

			$this->wp_post = get_post( $this->ID, ARRAY_A);

			// print_r($this->wp_post);

		}

		$this->data = array();		

	}

	public function debug() {

		$debug = print_r($this->data, true);

		$debug .= print_r($this->wp_post, true);

		return $debug;

	}


}
