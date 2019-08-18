<?php
namespace Redaxscript\Tests\Module;

use Redaxscript\Db;
use Redaxscript\Module;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ModuleTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Module\Module
 */

class ModuleTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$this->createDatabase();
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown() : void
	{
		$this->dropDatabase();
	}

	/**
	 * testInit
	 *
	 * @since 2.2.0
	 */

	public function testInit() : void
	{
		/* setup */

		$module = new Module\Module($this->_registry, $this->_request, $this->_language, $this->_config);
		$module->init(
		[
			'name' => 'Test Dummy',
			'alias' => 'TestDummy'
		]);

		/* actual */

		$actual = $module;

		/* compare */

		$this->assertIsObject($actual);
	}

	/**
	 * testInstallAndUninstall
	 *
	 * @since 2.6.0
	 */

	public function testInstallAndUninstall() : void
	{
		/* setup */

		$module = new Module\Module($this->_registry, $this->_request, $this->_language, $this->_config);
		$module->init(
		[
			'name' => 'Test Dummy',
			'alias' => 'TestDummy'
		]);

		/* install */

		$actualInstall = $module->install();
		$actualModulesInstall = Db::forTablePrefix('modules')->count();
		$actualTablesInstall = Db::countTablePrefix();

		/* uninstall */

		$actualUninstall = $module->uninstall();
		$actualModulesUninstall = Db::forTablePrefix('modules')->count();
		$actualTablesUninstall = Db::countTablePrefix();

		/* compare */

		$this->assertTrue($actualInstall);
		$this->assertTrue($actualUninstall);
		$this->assertEquals(1, $actualModulesInstall);
		$this->assertEquals(9, $actualTablesInstall);
		$this->assertEquals(0, $actualModulesUninstall);
		$this->assertEquals(8, $actualTablesUninstall);
	}

	/**
	 * testInstallAndUninstallInvalid
	 *
	 * @since 4.0.0
	 *
	 * @runInSeparateProcess
	 */

	public function testInstallAndUninstallInvalid() : void
	{
		/* setup */

		$module = new Module\Module($this->_registry, $this->_request, $this->_language, $this->_config);
		$module->init();

		/* actual */

		$actualInstall = $module->install();
		$actualUninstall = $module->uninstall();

		/* compare */

		$this->assertFalse($actualInstall);
		$this->assertFalse($actualUninstall);
	}
}
