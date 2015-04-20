<?php
namespace Redaxscript\Tests;

use Redaxscript\Config;
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
 */

class InstallerTest extends TestCase
{
	/**
	 * instance of the config class
	 *
	 * @var object
	 */

	protected $_config;

	/**
	 * setUp
	 *
	 * @since 2.4.0
	 */

	public function setUp()
	{
		$this->_config = Config::getInstance();
		$this->_config->set('dbPrefix', 'installer_');
		Db::clearCache();
	}

	/**
	 * tearDown
	 *
	 * @since 2.4.0
	 */

	public function tearDown()
	{
		$this->_config->set('dbPrefix', '');
	}

	/**
	 * testRawCreate
	 *
	 * @since 2.4.0
	 */

	public function testRawCreate()
	{
		/* setup */

		$installer = new Installer();
		$installer->init($this->_config);
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

		$installer = new Installer();
		$installer->init($this->_config);
		$installer->insertData(array(
			'adminName' => 'Admin',
			'adminUser' => 'admin',
			'adminPassword' => 'admin',
			'adminEmail' => 'admin@localhost'
		));

		/* actual */

		$actualArticles = Db::forTablePrefix('articles')->findMany()->count();
		$actualCategories = Db::forTablePrefix('categories')->findMany()->count();
		$actualExtras = Db::forTablePrefix('extras')->findMany()->count();
		$actualGroups = Db::forTablePrefix('groups')->findMany()->count();
		$actualSettings = Db::forTablePrefix('settings')->findMany()->count();
		if (is_dir('modules/CallHome'))
		{
			$actualModules = Db::forTablePrefix('modules')->findMany()->count();
		}
		$actualUsers = Db::forTablePrefix('users')->findMany()->count();

		/* compare */

		$this->assertEquals(1, $actualArticles);
		$this->assertEquals(1, $actualCategories);
		$this->assertEquals(6, $actualExtras);
		$this->assertEquals(2, $actualGroups);
		$this->assertEquals(26, $actualSettings);
		if (is_dir('modules/CallHome'))
		{
			$this->assertEquals(1, $actualModules);
		}
		$this->assertEquals(1, $actualUsers);
	}

	/**
	 * testRawDrop
	 *
	 * @since 2.4.0
	 */

	public function testRawDrop()
	{
		/* setup */

		$installer = new Installer();
		$installer->init($this->_config);
		$installer->rawDrop();

		/* actual */

		$actual = Db::countTablePrefix();

		/* compare */

		$this->assertEquals(0, $actual);
	}
}
