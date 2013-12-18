<?php

/**
 * validator loader start
 *
 * @since 2.0.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function validator_loader_start()
{
	if (FIRST_PARAMETER != 'admin')
	{
		global $loader_modules_styles, $loader_modules_scripts;
		$loader_modules_styles[] = 'modules/validator/styles/validator.css';
		$loader_modules_scripts[] = 'modules/validator/scripts/startup.js';
		$loader_modules_scripts[] = 'modules/validator/scripts/validator.js';
	}
}

/**
 * validator loader scripts transport start
 *
 * @since 2.0.0
 * @deprecated 2.0.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

function validator_loader_scripts_transport_start()
{
	if (FIRST_PARAMETER != 'admin')
	{
		$output = languages_transport(array(
			'validator_from',
			'validator_to',
			'validator_line',
			'validator_column'
		));
		echo $output;
	}
}
?>