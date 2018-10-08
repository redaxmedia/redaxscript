<?php
namespace Redaxscript\Tests\Model;

use Redaxscript\Db;
use Redaxscript\Model;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * CategoryTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Model\Category
 */

class CategoryTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.0.0
	 */

	public function setUp()
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
				'alias' => 'category-two',
				'parent' => $categoryOne->id
			])
			->save();
	}

	/**
	 * tearDown
	 *
	 * @since 4.0.0
	 */

	public function tearDown()
	{
		$this->dropDatabase();
	}

	/**
	 * testGetByAlias
	 *
	 * @since 4.0.0
	 *
	 * @param string $categoryAlias
	 * @param int $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetByAlias(string $categoryAlias = null, int $expect = null)
	{
		/* setup */

		$categoryModel = new Model\Category();

		/* actual */

		$actual = $categoryModel->getByAlias($categoryAlias)->id;

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testGetRouteById
	 *
	 * @since 4.0.0
	 *
	 * @param int $categoryId
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetRouteById(int $categoryId = null, string $expect = null)
	{
		/* setup */

		$categoryModel = new Model\Category();

		/* actual */

		$actual = $categoryModel->getRouteById($categoryId);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
