<?php

/**
 * Provide a public-facing view for the plugin
 *
 * expects campaign_id, payment_page_url
 *
 * @link       https://github.com/adhocmaster/crowd-fundraiser
 * @since      1.0.0
 *
 * @package    Crowd_Fundraiser
 * @subpackage Crowd_Fundraiser/public/partials
 */



$html = "<div class='payment-method'>
			<a href='". esc_url( add_query_arg( 'campaign_id', $campaign_id, $payment_page_url ) ) ."'>". __( 'Make a payment', CROWD_FUNDRAISER_TEXT_DOMAIN ) ."</a>
		</div>";
