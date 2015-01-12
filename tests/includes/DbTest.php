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

		/* result */

		$result = Db::getDb();

		/* compare */

		$this->assertInstanceOf('PDO', $result);
	}

	/**
	 * testForTablePrefix
	 *
	 * @since 2.2.0
	 *
	 */

	public function testForTablePrefix()
	{
		/* result */

		$result = Db::forTablePrefix('categories')->where('id', 1)->findOne()->alias;

		/* compare */

		$this->assertEquals('home', $result);
	}

	/**
	 * testLeftJoinPrefix
	 *
	 * @since 2.2.0
	 *
	 */

	public function testLeftJoinPrefix()
	{
		/* expect and result */

		$expect = array(
			'category_alias' => 'home',
			'article_alias' => 'welcome'
		);
		$result = Db::forTablePrefix('articles')
			->tableAlias('a')
			->leftJoinPrefix('categories', array('a.category', '=', 'c.id'), 'c')
			->select('c.alias', 'category_alias')
			->select('a.alias', 'article_alias')
			->where('a.id', 1)
			->findArray();
		$result = $result[0];

		/* compare */

		$this->assertEquals($expect, $result);
	}

	/**
	 * testWhereLikeMany
	 *
	 * @since 2.3.0
	 *
	 */

	public function testWhereLikeMany()
	{
		/* result */

		$result = Db::forTablePrefix('articles')->whereLikeMany(array(
			'title'
		), array(
			'%welcome%'
		))->findOne()->alias;

		/* compare */

		$this->assertEquals('welcome', $result);
	}

	/**
	 * testGetSettings
	 *
	 * @since 2.2.0
	 *
	 */

	public function testGetSettings()
	{
		/* result */

		$result = Db::getSettings('charset');

		/* compare */

		$this->assertEquals('utf-8', $result);
	}

	/**
	 * testOrderGlobal
	 *
	 * @since 2.2.0
	 *
	 */

	public function testOrderGlobal()
	{
		/* result */

		$result = Db::forTablePrefix('categories')->orderGlobal('rank')->findOne()->alias;

		/* compare */

		$this->assertEquals('home', $result);
	}

	/**
	 * testLimitGlobal
	 *
	 * @since 2.2.0
	 *
	 */

	public function testLimitGlobal()
	{
		/* result */

		$result = Db::forTablePrefix('categories')->limitGlobal('rank')->findOne()->alias;

		/* compare */

		$this->assertEquals('home', $result);
	}
}
