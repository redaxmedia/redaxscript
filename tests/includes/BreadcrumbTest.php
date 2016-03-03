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

class BreadcrumbTest extends TestCase
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

	protected function setUp()
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
		$ultra->set(array(
			'title' => 'Ultra',
			'alias' => 'ultra',
			'parent' => 0
		));
		$ultra->save();

		/* second parameter */

		$lightweight = Db::forTablePrefix('categories')->create();
		$lightweight->set(array(
			'title' => 'Lightweight',
			'alias' => 'lightweight',
			'parent' => $ultra->id()
		));
		$lightweight->save();

		/* third parameter */

		$cms = Db::forTablePrefix('articles')->create();
		$cms->set(array(
			'title' => 'CMS',
			'alias' => 'cms',
			'category' => $lightweight->id()
		));
		$cms->save();
	}

	/**
	 * tearDownAfterClass
	 *
	 * @since 2.2.0
	 */

	public static function tearDownAfterClass()
	{
		Db::forTablePrefix('categories')->where('alias', 'ultra')->deleteMany();
		Db::forTablePrefix('categories')->where('alias', 'lightweight')->deleteMany();
		Db::forTablePrefix('articles')->where('alias', 'cms')->deleteMany();
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
	 * @param array $registry
	 * @param array $expect
	 *
	 * @dataProvider providerGetArray
	 */

	public function testGetArray($registry = array(), $expect = array())
	{
		/* setup */

		$this->_registry->init($registry);
		$breadcrumb = new Breadcrumb($this->_registry, $this->_language);
		$breadcrumb->init();

		/* actual */

		$actual = $breadcrumb->getArray();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testRender
	 *
	 * @since 2.1.0
	 *
	 * @param array $registry
	 * @param string $expect
	 *
	 * @dataProvider providerRender
	 */

	public function testRender($registry = array(), $expect = null)
	{
		/* setup */

		$this->_registry->init($registry);
		$breadcrumb = new Breadcrumb($this->_registry, $this->_language);
		$breadcrumb->init();

		/* actual */

		$actual = $breadcrumb;

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
