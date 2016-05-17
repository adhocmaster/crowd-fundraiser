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

$errorHtml = '';
if( isset( $this->public_errors ) ) {

	foreach ($this->public_errors as $error) {

		$errorHtml .= "<div class='error'>$error</div>";
	}

}