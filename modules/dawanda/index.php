<?php

/**
 * dawanda loader start
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function dawanda_loader_start()
{
	global $loader_modules_scripts;
	$loader_modules_scripts[] = 'modules/dawanda/scripts/startup.js';
	$loader_modules_scripts[] = 'modules/dawanda/scripts/dawanda.js';
}
