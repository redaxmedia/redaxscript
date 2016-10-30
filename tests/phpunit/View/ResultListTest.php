<?php
namespace Redaxscript\Tests\View;

use Redaxscript\Controller;
use Redaxscript\Db;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\View;

/**
 * ResultListTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class ResultListTest extends TestCaseAbstract
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

	public function setUp()
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
			->set(
			[
				'title' => 'test',
				'alias' => 'test-one',
				'author' => 'test',
				'text' => 'test',
				'category' => 1,
				'date' => '2017-01-01 00:00:00'
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'test',
				'alias' => 'test-two',
				'author' => 'test',
				'text' => 'test',
				'category' => 1,
				'date' => '2016-01-01 00:00:00'
			])
			->save();
		Db::forTablePrefix('comments')
			->create()
			->set(
			[
				'id' => 2,
				'author' => 'test',
				'email' => 'test@test.com',
				'text' => 'test',
				'article' => 1,
				'date' => '2016-01-01 00:00:00'
			])
			->save();
	}

	/**
	 * tearDownAfterClass
	 *
	 * @since 3.0.0
	 */

	public static function tearDownAfterClass()
	{
		Db::forTablePrefix('articles')->where('title', 'test')->deleteMany();
		Db::forTablePrefix('comments')->where('author', 'test')->deleteMany();
	}

	/**
	 * providerRender
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerRender()
	{
		return $this->getProvider('tests/provider/View/result_list_render.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $searchArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerRender
	 */

	public function testRender($searchArray = [], $expectArray = [])
	{
		/* setup */

		$resultList = new View\ResultList($this->_registry, $this->_language);
		$controllerSearch = new Controller\Search($this->_registry, $this->_language, $this->_request);
		$resultArray = $this->callMethod($controllerSearch, '_search',
		[
			$searchArray
		]);

		/* actual */

		$actualArray = $resultList->render($resultArray);

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}
}
