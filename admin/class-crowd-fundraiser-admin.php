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

}
