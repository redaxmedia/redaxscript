<?php

/**
 * editor loader start
 *
 * @since 1.2.1
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function editor_loader_start()
{
	global $loader_modules_styles, $loader_modules_scripts;
	$loader_modules_styles[] = 'modules/editor/styles/editor.css';
	$loader_modules_scripts[] = 'modules/editor/scripts/startup.js';
	$loader_modules_scripts[] = 'modules/editor/scripts/editor.js';
}