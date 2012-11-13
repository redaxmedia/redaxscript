<?php

/**
 * check login
 *
 * @param string $input
 * @return integer
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
 * check access
 *
 * @param array $access
 * @param array $groups
 * @return integer
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
 * check email
 *
 * @param string $input
 * @return integer
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
 * check url
 *
 * @param string $input
 * @return integer
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
 * check protocol
 *
 * @param string $input
 * @return integer
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
 * check dns
 *
 * @param string $input
 * @return integer
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
 * check alias
 *
 * @param string $input
 * @param integer $mode
 * @return integer
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
 * check captcha
 *
 * @param string $task
 * @param integer $solution
 * @return integer
 */

function check_captcha($task = '', $solution = '')
{
	if (LOGGED_IN == TOKEN || s('captcha') == 0 || sha1($task) == $solution)
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