<?php

/**
 * qunit loader start
 */

function qunit_loader_start()
{
	if (FIRST_PARAMETER == 'qunit')
	{
		global $loader_modules_styles, $loader_modules_scripts;
		$loader_modules_styles[] = 'modules/qunit/styles/qunit.css';
		$loader_modules_scripts[] = 'modules/qunit/scripts/startup.js';
		$loader_modules_scripts[] = 'modules/qunit/scripts/qunit.js';
		$loader_modules_scripts[] = 'modules/qunit/scripts/test.js';
	}
}

/**
 * qunit loader scripts transport start
 */

function qunit_loader_scripts_transport_start()
{
	if (FIRST_PARAMETER == 'qunit')
	{
		$output = languages_transport(array(
			'qunit_title',
			'qunit_description',
			'qunit_type_expected',
			'qunit_value_expected',
			'qunit_attribute_expected',
			'qunit_test_passed',
			'qunit_test_failed'
		));
		echo $output;
	}
}

/**
 * qunit scripts start
 */

function qunit_scripts_start()
{
	if (FIRST_PARAMETER == 'qunit')
	{
		$output = '<script src="http://code.jquery.com/qunit/qunit-1.10.0.js"></script>' . PHP_EOL;
		echo $output;
	}
}

/**
 * qunit render start
 */

function qunit_render_start()
{
	if (FIRST_PARAMETER == 'qunit')
	{
		define('CENTER_BREAK', 1);
		define('TITLE', l('qunit_title'));
		define('DESCRIPTION', l('qunit_description'));
	}
}

/**
 * qunit center start
 */

function qunit_center_start()
{
	if (FIRST_PARAMETER == 'qunit')
	{
		/* collect output */

		$output = '<div id="qunit" class="wrapper_qunit"></div><div id="qunit-fixture"></div>';
		echo $output;
	}
}
?>