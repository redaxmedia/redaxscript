<?php
namespace Redaxscript\Tests;

use Redaxscript\Config;
use Redaxscript\Db;
use Redaxscript\Installer;
use Redaxscript\Language;

/**
 * InstallerTest
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class InstallerTest extends TestCaseAbstract
{
	/**
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

	/**
	 * instance of the config class
	 *
	 * @var object
	 */

	protected $_config;

	/**
	 * array to restore config
	 *
	 * @var array
	 */

	protected $_configArray = [];

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp()
	{
		$this->_language = Language::getInstance();
		$this->_config = Config::getInstance();
		$this->_configArray = $this->_config->get();
		$this->_config->set('dbPrefix', 'installer_');
	}

	/**
	 * tearDown
	 *
	 * @since 3.0.0
	 */

	public function tearDown()
	{
		$this->_config->set('dbPrefix', $this->_configArray['dbPrefix']);
	}

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

		$installer = new Installer($this->_registry, $this->_request, $this->_language, $this->_config);
		$installer->init();
		$installer->insertData(
		[
			'adminName' => 'Test',
			'adminUser' => 'test',
			'adminPassword' => 'test',
			'adminEmail' => 'test@test.com'
		]);

		/* actual */

		$actualArticles = Db::forTablePrefix('articles')->findMany()->count();
		$actualCategories = Db::forTablePrefix('categories')->findMany()->count();
		$actualExtras = Db::forTablePrefix('extras')->findMany()->count();
		$actualComments = Db::forTablePrefix('comments')->findMany()->count();
		$actualGroups = Db::forTablePrefix('groups')->findMany()->count();
		$actualUsers = Db::forTablePrefix('users')->findMany()->count();
		$actualSettings = Db::forTablePrefix('settings')->findMany()->count();
		if (is_dir('modules/CallHome') && is_dir('modules/Validator'))
		{
			$actualModules = Db::forTablePrefix('modules')->findMany()->count();
		}

		/* compare */

		$this->assertEquals(1, $actualArticles);
		$this->assertEquals(1, $actualCategories);
		$this->assertEquals(6, $actualExtras);
		$this->assertEquals(1, $actualComments);
		$this->assertEquals(2, $actualGroups);
		$this->assertEquals(1, $actualUsers);
		$this->assertEquals(25, $actualSettings);
		if (is_dir('modules/CallHome') && is_dir('modules/Validator'))
		{
			$this->assertEquals(2, $actualModules);
		}
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
