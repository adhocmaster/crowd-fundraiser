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
            Crowd_Fundraiser_Campaign::CUSTOM_POST_TYPE, 
            'normal', 
            'high' 

            );

        // add_meta_box( 'codedrops_meta_box', 'CodeDrops', 
        //         array($this,'render_codedrops_meta_box'), $this->post_type, 'side' );

        // remove_meta_box( 'slider_sectionid', $this->post_type, 'normal' );
        // remove_meta_box( 'layout_sectionid', $this->post_type, 'normal' );

	}


	public function render_cause_meta_box() {


        wp_nonce_field( 'save_campaign', 'cause_nonce' ); // echo's nonce in the form
        
        echo $this->get_table();
        
        $this->add_editor();

	}

    function get_table () {

        global $post;
        
        $campaign_id = get_post_meta( $post->ID, Crowd_Fundraiser_Campaign::META_NAME_CAUSE_ID, true );
        // other meta data
       
        $campaign_id_label = __( 'Campaign ID', CROWD_FUNDRAISER_TEXT_DOMAIN );
        // other labels
        
        $table = "
			<div>see_help_message</div>

			<table class='form-table'>
			<tr>
			<th scope='row'><label for='campaign_id'>$campaign_id_label</label></th>
			    <td><input type='text' name='campaign_id' id='campaign_id' value='$campaign_id' /></td>
			</tr>
		    
		        // html elements of other metadata fields

		    </table>";
		
		return $table;

	}

	function add_editor () {

        global $post;
        
        $id = 'locker_text';
        $name = 'locker_text';
        $value = wp_kses_post( get_post_meta( $post->ID, Crowd_Fundraiser_Campaign::META_NAME_CAUSE_ID, true ) );
        
        $editor_options = array(
                'textarea_name' => $name,
                'textarea_rows' => 10,
                'tinymce' => array(
                        'toolbar1' => 'bold italic underline | alignleft aligncenter alignright | outdent indent | undo redo |  fullscreen',
                        'toolbar2' => 'formatselect fontselect fontsizeselect | forecolor backcolor | removeformat'
                )
        );
        wp_editor( $value, $id, $editor_options );

    }


}