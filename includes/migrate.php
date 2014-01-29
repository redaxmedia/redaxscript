<?php

/**
 * breadcrumb
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Migrate
 * @author Gary Aylward
 */

function breadcrumb()
{
	$registry = Redaxscript_Registry::instance();
	$breadcrumb = new Redaxscript_Breadcrumb($registry);
	echo $breadcrumb->displayBreadcrumb();
}

/**
 * migrate constants
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Migrate
 * @author Henry Ruhs, Gary Aylward
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
?>