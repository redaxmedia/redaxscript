<?php
namespace Redaxscript\Tests;

use Redaxscript\Breadcrumb;
use Redaxscript\Db;

/**
 * BreadcrumbTest
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Gary Aylward
 */

class BreadcrumbTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp()
	{
		parent::setUp();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings(
		[
			'adminName' => 'Test',
			'adminUser' => 'test',
			'adminPassword' => 'test',
			'adminEmail' => 'test@test.com'
		]);
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
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown()
	{
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawDrop();
	}

	/**
	 * providerGetArray
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerGetArray() : array
	{
		return $this->getProvider('tests/provider/breadcrumb_get_array.json');
	}

	/**
	 * providerRender
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerRender() : array
	{
		return $this->getProvider('tests/provider/breadcrumb_render.json');
	}

	/**
	 * testGetArray
	 *
	 * @since 2.1.0
	 *
	 * @param array $registryArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerGetArray
	 */

	public function testGetArray(array $registryArray = [], array $expectArray = [])
	{
		/* setup */

		$this->_registry->init($registryArray);
		$breadcrumb = new Breadcrumb($this->_registry, $this->_language);
		$breadcrumb->init();

		/* actual */

		$actualArray = $breadcrumb->getArray();

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testRender
	 *
	 * @since 2.1.0
	 *
	 * @param array $registryArray
	 * @param string $expect
	 *
	 * @dataProvider providerRender
	 */

	public function testRender(array $registryArray = [], string $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);
		$breadcrumb = new Breadcrumb($this->_registry, $this->_language);
		$breadcrumb->init();

		/* actual */

		$actual = $breadcrumb;

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
