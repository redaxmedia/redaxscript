<?php

/* clean alias */

function clean_alias($input = '')
{
	$output = trim(strtolower($input));
	$output = preg_replace('/[^a-z0-9_]/i', ' ', $output);
	$output = preg_replace('/\s+/i', '-', $output);
	return $output;
}

/* clean url */

function clean_url($input = '')
{
	$output = trim($input);
	$output = preg_replace('/www.(.*?)/i', '', $output);
	return $output;
}