<?php
namespace Redaxscript\Tests\View;

use Redaxscript\Db;
use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\View;

/**
 * CommentTest
 *
 * @since 4.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\View\Comment
 * @covers Redaxscript\View\ViewAbstract
 */

class CommentTest extends TestCaseAbstract
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
		$articleOne = Db::forTablePrefix('articles')->create();
		$articleOne
			->set(
			[
				'title' => 'Article One',
				'alias' => 'article-one'
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
				'author' => 'Comment Two',
				'text' => 'Comment Two',
				'article' => $articleTwo->id,
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
	 * @param int $articleId
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRender(array $registryArray = [], array $optionArray = [], int $articleId = null, string $expect = null) : void
	{
		/* setup */

		$this->_registry->init($registryArray);
		$comment = new View\Comment($this->_registry, $this->_language);
		$comment->init($optionArray);

		/* actual */

		$actual = $comment->render($articleId);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
