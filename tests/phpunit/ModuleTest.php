<?php
namespace Redaxscript\Tests;

use Redaxscript\Config;
use Redaxscript\Db;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Language;
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

class ModuleTest extends TestCaseAbstract
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

		$module = new Module(Registry::getInstance(), Request::getInstance(), Language::getInstance(), Config::getInstance());
		$module->init(
		[
			'alias' => 'TestDummy'
		]);

		/* actual */

		$actual = $module;

		/* compare */

		$this->assertTrue(is_object($actual));
	}

	/**
	 * testGetAndSetNotification
	 *
	 * @since 3.0.0
	 */

	public function testGetAndSetNotification()
	{
		/* setup */

		$module = new Module(Registry::getInstance(), Request::getInstance(), Language::getInstance(), Config::getInstance());
		$module->init(
		[
			'name' => 'Test dummy',
			'alias' => 'TestDummy'
		]);
		$module->setNotification('error', 'testValue');

		/* actual */

		$actual = $module->getNotification('error');

		/* compare */

		$this->assertEquals('testValue', $actual['Test dummy'][0]);
	}

	/**
	 * testGetAll
	 *
	 * @since 3.0.0
	 */

	public function testGetAll()
	{
		/* setup */

		$module = new Module(Registry::getInstance(), Request::getInstance(), Language::getInstance(), Config::getInstance());
		$module->init(
		[
			'name' => 'Test dummy',
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

		$module = new Module(Registry::getInstance(), Request::getInstance(), Language::getInstance(), Config::getInstance());

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

	public function testInstall()
	{
		/* setup */

		$module = new Module(Registry::getInstance(), Request::getInstance(), Language::getInstance(), Config::getInstance());
		$module->init(
		[
			'alias' => 'TestDummy'
		]);
		$module->install();

		/* actual */

		$actualModules = Db::forTablePrefix('modules')->findMany()->count();
		$actualTables = Db::countTablePrefix();

		/* compare */

		$this->assertEquals(4, $actualModules);
		$this->assertEquals(9, $actualTables);
	}

	/**
	 * testUninstall
	 *
	 * @since 2.6.0
	 */

	public function testUninstall()
	{
		/* setup */

		$module = new Module(Registry::getInstance(), Request::getInstance(), Language::getInstance(), Config::getInstance());
		$module->init(
		[
			'alias' => 'TestDummy'
		]);
		$module->uninstall();

		/* actual */

		$actualModules = Db::forTablePrefix('modules')->findMany()->count();
		$actualTables = Db::countTablePrefix();

		/* compare */

		$this->assertEquals(2, $actualModules);
		$this->assertEquals(8, $actualTables);
	}
}
