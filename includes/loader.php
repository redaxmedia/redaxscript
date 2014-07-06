<?php

/**
 * loader
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Loader
 * @author Henry Ruhs
 *
 * @param string $type
 * @param string $mode
 * @return string
 */

function loader($type = '', $mode = '')
{
	hook(__FUNCTION__ . '_start');

	if ($mode == 'inline' || $mode == 'outline')
	{
		/* get module scripts */

		if ($type == 'scripts')
		{
			global $loader_modules_scripts;
			$loader_modules = $loader_modules_scripts;
			if ($mode == 'outline')
			{
				header('content-type: text/javascript');
			}
		}

		/* get module styles */

		else if ($type == 'styles')
		{
			global $loader_modules_styles;
			$loader_modules = $loader_modules_styles;
			if ($mode == 'outline')
			{
				header('content-type: text/css');
			}
		}
		if ($mode == 'outline')
		{
			header('expires: ' . GMDATE_PLUS_WEEK);
		}
	}

	/* parse loader ini */

	$loader_ini = parse_ini_file('templates/' . TEMPLATE . '/' . $type . '/.loader', 1);

	/* inherit from other templates */

	$loader_inherit = $loader_ini['inherit'];
	if ($loader_inherit)
	{
		foreach ($loader_inherit as $key => $template)
		{
			$loader_inherit_ini = parse_ini_file('templates/' . $template . '/' . $type . '/.loader', 1);
			$loader_ini = array_merge_recursive($loader_inherit_ini, $loader_ini);
		}
	}
	else
	{
		$template = TEMPLATE;
	}

	/* startup mode */

	if ($mode == 'startup')
	{
		$loader_template_startup = $loader_ini['template_startup'];
		if (LOGGED_IN == TOKEN)
		{
			$loader_admin_startup = $loader_ini['admin_startup'];
		}
	}

	/* else general mode */

	else
	{
		$loader_template = $loader_ini['template'];
		$loader_browser = $loader_ini[MY_BROWSER];
		$loader_engine = $loader_ini[MY_ENGINE];

		/* logged in */

		if (LOGGED_IN == TOKEN)
		{
			$loader_admin = $loader_ini['admin'];
			$loader_admin_browser = $loader_ini['admin_' . MY_BROWSER];
			$loader_admin_engine = $loader_ini['admin_' . MY_ENGINE];
		}
		$loader_rewrite = $loader_ini['settings']['rewrite'];
	}
	$loader_minify = $loader_ini['settings']['minify'];

	/* merge loader include as needed */

	$loader_include = array();
	if ($loader_template_startup)
	{
		$loader_include = array_merge($loader_include, $loader_template_startup);
	}
	if ($loader_admin_startup)
	{
		$loader_include = array_merge($loader_include, $loader_admin_startup);
	}
	if ($loader_modules)
	{
		$loader_include = array_merge($loader_include, $loader_modules);
	}
	if ($loader_template)
	{
		$loader_include = array_merge($loader_include, $loader_template);
	}
	if ($loader_browser)
	{
		$loader_include = array_merge($loader_include, $loader_browser);
	}
	if ($loader_engine)
	{
		$loader_include = array_merge($loader_include, $loader_engine);
	}
	if ($loader_admin)
	{
		$loader_include = array_merge($loader_include, $loader_admin);
	}
	if ($loader_admin_browser)
	{
		$loader_include = array_merge($loader_include, $loader_admin_browser);
	}
	if ($loader_admin_engine)
	{
		$loader_include = array_merge($loader_include, $loader_admin_engine);
	}

	/* collect transport start */

	ob_start();
	if ($mode == 'inline' || $mode == 'outline')
	{
		hook(__FUNCTION__ . '_' . $type . '_transport_start');
	}

	/* collect include output */

	if ($loader_include)
	{
		$loader_include_keys = array_keys($loader_include);
		$last = end($loader_include_keys);
		foreach ($loader_include as $key => $value)
		{
			include_once($value);
		}
	}

	/* collect transport end */

	if ($mode == 'inline' || $mode == 'outline')
	{
		hook(__FUNCTION__ . '_' . $type . '_transport_end');
	}
	$output .= ob_get_clean() . PHP_EOL;

	/* rewrite path */

	if ($loader_rewrite)
	{
		$output = preg_replace('/templates\/'. $template . '/', ROOT . '/templates/' . $template, $output);
		if (LOGGED_IN == TOKEN)
		{
			$output = preg_replace('/templates\/admin/', ROOT . '/templates/admin', $output);
		}
		$output = preg_replace('/modules/', ROOT . '/modules', $output);
	}

	/* minify output */

	if ($loader_minify)
	{
		$output = minify($type, $output);
	}
	$output .= hook(__FUNCTION__ . '_end');
	return $output;
}

/**
 * styles
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Loader
 * @author Henry Ruhs
 */

