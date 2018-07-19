<?php
namespace Redaxscript\Tests\Bootstrap;

use Redaxscript\Bootstrap;
use Redaxscript\Db;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ContentTest
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Bootstrap\BootstrapAbstract
 * @covers Redaxscript\Bootstrap\Content
 *
 * @runTestsInSeparateProcesses
 */

class ContentTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
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
				'status' => 1
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article One',
				'alias' => 'article-one',
				'category' => $categoryOne->id,
				'rank' => 1,
				'status' => 1
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article Two',
				'alias' => 'article-two',
				'category' => $categoryTwo->id,
				'rank' => 2,
				'status' => 1
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
		$this->dropDatabase();
	}

	/**
	 * testContent
	 *
	 * @since 3.1.0
	 *
	 * @param array $registryArray
	 * @param array $settingArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testContent(array $registryArray = [], array $settingArray = [], array $expectArray = [])
	{
		/* setup */

		$this->_registry->init($registryArray);
		$setting = $this->settingFactory();
		$setting->set('homepage', $settingArray['homepage']);
		$setting->set('order', $settingArray['order']);
		new Bootstrap\Content($this->_registry, $this->_request);

		/* actual */

		$actualArray =
		[
			'firstTable' => $this->_registry->get('firstTable'),
			'secondTable' => $this->_registry->get('secondTable'),
			'thirdTable' => $this->_registry->get('thirdTable'),
			'lastTable' => $this->_registry->get('lastTable'),
			'categoryId' => $this->_registry->get('categoryId'),
			'articleId' => $this->_registry->get('articleId'),
			'lastId' => $this->_registry->get('lastId'),
			'contentError' => $this->_registry->get('contentError')
		];

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}
}
