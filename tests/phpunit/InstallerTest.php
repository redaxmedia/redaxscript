<?php
namespace Redaxscript\Tests;

use Redaxscript\Db;
use Redaxscript\Installer;

/**
 * InstallerTest
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Installer
 */

class InstallerTest extends TestCaseAbstract
{
	/**
	 * testRawCreate
	 *
	 * @since 2.4.0
	 */

	public function testRawCreate()
	{
		/* setup */

		$installer = new Installer($this->_registry, $this->_request, $this->_language, $this->_config);
		$installer->init();
		$installer->rawCreate();

		/* actual */

		$actual = Db::countTablePrefix();

		/* compare */

		$this->assertEquals(8, $actual);
	}

	/**
	 * testInsertData
	 *
	 * @since 2.4.0
	 */

	public function testInsertData()
	{
		/* setup */

		$optionArray =
		[
			'adminName' => 'Test',
			'adminUser' => 'test',
			'adminPassword' => 'test',
			'adminEmail' => 'test@test.com'
		];
		$installer = new Installer($this->_registry, $this->_request, $this->_language, $this->_config);
		$installer->init();
		$installer->insertData($optionArray);

		/* actual */

		$actualArticles = Db::forTablePrefix('articles')->count();
		$actualCategories = Db::forTablePrefix('categories')->count();
		$actualExtras = Db::forTablePrefix('extras')->count();
		$actualComments = Db::forTablePrefix('comments')->count();
		$actualGroups = Db::forTablePrefix('groups')->count();
		$actualUsers = Db::forTablePrefix('users')->count();
		$actualSettings = Db::forTablePrefix('settings')->count();
		$actualModules = Db::forTablePrefix('modules')->count();

		/* compare */

		$this->assertEquals(1, $actualArticles);
		$this->assertEquals(1, $actualCategories);
		$this->assertEquals(6, $actualExtras);
		$this->assertEquals(1, $actualComments);
		$this->assertEquals(2, $actualGroups);
		$this->assertEquals(1, $actualUsers);
		$this->assertEquals(26, $actualSettings);
		$this->assertEquals(7, $actualModules);
	}

	/**
	 * testRawDrop
	 *
	 * @since 2.4.0
	 */

	public function testRawDrop()
	{
		/* setup */

		$installer = new Installer($this->_registry, $this->_request, $this->_language, $this->_config);
		$installer->init();
		$installer->rawDrop();

		/* actual */

		$actual = Db::countTablePrefix();

		/* compare */

		$this->assertEquals(0, $actual);
	}
}
