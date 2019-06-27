<?php
namespace Redaxscript\Tests\Model;

use Redaxscript\Db;
use Redaxscript\Model;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * CommentTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Model\Comment
 */

class CommentTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.0.0
	 */

	public function setUp() : void
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
				'alias' => 'category-one'
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
		$articleTwo = Db::forTablePrefix('articles')->create();
		$articleTwo
			->set(
			[
				'title' => 'Article Two',
				'alias' => 'article-two'
			])
			->save();
		Db::forTablePrefix('comments')
			->create()
			->set(
			[
				'author' => 'Comment One',
				'text' => 'Comment One',
				'article' => $articleOne->id
			])
			->save();
		Db::forTablePrefix('comments')
			->create()
			->set(
			[
				'author' => 'Comment Two',
				'text' => 'Comment Two',
				'article' => $articleTwo->id
			])
			->save();
		Db::forTablePrefix('comments')
			->create()
			->set(
			[
				'author' => 'Comment Three',
				'text' => 'Comment Three',
				'language' => 'en',
				'article' => $articleTwo->id
			])
			->save();
		Db::forTablePrefix('comments')
			->create()
			->set(
			[
				'author' => 'Comment Four',
				'text' => 'Comment Four',
				'language' => 'de',
				'article' => $articleTwo->id
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
	 * testCountByArticleAndLanguage
	 *
	 * @since 4.0.0
	 *
	 * @param int $articleId
	 * @param string $language
	 * @param int $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testCountByArticleAndLanguage(int $articleId = null, string $language = null, int $expect = null) : void
	{
		/* setup */

		$commentModel = new Model\Comment();

		/* actual */

		$actual = $commentModel->countByArticleAndLanguage($articleId, $language);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testGetByArticleAndLanguageAndOrderAndStep
	 *
	 * @since 4.0.0
	 *
	 * @param int|null $articleId
	 * @param string|null $language
	 * @param string|null $orderColumn
	 * @param int|null $limitStep
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetByArticleAndLanguageAndOrderAndStep(int $articleId = null, string $language = null, string $orderColumn = null, int $limitStep = null, array $expectArray = []) : void
	{
		/* setup */

		$commentModel = new Model\Comment();
		$setting = $this->settingFactory();
		$setting->set('limit', 1);

		/* actual */

		$actualArray = [];
		$actualObject = $commentModel->getByArticleAndLanguageAndOrderAndStep($articleId, $language, $orderColumn, $limitStep);

		/* process comments */

		foreach ($actualObject as $value)
		{
			$actualArray[] = $value->author;
		}

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testGetRouteById
	 *
	 * @since 4.0.0
	 *
	 * @param int $commentId
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetRouteById(int $commentId = null, string $expect = null) : void
	{
		/* setup */

		$commentModel = new Model\Comment();

		/* actual */

		$actual = $commentModel->getRouteById($commentId);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testCreateByArray
	 *
	 * @since 4.0.0
	 *
	 * @param array $createArray
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testCreateByArray(array $createArray = [], bool $expect = null) : void
	{
		/* setup */

		$commentModel = new Model\Comment();

		/* actual */

		$actual = $commentModel->createByArray($createArray);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
