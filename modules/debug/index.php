<?php

/**
 * debug loader start
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function debug_loader_start()
{
	global $loader_modules_styles, $loader_modules_scripts;
	$loader_modules_styles[] = 'modules/debug/styles/debug.css';
	$loader_modules_scripts[] = 'modules/debug/scripts/startup.js';
	$loader_modules_scripts[] = 'modules/debug/scripts/debug.js';
}

/**
 * debug render start
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function debug_render_start()
{
	/* log error */

	if (function_exists('ini_set'))
	{
		error_reporting(-1);
		ini_set('log_errors', '1');
		ini_set('error_log', 'logs/error.log');
	}

	/* break center */

	if (FIRST_PARAMETER == 'debug')
	{
		define('CENTER_BREAK', 1);
		define('TITLE', l('debug', 'debug'));

		/* registry object */

		$registry = Redaxscript_Registry::getInstance();
		$registry->set('title', l('debug', 'debug'));
	}
}

/**
 * debug center start
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function debug_center_start()
{
	/* collect output */

	if (FIRST_PARAMETER == 'debug')
	{
		$error_log = file_get_contents('logs/error.log');
		file_put_contents('logs/error.log', '');

		/* if error log */

		if ($error_log)
		{
			$output = '<div class="box_note note_warning">' . break_up($error_log) . '</div>';
		}

		/* else handle error */

		else
		{
			$output = '<div class="box_note note_error">' . l('file_permission_grant') . l('colon') . ' logs/error.log' . l('point') . '</div>';
		}
		echo $output;
	}
}

/**
 * debug admin panel panel list modules
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 *
 * @return string
 */

function debug_admin_panel_list_modules()
{
	$output = '<li>' . anchor_element('internal', '', '', l('debug', 'debug'), 'debug') . '</li>';
	return $output;
}

/**
 * debug extras end
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function debug_extras_end()
{
	global $hooks;

	/* debug general */

	if (function_exists('memory_get_usage'))
	{
		$memory_usage = memory_get_usage();
		$debug['general']['memory_usage'] = round($memory_usage / 1024) . ' Kb';
	}
	$debug['general']['operating_system'] = php_uname('s');
	$debug['general']['server_software'] = $_SERVER['SERVER_SOFTWARE'];
	if (function_exists('phpversion'))
	{
		$php_version = phpversion();
		$debug['general']['php_version'] = substr($php_version, 0, strpos($php_version, '-'));
	}
	if (function_exists('mysql_get_server_info'))
	{
		$mysql_version = mysql_get_server_info();
		$debug['general']['mysql_version'] = substr($mysql_version, 0, strpos($mysql_version, '-'));
	}

	/* debug last error */

	if (function_exists('error_get_last'))
	{
		$debug['last_error'] = error_get_last();
	}

	/* debug disabled functions */

	if (function_exists('ini_get'))
	{
		$debug['disabled_functions'] = explode(',', ini_get('disable_functions'));
	}

	/* debug session */

	foreach ($_SESSION as $key => $value)
	{
		$key = str_replace(ROOT . '/', '', $key);
		$debug['session'][$key] = $value;
	}

	/* debug constants */

	$defined_constants = get_defined_constants(1);
	foreach ($defined_constants['user'] as $key => $value)
	{
		$debug['constants'][$key] = $value;
	}

	/* debug hooks */

	$debug['hooks'] = $hooks;

	/* debug file */

	$included_files = get_included_files();
	$document_root = $_SERVER['DOCUMENT_ROOT'];
	foreach ($included_files as $key => $value)
	{
		$debug['file'][$key] = str_replace($document_root, '', $value);
	}

	/* collect output */

	foreach ($debug as $key => $value)
	{
		if (count($value))
		{
			$output .= '<h3 class="title_extra title_debug">Debug: ' . $key . '</h3><div class="box_extra box_debug"><ul class="js_list_debug list_sidebar list_debug">';
			foreach ($value as $key_sub => $value_sub)
			{
				if ($value_sub)
				{
					$output .= '<li>'. $key_sub . ': <span>' . $value_sub . '</span>';
				}
				else
				{
					$output .= '<li class="js_item_debug item_debug_strike"><del>' . $key_sub . '</del>';
				}
				$output .= '</li>';
			}
			$output .= '</ul></div>';
		}
	}
	echo $output;
}
