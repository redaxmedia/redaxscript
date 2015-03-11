<?php
namespace Redaxscript\Tests;

use Redaxscript\Db;
use Redaxscript\Module;

/**
 * ModuleTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ModuleTest extends TestCase
{
	/**
	 * testInit
	 *
	 * @since 2.2.0
	 */

	public function testInit()
	{
		/* setup */

		$module = new Module(array(
			'alias' => 'Test',
		));
		$module->init();

		/* actual */

		$actual = $module;

		/* compare */

		$this->assertTrue(is_object($actual));
	}

	/**
	 * testInstall
	 *
	 * @since 2.2.0
	 */

	public function testInstall()
	{
		/* setup */

		$module = new Module(array(
			'name' => 'Test',
			'alias' => 'Test',
		));
		$module->install();

		/* actual */

		$actual = Db::forTablePrefix('modules')->where('alias', 'Test')->findOne()->name;

		/* compare */

		$this->assertEquals('Test', $actual);
	}

	/**
	 * testUninstall
	 *
	 * @since 2.2.0
	 */

	public function testUninstall()
	{
		/* setup */

		$module = new Module(array(
			'alias' => 'Test',
		));
		$module->uninstall();
		Db::clearCache();

		/* actual */

		$actual = Db::forTablePrefix('modules')->where('alias', 'Test')->findOne();

		/* compare */

		$this->assertFalse(is_object($actual));
	}
}
