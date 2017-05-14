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
 */

class ModuleTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp()
	{
		parent::setUp();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown()
	{
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawDrop();
	}

	/**
	 * testInit
	 *
	 * @since 2.2.0
	 */

	public function testInit()
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

		$this->assertObject($actual);
	}

	/**
	 * testGetAndSetNotification
	 *
	 * @since 3.0.0
	 */

	public function testGetAndSetNotification()
	{
		/* setup */

		$module = new Module\Module($this->_registry, $this->_request, $this->_language, $this->_config);
		$module->init(
		[
			'name' => 'Test Dummy',
			'alias' => 'TestDummy'
		]);
		$module->setNotification('error', 'testValue');

		/* actual */

		$actual = $module->getNotification('error');

		/* compare */

		$this->assertEquals('testValue', $actual['Test Dummy'][0]);
	}

	/**
	 * testGetAll
	 *
	 * @since 3.0.0
	 */

	public function testGetAll()
	{
		/* setup */

		$module = new Module\Module($this->_registry, $this->_request, $this->_language, $this->_config);
		$module->init(
		[
			'name' => 'Test Dummy',
			'alias' => 'TestDummy'
		]);
		$module->setNotification('success', 'testValue');
		$module->setNotification('error', 'testValue');

		/* actual */

		$actualArray = $module->getNotification();

		/* compare */

		$this->assertArrayHasKey('success', $actualArray);
		$this->assertArrayHasKey('error', $actualArray);
	}

	/**
	 * testGetInvalid
	 *
	 * @since 2.1.0
	 */

	public function testGetInvalid()
	{
		/* setup */

		$module = new Module\Module($this->_registry, $this->_request, $this->_language, $this->_config);
		$module->init(
		[
			'name' => 'Test Dummy',
			'alias' => 'TestDummy'
		]);

		/* actual */

		$actual = $module->getNotification('invalidKey');

		/* compare */

		$this->assertFalse($actual);
	}

	/**
	 * testInstall
	 *
	 * @since 2.6.0
	 */

	public function testInstallAndUninstall()
	{
		/* setup */

		$module = new Module\Module($this->_registry, $this->_request, $this->_language, $this->_config);
		$module->init(
		[
			'name' => 'Test Dummy',
			'alias' => 'TestDummy'
		]);

		/* install */

		$module->install();
		Db::clearCache();
		$actualModulesInstall = Db::forTablePrefix('modules')->count();
		$actualTablesInstall = Db::countTablePrefix();

		/* uninstall */

		$module->uninstall();
		Db::clearCache();
		$actualModulesUninstall = Db::forTablePrefix('modules')->count();
		$actualTablesUninstall = Db::countTablePrefix();

		/* compare */

		$this->assertEquals(1, $actualModulesInstall);
		$this->assertEquals(9, $actualTablesInstall);
		$this->assertEquals(0, $actualModulesUninstall);
		$this->assertEquals(8, $actualTablesUninstall);
	}
}
