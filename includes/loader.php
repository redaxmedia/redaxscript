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
	$output = Redaxscript\Hook::trigger('loaderStart');
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
			header('expires: ' . gmdate('D, d M Y H:i:s', strtotime('+1 week')) . ' GMT');
		}
	}

	/* parse loader ini */

	$loader_ini = parse_ini_file('templates/' . Redaxscript\Registry::get('template') . '/assets/' . $type . '/.loader', 1);

	/* inherit from other templates */

	$loader_inherit = $loader_ini['inherit'];
	if ($loader_inherit)
	{
		foreach ($loader_inherit as $key => $template)
		{
			$loader_inherit_ini = parse_ini_file('templates/' . $template . '/assets/' . $type . '/.loader', 1);
			$loader_ini = array_merge_recursive($loader_inherit_ini, $loader_ini);
		}
	}
	else
	{
		$template = Redaxscript\Registry::get('template');
	}

	/* init mode */

	if ($mode == 'init')
	{
		$loader_template_init = $loader_ini['template_init'];
		if (Redaxscript\Registry::get('loggedIn') == Redaxscript\Registry::get('token'))
		{
			$loader_admin_init = $loader_ini['admin_init'];
		}
	}

	/* else general mode */

	else
	{
		$myBrowser = Redaxscript\Registry::get('myMobile');
		$myEngine = Redaxscript\Registry::get('myEngine');
		$loader_template = $loader_ini['template'];
		$loader_browser = $loader_ini[$myBrowser];
		$loader_engine = $loader_ini[$myEngine];

		/* logged in */

		if (Redaxscript\Registry::get('loggedIn') == Redaxscript\Registry::get('token'))
		{
			$loader_admin = $loader_ini['admin'];
			$loader_admin_browser = $loader_ini['admin_' . $myBrowser];
			$loader_admin_engine = $loader_ini['admin_' . $myEngine];
		}
		$loader_rewrite = $loader_ini['settings']['rewrite'];
	}
	$loader_minify = $loader_ini['settings']['minify'];

	/* merge loader include as needed */

	$loader_include = array();
	if ($loader_template_init)
	{
		$loader_include = array_merge($loader_include, $loader_template_init);
	}
	if ($loader_admin_init)
	{
		$loader_include = array_merge($loader_include, $loader_admin_init);
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
		Redaxscript\Hook::trigger('loader' . ucfirst($type) . 'TransportStart');
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
		Redaxscript\Hook::trigger('loader' . ucfirst($type) . 'TransportEnd');
	}
	$output .= ob_get_clean() . PHP_EOL;

	/* rewrite path */

	if ($loader_rewrite)
	{
		$root = Redaxscript\Registry::get('root');
		$output = preg_replace('/templates\/'. $template . '/', $root . '/templates/' . $template, $output);
		if (Redaxscript\Registry::get('loggedIn') == Redaxscript\Registry::get('token'))
		{
			$output = preg_replace('/templates\/admin/', $root . '/templates/admin', $output);
		}
		$output = preg_replace('/modules/', $root . '/modules', $output);
	}

	/* minify output */

	if ($loader_minify)
	{
		$minifier = new Redaxscript\Minifier();
		if ($type == 'scripts')
		{
			$output = $minifier->scripts($output);
		}
		else if ($type == 'styles')
		{
			$output = $minifier->styles($output);
		}
	}
	$output .= Redaxscript\Hook::trigger('loaderEnd');
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
	$output = Redaxscript\Hook::trigger('styleStart');

	/* parse loader ini */

	$loader_ini = parse_ini_file('templates/' . Redaxscript\Registry::get('template') . '/assets/styles/.loader', 1);

	/* inherit from other templates */

	$loader_inherit = $loader_ini['inherit'];
	if ($loader_inherit)
	{
		foreach ($loader_inherit as $key => $template)
		{
			$loader_inherit_ini = parse_ini_file('templates/' . $template . '/assets/styles/.loader', 1);
			$loader_ini = array_merge_recursive($loader_inherit_ini, $loader_ini);
		}
	}
	$loader_single = $loader_ini['single'];

	/* logged in */

	if (Redaxscript\Registry::get('loggedIn') == Redaxscript\Registry::get('token'))
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

	$output .= Redaxscript\Hook::trigger('linkStart');
	if ($loader_include)
	{
		foreach ($loader_include as $value)
		{
			$output .= '<link type="text/css" href="' . $value . '" media="all" rel="stylesheet" />' . PHP_EOL;
		}
	}
	if ($loader_deploy != 'inline')
	{
		$output .= '<link type="text/css" href="' . Redaxscript\Registry::get('rewriteRoute') . 'loader/styles" media="all" rel="stylesheet" />' . PHP_EOL;
	}
	$output .= Redaxscript\Hook::trigger('linkEnd');

	/* inline deployment */

	if ($loader_deploy == 'inline')
	{
		$output .= '<style media="all"><!-- /* <![cdata[ */ ' . loader('styles', 'inline') . ' /* ]]> */ --></style>' . PHP_EOL;
	}
	$output .= Redaxscript\Hook::trigger('styleEnd');
	echo $output;
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
	if (!$mode)
	{
		$output = Redaxscript\Hook::trigger('scriptStart');
	}

	/* parse loader ini */

	$loader_ini = parse_ini_file('templates/' . Redaxscript\Registry::get('template') . '/assets/scripts/.loader', 1);

	/* inherit from other templates */

	$loader_inherit = $loader_ini['inherit'];
	if ($loader_inherit)
	{
		foreach ($loader_inherit as $key => $template)
		{
			$loader_inherit_ini = parse_ini_file('templates/' . $template . '/assets/scripts/.loader', 1);
			$loader_ini = array_merge_recursive($loader_inherit_ini, $loader_ini);
		}
	}
	$loader_minify = $loader_ini['settings']['minify'];

	/* init mode */

	if ($mode == 'init')
	{
		$output .= '<script> /* <![cdata[ */ ' . loader('scripts', 'init') . ' /* ]]> */ </script>' . PHP_EOL;
	}

	/* else general mode */

	else
	{
		$loader_single = $loader_ini['single'];

		/* logged in */

		if (Redaxscript\Registry::get('loggedIn') == Redaxscript\Registry::get('token'))
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
			$output .= '<script src="' . Redaxscript\Registry::get('rewriteRoute') . 'loader/scripts"></script>' . PHP_EOL;
		}
	}
	if (!$mode)
	{
		$output .= Redaxscript\Hook::trigger('scriptEnd');
	}
	echo $output;
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
 * @param mixed $minify
 * @return string
 */

