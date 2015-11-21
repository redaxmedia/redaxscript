<?php

/**
 * clean
 *
 * @since 2.2.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Migrate
 * @author Henry Ruhs
 *
 * @param string $input
 * @param integer $mode
 * @return string
 */

function clean($input = null, $mode = null)
{
	$output = $input;
	$registry = Redaxscript\Registry::getInstance();

	/* untrusted user */

	if ($registry->get('filter') == 1)
	{
		if ($mode == 0)
		{
			$specialFilter = new Redaxscript\Filter\Special();
			$output = $specialFilter->sanitize($output);
		}
		if ($mode == 1)
		{
			$htmlFilter = new Redaxscript\Filter\Html();
			$output = $htmlFilter->sanitize($output);
		}
		if ($mode == 5)
		{
			$output = strip_tags($output);
		}
	}

	/* type related clean */

	if ($mode == 2)
	{
		$aliasFilter = new Redaxscript\Filter\Alias();
		$output = $aliasFilter->sanitize($output);
	}
	if ($mode == 3)
	{
		$emailFilter = new Redaxscript\Filter\Email();
		$output = $emailFilter->sanitize($output);
	}
	if ($mode == 4)
	{
		$urlFilter = new Redaxscript\Filter\Url();
		$output = $urlFilter->sanitize($output);
	}
	$output = stripslashes($output);
	return $output;
}

/**
 * language shortcut
 *
 * @since 2.2.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Migrate
 * @author Henry Ruhs
 *
 * @param string $key
 * @param string $index
 *
 * @return string
 */

function l($key = null, $index = null)
{
	$language = Redaxscript\Language::getInstance();
	$output = $language->get($key, $index);
	return $output;
}

/**
 * settings shortcut
 *
 * @since 2.2.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Migrate
 * @author Henry Ruhs
 *
 * @param string $key
 *
 * @return string
 */

function s($key = null)
{
	$output = Redaxscript\Db::getSettings($key);
	return $output;
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
	$output = array_merge($output, Redaxscript\Registry::get());
	return $output;
}
