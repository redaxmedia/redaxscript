<?php

/**
 * dawanda loader start
 */

function dawanda_loader_start()
{
	global $loader_modules_scripts;
	$loader_modules_scripts[] = 'modules/dawanda/scripts/startup.js';
	$loader_modules_scripts[] = 'modules/dawanda/scripts/dawanda.js';
}
?>