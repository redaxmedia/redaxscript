<?php
namespace Redaxscript\Tests\View;

use Redaxscript\Db;
use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\View;

/**
 * ArticleTest
 *
 * @since 4.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\View\Article
 * @covers Redaxscript\View\ViewAbstract
 */

class ArticleTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.2.0
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
				'alias' => 'category-two'
			])
			->save();
		$articleOne = Db::forTablePrefix('articles')->create();
		$articleOne
			->set(
			[
				'title' => 'Article One',
				'alias' => 'article-one',
				'text' => 'Article One',
				'category' => $categoryOne->id
			])
			->save();
		$articleTwo = Db::forTablePrefix('articles')->create();
		$articleTwo
			->set(
			[
				'title' => 'Article Two',
				'alias' => 'article-two',
				'text' => 'Article Two',
				'category' => $categoryOne->id
			])
			->save();
		$articleThree = Db::forTablePrefix('articles')->create();
		$articleThree
			->set(
			[
				'title' => 'Article Three',
				'alias' => 'article-three',
				'text' => 'Article Three',
				'category' => $categoryTwo->id
			])
			->save();
		$articleFour = Db::forTablePrefix('articles')->create();
		$articleFour
			->set(
			[
				'title' => 'Article Four',
				'alias' => 'article-four',
				'text' => 'Article Four',
				'access' => '[1]'
			])
			->save();
	}

	/**
	 * tearDown
	 *
	 * @since 4.2.0
	 */

	public function tearDown() : void
	{
		$this->dropDatabase();
	}

	/**
	 * testRender
	 *
	 * @since 4.2.0
	 *
	 * @param array $registryArray
	 * @param array $optionArray
	 * @param int $categoryId
	 * @param int $articleId
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRender(array $registryArray = [], array $optionArray = [], int $categoryId = null, int $articleId = null, string $expect = null) : void
	{
		/* setup */

		$this->_registry->init($registryArray);
		$article = new View\Article($this->_registry, $this->_request, $this->_language, $this->_config);
		$article->init($optionArray);

		/* actual */

		$actual = $article->render($categoryId, $articleId);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
