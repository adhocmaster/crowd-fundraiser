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

		$labels = array(
			'name' => 'Campaign',
			'singular_name' => 'Campaign',
			'add_new' => 'Add campaign',
			'all_items' => 'All campaigns',
			'add_new_item' => 'Add campaign',
			'edit_item' => 'Edit campaign',
			'new_item' => 'New campaign',
			'view_item' => 'View campaign',
			'search_items' => 'Search campaigns',
			'not_found' => 'No campaigns found',
			'not_found_in_trash' => 'No campaigns found in trash',
			'parent_item_colon' => 'Parent campaign'
			//'menu_name' => default to 'name'
		);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'has_archive' => true,
			'publicly_queryable' => true,
			'query_var' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'supports' => array(
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
			'taxonomies' => array( 'category', 'post_tag' ), // add default post categories and tags
			'menu_position' => 5,
			'exclude_from_search' => false,
			'register_meta_box_cb' => 'campaign_add_post_type_metabox'
		);

		register_post_type( 'campaign', $args );

	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

}
