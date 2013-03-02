<?php

/**
 * maps loader start
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
 */

function maps_scripts_start()
{
	if (ADMIN_PARAMETER == '')
	{
		$output = '<script src="' . MAPS_API_URL . '?key=' .  MAPS_API_KEY . '&amp;sensor=' . MAPS_SENSOR . '"></script>' . PHP_EOL;
		echo $output;
	}
}
?>