<?php
namespace Redaxscript\Tests\Controller;

use Redaxscript\Db;
use Redaxscript\Controller;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Tests\TestCaseAbstract;

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

class SearchTest extends TestCaseAbstract
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
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	protected function setUp()
	{
		$this->_registry = Registry::getInstance();
		$this->_language = Language::getInstance();
		$this->_request = Request::getInstance();
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
				'id' => 1,
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
				'id' => 2,
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
	 * @param array $registryArray
	 * @param string $expect
	 *
	 * @dataProvider providerProcess
	 */

	public function testProcess($registryArray = array(), $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);
		$searchController = new Controller\Search($this->_registry, $this->_language, $this->_request);

		/* actual */

		$actual = $searchController->process();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
