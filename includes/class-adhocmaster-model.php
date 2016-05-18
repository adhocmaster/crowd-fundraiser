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
 * A data entity which is saved in post table of wordpress. The entitiy should extend this class rather than copying.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Crowd_Fundraiser
 * @subpackage Crowd_Fundraiser/includes
 * @author     AdhocMaster <adhocmaster@live.com>
 */
class Adhocmaster_Model {


	const TEXT_DOMAIN = CROWD_FUNDRAISER_TEXT_DOMAIN;

	/**
	 * Define a unique custom post type here. Must be overriden for child classes
	 *
	 * @since    1.0.0
	 */

	protected static $post_type = 'adhocmaster_model';

	/**
	 * Map between cart model and post model. 
	 *
	 * Fields not in this map is save in post_meta table. Do not use 'post' prefix in data fields as it will try to pull from table. You may need to sanitize meta data. Must be overriden
	 * ID field is kept as convention.
	 * you can save unlimited fields without declaring the field names in the map. Those will be saved as post meta data. Make sure they don't have 'post_' prefix
	 *
	 * @since    1.0.0
	 */

	protected static $map = array( 
		
		// 'order_id' 		=> 'post_parent',
		// 'amount' 		=> 'menu_order',
		// 'currency_code' => 'pinged',
		// 'message'		=> 'post_title',
		// 'txn_id'		=> 'post_password',
		// 'payer_id'		=> 'post_author',
		// 'admin_id'		=> 'post_name',
		// 'address'		=> 'post_content',
		// 'guest_info'	=> 'post_excerpt',
		// 'date_added'	=> 'post_date',
		// 'date_completed'=> 'post_modified',
		// 'status'		=> 'post_status'

		);


	/**
	 *  Automated validation fields
	 *
	 * @since    1.0.0
	 */
	protected static $required_fields = array(


	);

	/**
	 *  used for saving and manipulating data> Lazily loaded when accessed from post data or meta data.
	 *
	 * @since    1.0.0
	 */

	protected $data;


	/**
	 *  original post array.
	 *
	 * @since    1.0.0
	 */


	protected $wp_post;

	/**
	 * Magic function to access data from a post row
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function __get($name) {

		// print_r('get called with: ' . $name);

		if( isset( $this->data[$name] ) ) {

			return $this->data[$name];

		}

		if( array_key_exists( $name, static::$map ) ) {

			$this->data[$name] = $this->wp_post[static::$map[$name]];

			return $this->data[$name];

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

		$this->set_meta( $name ); //true is not default value, it means singular value

		return $this->data[$name];

	}

	/**
	 * WP meta may not be enough.
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

	public function set_meta( $name ) {

		$meta = get_post_meta( $this->ID, $name );

		if( is_array( $meta ) && ! empty( $meta ) ) {

			if( sizeof( $meta ) == 1 ) {

				$this->data[$name] = $meta[0];


			} else {

				$this->data[$name] = $meta;

			}

		}

	}

	/**
	 * Magic function to access data from a post row
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function __set( $name, $val ) {

		// print_r($name);
		// print_r($val);

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

			// $this->wp_post = get_post( $post_id, ARRAY_A );

			// $this->data = array( 'ID' => $post_id ); //pre loaded because will be used for sure
			// echo "why post id is an array?";

			// print_r($post_id);

			// print_r($this);

			$this->ID = $post_id;

			// print_r($this);

			$this->refresh_from_db();


		} else {



			$this->data = array( 'ID' => 0);

			$this->wp_post = array();

			$this->status = 'payment_waiting';

		}

	}

	/**
	 * Saves object data into database. You should validate data before calling this function. 
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function save() {

		
		$post_arr = array();
		$meta_arr = array();

		foreach ( $this->data as $key => $value ) {

			if( isset( static::$map[$key] ) ) {

				$post_arr[static::$map[$key]] = $value;

			} else {

				// save in meta
				$meta_arr[$key] = $value;

			}

		}

		unset($meta_arr['ID']); // otherwise it will be saved as a meta data

		$errorText = '';


		if( $this->ID > 0 ) {

			$post_arr['ID'] = $this->ID;

		} else {


			$post_arr['post_type'] = static::$post_type; //dynamic binding for child classes

		}

		// print_r($post_arr);

		$post_id = wp_insert_post( $post_arr, true );		

		if ( is_wp_error($post_id) ) {

			// $errors = $post_id->get_error_messages();

			// foreach ($errors as $error) {

			// 	$$errorText .= $error;

			// }

			// return $errorText;

			return $post_id;

		} 

		// update meta data
		if( ! empty($meta_arr) ) {

			foreach ($meta_arr as $key => $value) {

				update_post_meta($post_id, $key, $value);

			}

		}

		$this->ID = $post_id;

		$this->refresh_from_db();

		return $post_id;

	}

	public function refresh_from_db() {

		// print_r($this);

		if( $this->ID > 0 ) {

			$this->wp_post = get_post( $this->ID, ARRAY_A );

			// print_r($this->wp_post);

			$this->data = array();	// this is a must to verify if all data is saved at all.

			// print_r( static::$map );

			foreach ( static::$map as $data_key => $wp_key ) {

				$this->data[$data_key] = $this->wp_post[$wp_key];

			}	

			// now load the meta data

			$all_meta = get_post_meta( $this->ID );

			// print_r($all_meta);

			if( is_array($all_meta) && ! empty( $all_meta ) ) {

				foreach( $all_meta as $key => $val ) {

					if( sizeof($val) == 1) {

						$this->data[$key] = $val[0];


					} else {

						$this->data[$key] = $val;

					}


				}

			}

			return true;

		}

		return false;

	}

	public function debug() {

		$debug = print_r($this->data, true);

		$debug .= print_r($this->wp_post, true);

		return $debug;

	}

	/**
	 *  Automated validation. 
	 *
	 * This function is not called from this class. It must be explicitly called from child class's validate function to keep things clear.
	 *
	 * @since    1.0.0
	 */
	public function validate() {

		$errors = new WP_Error();

		foreach ( static::$required_fields as $field) {

			if( ! isset( $this->data[$field] ) ) {

				$errors->add( $field, $field );

			}
		}

		return $errors;

	}


}
