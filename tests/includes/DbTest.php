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
	 * setUp
	 *
	 * @since 2.2.0
	 */

	public function setUp()
	{
		$this->_config = Config::getInstance();
		$this->_config->set('restore', $this->_config->get('type'));
	}

	/**
	 * tearDown
	 *
	 * @since 2.4.0
	 */

	public function tearDown()
	{
		$this->_config->set('type', $this->_config->get('restore'));
	}

	/**
	 * providerDb
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerDb()
	{
		return $this->getProvider('tests/provider/db.json');
	}

	/**
	 * testInit
	 *
	 * @since 2.2.0
	 *
	 * @param string $type
	 *
	 * @dataProvider providerDb
	 */

	public function testInit($type = null)
	{
		/* setup */

		$this->_config->set('type', $type);
		Db::init($this->_config);

		/* actual */

		$actual = Db::getDb();

		/* compare */

		$this->assertInstanceOf('PDO', $actual);
	}

	/**
	 * testRawInstance
	 *
	 * @since 2.4.0
	 *
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
	 *
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
	 *
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
	 *
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
	 *
	 */

	public function testWhereLikeMany()
	{
		/* actual */

		$actual = Db::forTablePrefix('articles')->whereLikeMany(array(
			'title'
		), array(
			'%welcome%'
		))->findOne()->alias;

		/* compare */

		$this->assertEquals('welcome', $actual);
	}

	/**
	 * testfindArrayFlat
	 *
	 * @since 2.4.0
	 *
	 */

	public function testfindArrayFlat()
	{
		/* expect and actual */

		$expect = array(
			1
		);
		$actual = Db::forTablePrefix('articles')->findArrayFlat();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testGetSettings
	 *
	 * @since 2.2.0
	 *
	 */

	public function testGetSettings()
	{
		/* actual */

		$actual = Db::getSettings('charset');

		/* compare */

		$this->assertEquals('utf-8', $actual);
	}

	/**
	 * testOrderGlobal
	 *
	 * @since 2.2.0
	 *
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
	 *
	 */

	public function testLimitGlobal()
	{
		/* actual */

		$actual = Db::forTablePrefix('categories')->limitGlobal('rank')->findOne()->alias;

		/* compare */

		$this->assertEquals('home', $actual);
	}
}
