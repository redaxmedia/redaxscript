<?php
namespace Redaxscript\Modules\Qunit;

use Redaxscript\Language;
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
	 * array of the module
	 *
	 * @var array
	 */

	protected static $_moduleArray = array(
		'name' => 'QUnit',
		'alias' => 'Qunit',
		'author' => 'Redaxmedia',
		'description' => 'Javascript unit testing',
		'version' => '2.4.0'
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
			$loader_modules_scripts[] = 'modules/Qunit/scripts/init.js';
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
			$output = '<script src="//cdnjs.cloudflare.com/ajax/libs/qunit/1.14.0/qunit.min.js"></script>';
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
			Registry::set('title', Language::get('qunit', '_qunit'));
			Registry::set('description', Language::get('description', '_qunit'));
			Registry::set('centerBreak', true);
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