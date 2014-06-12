<?php

/**
 * breadcrumb
 *
 * @since 2.1.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Migrate
 * @author Gary Aylward
 */

function breadcrumb()
{
	$registry = Redaxscript_Registry::instance();
	$breadcrumb = new Redaxscript_Breadcrumb($registry);
	echo $breadcrumb->render();
}

/**
 * helper class
 *
 * @since 2.1.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Migrate
 * @author Kim Kha Nguyen
 */

function helper_class()
{
	$registry = Redaxscript_Registry::instance();
	$helper = new Redaxscript_Helper($registry);
	echo $helper->getClass();
}

/**
 * helper subset
 *
 * @since 2.1.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Migrate
 * @author Kim Kha Nguyen
 */

function helper_subset()
{
	$registry = Redaxscript_Registry::instance();
	$helper = new Redaxscript_Helper($registry);
	echo $helper->getSubset();
}

/**
 * migrate constants
 *
 * @since 2.1.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Migrate
 * @author Henry Ruhs
 * @author Gary Aylward
 *
 * @return array
 */

function migrate_constants()
{
	/* get user constants */

	$constants = get_defined_constants(true);
	$constants_user = $constants['user'];

	/* process constants user */

	foreach ($constants_user as $key => $value)
	{
		/* transform to camelcase */

		$key = mb_convert_case($key, MB_CASE_TITLE);
		$key[0] = strtolower($key[0]);

		/* remove underline */

		$key = str_replace('_', '', $key);

		/* store in array */

		$output[$key] = $value;
	}
	return $output;
}

/**
 * shortcut
 *
 * @since 2.2.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Migrate
 * @author Henry Ruhs
 *
 * @return string
 */

function l($name = null)
{
	global $l;
	$output = '';
	if ($l === null)
	{
		$l = array();
	}
	$registry = Redaxscript_Registry::instance();
	$language = $registry->get('language');
	$language = 'en';
	$json_default = json_decode(file_get_contents('languages/' . $language . '.json'), true);
	$json_current = array();
	if ($language !== 'en')
	{
		$json_current = json_decode(file_get_contents('languages/' . $language . '.json'), true);;
	}
	if (is_array($json_default))
	{
		$l = array_merge($l, $json_default, $json_current);
	}
	if (array_key_exists($name, $l))
	{
		$output = $l[$name];
		if (function_exists('mb_convert_encoding'))
		{
			$output = mb_convert_encoding($l[$name], s('charset'), 'utf-8, latin1');
		}
	}
	return $output;
}