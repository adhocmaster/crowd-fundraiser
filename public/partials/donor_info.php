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

$country_options = '';

foreach ($countries as $key => $value) {

	$country_options .= "<option value='{$key}'>{$value}</option>";

}

$html="<div class='payment-method'>
			<form>

			  <h2>Your info:</h2>

			  <div class='form-group'>
			    <label for='d_name'></label>
			    <input type='text' class='form-control' id='d_name' placeholder='Full name' name='d_name'>
			  </div>
			  
			  <div class='form-group'>
			    <label for='d_email'>Email address</label>
			    <input type='email' class='form-control' id='d_email' placeholder='Email' name='d_email'>
			  </div>

			  <div class='form-group'>
			    <label for='d_password'>Password</label>
			    <input type='password' class='form-control' id='d_password' placeholder='Password' name='d_password'>
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
			    <input type='text' class='form-control' id='d_billing_state' placeholder='City' name='d_billing_state'>
			  </div>

			  <div class='form-group'>
			    <label for='d_billing_country'></label>
			    <select class='form-control' name='d_billing_country' id='d_billing_country'>
			    	
				  	<option value=''>Select</option>
			    	{$country_options}

				</select>
			  </div>

			  ". wp_nonce_field($nonce_action, $nonce_name)."

			  <button type='submit' class='button submit-button btn-submit btn btn-default'>Submit</button>
			</form>
		</div>";
