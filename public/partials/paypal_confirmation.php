<?php

/**
 * Provide a public-facing view for the plugin
 *
 * expects $cart and $campaign
 *
 * @link       https://github.com/adhocmaster/crowd-fundraiser
 * @since      1.0.0
 *
 * @package    Crowd_Fundraiser
 * @subpackage Crowd_Fundraiser/public/partials
 */

require_once "cart_confirmation.php";

$paypal_button = Adhocmaster_Paypal::get_form_classic( $cart, $notification_url );

if ( is_wp_error( $paypal_button ) ) {

	$html .= "<div class='error'> ". $paypal_button->get_error_message() ."</div>";

} else {

	$html .= $paypal_button;

}