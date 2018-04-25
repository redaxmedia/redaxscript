<?php
namespace Redaxscript\Tests\Module;

use Redaxscript\Db;
use Redaxscript\Model;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * CategoryTest
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class CategoryTest extends TestCaseAbstract
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
				'status' => 2,
				'date' => '2036-01-01 00:00:00'
			])
			->save();
		$categoryThree = Db::forTablePrefix('categories')->create();
		$categoryThree
			->set(
			[
				'title' => 'Category Three',
				'alias' => 'category-three',
				'parent' => $categoryTwo->id,
				'rank' => 3,
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
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawDrop();
	}

	/**
	 * providerCategoryGetId
	 *
	 * @since 3.3.0
	 *
	 * @return array
	 */

	public function providerCategoryGetId() : array
	{
		return $this->getProvider('tests/provider/Model/category_get_id.json');
	}

	/**
	 * providerCategoryGetRoute
	 *
	 * @since 3.3.0
	 *
	 * @return array
	 */

	public function providerCategoryGetRoute() : array
	{
		return $this->getProvider('tests/provider/Model/category_get_route.json');
	}

	/**
	 * providerCategoryPublishDate
	 *
	 * @since 3.3.0
	 *
	 * @return array
	 */

	public function providerCategoryPublishDate() : array
	{
		return $this->getProvider('tests/provider/Model/category_publish_date.json');
	}

	/**
	 * testGetIdByAlias
	 *
	 * @since 3.3.0
	 *
	 * @param string $alias
	 * @param int $expect
	 *
	 * @dataProvider providerCategoryGetId
	 */

	public function testGetIdByAlias(string $alias = null, int $expect = null)
	{
		/* setup */

		$categoryModel = new Model\Category();

		/* actual */

		$actual = $categoryModel->getIdByAlias($alias);

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
	 * @dataProvider providerCategoryGetRoute
	 */

	public function testGetRouteById(int $id = null, string $expect = null)
	{
		/* setup */

		$categoryModel = new Model\Category();

		/* actual */

		$actual = $categoryModel->getRouteById($id);

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
	 * @dataProvider providerCategoryPublishDate
	 */

	public function testPublishByDate(string $date = null, int $expect = null)
	{
		/* setup */

		$categoryModel = new Model\Category();

		/* actual */

		$actual = $categoryModel->publishByDate($date);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
