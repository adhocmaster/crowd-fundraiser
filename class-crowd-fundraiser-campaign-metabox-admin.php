<?php

/**
 * Metabox for campaign in backend
 *
 *
 * @link       https://github.com/adhocmaster/crowd-fundraiser
 * @since      1.0.0
 *
 * @package    Crowd_Fundraiser
 * @subpackage Crowd_Fundraiser/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Crowd_Fundraiser
 * @subpackage Crowd_Fundraiser/includes
 * @author     AdhocMaster <adhocmaster@live.com>
 */
class Crowd_Fundraiser_Campaign_Metabox_Admin {

	/**
	 * Initialize the class.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {


	}

	// public function add_metabox() {

	// 	add_meta_box(
	// 		CROWD_FUNDRAISER_SLUG,
	// 		__( 'Cause post ID', CROWD_FUNDRAISER_TEXT_DOMAIN ),
	// 		array( $this, 'render_metabox' ),
	// 		'car',
	// 		'advanced',
	// 		'default'
	// 	);

	// }

	/**
	 * id of the custom post type
	 *
	 * @since    1.0.0
	 * @access   public
	 */

	public function setup_metaboxes() {

		add_meta_box( 

			CROWD_FUNDRAISER_SLUG . '-cause', 
			__( 'Cause post ID', CROWD_FUNDRAISER_TEXT_DOMAIN ),
            array($this,'render_cause_meta_box'), 
            Crowd_Fundraiser_Campaign::POST_TYPE, 
            'normal', 
            'high' 

            );

        // add_meta_box( 'codedrops_meta_box', 'CodeDrops', 
        //         array($this,'render_codedrops_meta_box'), $this->post_type, 'side' );

        // remove_meta_box( 'slider_sectionid', $this->post_type, 'normal' );
        // remove_meta_box( 'layout_sectionid', $this->post_type, 'normal' );

	}

	/**
	 * render cause meta
	 *
	 * @since    1.0.0
	 * @access   public
	 */

	public function render_cause_meta_box() {


        wp_nonce_field( 'save_campaign', 'cause_nonce' ); // echo's nonce in the form
        
        echo $this->get_table();
        
        $this->add_editor();

	}

	/**
	 * get id of table
	 *
	 * @since    1.0.0
	 * @access   public
	 */

    function get_table () {

        global $post;
        
        $cause_id = get_post_meta( $post->ID, Crowd_Fundraiser_Campaign::META_NAME_CAUSE_ID, true );
        // other meta data
       
        $cause_id_label = __( 'Cause ID', CROWD_FUNDRAISER_TEXT_DOMAIN );
        // other labels
        
        $table = "
			<div>Put the post ID of the Cause here.</div>

			<table class='form-table'>
			
				<tr>
					<th scope='row'><label for='cause_id'>$cause_id_label</label></th>
				    <td><input type='text' value='$cause_id' disabled /></td>
				</tr>
		   
		    </table>";
		
		return $table;

	}

	/**
	 * add editor the field cause_id
	 *
	 * @since    1.0.0
	 * @access   public
	 */

	function add_editor () {

        global $post;
        
        $id = 'cause_id';
        $name = 'cause_id';
        $value = wp_kses_post( get_post_meta( $post->ID, Crowd_Fundraiser_Campaign::META_NAME_CAUSE_ID, true ) );
        
        echo "<div><input type='text' id='cause_id' name='cause_id' value='{$value}' /> </div>";

    }


}