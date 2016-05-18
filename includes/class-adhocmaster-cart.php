<?php

/**
 * Cart model
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
class Adhocmaster_Cart extends Adhocmaster_Model{


	/**
	 * Define a unique custom post type here
	 *
	 * @since    1.0.0
	 */
	protected static $post_type = 'adhocmaster_cart';


	/**
	 * Map between cart model and post model
	 *
	 * @var array $map map from object properties to post table fields
	 *
	 * admin_id is set when cart made by an admin in lieu of another account or person. In case of a person who is not an user, 
	 * payer_id is 0. post name has a index, so, it's used. As usually index is not create for 3 or less charactersm we will save data as admin-ID
	 * amount is given in cents (*100 to actual ammount). No decimal numbers. Only integers
	 *
	 * @since    1.0.0
	 */

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
		'status'		=> 'post_status',
		'gateway'		=> 'post_mime_type'

	);

	protected static $required_fields = array(

		'order_id',
		'status',
		'amount',
		'currency_code'

	);

	/**
	 * create new object, optionally from database
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function __construct($post_id = 0) {

		parent::__construct( $post_id );

		if( $post_id > 0 ) {

			//load $this->wp_post and forget everything

			// $this->wp_post = get_post( $post_id, ARRAY_A );

			// $this->data = array( 'ID' => $post_id ); //pre loaded because will be used for sure


		} else {



			// $this->data = array( 'ID' => 0);

			// $this->wp_post = array();

			$this->status = 'payment_waiting';

			$this->admin_id = get_current_user_id();

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

		$validation_errors = $this->validate();

		if( is_wp_error( $validation_errors  ) ) {

			return $validation_errors;

		}

		return parent::save();

	}

	/**
	 * Validates data before save
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

	public function validate() {

		$core_validation = parent::validate();

		$errors = new WP_Error();

		if ( ! empty( $core_validation->errors ) ) {

			$error_fields = $core_validation->get_error_codes();

			foreach( $error_fields as $field ) {

				$errors->add( $field, sprintf( __( 'Invalid %s', Adhocmaster_Model::TEXT_DOMAIN ), $field ) );
				# code...
			}

		}

		if ( $this->ID < 1 ) {

			// print_r($this);

			if( ! isset( $this->data['payer_id'] ) ) {

				$this->payer_id = 0;

			}

			if( $this->payer_id < 1 && $this->admin_id < 1 ) {

				$errors->add( 'error', __( 'Guest checkout is disabled', Adhocmaster_Model::TEXT_DOMAIN ) );

			}



		}


		if ( ! empty( $errors->errors ) ) {

			return $errors;

		}

		return true;

	}


	/**
	 * accepts payment
	 * This is a API method for payment gateways. They call it after validating payments. this function does not validate payment. Payment gateway must validate their data first
	 ** 
	 * @api
	 * @return cart_id on success, WP_Error object on failure.
	 * @since    1.0.0
	 */

	public function accept_payment($amount, $gateway = 'offline', $currency_code) {

		// payment validation

		$errors = new WP_Error();

		if( $this->amount != $amount * 100 ) {

			$errors->add( 'amount', __( 'Amounts do not match', Adhocmaster_Model::TEXT_DOMAIN ) );

		}

		if( $this->currency_code != $currency_code ) {

			$errors->add( 'amount', __( 'Currency codes do not match', Adhocmaster_Model::TEXT_DOMAIN ) );

		}

		if( ! empty( $errors->errors ) ){

			return $errors;

		}

		$this->status = 'payment_accepted';

		$this->gateway = $gateway;

		return $this->save();


	}


}
