<?php
namespace Redaxscript\Tests\Model;

use Redaxscript\Db;
use Redaxscript\Model;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ModelAbstractTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Model\ModelAbstract
 */

class ModelAbstractTest extends TestCaseAbstract
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
		 Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article One',
				'alias' => 'article-one',
				'language' => 'en'
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article Two',
				'alias' => 'article-two',
				'language' => 'en'
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
	 * testGetById
	 *
	 * @since 4.0.0
	 *
	 * @param int $id
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetById(int $id = null, string $expect = null) : void
	{
		/* setup */

		$articleModel = new Model\Article();

		/* actual */

		$actual = $articleModel->getById($id)->alias;

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testGetById
	 *
	 * @since 4.0.0
	 *
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetAll(array $expectArray = []) : void
	{
		/* setup */

		$articleModel = new Model\Article();

		/* actual */

		$actualArray = [];
		$actualObject = $articleModel->getAll();

		/* process articles */

		foreach ($actualObject as $value)
		{
			$actualArray[] = $value->alias;
		}

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testQuery
	 *
	 * @since 4.0.0
	 *
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testQuery(array $expectArray = []) : void
	{
		/* setup */

		$articleModel = new Model\Article();

		/* actual */

		$actualArray = $articleModel->query()->findFlatArray('id');

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testClearCache
	 *
	 * @since 4.0.0
	 */

	public function testClearCache() : void
	{
		/* setup */

		$articleModel = new Model\Article();

		/* actual */

		$actual = $articleModel->clearCache();

		/* compare */

		$this->assertNull($actual);
	}
}
