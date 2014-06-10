<?php

/**
 * clean
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Clean
 * @author Henry Ruhs
 *
 * @param string $input
 * @param integer $mode
 * @return string
 */

function clean($input = '', $mode = '')
{
	$output = $input;

	/* if untrusted user */

	if (FILTER == 1)
	{
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

	/* type related clean */

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

	/* mysql clean */

	$output = clean_mysql($output);
	return $output;
}

/**
 * clean special
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Clean
 * @author Henry Ruhs
 *
 * @param string $input
 * @return string
 */

function clean_special($input = '')
{
	$output = preg_replace('/[^a-z0-9]/i', '', $input);
	return $output;
}

/**
 * clean script
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Clean
 * @author Henry Ruhs
 *
 * @param string $input
 * @return string
 */

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

/**
 * clean html
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Clean
 * @author Henry Ruhs
 *
 * @param string $input
 * @return string
 */

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

/**
 * clean alias
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Clean
 * @author Henry Ruhs
 *
 * @param string $input
 * @return string
 */

function clean_alias($input = '')
{
	$output = trim(strtolower($input));
	$output = preg_replace('/[^a-z0-9_]/i', ' ', $output);
	$output = preg_replace('/\s+/i', '-', $output);
	return $output;
}

/**
 * clean email
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Clean
 * @author Henry Ruhs
 *
 * @param string $input
 * @return string
 */

function clean_email($input = '')
{
	$output = trim(strtolower($input));
	$output = preg_replace('/[^@a-z0-9._-]/i', '', $input);
	return $output;
}

/**
 * clean url
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Clean
 * @author Henry Ruhs
 *
 * @param string $input
 * @return string
 */

function clean_url($input = '')
{
	$output = trim($input);
	$output = preg_replace('/www.(.*?)/i', '', $output);
	return $output;
}

/**
 * clean mysql
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Clean
 * @author Henry Ruhs
 *
 * @param string $input
 * @return string
 */

function clean_mysql($input = '')
{
	if (get_magic_quotes_gpc())
	{
		$input = stripslashes($input);
	}

	/* mysql real escape */

	if (DB_CONNECTED == 1 && function_exists('mysql_real_escape_string'))
	{
		$output = mysql_real_escape_string($input);
	}

	/* mysql escape fallback */

	else if (function_exists('mysql_escape_string'))
	{
		$output = mysql_escape_string($input);
	}
	return $output;
}