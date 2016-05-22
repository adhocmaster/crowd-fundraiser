<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/adhocmaster/crowd-fundraiser
 * @since      1.0.0
 *
 * @package    Crowd_Fundraiser
 * @subpackage Crowd_Fundraiser/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Crowd_Fundraiser
 * @subpackage Crowd_Fundraiser/admin
 * @author     AdhocMaster <adhocmaster@live.com>
 */
class Crowd_Fundraiser_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $crowd_fundraiser    The ID of this plugin.
	 */
	private $crowd_fundraiser;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	const TOP_MENU_SLUG = 'crowd-fundraiser';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $crowd_fundraiser       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $crowd_fundraiser, $version ) {

		$this->crowd_fundraiser = $crowd_fundraiser;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Crowd_Fundraiser_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Crowd_Fundraiser_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->crowd_fundraiser, plugin_dir_url( __FILE__ ) . 'css/crowd-fundraiser-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Crowd_Fundraiser_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Crowd_Fundraiser_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->crowd_fundraiser, plugin_dir_url( __FILE__ ) . 'js/crowd-fundraiser-admin.js', array( 'jquery' ), $this->version, false );

	}


	/**
	 * Menu for administrator
	 *
	 * @since    1.0.0
	 */
	public function menu() {

		$capability = 'manage_options';


		add_menu_page(  
						__( 'Crowd Fundraiser', CROWD_FUNDRAISER_TEXT_DOMAIN ), 
						__( 'Crowd Fundraiser', CROWD_FUNDRAISER_TEXT_DOMAIN ), 
						$capability, 
						self::TOP_MENU_SLUG, 
						array( $this, 'general_settings' ), 
						'dashicons-groups'
					);

		add_submenu_page( 
							self::TOP_MENU_SLUG, 
							__( 'Payment Settings', CROWD_FUNDRAISER_TEXT_DOMAIN ), 
							__( 'Payment Settings', CROWD_FUNDRAISER_TEXT_DOMAIN ), 
							$capability, 
							self::TOP_MENU_SLUG . '-payment-settings', 
							array( $this, 'payment_settings' ) 
						); 

	}


	/**
	 * Menu for administrator
	 *
	 * @since    1.0.0
	 */

	public function general_settings() {

		if ( ! current_user_can( 'manage_options' ) )
			wp_die( __( 'You do not have sufficient permissions to manage options for this site.' ) );

	    // Create a header in the default WordPress 'wrap' container
	    $html = '<div class="wrap">';
	        $html .= '<h2>General Settings</h2>';
	    $html .= '</div>';
	     
	    // Send the markup to the browser
	    echo $html;

	}


	/**
	 * Menu for administrator
	 *
	 * @since    1.0.0
	 */

	public function payment_settings() {

		if ( ! current_user_can( 'manage_options' ) )
			wp_die( __( 'You do not have sufficient permissions to manage options for this site.' ) );
 
		$payment_menu_slug = self::TOP_MENU_SLUG . '-payment-settings';

	    // Create a header in the default WordPress 'wrap' container
	    $html = '<div class="wrap">';
	        $html .= '<h2>Sandbox Plugin Options</h2>';
	        $html .= '<p class="description">There are currently no options. This is just for demo purposes.</p>';
	    $html .= '</div>';
	     
	    // Send the markup to the browser
	    echo $html;

		settings_errors();

		echo "<form method='post' action='options.php'>";

		settings_fields( $payment_menu_slug );
		do_settings_sections( $payment_menu_slug );
		submit_button();

		echo '</form>';
	    // do_settings_sections( self::TOP_MENU_SLUG . '-payment-settings' );

	}

	public function init_settings() {

		$payment_menu_slug = self::TOP_MENU_SLUG . '-payment-settings';

		// First, we register a section. This is necessary since all future options must belong to a 
	    add_settings_section(
	        'paypal_settings_section',         // ID used to identify this section and with which to register options
	        'Paypal Options',                  // Title to be displayed on the administration page
	        array( $this, 'paypal_settings_section' ) , // Callback used to render the description of the section
	        $payment_menu_slug  // Page on which to add this section of options
	    );


	    add_settings_field( 
	        'show_content',                     
	        'Content',              
	        array( $this, 'sandbox_toggle_content_callback' ),  
	        $payment_menu_slug,                          
	        'paypal_settings_section',         
	        array(                              
	            'Activate this setting to display the content.'
	        )
	    );


	    // Finally, we register the fields with WordPress
	     
	    register_setting(
	        $payment_menu_slug,
	        'show_content'
	    );

	}

	public function paypal_settings_section() {

    	echo '<p>Select which areas of content you wish to display.</p>';

	}

	public function sandbox_toggle_content_callback($args) {
 
	    $html = '<input type="checkbox" id="show_content" name="show_content" value="1" ' . checked(1, get_option('show_content'), false) . '/>'; 
	    $html .= '<label for="show_content"> '  . $args[0] . '</label>'; 
	     
	    echo $html;
	     
	} // end sandbox_toggle_content_callback

}
