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
	 * setUp
	 *
	 * @since 2.4.0
	 */

	public function setUp()
	{
		Db::clearCache();
	}

	/**
	 * testInit
	 *
	 * @since 2.2.0
	 */

	public function testInit()
	{
		/* setup */

		$module = new Module();
		$module->init(array(
			'alias' => 'Test',
		));

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

		$module = new Module();
		$module->init(array(
			'alias' => 'TestDummy',
		));
		$module->install();

		/* actual */

		$actual = Db::forTablePrefix('modules')->where('alias', 'TestDummy')->findOne()->alias;

		/* compare */

		$this->assertEquals('TestDummy', $actual);
	}

	/**
	 * testUninstall
	 *
	 * @since 2.2.0
	 */

	public function testUninstall()
	{
		/* setup */

		$module = new Module();
		$module->init(array(
			'alias' => 'TestDummy'
		));
		$module->uninstall();

		/* actual */

		$actual = Db::forTablePrefix('modules')->where('alias', 'TestDummy')->findOne();

		/* compare */

		$this->assertFalse(is_object($actual));
	}
}
