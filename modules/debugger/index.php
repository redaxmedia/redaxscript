<?php

/* debugger loader start */

function debugger_loader_start()
{
	global $loader_modules_styles, $loader_modules_scripts;
	$loader_modules_styles[] = 'modules/debugger/styles/debugger.css';
	$loader_modules_scripts[] = 'modules/debugger/scripts/startup.js';
	$loader_modules_scripts[] = 'modules/debugger/scripts/debugger.js';
}

/* debugger extras end */

function debugger_extras_end()
{
	global $hook;

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

	/* debug error */

	if (function_exists('error_get_last'))
	{
		$debug['error'] = error_get_last();
	}

	/* debug session */

	foreach ($_SESSION as $key => $value)
	{
		$key = str_replace(ROOT . '/', '', $key);
		$debug['session'][$key] = $value;
	}

	/* debug constant */

	$defined_constant = get_defined_constants(1);
	foreach ($defined_constant['user'] as $key => $value)
	{
		$debug['constant'][$key] = $value;
	}

	/* debug hook */

	$debug['hook'] = $hook;

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
			$output .= '<h3 class="title_extra title_debugger">Debug: ' . $key . '</h3><div class="box_extra box_debugger "><ul class="js_list_debugger list_debugger">';
			foreach ($value as $key_sub => $value_sub)
			{
				$output .= '<li>';
				if ($value_sub)
				{
					$output .= $key_sub . ': <span>' . $value_sub . '</span>';
				}
				else
				{
					$output .= '<del>' . $key_sub . '</del>';
				}
				$output .= '</li>';
			}
			$output .= '</ul></div>';
		}
	}
	echo $output;
}
?>