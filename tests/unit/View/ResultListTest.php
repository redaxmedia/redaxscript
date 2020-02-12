<?php
namespace Redaxscript\Tests\View;

use Redaxscript\Controller;
use Redaxscript\Db;
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
 *
 * @covers Redaxscript\View\ResultList
 * @covers Redaxscript\View\ViewAbstract
 */

class ResultListTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$optionArray = $this->getOptionArray();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
		$categoryOne = Db::forTablePrefix('categories')->create();
		$categoryOne
			->set(
			[
				'title' => 'Category One',
				'alias' => 'category-one',
				'keywords' => 'category, one',
				'date' => 1483225200
			])
			->save();
		$articleOne = Db::forTablePrefix('articles')->create();
		$articleOne
			->set(
			[
				'title' => 'Article One',
				'alias' => 'article-one',
				'keywords' => 'article, one',
				'category' => $categoryOne->id,
				'date' => 1483225200
			])
			->save();
		Db::forTablePrefix('comments')
			->create()
			->set(
			[
				'author' => 'Comment One',
				'text' => 'Comment One',
				'article' => $articleOne->id,
				'date' => 1451602800
			])
			->save();
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown() : void
	{
		$this->dropDatabase();
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $searchArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRender(array $searchArray = [], string $expect = null) : void
	{
		/* setup */

		$resultList = new View\ResultList($this->_registry, $this->_language);
		$controllerSearch = new Controller\Search($this->_registry, $this->_request, $this->_language, $this->_config);
		$resultArray = $this->callMethod($controllerSearch, '_search',
		[
			$searchArray
		]);

		/* actual */

		$actual = $resultList->render($resultArray);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
