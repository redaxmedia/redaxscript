<?php
namespace Redaxscript\Tests\Model;

use Redaxscript\Db;
use Redaxscript\Model;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ContentTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Model\Content
 */

class ContentTest extends TestCaseAbstract
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
		$articleOne = Db::forTablePrefix('articles')->create();
		$articleOne
			->set(
			[
				'title' => 'Article One',
				'alias' => 'article-one',
				'category' => $categoryOne->id
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
	 * testGetTableByAlias
	 *
	 * @since 4.0.0
	 *
	 * @param string $contentAlias
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetTableByAlias(string $contentAlias = null, string $expect = null) : void
	{
		/* setup */

		$contentModel = new Model\Content();

		/* actual */

		$actual = $contentModel->getTableByAlias($contentAlias);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testGetByTableAndId
	 *
	 * @since 4.0.0
	 *
	 * @param string $table
	 * @param int $contentId
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetByTableAndId(string $table = null, int $contentId = null, string $expect = null) : void
	{
		/* setup */

		$contentModel = new Model\Content();

		/* actual */

		$actual = $contentModel->getByTableAndId($table, $contentId)->alias;

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testGetRouteByTableAndId
	 *
	 * @since 4.0.0
	 *
	 * @param string $table
	 * @param int $contentId
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetRouteByTableAndId(string $table = null, int $contentId = null, string $expect = null) : void
	{
		/* setup */

		$contentModel = new Model\Content();

		/* actual */

		$actual = $contentModel->getRouteByTableAndId($table, $contentId);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
