<?php
include_once('tests/stubs.php');

/**
 * Redaxscript Breadcrumb Test
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Gary Aylward
 */

class Redaxscript_Breadcrumb_Test extends PHPUnit_Framework_TestCase
{
	/**
	 * registry
	 *
	 * instance of the registry class
	 *
	 * @var object
	 */

	private $_registry;

	/**
	 * setUp
	 *
	 * @since 2.1.0
	 */

	protected function setUp()
	{
		$this->_registry = Redaxscript_Registry::instance();
	}

	/**
	 * providerTestGet
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerTestGet()
	{
		$contents = file_get_contents('tests/provider/breadcrumb_get.json');
		$output = json_decode($contents, true);
		return $output;
	}

	/**
	 * providerTestRender
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerTestRender()
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
	 * @dataProvider providerTestGet
	 */

	public function testGet($registry = null, $expect = array())
	{
		/* setup */

		$this->_registry->init($registry);
		$breadcrumb = new Redaxscript_Breadcrumb($this->_registry);

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
	 * @dataProvider providerTestRender
	 */

	public function testRender($registry = null, $expect = array())
	{
		/* setup */

		$this->_registry->init($registry);
		$breadcrumb = new Redaxscript_Breadcrumb($this->_registry);

		/* result */

		$result = $breadcrumb->render();

		/* compare */

		$this->assertEquals($expect, $result);
	}
}
?>