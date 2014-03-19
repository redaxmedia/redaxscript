<?php

/**
 * check login
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Check
 * @author Henry Ruhs
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
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Check
 * @author Henry Ruhs
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
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Check
 * @author Henry Ruhs
 *
 * @param string $input
 * @return integer
 */

function check_email($input = '')
{
	if ($input == clean_email($input))
	{
		list($user, $host) = preg_split('/@/', $input);

		/* lookup domain name */

		$output = check_dns($host, 'mx');
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
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Check
 * @author Henry Ruhs
 *
 * @param string $input
 * @return integer
 */

function check_url($input = '')
{
	if ($input == clean_url($input))
	{
		list($protocol, $host) = preg_split('/\/\//', $input);

		/* empty host fallback */

		if ($host == '')
		{
			$host = $input;
		}

		/* lookup domain name */

		$output = check_dns($host, 'a');
	}
	else
	{
		$output = 0;
	}
	return $output;
}

/**
 * check dns
 *
 * @since 2.0.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Check
 * @author Henry Ruhs
 *
 * @param string $input
 * @param string $type
 * @return integer
 */

function check_dns($input = '', $type = '')
{
	if ($input)
	{
		if (function_exists('checkdnsrr') && checkdnsrr($input, $type) == '')
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
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Check
 * @author Henry Ruhs
 *
 * @param string $input
 * @param string $mode
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
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Check
 * @author Henry Ruhs
 *
 * @param string $task
 * @param integer $solution
 * @return integer
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