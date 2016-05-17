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
class Crowd_Fundraiser_Campaign_Controller {


	protected $loader;

	private static $instance = null;

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

			self::$instance = new Crowd_Fundraiser_Campaign_Controller($hook_loader);

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

		add_action( 'admin_notices', array($this,'show_admin_notice') );

		add_action( 'save_post', array($this,'save_post_meta'), 10, 3); // Do not change signature. Remove actions is called with same signature


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
		$this->loader->add_filter( 'the_content', $this, 'render_post' );



	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function register_custom_post() {

		$metabox = new Crowd_Fundraiser_Campaign_Metabox_Admin();

		$labels = array(
			'name'				=> __( 'Campaign', CROWD_FUNDRAISER_TEXT_DOMAIN ),
			'singular_name' 	=> __( 'Campaign', CROWD_FUNDRAISER_TEXT_DOMAIN ),
			'add_new' 			=> __( 'Add campaign', CROWD_FUNDRAISER_TEXT_DOMAIN ),
			'all_items' 		=> __( 'All campaigns', CROWD_FUNDRAISER_TEXT_DOMAIN ),
			'add_new_item'		=> __( 'Add campaign', CROWD_FUNDRAISER_TEXT_DOMAIN ),
			'edit_item' 		=> __( 'Edit campaign', CROWD_FUNDRAISER_TEXT_DOMAIN ),
			'new_item' 			=> __( 'New campaign', CROWD_FUNDRAISER_TEXT_DOMAIN ),
			'view_item' 		=> __( 'View campaign', CROWD_FUNDRAISER_TEXT_DOMAIN ),
			'search_items' 		=> __( 'Search campaigns', CROWD_FUNDRAISER_TEXT_DOMAIN ),
			'not_found' 		=> __( 'No campaigns found', CROWD_FUNDRAISER_TEXT_DOMAIN ),
			'not_found_in_trash' => __( 'No campaigns found in trash', CROWD_FUNDRAISER_TEXT_DOMAIN ),
			'parent_item_colon' => __( 'Parent campaign', CROWD_FUNDRAISER_TEXT_DOMAIN )
			//'menu_name' => __( default to 'name'
		);

		$args = array(
			'labels' 			=> $labels,
			'public' 			=> true,
			'has_archive' 		=> true,
			'publicly_queryable' => true,
			'query_var' 		=> true,
			'rewrite' 			=> array( 'slug' => 'campaign' ),
			'capability_type' 	=> 'post',
			'hierarchical' 		=> false,
			'supports' 			=> array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'author',
				//'trackbacks',
				'custom-fields',
				'comments',
				'revisions'
				//'page-attributes', // (menu order, hierarchical must be true to show Parent option)
				//'post-formats',
			),
			'taxonomies' 		=> array( 'category', 'post_tag' ), // add default post categories and tags
			'menu_position' 	=> 30,
			'menu_icon'     	=> 'dashicons-megaphone',
			'exclude_from_search' => false,
			'register_meta_box_cb' => array( $metabox, 'setup_metaboxes')
		);

		register_post_type( Crowd_Fundraiser_Campaign::CUSTOM_POST_TYPE, $args );

	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */


	public function save_post_meta( $post_id, $post, $update ) {

		// var_dump($post_id);

		// var_dump($post);

		// var_dump($_POST);

		// exit();
	    // If this isn't a 'campaign' post, don't update it.
	    if ( Crowd_Fundraiser_Campaign::CUSTOM_POST_TYPE != $post->post_type ) {
	        return;
	    }

	    // If this is just a revision, don't send the email.
		if ( wp_is_post_revision( $post_id ) )
			return;

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return;
        
        if ( ! isset( $_POST[ 'cause_nonce' ] ) || ! wp_verify_nonce( $_POST[ 'cause_nonce' ], 'save_campaign' ) )
            return;
        
        if ( ! current_user_can( 'edit_posts' ) )
            return;
	    // - Update the post's metadata.

	    delete_transient( 'cause_id_error' );

		$cause_id = (int) get_post_meta( $post->ID, Crowd_Fundraiser_Campaign::META_NAME_CAUSE_ID, true );

	    if ( isset( $_REQUEST['cause_id'] ) ) {

	    	$new_cause_id = (int) trim($_REQUEST['cause_id']);

			if( $cause_id > 0 && $cause_id != $new_cause_id ) {

				$error_message = __("The campaign already has a cause attached. It cannot be changed.", CROWD_FUNDRAISER_TEXT_DOMAIN);

				set_transient( 'cause_id_error', $error_message, 180 );

				return;

			}

			$cause_id = $new_cause_id;

	        update_post_meta( $post_id, Crowd_Fundraiser_Campaign::META_NAME_CAUSE_ID, sanitize_text_field( $cause_id ) );

	        // set parent\
	        remove_action( 'save_post', array($this,'save_post_meta'), 10, 3);

			// update the post, which calls save_post again
			wp_update_post( array( 'ID' => $post->ID, 'post_parent' => $cause_id) );


	    }

	    if( $cause_id < 1 ) {

			$error_message = sprintf( __('The campaign, %1$s, is not associated with any cause. 
										<a href="%2$s">Click here</a> and associate with a cause.', 
										CROWD_FUNDRAISER_TEXT_DOMAIN), 
										get_the_title($post), 
										get_edit_post_link($post) 
									);

			set_transient( 'cause_id_error', $error_message, 180 );

			return;


	    }

	}

	/**
	 * Shows admin notice
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

	public function show_admin_notice() {

        $notice = get_transient( 'cause_id_error' );

        //delete_transient( 'cause_id_error' );

        Crowd_Fundraiser_Log::echo_admin($notice);

	}

	public function get_payment_button($campaign_id) {

		// get payment page url first

		$payment_page_url = Crowd_Fundraiser_Page_Controller::get_instance()->get_page_link_by_setting( Crowd_Fundraiser_Page_Controller::PAMYMENT_PAGE_SETTING );

		if ( false === $payment_page_url ) {

			return __( 'payment page not configured', CROWD_FUNDRAISER_TEXT_DOMAIN );

		}

		require_once CROWD_FUNDRAISER_PATH . 'public/partials/payment_button.php';

		// var_dump($html);

		return $html;

	}

	/**
	 * Shows admin notice
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

	public function render_post($content) {

		$post = get_post();

		// var_dump($post);

		if ( $post->post_type == Crowd_Fundraiser_Campaign::CUSTOM_POST_TYPE ) {

			$content .= $this->get_payment_button( $post->ID );

		}

		return $content;

	}


}
