<?php

/* clean */

function clean($input = '', $mode = '')
{
	$output = $input;
	if (FILTER == 1)
	{
		/* filter untrusted users */

		if ($mode == 0)
		{
			$output = clean_special($output);
		}
		if ($mode == 1)
		{
			$output = clean_script($output);
			$output = clean_html($output);
		}
	}
	if ($mode == 2)
	{
		$output = clean_alias($output);
	}
	if ($mode == 3)
	{
		$output = clean_email($output);
	}
	if ($mode == 4)
	{
		$output = clean_url($output);
	}
	$output = clean_mysql($output);
	return $output;
}

/* clean special */

function clean_special($input = '')
{
	$output = preg_replace('/[^a-z0-9]/i', '', $input);
	return $output;
}

/* clean script */

function clean_script($input = '')
{
	$search_characters = explode(', ', b('script_characters'));
	$search = explode(', ', b('script_tags') . ', ' . b('script_handlers'));
	foreach ($search as $key => $value)
	{
		$replace[$key] = '[' . $value . ']';
		$search[$key] = '/' . $value . '/i';
	}
	$output = str_replace($search_characters, '', $input);
	$output = preg_replace($search, $replace, $output);
	return $output;
}

/* clean html */

function clean_html($input = '')
{
	$search = explode(', ', b('html_tags') . ', ' . b('html_attributes'));
	foreach ($search as $key => $value)
	{
		$replace[$key] = '[' . $value . ']';
		$search[$key] = '/' . $value . '/i';
	}
	$output = preg_replace($search, $replace, $input);
	return $output;
}

/* clean alias */

function clean_alias($input = '')
{
	$output = trim($input);
	$output = strtolower($output);
	$output = preg_replace('/[^a-z0-9_]/i', ' ', $output);
	$output = preg_replace('/\s+/i', '-', $output);
	return $output;
}

/* clean email */

function clean_email($input = '')
{
	$output = trim($input);
	$output = strtolower($output);
	$output = preg_replace('/[^@a-z0-9._-]/i', '', $input);
	return $output;
}

/* clean url */

function clean_url($input = '')
{
	$output = trim($input);
	$output = strtolower($output);
	$output = preg_replace('/www.(.*?)/i', '', $output);
	return $output;
}

/* clean mysql */

function clean_mysql($input = '')
{
	if (get_magic_quotes_gpc())
	{
		$input = stripslashes($input);
	}
	if (DB_CONNECTED == 1 && function_exists('mysql_real_escape_string'))
	{
		$output = mysql_real_escape_string($input);
	}
	else if (function_exists('mysql_escape_string'))
	{
		$output = mysql_escape_string($input);
	}
	return $output;
}
?>