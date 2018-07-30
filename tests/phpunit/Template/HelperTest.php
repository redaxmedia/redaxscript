<?php
namespace Redaxscript\Tests\Template;

use Redaxscript\Db;
use Redaxscript\Template;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * HelperTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 *
 * @covers Redaxscript\Template\Helper
 * @covers Redaxscript\Template\Helper\Canonical
 * @covers Redaxscript\Template\Helper\Client
 * @covers Redaxscript\Template\Helper\Description
 * @covers Redaxscript\Template\Helper\Direction
 * @covers Redaxscript\Template\Helper\HelperAbstract
 * @covers Redaxscript\Template\Helper\Keywords
 * @covers Redaxscript\Template\Helper\Robots
 * @covers Redaxscript\Template\Helper\Subset
 * @covers Redaxscript\Template\Helper\Title
 */

class HelperTest extends TestCaseAbstract
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
				'alias' => 'category-one'
			])
			->save();
		$categoryTwo = Db::forTablePrefix('categories')->create();
		$categoryTwo
			->set(
			[
				'title' => 'Category Two',
				'alias' => 'category-two',
				'description' => 'Category Two',
				'keywords' => 'Category Two',
				'robots' => 5
			])
			->save();
		$categoryThree = Db::forTablePrefix('categories')->create();
		$categoryThree
			->set(
			[
				'title' => 'Category Three',
				'alias' => 'category-three',
				'parent' => $categoryTwo->id
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
				'description' => 'Article Two',
				'keywords' => 'Article Two',
				'robots' => 4,
				'category' => $categoryTwo->id
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article Three',
				'alias' => 'article-three',
				'category' => $categoryTwo->id
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'title' => 'Article Four',
				'alias' => 'article-four',
				'category' => $categoryThree->id
			])
			->save();
		$setting = $this->settingFactory();
		$setting->set('title', 'Setting');
		$setting->set('description', 'Setting');
		$setting->set('keywords', 'Setting');
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
	 * testGetRegistry
	 *
	 * @since 2.6.0
	 */

	public function testGetRegistry()
	{
		/* setup */

		$this->_registry->set('testKey', 'testValue');

		/* actual */

		$actual = Template\Helper::getRegistry('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testGetLanguage
	 *
	 * @since 2.6.0
	 */

	public function testGetLanguage()
	{
		/* setup */

		$this->_language->set('testKey', 'testValue');

		/* actual */

		$actual = Template\Helper::getLanguage('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testGetSetting
	 *
	 * @since 2.6.0
	 */

	public function testGetSetting()
	{
		/* actual */

		$actual = Template\Helper::getSetting('charset');

		/* compare */

		$this->assertEquals('utf-8', $actual);
	}

	/**
	 * testGetTitle
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetTitle(array $registryArray = [], string $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);

		/* actual */

		$actual = Template\Helper::getTitle();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testGetCanonical
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetCanonical(array $registryArray = [], string $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);

		/* actual */

		$actual = Template\Helper::getCanonical();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testGetDescription
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetDescription(array $registryArray = [], string $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);

		/* actual */

		$actual = Template\Helper::getDescription();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testGetKeywords
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetKeywords(array $registryArray = [], string $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);

		/* actual */

		$actual = Template\Helper::getKeywords();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testGetRobots
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetRobots(array $registryArray = [], string $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);

		/* actual */

		$actual = Template\Helper::getRobots();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testGetTransport
	 *
	 * @since 3.0.0
	 */

	public function testGetTransport()
	{
		/* actual */

		$actualArray = Template\Helper::getTransport();

		/* compare */

		$this->assertArrayHasKey('baseURL', $actualArray);
		$this->assertArrayHasKey('generator', $actualArray);
		$this->assertArrayHasKey('language', $actualArray);
		$this->assertArrayHasKey('registry', $actualArray);
		$this->assertArrayHasKey('version', $actualArray);
	}

	/**
	 * testGetSubset
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetSubset(array $registryArray = [], string $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);

		/* actual */

		$actual = Template\Helper::getSubset();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testGetDirection
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetDirection(array $registryArray = [], string $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);

		/* actual */

		$actual = Template\Helper::getDirection();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testGetClass
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetClass(array $registryArray = [], string $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);

		/* actual */

		$actual = Template\Helper::getClass('rs-is-');

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
