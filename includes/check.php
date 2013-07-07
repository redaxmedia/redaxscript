<?php

/**
 * Check Login
 *
 * @since 1.2.1
 *
 * @category Check
 * @package Redaxscript
 * @author Henry Ruhs
 * 
 * @param $input string
 * @return $output integer
 */

function check_login($input = '')
{
	if (ctype_alnum($input) && strlen($input) > 4 && strlen($input) < 51)
	{
		$output = 1;
	}
	else
	{
		$output = 0;
	}
	return $output;
}

/**
 * Check Access
 *
 * @since 1.2.1
 *
 * @category Check
 * @package Redaxscript
 * @author Henry Ruhs
 * 
 * @param $access array
 * @param $groups array
 * @return $output integer
 */

function check_access($access = '', $groups = '')
{
	$access_array = explode(', ', $access);
	$groups_array = explode(', ', $groups);

	/* intersect access and groups */

	if ($access == 0 || in_array(1, $groups_array) || array_intersect($access_array, $groups_array))
	{
		$output = 1;
	}
	else
	{
		$output = 0;
	}
	return $output;
}

/**
 * Check Email
 *
 * @since 1.2.1
 *
 * @category Check
 * @package Redaxscript
 * @author Henry Ruhs
 * 
 * @param $input string
 * @return $output integer
 */

function check_email($input = '')
{
	if ($input == clean_email($input))
	{
		list($user, $host) = split('@', $input);

		/* lookup domain name */

		$output = check_dns($host);
	}
	else
	{
		$output = 0;
	}
	return $output;
}

/**
 * Check Url
 *
 * @since 1.2.1
 *
 * @category Check
 * @package Redaxscript
 * @author Henry Ruhs
 * 
 * @param $input string
 * @return $output integer
 */

function check_url($input = '')
{
	if ($input == clean_url($input))
	{
		list($protocol, $host) = split('//', $input);
		if ($host == '')
		{
			$host = $input;
		}

		/* lookup domain name */

		$output = check_dns($host);
	}
	else
	{
		$output = 0;
	}
	return $output;
}

/**
 * Check Protocol
 *
 * @since 1.2.1
 *
 * @category Check
 * @package Redaxscript
 * @author Henry Ruhs
 * 
 * @param $input string
 * @return $output integer
 */

function check_protocol($input = '')
{
	if (substr($input, 0, 7) == 'http://')
	{
		$output = 'http';
	}
	else if (substr($input, 0, 8) == 'https://')
	{
		$output = 'https';
	}
	return $output;
}

/**
 * Check Dns
 *
 * @since 1.2.1
 *
 * @category Check
 * @package Redaxscript
 * @author Henry Ruhs
 * 
 * @param $input string
 * @return $output integer
 */

function check_dns($input = '')
{
	if ($input)
	{
		if (function_exists('checkdnsrr') && checkdnsrr($input, 'mx') == '')
		{
			$output = 0;
		}
		else
		{
			$output = 1;
		}
	}
	else
	{
		$output = 0;
	}
	return $output;
}

/**
 * Check Alias
 *
 * @since 1.2.1
 *
 * @category Check
 * @package Redaxscript
 * @author Henry Ruhs
 * 
 * @param $input string
 * @param $mode integer
 * @return $output integer
 */

function check_alias($input = '', $mode = '')
{
	/* validate alias */

	if ($mode == 0)
	{
		if ($input != clean_alias($input) || is_numeric($input))
		{
			$output = 1;
		}
		else
		{
			$output = 0;
		}
	}

	/* check for default alias */

	else if ($mode == 1)
	{
		$default_alias = explode(', ', b('default_alias'));
		if (in_array($input, $default_alias))
		{
			$output = 1;
		}
		else
		{
			$output = 0;
		}
	}
	return $output;
}

/**
 * Check Captcha
 *
 * @since 1.2.1
 *
 * @category Check
 * @package Redaxscript
 * @author Henry Ruhs
 * 
 * @param $task string
 * @param $solution integer
 * @return $output integer
 */

function check_captcha($task = '', $solution = '')
{
	if (s('captcha') == 0 || sha1($task) == $solution)
	{
		$output = 1;
	}
	else
	{
		$output = 0;
	}
	return $output;
}
?>