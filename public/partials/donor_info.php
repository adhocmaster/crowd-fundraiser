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


require_once( CROWD_FUNDRAISER_PATH . 'includes/country_codes.php' );

require_once( 'error.php' );

$country_options = '';

foreach ($countries as $key => $value) {

	$country_options .= "<option value='{$key}'>{$value}</option>";

}

$html =	$errorHtml .
		"<div class='payment-method'>
			<form method='post' action=''>

			  <h2>Payment Info:</h2>

			  <div class='form-group'>
			    <label for='d_amount'>Amount</label>
			    <input type='text' class='form-control' id='d_amount' placeholder='Number only' name='d_amount'>
			  </div>
			  <div class='form-group'>
			    <label for='d_currency'>Currency</label>
			    <select class='form-control' name='d_currency' id='d_currency'>
			    	
				  	<option value='USD'>USD</option>
				  	<option value='EUR'>Euro</option>

				</select>
			  </div>

			  <h2>Your info:</h2>

			  <div class='form-group'>
			    <label for='d_name'>Name</label>
			    <input type='text' class='form-control' id='d_name' placeholder='Full name' name='d_name'>
			  </div>
			  
			  <div class='form-group'>
			    <label for='d_email'>Email address</label>
			    <input type='email' class='form-control' id='d_email' placeholder='Email' name='d_email'>
			  </div>

			  <div class='form-group'>
			    <label for='d_password'>Password</label>
			    <input type='password' class='form-control' id='d_password' placeholder='Password' name='d_password'>
			    <p class='help-block'>Leave blank if you already have an account or don't want to create an account</p>
			  </div>

			  <h2>Billing Address:</h2>

			  <div class='form-group'>
			    <label for='d_billing_address'></label>
			    <input type='text' class='form-control' id='d_billing_address' placeholder='Address' name='d_billing_address'>
			  </div>

			  <div class='form-group'>
			    <label for='d_billing_city'></label>
			    <input type='text' class='form-control' id='d_billing_city' placeholder='City' name='d_billing_city'>
			  </div>

			  <div class='form-group'>
			    <label for='d_billing_state'></label>
			    <input type='text' class='form-control' id='d_billing_state' placeholder='State' name='d_billing_state'>
			  </div>

			  <div class='form-group'>
			    <label for='d_billing_country'></label>
			    <select class='form-control' name='d_billing_country' id='d_billing_country'>
			    	
				  	<option value=''>Select</option>
			    	{$country_options}

				</select>
			  </div>

			  <div class='form-group'>
			    <label for='d_message'>Message</label>
			    <textarea class='form-control' rows='3' id='d_message' placeholder='A nice but optional message' name='d_message'></textarea>
			  </div>

			  ". wp_nonce_field($nonce_action, $nonce_name)."

			  <input type='hidden' name='donor_info_submitted' value='submitted' />

			  <button type='submit' class='button submit-button btn-submit btn btn-default'>Submit</button>
			</form>
		</div>";
