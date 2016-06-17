<?php

/**
 * Cart management
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
class Crowd_Fundraiser_Cart_Controller {


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

			self::$instance = new Crowd_Fundraiser_Cart_Controller($hook_loader);

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

		// add_action( 'admin_notices', array($this,'show_admin_notice') );

		// add_action( 'save_post', array($this,'save_post_meta'), 10, 3); // Do not change signature. Remove actions is called with same signature


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		// $cart_controller = new Crowd_Fundraiser_Cart_Controller($this->loader);
		// $this->loader->add_filter( 'the_content', $this, 'render_post' );



	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function register_custom_post() {

		// $metabox = new Crowd_Fundraiser_Cart_Metabox_Admin();

		$labels = array(
			'name'				=> __( 'Cart', CROWD_FUNDRAISER_TEXT_DOMAIN ),
			'singular_name' 	=> __( 'Cart', CROWD_FUNDRAISER_TEXT_DOMAIN ),
			'add_new' 			=> __( 'Add cart', CROWD_FUNDRAISER_TEXT_DOMAIN ),
			'all_items' 		=> __( 'All carts', CROWD_FUNDRAISER_TEXT_DOMAIN ),
			'add_new_item'		=> __( 'Add cart', CROWD_FUNDRAISER_TEXT_DOMAIN ),
			'edit_item' 		=> __( 'Edit cart', CROWD_FUNDRAISER_TEXT_DOMAIN ),
			'new_item' 			=> __( 'New cart', CROWD_FUNDRAISER_TEXT_DOMAIN ),
			'view_item' 		=> __( 'View cart', CROWD_FUNDRAISER_TEXT_DOMAIN ),
			'search_items' 		=> __( 'Search carts', CROWD_FUNDRAISER_TEXT_DOMAIN ),
			'not_found' 		=> __( 'No carts found', CROWD_FUNDRAISER_TEXT_DOMAIN ),
			'not_found_in_trash' => __( 'No carts found in trash', CROWD_FUNDRAISER_TEXT_DOMAIN ),
			'parent_item_colon' => __( 'Parent cart', CROWD_FUNDRAISER_TEXT_DOMAIN )
			//'menu_name' => __( default to 'name'
		);

		$args = array(
			'labels' 			=> $labels,
			'public' 			=> true,
			'has_archive' 		=> true,
			'publicly_queryable' => true,
			'query_var' 		=> true,
			'rewrite' 			=> array( 'slug' => 'cart' ),
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
			'exclude_from_search' => false
		);

		register_post_type( Adhocmaster_Cart::POST_TYPE, $args );

	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */




}