function scripts_transport($minify = '')
{
	/* extend redaxscript object */

	$public_registry = array(
		'token',
		'loggedIn',
		'firstParameter',
		'secondParameter',
		'thirdParameter',
		'adminParameter',
		'tableParameter',
		'idParameter',
		'aliasParameter',
		'lastParameter',
		'firstTable',
		'secondTable',
		'thirdTable',
		'lastTable',
		'fullRoute',
		'fullTopRoute',
		'rewriteRoute',
		'languageRoute',
		'templateRoute',
		'refreshRoute',
		'myBrowser',
		'myBrowserVersion',
		'myEngine',
		'myDesktop',
		'myMobile',
		'myTablet'
	);

	/* collect output */

	$output = 'if (typeof rs === \'object\')' . PHP_EOL;
	$output .= '{' . PHP_EOL;

	/* languages object */

	$language = Redaxscript\Language::getInstance();

	/* add language */

	$output .= 'rs.language = ' . json_encode($language->get()) . ';' . PHP_EOL;

	/* add registry */

	$output .= 'rs.registry = {};';
	foreach ($public_registry as $value)
	{
		$output .= 'rs.registry.' . $value . ' = \'' . Redaxscript\Registry::get($value) . '\';' . PHP_EOL;
	}

	/* baseURL fallback */

	$output .= 'if (rs.baseURL === \'\')' . PHP_EOL;
	$output .= '{' . PHP_EOL;
	$output .= 'rs.baseURL = \'' . Redaxscript\Registry::get('root') . '\/\';' . PHP_EOL;
	$output .= '}' . PHP_EOL;

	/* generator and version */

	$output .= 'rs.generator = \'' . Redaxscript\Language::get('name', '_package') . ' ' . Redaxscript\Language::get('version', '_package') . '\';' . PHP_EOL;
	$output .= 'rs.version = \'' . Redaxscript\Language::get('version', '_package') . '\';' . PHP_EOL;
	$output .= '}' . PHP_EOL;

	/* minify */

	if ($minify)
	{
		$minifier = new Redaxscript\Minifier();
		$output = $minifier->scripts($output);
	}
	return $output;
}
