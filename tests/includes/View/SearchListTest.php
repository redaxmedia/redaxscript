<?php
namespace Redaxscript\Tests\View;

use Redaxscript\Db;
use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Tests\TestCase;
use Redaxscript\View;

/**
 * SearchListTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 */

class SearchListTest extends TestCase
{
	/**
	 * instance of the registry class
	 *
	 * @var \Redaxscript\Registry
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var \Redaxscript\Language
	 */

	protected $_language;

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp()
	{
		$this->_registry = Registry::getInstance();
		$this->_language = Language::getInstance();
		Db::forTablePrefix('articles')
			->create()
			->set(array(
				'title' => 'test search',
				'alias' => 'test-one',
				'author' => 'admin',
				'text' => 'test',
				'category' => 1,
				'date' => '2016-04-04 04:00:00'
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
	 * providerRender
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerRender()
	{
		return $this->getProvider('tests/provider/View/search_list_render.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $tableArray array of query tables
	 * @param string $searchParameter
	 * @param array $expect
	 *
	 * @dataProvider providerRender
	 */

	public function testRender($tableArray = array(), $searchParameter = null, $expect = array())
	{
		/* setup */

		$searchList = new View\SearchList($this->_registry, $this->_language);
		$search = $searchParameter['search'];

		//TODO: More test cases

		foreach ($tableArray as $table)
		{
			echo $table . "@@@@@\n";
			$columnArray = array_filter(array(
				$table === 'categories' || $table === 'articles' ? 'title' : null,
				$table === 'categories' || $table === 'articles' ? 'description' : null,
				$table === 'categories' || $table === 'articles' ? 'keywords' : null,
				$table === 'articles' || $table === 'comments' ? 'text' : null
			));
			$likeArray = array_filter(array(
				$table === 'categories' || $table === 'articles' ? '%' . $search . '%' : null,
				$table === 'categories' || $table === 'articles' ? '%' . $search . '%' : null,
				$table === 'categories' || $table === 'articles' ? '%' . $search . '%' : null,
				$table === 'articles' || $table === 'comments' ? '%' . $search . '%' : null
			));

			/* fetch result */

			$result[$table] = Db::forTablePrefix($table)
				->whereLikeMany($columnArray, $likeArray)
				->where('status', 1)
				->whereRaw('(language = ? OR language is ?)', array(
					$this->_registry->get('language'),
					null
				))
				->orderByDesc('date')
				->findMany();
		}

		/* actual */

		$actual = $searchList->render($result);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
