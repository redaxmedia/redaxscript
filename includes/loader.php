<?php

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

function scripts_transport($minify)
{
	/* extend redaxscript */

	$public_registry =
	[
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
		'parameterRoute',
		'languageRoute',
		'templateRoute',
		'refreshRoute',
		'language',
		'template',
		'myBrowser',
		'myBrowserVersion',
		'myEngine',
		'myDesktop',
		'myMobile',
		'myTablet'
	];

	/* collect output */

	$output = 'if (typeof rs === \'object\')' . PHP_EOL;
	$output .= '{' . PHP_EOL;

	/* languages */

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
