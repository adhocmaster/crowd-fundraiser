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


	const PAMYMENT_METHOD_SETTING = 'cf_payment_method_page';

	protected $loader;
	private static $instance = null;

	private $query_vars = array('payment_method');


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

                wp_die( 'Error creating necessary pages for Crowd Fundraiser' );

            } else {

                update_post_meta( $post_id, Crowd_Fundraiser_Page_Controller::PAMYMENT_METHOD_SETTING, Crowd_Fundraiser_Page_Controller::PAMYMENT_METHOD_SETTING . '_template.php' );

                update_post_meta( $post_id, '_wp_page_template', 'default');

                //set options

                update_option(Crowd_Fundraiser_Page_Controller::PAMYMENT_METHOD_SETTING, $post_id); 

            }
        } // end check if

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
	 * Hooked into wordpress the_content for all pages needed. 
	 *
	 * @since    1.0.0
	 */
	public function render_pages($content) {

		$post = get_post();


		switch($post->ID) {

			case get_option(Crowd_Fundraiser_Page_Controller::PAMYMENT_METHOD_SETTING, 0):

				$content = $this->render_payment_method_page($content);

				break;

		}

		return $content;

	}

	public function render_payment_method_page($content) {

		$payment_method = get_query_var('payment_method', false);

		var_dump($payment_method);

		$nonce_name = 'Kudh__et3';
		$nonce_action = 'donor_info_form';

		// order step 1

		if(false === $payment_method) {

			require_once(CROWD_FUNDRAISER_PATH . 'public/partials/payment_methods.php');

		} else if( ! isset( $_POST['donor_info_submitted'] ) ) {

			//$nonce = wp_create_nonce($nonce_action);

			require_once(CROWD_FUNDRAISER_PATH . 'public/partials/donor_info.php');

		} else {


			//verify nonce first

			$nonce = $_REQUEST[$nonce_name];

			if ( ! wp_verify_nonce( $nonce, $nonce_action ) ) {

			    // This nonce is not valid.
			    $html = 'Security check failed'; 

			} else {

			    // validate form data first

			}

		}

		return $content . $html;

	}

}
