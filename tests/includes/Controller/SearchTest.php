<?php
namespace Redaxscript\Tests;

use Redaxscript\Db;
use Redaxscript\Validator;
use Redaxscript\Controller;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;
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
				'title' => 'test',
				'alias' => 'test',
				'author' => 'admin',
				'text' => 'test text',
				'category' => 1,
				'access' => NULL,
				'date' => '2016-04-04 04:00:00'
			))
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(array(
				'title' => 'test article',
				'alias' => 'test_article',
				'author' => 'admin',
				'text' => 'test text',
				'category' => 1,
				'access' => NULL,
				'date' => '2016-04-04 04:00:00'
			))
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(array(
				'title' => 'article',
				'alias' => 'article',
				'author' => 'admin',
				'text' => 'test text',
				'category' => 1,
				'access' => 1,
				'date' => '2016-04-04 04:00:00'
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
		Db::forTablePrefix('articles')->where('title', 'title')->deleteMany();
		Db::forTablePrefix('articles')->where('title', 'test article')->deleteMany();
		Db::forTablePrefix('articles')->where('title', 'article')->deleteMany();
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
	 * @param string $registry
	 * @param string $expect
	 *
	 * @dataProvider providerProcess
	 */

	public function testProcess($registry = null, $expect = null)
	{
		/* setup */

		$this->_registry->init($registry);

		$searchController = new Controller\Search($this->_registry, $this->_language, $this->_request);

		/* actual */

		$actual = $searchController->process();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
