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

// $campaign = 

$amount = $cart->get_amount();

$html =	
"<div>

	<table class='table .table-striped .table-condensed'>

		<tr>
			<td class='text-right'>
				". __( 'Amount', CROWD_FUNDRAISER_TEXT_DOMAIN ) ."
			</td>
			<td>
				{$amount}
			</td>
		</tr>

		<tr>
			<td class='text-right'>
				". __( 'Currency', CROWD_FUNDRAISER_TEXT_DOMAIN ) ."
			</td>
			<td>
				{$cart->currency_code}
			</td>
		</tr>

		<tr>
			<td class='text-right'>
				". _x( 'For', 'front-end', CROWD_FUNDRAISER_TEXT_DOMAIN ) ."
			</td>
			<td>
				{$campaign->title}
			</td>
		</tr>

	</table>

</div>";