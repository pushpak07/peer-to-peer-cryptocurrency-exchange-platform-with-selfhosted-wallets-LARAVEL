<?php

// Alert...
HTML::macro('alert', function ($message = '', $type = 'primary', $icon = '<i class="la la-info-circle"></i>') {
	$alert = '';
	$alert .= "<div class='alert alert-$type alert-icon-left alert-dismissible mb-2'>";

	$alert .= '<span class="alert-icon">';
	$alert .= "<i class='" . alert_icon($type) . "'></i>";
	$alert .= '</span>';

	$alert .= "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>";
	$alert .= "<span aria-hidden='true'>×</span>";
	$alert .= "</button>";

	$alert .= "<span>$message</span>";

	$alert .= "</div>";

	return $alert;
});
