<?php
namespace Redaxscript\Tests;

use Redaxscript\Db;
use Redaxscript\Validator;
use Redaxscript\Controller;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Router;

/**
 * SearchTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class SearchTest extends TestCase
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	protected function setUp()
	{
		$this->_registry = Registry::getInstance();
		$this->_language = Language::getInstance();
	}

	/**
	 * setUpBeforeClass
	 *
	 * @since 3.0.0
	 */

	public static function setUpBeforeClass()
	{
		Db::forTablePrefix('articles')
			->create()
			->set(array(
				'title' => 'test search',
				'alias' => 'test-one',
				'author' => 'admin',
				'text' => 'test',
				'category' => 1,
				'date' => '2016-01-01 00:00:00'
			))
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(array(
				'title' => 'test search',
				'alias' => 'test-two',
				'author' => 'admin',
				'text' => 'test',
				'category' => 1,
				'date' => '2016-01-01 00:00:00'
			))
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(array(
				'title' => 'test search',
				'alias' => 'test-three',
				'author' => 'admin',
				'text' => 'test',
				'category' => 1,
				'status' => 0,
				'date' => '2016-01-01 00:00:00'
			))
			->save();
		Db::forTablePrefix('comments')
			->create()
			->set(array(
				'author' => 'test search',
				'email' => 'test@test.com',
				'text' => 'test',
				'article' => 1,
				'date' => '2016-01-01 00:00:00'
			))
			->save();
		Db::forTablePrefix('comments')
			->create()
			->set(array(
				'author' => 'test search',
				'email' => 'test@test.com',
				'text' => 'test',
				'article' => 1,
				'status' => 0,
				'date' => '2016-01-01 00:00:00'
			))
			->save();
	}

	/**
	 * tearDownAfterClass
	 *
	 * @since 3.0.0
	 */

	public static function tearDownAfterClass()
	{
		Db::forTablePrefix('articles')->where('title', 'test search')->deleteMany();
		Db::forTablePrefix('comments')->where('author', 'test search')->deleteMany();
	}

	/**
	 * providerProcess
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerProcess()
	{
		return $this->getProvider('tests/provider/Controller/search_process.json');
	}

	/**
	 * testProcess
	 *
	 * @since 3.0.0
	 *
	 * @param array $registry
	 * @param string $expect
	 *
	 * @dataProvider providerProcess
	 */

	public function testProcess($registry = array(), $expect = null)
	{
		/* setup */

		$this->_registry->init($registry);
		$searchController = new Controller\Search($this->_registry, $this->_language);

		/* actual */

		$actual = $searchController->process();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
