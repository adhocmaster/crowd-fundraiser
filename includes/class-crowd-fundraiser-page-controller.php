<?php

/**
 * Pages required by the plugin.
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
class Crowd_Fundraiser_Page_Controller {


	/**
	 * setting name for payment page. By default a page is created which can be changed by user if needed.
	 *
	 * @since    1.0.0
	 */

	const PAMYMENT_PAGE_SETTING = 'cf_payment_method_page';

	protected $loader;

	private static $instance = null;

	private $public_errors = array();

	protected $nonce_name = 'Kudh__et3';

	protected $nonce_action = 'donor_info_form';

	/**
	 * query vars for payment page.
	 *
	 * @since    1.0.0
	 */
	private $query_vars = array( 'payment_method', 'cart_id', 'campaign_id', 'notification', 'thankyou' );


	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Crowd_Fundraiser_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */

	protected function __construct($hook_loader) {

		$this->loader = $hook_loader;

		$this->define_admin_hooks();
		$this->define_public_hooks();

	}


	public static function get_instance($hook_loader = null) {

		if( is_null(self::$instance) ) {

			self::$instance = new Crowd_Fundraiser_Page_Controller($hook_loader);

		}

		return self::$instance;

	}


	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		if(!is_admin()){

			return;

		}

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		// $campaign_controller = new Crowd_Fundraiser_Campaign_Controller($this->loader);


		// we will process all the data in template_redirect so that we can redirect if needed

		$this->loader->add_filter( 'template_redirect', $this, 'process_data' );

		$this->loader->add_filter( 'the_content', $this, 'render_pages' );

		$this->loader->add_filter( 'query_vars', $this, 'register_query_vars' );

	}

	/**
	 * Sets up pages used by different processes of the plugin. Called only on plugin activation
	 *
	 * @since    1.0.0
	 */
	public static function setup_pages() {

		$author_id = get_current_user_id();


        $post_id = -1;

        // Setup custom vars

        $slug = 'crowd-fundraiser-payment-process';
        $title = __('Payment Process
        	', CROWD_FUNDRAISER_TEXT_DOMAIN);

        // Check if page exists, if not create it
        if ( null == get_page_by_title( $title )) {

            $payment_method_page = array(

                    'comment_status'        => 'closed',
                    'ping_status'           => 'closed',
                    'post_author'           => $author_id,
                    'post_name'             => $slug,
                    'post_title'            => $title,
                    'post_status'           => 'publish',
                    'post_type'             => 'page'
            	);

            $post_id = wp_insert_post( $payment_method_page );


            if ( !$post_id ) {

                wp_die( __( 'Error creating necessary pages for Crowd Fundraiser', CROWD_FUNDRAISER_TEXT_DOMAIN ) );

            } else {

                update_post_meta( $post_id, Crowd_Fundraiser_Page_Controller::PAMYMENT_PAGE_SETTING, Crowd_Fundraiser_Page_Controller::PAMYMENT_PAGE_SETTING . '_template.php' );

                update_post_meta( $post_id, '_wp_page_template', 'default');

                //set options

                update_option(Crowd_Fundraiser_Page_Controller::PAMYMENT_PAGE_SETTING, $post_id); 

            }
        } // end check if

	}


	/**
	 * We can save page ids in wp_options. This function returns page permalink
	 *
	 * @since    1.0.0
	 */
	public function get_page_link_by_setting( $setting ) {


		$page_id = get_option( $setting, 0 );

		if ( $page_id < 1 ) {

			return false;

		}

		return get_page_link( $page_id );

	}


	/**
	 * We need to register query vars that we are adding to the links
	 *
	 * @since    1.0.0
	 */

	public function register_query_vars($vars) {

		//return $this->query_vars;

		$vars = array_merge($vars, $this->query_vars);

		// var_dump($vars);

		return $vars;

	}



	/**
	 * we will process all the data in template_redirect so that we can redirect if needed. Several hooks can be implemnented here
	 *
	 * @hook template_redirect
	 *
	 * @since    1.0.0
	 */
	public function process_data() {


		// apply_filter( 'process_data_redirect', '' );

		$post = get_post();


		switch($post->ID) {

			case get_option(Crowd_Fundraiser_Page_Controller::PAMYMENT_PAGE_SETTING, 0):

				/**
				* process_data hook for payment methods to process payment. If successfull redirect to thank you page
				*/

				do_action('c_f_process_data');
				
				$this->process_data_cart();

				break;

		}

	}

	/**
	 * Creates the cart with default data. Before payment method
	 *
	 * @since    1.0.0
	 */
	public function process_data_cart() {

		$campaign_id = get_query_var('campaign_id', 0);

		if( $campaign_id > 0 && isset( $_POST['donor_info_submitted'] ) ) {


			//verify nonce first

			$nonce = $_REQUEST[$this->nonce_name];

			if ( ! wp_verify_nonce( $nonce, $this->nonce_action ) ) {

			    // This nonce is not valid.
			    $this->public_errors[] = __( 'Security check failed', CROWD_FUNDRAISER_TEXT_DOMAIN ); 

			    return;

			} 

			//create a cart and redirect

			// var_dump($_POST);

			// exit();


			$address['d_billing_address'] = sanitize_text_field( $_POST['d_billing_address'] );
			$address['d_billing_city'] = sanitize_text_field( $_POST['d_billing_city'] );
			$address['d_billing_state'] = sanitize_text_field( $_POST['d_billing_state'] );
			$address['d_billing_country'] = sanitize_text_field( $_POST['d_billing_country'] );


			$guest_info['d_name'] = sanitize_text_field( $_POST['d_name'] );
			$guest_info['d_email'] = sanitize_email( $_POST['d_email'] );
			$guest_info['d_password'] = sanitize_text_field( $_POST['d_password'] );

			$user_id = get_current_user_id();

			$cart = new Adhocmaster_Cart();

			$cart->amount = number_format( floatval( $_POST['d_amount'] ), 2 ); // takes only two decimal points

			$cart->currency_code = sanitize_text_field( $_POST['d_currency'] );

			$cart->guest_info = serialize($guest_info);

			$cart->payer_id = $user_id;

			$cart->order_id = $campaign_id;

			$cart->address = serialize($address);

			$cart->message = sanitize_text_field( $_POST['d_message'] );

			// var_dump($cart);

			$cart_id = $cart->save();

			// die( var_dump($cart) );

			if( is_numeric( $cart_id ) ) {

				$payment_page_url = $this->get_page_link_by_setting( Crowd_Fundraiser_Page_Controller::PAMYMENT_PAGE_SETTING );

				setcookie( 'cart_id', $cart_id, 24 * 3600, '/' );

				wp_redirect( add_query_arg( 'cart_id', $cart_id, $payment_page_url ) );

			}

			$this->public_errors[] = __( 'Failed to create cart', CROWD_FUNDRAISER_TEXT_DOMAIN );

			return;

		}

		//process anything else, payment gateway calls or other things


	}

	/**
	 * Hooked into wordpress the_content for all pages needed. 
	 *
	 * @hook the_content
	 *
	 * @since    1.0.0
	 */
	public function render_pages($content) {

		$post = get_post();


		switch($post->ID) {

			case get_option(Crowd_Fundraiser_Page_Controller::PAMYMENT_PAGE_SETTING, 0):

				$content = $this->render_payment_method_page($content);

				break;

		}

		return $content;

	}

	public function render_payment_method_page($content) {


		$campaign_id = get_query_var('campaign_id', 0);

		$payment_method = get_query_var('payment_method', false);

		$cart_id = get_query_var('cart_id', 0);

		// var_dump($payment_method);

		// var_dump($campaign_id);

		// var_dump($cart_id);

		$nonce_name = $this->nonce_name;
		$nonce_action = $this->nonce_action;

		// order step 1

		if ( $campaign_id > 0  && ! isset( $_POST['donor_info_submitted'] ) ) {

			require_once CROWD_FUNDRAISER_PATH . 'public/partials/donor_info.php';

		} elseif ( 0 == $cart_id ) {

			$html = __( 'No cart chosen.', CROWD_FUNDRAISER_TEXT_DOMAIN );

		} elseif (false === $payment_method) {

			$cart = new Adhocmaster_Cart($cart_id);

			var_dump($cart);

			require_once(CROWD_FUNDRAISER_PATH . 'public/partials/payment_methods.php');

		} else {

			//payment method chosen. Display cart data and show payment button

			$cart = new Adhocmaster_Cart( $cart_id );

			$campaign = new Crowd_Fundraiser_Campaign( $cart->order_id );

			switch ( $payment_method ) {
				case 'paypal':

						$notification_url = $this->get_page_link_by_setting( Crowd_Fundraiser_Page_Controller::PAMYMENT_PAGE_SETTING );

						require_once( CROWD_FUNDRAISER_PATH . 'public/partials/paypal_confirmation.php' );

					break;
				
				default:
					# code...
					break;
			}



		}

		return $content . $html;

	}

}
