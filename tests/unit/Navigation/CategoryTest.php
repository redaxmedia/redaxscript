<?php
namespace Redaxscript\Tests\Navigation;

use Redaxscript\Db;
use Redaxscript\Navigation;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * CategoryTest
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Navigation\Category
 * @covers Redaxscript\Navigation\NavigationAbstract
 */

class CategoryTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.3.0
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
		$categoryTwo = Db::forTablePrefix('categories')->create();
		$categoryTwo
			->set(
			[
				'title' => 'Category Three',
				'alias' => 'category-three',
				'parent' => $categoryOne->id,
				'rank' => 2,
				'status' => 1
			])
			->save();
	}

	/**
	 * tearDown
	 *
	 * @since 3.3.0
	 */

	public function tearDown() : void
	{
		$this->dropDatabase();
	}

	/**
	 * testRender
	 *
	 * @since 3.3.0
	 *
	 * @param array $registryArray
	 * @param array $optionArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRender(array $registryArray = [], array $optionArray = [], string $expect = null) : void
	{
		/* setup */

		$this->_registry->init($registryArray);
		$navigation = new Navigation\Category($this->_registry, $this->_language);
		$navigation->init($optionArray);

		/* actual */

		$actual = $navigation;

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
