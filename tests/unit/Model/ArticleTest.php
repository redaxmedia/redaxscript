<?php
namespace Redaxscript\Tests\Model;

use Redaxscript\Db;
use Redaxscript\Model;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ArticleTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Model\Article
 */

class ArticleTest extends TestCaseAbstract
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
		$categoryTwo = Db::forTablePrefix('categories')->create();
		$categoryTwo
			->set(
			[
				'title' => 'Category Two',
				'alias' => 'category-two',
				'parent' => $categoryOne->id
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
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
				'category' => $categoryTwo->id
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article Three',
				'alias' => 'article-three',
				'language' => 'en',
				'category' => $categoryTwo->id
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article Four',
				'alias' => 'article-four'
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
	 * testGetByAlias
	 *
	 * @since 4.0.0
	 *
	 * @param string $articleAlias
	 * @param int $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetByAlias(string $articleAlias = null, int $expect = null) : void
	{
		/* setup */

		$articleModel = new Model\Article();

		/* actual */

		$actual = $articleModel->getByAlias($articleAlias)->id;

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 *
	 * testCountByCategoryAndLanguage
	 *
	 * @since 4.0.0
	 *
	 * @param int $categoryId
	 * @param string $language
	 * @param int $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testCountByCategoryAndLanguage(int $categoryId = null, string $language = null, int $expect = null) : void
	{
		/* setup */

		$articleModel = new Model\Article();

		/* actual */

		$actual = $articleModel->countByCategoryAndLanguage($categoryId, $language);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testGetRouteById
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetRouteById(int $articleId = null, string $expect = null) : void
	{
		/* setup */

		$articleModel = new Model\Article();

		/* actual */

		$actual = $articleModel->getRouteById($articleId);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
