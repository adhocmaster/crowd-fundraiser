<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://github.com/adhocmaster/crowd-fundraiser
 * @since      1.0.0
 *
 * @package    Crowd_Fundraiser
 * @subpackage Crowd_Fundraiser/public/partials
 */

$html  = apply_filters( 'payment_methods', '');

$html .= "
			 <div class='payment-method'>
				<a href='". esc_url( add_query_arg('payment_method', 'paypal') ) ."'><img src='" . CROWD_FUNDRAISER_URL . "assets/paypal.jpg'></a>
			 </div>
		 ";
