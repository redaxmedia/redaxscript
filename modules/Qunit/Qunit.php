<?php
namespace Redaxscript\Modules\Qunit;

use Redaxscript\Module;
use Redaxscript\Registry;

/**
 * javascript unit testing
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Modules
 * @author Henry Ruhs
 */

class Qunit extends Module
{
	/**
	 * custom module setup
	 *
	 * @var array
	 */

	protected static $_module = array(
		'name' => 'QUnit',
		'alias' => 'Qunit',
		'author' => 'Redaxmedia',
		'description' => 'Javascript unit testing',
		'version' => '2.2.0',
		'status' => 1,
		'access' => 0
	);

	/**
	 * loaderStart
	 *
	 * @since 2.2.0
	 */

	public static function loaderStart()
	{
		if (Registry::get('firstParameter') === 'qunit')
		{
			global $loader_modules_styles, $loader_modules_scripts;
			$loader_modules_styles[] = 'modules/Qunit/styles/qunit.css';
			$loader_modules_scripts[] = 'modules/Qunit/scripts/startup.js';
			$loader_modules_scripts[] = 'modules/Qunit/scripts/qunit.js';
			$loader_modules_scripts[] = 'modules/Qunit/scripts/test.js';
		}
	}

	/**
	 * scriptsEnd
	 *
	 * @since 2.2.0
	 */

	public static function scriptsEnd()
	{
		if (Registry::get('firstParameter') === 'qunit')
		{
			$output = '<script src="//cdnjs.cloudflare.com/ajax/libs/qunit/1.14.0/qunit.min.js"></script>' . PHP_EOL;
			echo $output;
		}
	}

	/**
	 * renderStart
	 *
	 * @since 2.2.0
	 */

	public static function renderStart()
	{
		if (Registry::get('firstParameter') === 'qunit')
		{
			Registry::set('title', l('qunit', 'qunit'));
			Registry::set('description', l('description', 'qunit'));
			Registry::set('centerBreak', 1);
		}
	}

	/**
	 * centerStart
	 *
	 * @since 2.2.0
	 */

	public static function centerStart()
	{
		if (Registry::get('firstParameter') === 'qunit')
		{
			$output = '<div id="qunit" class="wrapper_qunit"></div><div id="qunit-fixture"></div>';
			echo $output;
		}
	}
}