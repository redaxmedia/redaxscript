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
 *
 * @covers Redaxscript\Db
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

	public function setUp() : void
	{
		parent::setUp();
		$optionArray =
		[
			'adminName' => 'Test',
			'adminUser' => 'test',
			'adminPassword' => 'test',
			'adminEmail' => 'test@test.com'
		];
		$this->_configArray = $this->_config->get();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
		$categoryOne = Db::forTablePrefix('categories')->create();
		$categoryOne
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
				'language' => 'en',
				'category' => $categoryOne->id
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article Two',
				'alias' => 'article-two',
				'language' => 'de',
				'category' => $categoryOne->id
			])
			->save();
	}

	/**
	 * tearDown
	 *
	 * @since 3.0.0
	 */

	public function tearDown() : void
	{
		$this->dropDatabase();
		$this->_config->set('dbType', $this->_configArray['dbType']);
		$this->_config->set('dbPassword', $this->_configArray['dbPassword']);
	}

	/**
	 * testInit
	 *
	 * @since 3.0.0
	 *
	 * @param array $configArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testInit(array $configArray = []) : void
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

	public function testGetStatus() : void
	{
		/* actual */

		$actual = Db::getStatus();

		/* compare */

		$this->assertEquals(2, $actual);
	}

	/**
	 * testSetAutoIncrement
	 *
	 * @since 4.0.0
	 */

	public function testSetAutoIncrement() : void
	{
		/* actual */

		$actual = Db::setAutoIncrement('categories', 0);

		/* compare */

		$this->_config->get('dbType') === 'mysql' ? $this->assertTrue($actual) : $this->markTestSkipped();
	}

	/**
	 * testCountTablePrefix
	 *
	 * @since 2.4.0
	 */

	public function testCountTablePrefix() : void
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

	public function testForTablePrefix() : void
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

	public function testLeftJoinPrefix() : void
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

	public function testWhereLikeMany() : void
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
	 * @dataProvider providerAutoloader
	 */

	public function testWhereLanguageIs(string $language = null, string $expect = null) : void
	{
		/* actual */

		$actual = Db::forTablePrefix('articles')->whereLanguageIs($language)->findOne()->alias;

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testOrderBySetting
	 *
	 * @since 4.0.0
	 */

	public function testOrderBySetting() : void
	{
		/* actual */

		$actual = Db::forTablePrefix('categories')->orderBySetting('rank')->findOne()->alias;

		/* compare */

		$this->assertEquals('category-one', $actual);
	}

	/**
	 * testLimitBySetting
	 *
	 * @since 4.0.0
	 */

	public function testLimitBySetting() : void
	{
		/* actual */

		$actual = Db::forTablePrefix('categories')->limitBySetting()->findOne()->alias;

		/* compare */

		$this->assertEquals('category-one', $actual);
	}
}
