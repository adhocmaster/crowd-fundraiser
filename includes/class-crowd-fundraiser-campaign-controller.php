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


	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Crowd_Fundraiser_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	public function __construct($hook_loader) {

		$this->loader = $hook_loader;

		$this->define_admin_hooks();
		$this->define_public_hooks();

	}



	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {


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
			'menu_icon'     	=> 'dashicons-id',
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

}
