<?php

/**
 * maps loader start
 *
 * @since 2.0
 * @deprecated 2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function maps_loader_start()
{
	if (ADMIN_PARAMETER == '')
	{
		global $loader_modules_styles, $loader_modules_scripts;
		$loader_modules_styles[] = 'modules/maps/styles/maps.css';
		$loader_modules_scripts[] = 'modules/maps/scripts/startup.js';
		$loader_modules_scripts[] = 'modules/maps/scripts/maps.js';
	}
}

/**
 * maps scripts start
 *
 * @since 2.0
 * @deprecated 2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function maps_scripts_start()
{
	if (ADMIN_PARAMETER == '')
	{
		$output = '<script src="' . MAPS_API_URL . '?key=' .  MAPS_API_KEY . '&amp;sensor=' . MAPS_SENSOR . '"></script>' . PHP_EOL;
		echo $output;
	}
}

/**
 * maps
 *
 * @since 2.0
 * @deprecated 2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 *
 * @param integer $lat
 * @param integer $lng
 * @param integer $zoom
 */

function maps($lat = '', $lng = '', $zoom = '')
{
	$output = '<div class="js_map map"';
	if ($lat)
	{
		$output .= 'data-lat="' . $lat . '"';
	}
	if ($lng)
	{
		$output .= 'data-lng="' . $lng . '"';
	}
	if ($zoom)
	{
		$output .= 'data-zoom="' . $zoom . '"';
	}
	$output .= '></div>';
	echo $output;
}
?>