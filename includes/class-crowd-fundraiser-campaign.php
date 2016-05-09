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
class Crowd_Fundraiser_Campaign {

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

}