function styles()
{
	hook(__FUNCTION__ . '_start');

	/* parse loader ini */

	$loader_ini = parse_ini_file('templates/' . TEMPLATE . '/styles/.loader', 1);

	/* inherit from other templates */

	$loader_inherit = $loader_ini['inherit'];
	if ($loader_inherit)
	{
		foreach ($loader_inherit as $key => $template)
		{
			$loader_inherit_ini = parse_ini_file('templates/' . $template . '/styles/.loader', 1);
			$loader_ini = array_merge_recursive($loader_inherit_ini, $loader_ini);
		}
	}
	$loader_single = $loader_ini['single'];

	/* logged in */

	if (LOGGED_IN == TOKEN)
	{
		$loader_admin_single = $loader_ini['admin_single'];
	}
	$loader_deploy = $loader_ini['settings']['deploy'];

	/* merge loader include as needed */

	$loader_include = array();
	if ($loader_single)
	{
		$loader_include = array_merge($loader_include, $loader_single);
	}
	if ($loader_admin_single)
	{
		$loader_include = array_merge($loader_include, $loader_admin_single);
	}

	/* collect output */

	if ($loader_include)
	{
		foreach ($loader_include as $value)
		{
			$output .= '<link type="text/css" href="' . $value . '" media="all" rel="stylesheet" />' . PHP_EOL;
		}
	}

	/* type of deployment */

	if ($loader_deploy == 'inline')
	{
		$output .= '<style media="all"><!-- /* <![cdata[ */ ' . loader('styles', 'inline') . ' /* ]]> */ --></style>' . PHP_EOL;
	}
	else
	{
		$output .= '<link type="text/css" href="' . REWRITE_ROUTE . 'loader/styles" media="all" rel="stylesheet" />' . PHP_EOL;
	}
	echo $output;
	hook(__FUNCTION__ . '_end');
}

/**
 * scripts
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Loader
 * @author Henry Ruhs
 *
 * @param string $mode
 */

function scripts($mode = '')
{
	if ($mode == '')
	{
		hook(__FUNCTION__ . '_start');
	}

	/* parse loader ini */

	$loader_ini = parse_ini_file('templates/' . TEMPLATE . '/scripts/.loader', 1);

	/* inherit from other templates */

	$loader_inherit = $loader_ini['inherit'];
	if ($loader_inherit)
	{
		foreach ($loader_inherit as $key => $template)
		{
			$loader_inherit_ini = parse_ini_file('templates/' . $template . '/scripts/.loader', 1);
			$loader_ini = array_merge_recursive($loader_inherit_ini, $loader_ini);
		}
	}
	$loader_minify = $loader_ini['settings']['minify'];

	/* startup mode */

	if ($mode == 'startup')
	{
		$output = '<script> /* <![cdata[ */ ' . loader('scripts', 'startup') . ' /* ]]> */ </script>' . PHP_EOL;
	}

	/* else general mode */

	else
	{
		$loader_single = $loader_ini['single'];

		/* logged in */

		if (LOGGED_IN == TOKEN)
		{
			$loader_admin_single = $loader_ini['admin_single'];
		}
		$loader_deploy = $loader_ini['settings']['deploy'];

		/* merge loader include as needed */

		$loader_include = array();
		if ($loader_single)
		{
			$loader_include = array_merge($loader_include, $loader_single);
		}
		if ($loader_admin_single)
		{
			$loader_include = array_merge($loader_include, $loader_admin_single);
		}

		/* collect output */

		if ($loader_include)
		{
			foreach ($loader_include as $value)
			{
				$output .= '<script src="' . $value . '"></script>' . PHP_EOL;
			}
		}

		/* type of deployment */

		$output .= '<script> /* <![cdata[ */ ' . scripts_transport($loader_minify);
		if ($loader_deploy == 'inline')
		{
			$output .= loader('scripts', 'inline') . ' /* ]]> */ </script>' . PHP_EOL;
		}
		else
		{
			$output .= ' /* ]]> */ </script>' . PHP_EOL;
			$output .= '<script src="' . REWRITE_ROUTE . 'loader/scripts"></script>' . PHP_EOL;
		}
	}
	echo $output;
	if ($mode == '')
	{
		hook(__FUNCTION__ . '_end');
	}
}

/**
 * scripts transport
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Loader
 * @author Henry Ruhs
 *
 * @param string|boolean $minify
 * @return string
 */

function scripts_transport($minify = '')
{
	/* languages object */

	$language = Redaxscript_Language::getInstance();

	/* languages transport */

	$output = 'var l = ' . json_encode($language->get()) . ';' . PHP_EOL;

	/* extend redaxscript object */

	$public_constants = explode(', ', b('constant_public'));

	/* collect output */

	$output .= 'if (typeof r === \'object\')' . PHP_EOL;
	$output .= '{' . PHP_EOL;

	/* add constants */

	$output .= 'r.constants = {};';
	foreach ($public_constants as $value)
	{
		$output .= 'r.constants.' . $value . ' = \'' . constant($value) . '\';' . PHP_EOL;
	}

	/* baseURL fallback */

	$output .= 'if (r.baseURL === \'\')' . PHP_EOL;
	$output .= '{' . PHP_EOL;
	$output .= 'r.baseURL = \'' . ROOT . '\/\';' . PHP_EOL;
	$output .= '}' . PHP_EOL;

	/* generator and version */

	$output .= 'r.generator = \'' . l('name', '_package') . ' ' . l('version', '_package') . '\';' . PHP_EOL;
	$output .= 'r.version = \'' . l('version', '_package') . '\';' . PHP_EOL;
	$output .= '}' . PHP_EOL;

	/* minify */

	if ($minify)
	{
		$output = minify('scripts', $output);
	}
	return $output;
}
