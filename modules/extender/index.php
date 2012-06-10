<?php

/* extender loader start */

function extender_loader_start()
{
	global $loader_modules_styles, $loader_modules_scripts;
	$loader_modules_styles[] = 'modules/extender/styles/extender.css';
	$loader_modules_scripts[] = 'modules/extender/scripts/extender.js';
}
?>