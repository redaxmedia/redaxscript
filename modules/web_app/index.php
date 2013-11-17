<?php
/**
 * web app loader start
 *
 * @since 2.0.2
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function web_app_loader_start()
{
	global $loader_modules_scripts;
	$loader_modules_scripts[] = 'modules/web_app/scripts/startup.js';
	$loader_modules_scripts[] = 'modules/web_app/scripts/web_app.js';
}


/**
 * web app render start
 *
 * @since 2.0.2
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function web_app_render_start()
{
	if (FIRST_PARAMETER === 'manifest_webapp')
	{
		header('content-type: application/x-web-app-manifest+json');
		include_once('modules/web_app/files/manifest.json');
		define('RENDER_BREAK', 1);
	}
}
?>