<?php
namespace Redaxscript\Tests;

use Redaxscript\Config;
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

class DbTest extends TestCase
{
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

	protected $_configArray = array();

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp()
	{
		$this->_config = Config::getInstance();
		$this->_configArray['dbType'] = $this->_config->get('dbType');
	}

	/**
	 * tearDown
	 *
	 * @since 3.0.0
	 */

	public function tearDown()
	{
		$this->_config->set('dbType', $this->_configArray['dbType']);
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
	 * @since 2.2.0
	 *
	 * @param string $dbType
	 *
	 * @dataProvider providerInit
	 */

	public function testInit($dbType = null)
	{
		/* setup */

		$this->_config->set('dbType', $dbType);
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
	 * testGetSettings
	 *
	 * @since 2.2.0
	 */

	public function testGetSettings()
	{
		/* actual */

		$actual = Db::getSetting('charset');

		/* compare */

		$this->assertEquals('utf-8', $actual);
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

		$this->assertGreaterThan(-1, $actual);
	}

	/**
	 * testForTablePrefix
	 *
	 * @since 2.2.0
	 */

	public function testForTablePrefix()
	{
		/* actual */

		$actual = Db::forTablePrefix('categories')->where('alias', 'home')->findOne()->alias;

		/* compare */

		$this->assertEquals('home', $actual);
	}

	/**
	 * testLeftJoinPrefix
	 *
	 * @since 2.2.0
	 */

	public function testLeftJoinPrefix()
	{
		/* expect and actual */

		$expect = array(
			'category_alias' => 'home',
			'article_alias' => 'welcome'
		);
		$actual = Db::forTablePrefix('articles')
			->tableAlias('a')
			->leftJoinPrefix('categories', array('a.category', '=', 'c.id'), 'c')
			->select('c.alias', 'category_alias')
			->select('a.alias', 'article_alias')
			->where('a.alias', 'welcome')
			->findArray();
		$actual = $actual[0];

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testWhereLikeMany
	 *
	 * @since 2.3.0
	 */

	public function testWhereLikeMany()
	{
		/* actual */

		$actual = Db::forTablePrefix('articles')->whereLikeMany(array(
			'alias'
		), array(
			'%welcome%'
		))->findOne()->alias;

		/* compare */

		$this->assertEquals('welcome', $actual);
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

		Db::forTablePrefix('articles')->where('alias', 'welcome')->findOne()->set('language', $language)->save();

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

		$expect = array(
			1
		);
		$actual = Db::forTablePrefix('articles')->findFlatArray();

		/* compare */

		$this->assertEquals($expect, $actual);
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

		$this->assertEquals('home', $actual);
	}

	/**
	 * testLimitGlobal
	 *
	 * @since 2.2.0
	 */

	public function testLimitGlobal()
	{
		/* actual */

		$actual = Db::forTablePrefix('categories')->limitGlobal('rank')->findOne()->alias;

		/* compare */

		$this->assertEquals('home', $actual);
	}
}
