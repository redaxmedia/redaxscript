<?php
include_once('tests/stubs.php');

/**
 * Redaxscript Breadcrumb Test
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Gary Aylward
 */

class Redaxscript_Breadcrumb_Test extends PHPUnit_Framework_TestCase
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
		$this->_registry = Redaxscript_Registry::getInstance();
		$this->_language = Redaxscript_Language::getInstance();
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
		$contents = file_get_contents('tests/provider/breadcrumb_get.json');
		$output = json_decode($contents, true);
		return $output;
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
		$contents = file_get_contents('tests/provider/breadcrumb_render.json');
		$output = json_decode($contents, true);
		return $output;
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
		$breadcrumb = new Redaxscript_Breadcrumb($this->_registry, $this->_language);

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
		$breadcrumb = new Redaxscript_Breadcrumb($this->_registry, $this->_language);

		/* result */

		$result = $breadcrumb->render();

		/* compare */

		$this->assertEquals($expect, $result);
	}
}
