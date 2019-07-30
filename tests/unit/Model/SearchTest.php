<?php
namespace Redaxscript\Tests\Model;

use Redaxscript\Db;
use Redaxscript\Model;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * SearchTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Model\Search
 */

class SearchTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.0.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$categoryOne = Db::forTablePrefix('categories')->create();
		$categoryOne
			->set(
			[
				'title' => 'Category One',
				'alias' => 'category-one'
			])
			->save();
		Db::forTablePrefix('categories')
			->create()
			->set(
			[
				'title' => 'Category Two',
				'alias' => 'category-two'
			])
			->save();
		$articleOne = Db::forTablePrefix('articles')->create();
		$articleOne
			->set(
			[
				'title' => 'Article One',
				'alias' => 'article-one',
				'category' => $categoryOne->id
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article Two',
				'alias' => 'article-two',
				'category' => $categoryOne->id
			])
			->save();
		Db::forTablePrefix('comments')
			->create()
			->set(
			[
				'author' => 'Comment One',
				'text' => 'comment-one',
				'article' => $articleOne->id
			])
			->save();
		Db::forTablePrefix('comments')
			->create()
			->set(
			[
				'author' => 'Comment Two',
				'text' => 'comment-two',
				'article' => $articleOne->id
			])
			->save();
	}

	/**
	 * tearDown
	 *
	 * @since 4.0.0
	 */

	public function tearDown() : void
	{
		$this->dropDatabase();
	}

	/**
	 * testGetByTable
	 *
	 * @since 4.0.0
	 *
	 * @param string $table
	 * @param string $search
	 * @param string $language
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetByTable(string $table = null, string $search = null, string $language = null, array $expectArray = []) : void
	{
		/* setup */

		$searchModel = new Model\Search();

		/* actual */

		$actualArray = [];
		$actualObject = $searchModel->getByTable($table, $search, $language);

		/* process search */

		foreach ($actualObject as $value)
		{
			$actualArray[] = $value->alias ? : $value->text;
		}

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testCreateColumnArray
	 *
	 * @since 4.0.0
	 *
	 * @param string $table
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testCreateColumnArray(string $table = null, array $expectArray = []) : void
	{
		/* setup */

		$searchModel = new Model\Search();

		/* actual */

		$actualArray = $this->callMethod($searchModel, '_createColumnArray',
		[
			$table
		]);

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}


	/**
	 * testCreateLikeArray
	 *
	 * @since 4.0.0
	 *
	 * @param string $table
	 * @param string $search
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testCreateLikeArray(string $table = null, string $search = null, array $expectArray = []) : void
	{
		/* setup */

		$searchModel = new Model\Search();

		/* actual */

		$actualArray = $this->callMethod($searchModel, '_createLikeArray',
		[
			$table,
			$search
		]);

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}
}
