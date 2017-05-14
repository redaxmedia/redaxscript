<?php
namespace Redaxscript\Tests;

use Redaxscript\Db;

/**
 * DbTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class DbTest extends TestCaseAbstract
{
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
		parent::setUp();
		$this->_configArray = $this->_config->get();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings(
		[
			'adminName' => 'Test',
			'adminUser' => 'test',
			'adminPassword' => 'test',
			'adminEmail' => 'test@test.com'
		]);
		Db::forTablePrefix('categories')
			->create()
			->set(
			[
				'title' => 'Category One',
				'alias' => 'category-one'
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article One',
				'alias' => 'article-one',
				'category' => 1
			])
			->save();
	}

	/**
	 * tearDown
	 *
	 * @since 3.0.0
	 */

	public function tearDown()
	{
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawDrop();
		$this->_config->set('dbType', $this->_configArray['dbType']);
		$this->_config->set('dbPassword', $this->_configArray['dbPassword']);
	}

	/**
	 * providerInit
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerInit()
	{
		return $this->getProvider('tests/provider/db_init.json');
	}

	/**
	 * providerLanguage
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerLanguage()
	{
		return $this->getProvider('tests/provider/db_language.json');
	}

	/**
	 * testInit
	 *
	 * @since 3.0.0
	 *
	 * @param array $configArray
	 *
	 * @dataProvider providerInit
	 */

	public function testInit($configArray = [])
	{
		/* setup */

		$this->_config->set('dbType', $configArray['dbType']);
		$this->_config->set('dbPassword', $configArray['dbPassword']);
		Db::construct($this->_config);
		Db::init();

		/* actual */

		$actual = Db::getDb();

		/* compare */

		$this->assertInstanceOf('PDO', $actual);
	}

	/**
	 * testGetStatus
	 *
	 * @since 2.4.0
	 */

	public function testGetStatus()
	{
		/* actual */

		$actual = Db::getStatus();

		/* compare */

		$this->assertEquals(2, $actual);
	}

	/**
	 * testRawInstance
	 *
	 * @since 2.4.0
	 */

	public function testRawInstance()
	{
		/* actual */

		$actual = Db::rawInstance()->getDb();

		/* compare */

		$this->assertInstanceOf('PDO', $actual);
	}

	/**
	 * testCountTablePrefix
	 *
	 * @since 2.4.0
	 */

	public function testCountTablePrefix()
	{
		/* actual */

		$actual = Db::countTablePrefix();

		/* compare */

		$this->assertEquals(8, $actual);
	}

	/**
	 * testForTablePrefix
	 *
	 * @since 2.2.0
	 */

	public function testForTablePrefix()
	{
		/* actual */

		$actual = Db::forTablePrefix('categories')->where('alias', 'category-one')->findOne()->alias;

		/* compare */

		$this->assertEquals('category-one', $actual);
	}

	/**
	 * testLeftJoinPrefix
	 *
	 * @since 2.2.0
	 */

	public function testLeftJoinPrefix()
	{
		/* expect and actual */

		$expectArray =
		[
			'category_alias' => 'category-one',
			'article_alias' => 'article-one'
		];
		$actualArray = Db::forTablePrefix('articles')
			->tableAlias('a')
			->leftJoinPrefix('categories', 'a.category = c.id', 'c')
			->select('c.alias', 'category_alias')
			->select('a.alias', 'article_alias')
			->where('a.alias', 'article-one')
			->findArray();

		/* compare */

		$this->assertEquals($expectArray, $actualArray[0]);
	}

	/**
	 * testWhereLikeMany
	 *
	 * @since 2.3.0
	 */

	public function testWhereLikeMany()
	{
		/* actual */

		$actual = Db::forTablePrefix('articles')->whereLikeMany(
		[
			'alias'
		],
		[
			'%article-one%'
		])
		->findOne()->alias;

		/* compare */

		$this->assertEquals('article-one', $actual);
	}

	/**
	 * testWhereLanguageIs
	 *
	 * @since 3.0.0
	 *
	 * @param string $language
	 * @param string $expect
	 *
	 * @dataProvider providerLanguage
	 */

	public function testWhereLanguageIs($language = null, $expect = null)
	{
		/* setup */

		Db::forTablePrefix('articles')->where('alias', 'article-one')->findOne()->set('language', $language)->save();

		/* actual */

		$actual = Db::forTablePrefix('articles')->whereLanguageIs('en')->findOne()->alias;

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testFindFlatArray
	 *
	 * @since 2.4.0
	 */

	public function testFindFlatArray()
	{
		/* expect and actual */

		$expectArray =
		[
			1
		];
		$actualArray = Db::forTablePrefix('articles')->findFlatArray();

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testGetAndSetSetting
	 *
	 * @since 2.2.0
	 */

	public function testGetAndSetSetting()
	{
		/* setup */

		Db::setSetting('charset', 'utf-8');

		/* actual */

		$actual = Db::getSetting('charset');

		/* compare */

		$this->assertEquals('utf-8', $actual);
	}

	/**
	 * testGetSettingInvalid
	 *
	 * @since 2.2.0
	 */

	public function testGetSettingInvalid()
	{
		/* actual */

		$actual = Db::getSetting('invalidKey');

		/* compare */

		$this->assertFalse($actual);
	}

	/**
	 * testOrderGlobal
	 *
	 * @since 2.2.0
	 */

	public function testOrderGlobal()
	{
		/* actual */

		$actual = Db::forTablePrefix('categories')->orderGlobal('rank')->findOne()->alias;

		/* compare */

		$this->assertEquals('category-one', $actual);
	}

	/**
	 * testLimitGlobal
	 *
	 * @since 2.2.0
	 */

	public function testLimitGlobal()
	{
		/* actual */

		$actual = Db::forTablePrefix('categories')->limitGlobal()->findOne()->alias;

		/* compare */

		$this->assertEquals('category-one', $actual);
	}
}
