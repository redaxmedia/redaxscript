<?php
namespace Redaxscript\Tests;

use Redaxscript\Breadcrumb;
use Redaxscript\Db;
use Redaxscript\Language;
use Redaxscript\Registry;

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
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

	/**
	 * setUp
	 *
	 * @since 2.1.0
	 */

	public function setUp()
	{
		$this->_registry = Registry::getInstance();
		$this->_language = Language::getInstance();
	}

	/**
	 * setUpBeforeClass
	 *
	 * @since 2.2.0
	 */

	public static function setUpBeforeClass()
	{
		/* first parameter */

		$ultra = Db::forTablePrefix('categories')->create();
		$ultra->set(
		[
			'title' => 'Ultra',
			'alias' => 'ultra',
			'parent' => 0
		])
		->save();

		/* second parameter */

		$lightweight = Db::forTablePrefix('categories')->create();
		$lightweight->set(
		[
			'title' => 'Lightweight',
			'alias' => 'lightweight',
			'parent' => $ultra->id()
		])
		->save();

		/* third parameter */

		$cms = Db::forTablePrefix('articles')->create();
		$cms->set(
		[
			'title' => 'CMS',
			'alias' => 'cms',
			'category' => $lightweight->id()
		])
		->save();
	}

	/**
	 * tearDownAfterClass
	 *
	 * @since 2.2.0
	 */

	public static function tearDownAfterClass()
	{
		Db::forTablePrefix('categories')->whereNotEqual('id', 1)->deleteMany();
		Db::forTablePrefix('articles')->whereNotEqual('id', 1)->deleteMany();
	}

	/**
	 * providerGetArray
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerGetArray()
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

	public function providerRender()
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

	public function testGetArray($registryArray = [], $expectArray = [])
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

	public function testRender($registryArray = [], $expect = null)
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
