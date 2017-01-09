<?php
namespace Redaxscript\Tests\Template;

use Redaxscript\Db;
use Redaxscript\Registry;
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
 */

class HelperTest extends TestCaseAbstract
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp()
	{
		$this->_registry = Registry::getInstance();
	}

	/**
	 * setUpBeforeClass
	 *
	 * @since 3.0.0
	 */

	public static function setUpBeforeClass()
	{
		Db::forTablePrefix('categories')
			->create()
			->set(
			[
				'id' => 2,
				'title' => 'test',
				'alias' => 'test-one',
				'author' => 'test',
				'description' => 'category-description',
				'keywords' => 'category-keywords',
				'robots' => 5
			])
			->save();
		Db::forTablePrefix('categories')
			->create()
			->set(
			[
				'id' => 3,
				'title' => 'test',
				'alias' => 'test-two',
				'author' => 'test',
				'parent' => 2
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'id' => 2,
				'title' => 'test',
				'alias' => 'test-three',
				'author' => 'test',
				'description' => 'article-description',
				'keywords' => 'article-keywords',
				'robots' => 4,
				'text' => 'test',
				'category' => 2
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'id' => 3,
				'title' => 'test',
				'alias' => 'test-four',
				'author' => 'test',
				'text' => 'test',
				'category' => 2
			])
			->save();
		Db::forTablePrefix('articles')
			->create()
			->set(
			[
				'id' => 4,
				'title' => 'test',
				'alias' => 'test-five',
				'author' => 'test',
				'text' => 'test',
				'category' => 3
			])
			->save();
		Db::setSetting('description', 'setting-description');
		Db::setSetting('keywords', 'setting-keywords');
	}

	/**
	 * tearDownAfterClass
	 *
	 * @since 3.0.0
	 */

	public static function tearDownAfterClass()
	{
		Db::forTablePrefix('categories')->whereNotEqual('id', 1)->deleteMany();
		Db::forTablePrefix('articles')->whereNotEqual('id', 1)->deleteMany();
	}

	/**
	 * providerGetCanonical
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerGetCanonical()
	{
		return $this->getProvider('tests/provider/Template/helper_get_canonical.json');
	}

	/**
	 * providerGetDescription
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerGetDescription()
	{
		return $this->getProvider('tests/provider/Template/helper_get_description.json');
	}

	/**
	 * providerGetKeywords
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerGetKeywords()
	{
		return $this->getProvider('tests/provider/Template/helper_get_keywords.json');
	}

	/**
	 * providerGetRobots
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerGetRobots()
	{
		return $this->getProvider('tests/provider/Template/helper_get_robots.json');
	}

	/**
	 * providerGetSubset
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerGetSubset()
	{
		return $this->getProvider('tests/provider/Template/helper_get_subset.json');
	}

	/**
	 * providerGetDirection
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerGetDirection()
	{
		return $this->getProvider('tests/provider/Template/helper_get_direction.json');
	}

	/**
	 * providerGetDirection
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerGetClass()
	{
		return $this->getProvider('tests/provider/Template/helper_get_class.json');
	}

	/**
	 * testGetCanonical
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param string $expect
	 *
	 * @dataProvider providerGetCanonical
	 */

	public function testCanonical($registryArray = [], $expect = null)
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
	 * @dataProvider providerGetDescription
	 */

	public function testGetDescription($registryArray = [], $expect = null)
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
	 * @dataProvider providerGetKeywords
	 */

	public function testGetKeywords($registryArray = [], $expect = null)
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
	 * @dataProvider providerGetRobots
	 */

	public function testGetRobots($registryArray = [], $expect = null)
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
	 * @dataProvider providerGetSubset
	 */

	public function testGetSubset($registryArray = [], $expect = null)
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
	 * @dataProvider providerGetDirection
	 */

	public function testGetDirection($registryArray = [], $expect = null)
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
	 * @dataProvider providerGetClass
	 */

	public function testGetClass($registryArray = [], $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);

		/* actual */

		$actual = Template\Helper::getClass();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
