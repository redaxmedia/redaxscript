<?php
namespace Redaxscript\Tests\Module;

use Redaxscript\Db;
use Redaxscript\Model;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ArticleTest
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ArticleTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.3.0
	 */

	public function setUp()
	{
		parent::setUp();
		$optionArray =
		[
			'adminName' => 'Test',
			'adminUser' => 'test',
			'adminPassword' => 'test',
			'adminEmail' => 'test@test.com'
		];
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
				'rank' => 1,
				'status' => 1
			])
			->save();
		$categoryTwo = Db::forTablePrefix('categories')->create();
		$categoryTwo
			->set(
			[
				'title' => 'Category Two',
				'alias' => 'category-two',
				'rank' => 2,
				'status' => 1
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article One',
				'alias' => 'article-one',
				'category' => $categoryOne->id,
				'rank' => 1,
				'status' => 1
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article Two',
				'alias' => 'article-two',
				'category' => $categoryTwo->id,
				'rank' => 2,
				'status' => 1
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article Three',
				'alias' => 'article-three',
				'rank' => 3,
				'status' => 1
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article Four',
				'alias' => 'article-four',
				'rank' => 4,
				'status' => 2,
				'date' => '2036-01-01 00:00:00'
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article Five',
				'alias' => 'article-five',
				'rank' => 5,
				'status' => 2,
				'date' => '2037-01-01 00:00:00'
			])
			->save();
	}

	/**
	 * tearDown
	 *
	 * @since 3.3.0
	 */

	public function tearDown()
	{
		$this->dropDatabase();
	}

	/**
	 * providerArticleGetId
	 *
	 * @since 3.3.0
	 *
	 * @return array
	 */

	public function providerArticleGetId() : array
	{
		return $this->getProvider('tests/provider/Model/article_get_id.json');
	}

	/**
	 * providerArticleGetRoute
	 *
	 * @since 3.3.0
	 *
	 * @return array
	 */

	public function providerArticleGetRoute() : array
	{
		return $this->getProvider('tests/provider/Model/article_get_route.json');
	}

	/**
	 * providerArticlePublishDate
	 *
	 * @since 3.3.0
	 *
	 * @return array
	 */

	public function providerArticlePublishDate() : array
	{
		return $this->getProvider('tests/provider/Model/article_publish_date.json');
	}

	/**
	 * testGetIdByAlias
	 *
	 * @since 3.3.0
	 *
	 * @param string $alias
	 * @param int $expect
	 *
	 * @dataProvider providerArticleGetId
	 */

	public function testGetIdByAlias(string $alias = null, int $expect = null)
	{
		/* setup */

		$articleModel = new Model\Article();

		/* actual */

		$actual = $articleModel->getIdByAlias($alias);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testGetRouteById
	 *
	 * @since 3.3.0
	 *
	 * @param int $id
	 * @param string $expect
	 *
	 * @dataProvider providerArticleGetRoute
	 */

	public function testGetRouteById(int $id = null, string $expect = null)
	{
		/* setup */

		$articleModel = new Model\Article();

		/* actual */

		$actual = $articleModel->getRouteById($id);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testPublishByDate
	 *
	 * @since 3.3.0
	 *
	 * @param string $date
	 * @param int $expect
	 *
	 * @dataProvider providerArticlePublishDate
	 */

	public function testPublishByDate(string $date = null, int $expect = null)
	{
		/* setup */

		$articleModel = new Model\Article();

		/* actual */

		$actual = $articleModel->publishByDate($date);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
