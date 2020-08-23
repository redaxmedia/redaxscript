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
		$optionArray = $this->getOptionArray();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
		$installer->rawMigrate();
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
				'language' => 'en',
				'parent' => $categoryOne->id
			])
			->save();
		$categoryTwoSister = Db::forTablePrefix('categories')->create();
		$categoryTwoSister
			->set(
			[
				'title' => 'Category Two Sister',
				'alias' => 'category-two-sister',
				'language' => 'de',
				'sibling' => $categoryTwo->id
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
		$articleThree = Db::forTablePrefix('articles')->create();
		$articleThree
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
					'title' => 'Article Three Sister',
					'alias' => 'article-three-sister',
					'language' => 'de',
					'sibling' => $articleThree->id,
					'category' => $categoryTwoSister->id
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
	 * testGetSiblingByCategoryAndLanguageAndOrderAndStep
	 *
	 * @since 4.0.0
	 *
	 * @param int $categoryId
	 * @param string $language
	 * @param string $orderColumn
	 * @param int $limitStep
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetSiblingByCategoryAndLanguageAndOrderAndStep(int $categoryId = null, string $language = null, string $orderColumn = null, int $limitStep = null, array $expectArray = []) : void
	{
		/* setup */

		$articleModel = new Model\Article();
		$setting = $this->settingFactory();
		$setting->set('limit', 1);

		/* actual */

		$actualArray = [];
		$actualObject = $articleModel->getSiblingByCategoryAndLanguageAndOrderAndStep($categoryId, $language, $orderColumn, $limitStep);

		/* process articles */

		foreach ($actualObject as $value)
		{
			$actualArray[] = $value->alias;
		}

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
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
