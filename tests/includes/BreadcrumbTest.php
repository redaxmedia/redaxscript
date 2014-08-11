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

		$ultra = Db::forPrefixTable('categories')->create();
		$ultra->set(array(
			'title' => 'Ultra',
			'alias' => 'ultra',
			'parent' => 0,
			'status' => 1,
			'access' => 0
		));
		$ultra->save();

		/* second parameter */

		$lightweight = Db::forPrefixTable('categories')->create();
		$lightweight->set(array(
			'title' => 'Lightweight',
			'alias' => 'lightweight',
			'parent' => $ultra->id(),
			'status' => 1,
			'access' => 0
		));
		$lightweight->save();

		/* third parameter */

		$cms = Db::forPrefixTable('articles')->create();
		$cms->set(array(
			'title' => 'CMS',
			'alias' => 'cms',
			'category' => $lightweight->id(),
			'status' => 1,
			'access' => 0
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
		Db::forPrefixTable('categories')->where('alias', 'ultra')->deleteMany();
		Db::forPrefixTable('categories')->where('alias', 'lightweight')->deleteMany();
		Db::forPrefixTable('articles')->where('alias', 'cms')->deleteMany();
	}

	/**
	 * providerGet
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerGet()
	{
		return $this->getProvider('tests/provider/breadcrumb_get.json');
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
	 * testGet
	 *
	 * @since 2.1.0
	 *
	 * @param array $registry
	 * @param array $expect
	 *
	 * @dataProvider providerGet
	 */

	public function testGet($registry = array(), $expect = array())
	{
		/* setup */

		$this->_registry->init($registry);
		$options = array(
			'className' => array(
				'list' => 'list-breadcrumb',
				'divider' => 'item-divider'
			)
		);
		$breadcrumb = new Breadcrumb($this->_registry, $this->_language, $options);

		/* result */

		$result = $breadcrumb->get();

		/* compare */

		$this->assertEquals($expect, $result);
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

		/* result */

		$result = $breadcrumb->render();

		/* compare */

		$this->assertEquals($expect, $result);
	}
}
