<?php

/**
 * live reload loader start
 *
 * @since 2.0.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function live_reload_loader_start()
{
	global $loader_modules_styles, $loader_modules_scripts;
	$loader_modules_styles[] = 'modules/live_reload/styles/live_reload.css';
	$loader_modules_scripts[] = 'modules/live_reload/scripts/startup.js';
	$loader_modules_scripts[] = 'modules/live_reload/scripts/live_reload.js';
}

/**
 * live reload loader scripts transport start
 *
 * @since 2.0.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function live_reload_loader_scripts_transport_start()
{
	$output = languages_transport(array(
		'live_reload_live_reload',
		'live_reload_updated'
	));
	echo $output;
}
