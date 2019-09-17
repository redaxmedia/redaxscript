<?php
namespace Redaxscript\Tests\View\Helper;

use Redaxscript\Db;
use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\View\Helper\Breadcrumb;

/**
 * BreadcrumbTest
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Gary Aylward
 *
 * @covers Redaxscript\View\Helper\Breadcrumb
 */

class BreadcrumbTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$optionArray = $this->getOptionArray();
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

	public function tearDown() : void
	{
		$this->dropDatabase();
	}

	/**
	 * testGetArray
	 *
	 * @since 2.1.0
	 *
	 * @param array $registryArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetArray(array $registryArray = [], array $expectArray = []) : void
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
	 * @dataProvider providerAutoloader
	 */

	public function testRender(array $registryArray = [], string $expect = null) : void
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
