<?php

/**
 * get parameter
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Get
 * @author Henry Ruhs
 *
 * @param string $input
 * @return string
 */

function get_parameter($input = '')
{
	static $parameter;

	/* get parameter */

	if ($parameter == '')
	{
		$parameter = explode('/', $_GET['p']);

		/* clean parameter */

		$parameter = array_map('clean_alias', $parameter);
		$parameter = array_map('clean_mysql', $parameter);
	}

	/* if admin parameter */

	if ($parameter[0] == 'admin')
	{
		$admin = 1;
	}

	/* switch parameter */

	switch (true)
	{
		case $input == 'first' && is_numeric($parameter[0]) == '':
			$output = $parameter[0];
			break;
		case $input == 'first_sub' && is_numeric($parameter[1]):
		case $input == 'second' && is_numeric($parameter[1]) == '':
		case $input == 'admin' && $admin == 1:
			$output = $parameter[1];
			break;
		case $input == 'second_sub' && is_numeric($parameter[2]):
		case $input == 'third' && is_numeric($parameter[2]) == '':
		case $input == 'table' && $admin == 1:
			$output = $parameter[2];
			break;
		case $input == 'third_sub' && is_numeric($parameter[3]):
		case $input == 'id' && $admin == 1 && is_numeric($parameter[3]):
		case $input == 'alias' && $admin == 1 && is_numeric($parameter[3]) == '':
			$output = $parameter[3];
			break;
		case $input == 'last' && is_numeric(end($parameter)):
			$output = prev($parameter);
			break;
		case $input == 'last' && is_numeric(end($parameter)) == '':
		case $input == 'last_sub' && is_numeric(end($parameter)):
		case $input == 'token' && end($parameter) == TOKEN:
			$output = end($parameter);
			break;
		default:
			$output = '';
			break;
	}
	return $output;
}

/**
 * get route
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Get
 * @author Henry Ruhs
 *
 * @param integer $mode
 * @return string
 */

function get_route($mode = '')
{
	/* switch admin parameter */

	switch (ADMIN_PARAMETER)
	{
		case 'up':
		case 'down':
		case 'publish':
		case 'unpublish':
		case 'enable':
		case 'disable':
		case 'install':
		case 'uninstall':
		case 'delete':
		case 'process':
			$output = 'admin/view/' . TABLE_PARAMETER;
			break;
		case 'update':
			$output = 'admin/edit/' . TABLE_PARAMETER;
			break;
		default:
			$parameter = explode('/', $_GET['p']);

			/* clean parameter */

			$parameter = array_map('clean_alias', $parameter);
			$parameter = array_map('clean_mysql', $parameter);

			/* mode one */

			if ($mode == 1)
			{
				$last_value = end($parameter);
				if (is_numeric($last_value))
				{
					$parameter_keys = array_keys($parameter);
					$last = end($parameter_keys);
					unset($parameter[$last]);
				}
			}
			$parameter_keys = array_keys($parameter);
			$last = end($parameter_keys);
			foreach ($parameter as $key => $value)
			{
				$output .= $value;
				if ($last != $key)
				{
					$output .= '/';
				}
			}
	}
	return $output;
}

/**
 * get file
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Get
 * @author Henry Ruhs
 *
 * @return string
 */

function get_file()
{
	$output = basename($_SERVER['SCRIPT_NAME']);
	return $output;
}

/**
 * get root
 *
 * @since 2.1.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Get
 * @author Henry Ruhs
 *
 * @return string
 */

function get_root()
{
	$protocol = $_SERVER['https'] ? 'https' : 'http';
	$host = $protocol . '://' . $_SERVER['HTTP_HOST'];
	$directory = dirname($_SERVER['SCRIPT_NAME']);
	$output = $directory == '/' || $directory == '\\' ? $host : $host . $directory;
	return $output;
}

/**
 * get user ip
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Get
 * @author Henry Ruhs
 *
 * @return string
 */

function get_user_ip()
{
	$output = $_SERVER['REMOTE_ADDR'];
	return $output;
}

/**
 * get user agent
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Get
 * @author Henry Ruhs
 *
 * @param integer $mode
 * @return string
 */

function get_user_agent($mode = '')
{
	/* switch mode */

	switch ($mode)
	{
		case 2:
			/* engines */

			$type_array = array(
				'gecko',
				'khtml',
				'presto',
				'trident',
				'webkit'
			);
			break;
		case 3:
			/* desktops */

			$type_array = array(
				'bsd',
				'linux',
				'macintosh',
				'solaris',
				'windows'
			);
			break;
		case 4:
			/* mobiles */

			$type_array = array(
				'mobile',
				'android',
				'blackberry',
				'ipod',
				'iphone',
				'palm'
			);
			break;
		case 5:
			/* tablets */

			$type_array = array(
				'tablet',
				'android',
				'ipad',
				'kindle',
				'xoom'
			);
			break;
		default:
			/* _browsers */

			$type_array = array(
				'chrome',
				'firefox',
				'konqueror',
				'msie',
				'netscape',
				'opera',
				'safari'
			);
			break;
	}
	$user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);

	/* collect output */

	foreach ($type_array as $value)
	{
		if (stristr($user_agent, $value))
		{
			/* get browser version */

			if ($mode == 1)
			{
				$output = floor(substr($user_agent, strpos($user_agent, $value) + strlen($value) + 1, 3));

				/* fallback */

				if ($output > 100)
				{
					$output = substr($output, 0, 1);
				}
			}

			/* else output string */

			else
			{
				$output = $value;
			}
		}
	}
	if ($output)
	{
		return $output;
	}
}

/**
 * get token
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Get
 * @author Henry Ruhs
 *
 * @return string
 */

function get_token()
{
	$a = session_id();
	$b = $_SERVER['REMOTE_ADDR'];
	$c = $_SERVER['HTTP_USER_AGENT'];
	$d = $_SERVER['HTTP_HOST'];
	$output = sha1($a . $b . $c . $d);
	return $output;
}