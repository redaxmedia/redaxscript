<?php

/**
 * breadcrumb test
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Gary Aylward
 */

/* Include stubs */
include (dirname(__FILE__).'\..\stubs.php');

/**
 * Redaxscript_Breadcrumb_Test
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Gary Aylward
 */

class Redaxscript_Breadcrumb_Test extends PHPUnit_Framework_TestCase
{

	private $_constants;

	/**
	 * providerTestGetArray
	 *
	 * Data provider for testGetArray
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */
	public function providerTestGetArray()
	{
		return array(
			/* test 0 */
			/* single node, module title */
			array(
				array(
					'title' => 'title',
					'fullRoute' => '',
					'firstParameter' => '',
					'secondParameter' => '',
					'thirdParameter' => '',
					'adminParameter' => '',
					'tableParameter' => '',
					'lastParameter' => '',
					'firstTable' => '',
					'secondTable' => '',
					'thirdTable' => '',
					'lastTable' => '',
					'lastId' => ''
				),
				array(array('title' => 'title'))
			),
			/* test 1 */
			/* single node, home */
			array(
				array(
					'title' => '',
					'fullRoute' => '',
					'firstParameter' => '',
					'secondParameter' => '',
					'thirdParameter' => '',
					'adminParameter' => '',
					'tableParameter' => '',
					'lastParameter' => '',
					'firstTable' => '',
					'secondTable' => '',
					'thirdTable' => '',
					'lastTable' => '',
					'lastId' => ''
				),
				array(array('title' => 'home'))
			),
			/* test 2 */
			/* single node, admin */
			array(
				array(
					'title' => '',
					'fullRoute' => 'admin',
					'firstParameter' => 'admin',
					'secondParameter' => '',
					'thirdParameter' => '',
					'adminParameter' => '',
					'tableParameter' => '',
					'lastParameter' => 'admin',
					'firstTable' => '',
					'secondTable' => '',
					'thirdTable' => '',
					'lastTable' => '',
					'lastId' => ''
				),
				array(array('title' => 'administration'))
			),
			/* test 3 */
			/* Three nodes, admin + view + e.g. categories */
			array(
				array(
					'title' => '',
					'fullRoute' => 'admin/view/categories',
					'firstParameter' => 'admin',
					'secondParameter' => 'view',
					'thirdParameter' => 'categories',
					'adminParameter' => 'view',
					'tableParameter' => 'categories',
					'lastParameter' => 'categories',
					'firstTable' => '',
					'secondTable' => '',
					'thirdTable' => '',
					'lastTable' => '',
					'lastId' => ''
				),
				array(array('title' => 'administration', 'route' => 'admin'),
					array('title' => 'view', 'route' => 'admin/view/categories'),
					array('title' => 'categories')
				)
			),
			/* test 4 */
			/* single node, has default alias, e.g. login */
			array(
				array(
					'title' => '',
					'fullRoute' => 'login',
					'firstParameter' => 'login',
					'secondParameter' => '',
					'thirdParameter' => '',
					'adminParameter' => '',
					'tableParameter' => '',
					'lastParameter' => 'login',
					'firstTable' => '',
					'secondTable' => '',
					'thirdTable' => '',
					'lastTable' => '',
					'lastId' => ''
				),
				array(array('title' => 'login'))
			),
			/* test 5 */
			/* single node, error */
			array(
				array(
					'title' => '',
					'fullRoute' => 'test',
					'firstParameter' => 'test',
					'secondParameter' => '',
					'thirdParameter' => '',
					'adminParameter' => '',
					'tableParameter' => '',
					'lastParameter' => 'test',
					'firstTable' => '',
					'secondTable' => '',
					'thirdTable' => '',
					'lastTable' => '',
					'lastId' => ''
				),
				array(array('title' => 'error'))
			),
			/* test 6 */
			/* single node, category */
			array(
				array(
					'title' => '',
					'fullRoute' => 'test',
					'firstParameter' => 'test',
					'secondParameter' => '',
					'thirdParameter' => '',
					'adminParameter' => '',
					'tableParameter' => '',
					'lastParameter' => 'test',
					'firstTable' => 'categories',
					'secondTable' => '',
					'thirdTable' => '',
					'lastTable' => 'categories',
					'lastId' => '2'
				),
				array(array('title' => 'test'))
			),
			/* test 7 */
			/* Two nodes, category + sub-category */
			array(
				array(
					'title' => '',
					'fullRoute' => 'test/sub-test',
					'firstParameter' => 'test',
					'secondParameter' => 'sub-test',
					'thirdParameter' => '',
					'adminParameter' => '',
					'tableParameter' => '',
					'lastParameter' => 'sub-test',
					'firstTable' => 'categories',
					'secondTable' => 'categories',
					'thirdTable' => '',
					'lastTable' => 'categories',
					'lastId' => '3'
				),
				array(array('title' => 'test', 'route' => 'test'),
					array('title' => 'sub-test')
				)
			),
			/* test 8 */
			/* Three nodes, category + sub-category + article */
			array(
				array(
					'title' => '',
					'fullRoute' => 'test/sub-test/test2',
					'firstParameter' => 'test',
					'secondParameter' => 'sub-test',
					'thirdParameter' => 'test2',
					'adminParameter' => '',
					'tableParameter' => '',
					'lastParameter' => 'test2',
					'firstTable' => 'categories',
					'secondTable' => 'categories',
					'thirdTable' => 'articles',
					'lastTable' => 'articles',
					'lastId' => '3'
				),
				array(array('title' => 'test', 'route' => 'test'),
					array('title' => 'sub-test', 'route' => 'test/sub-test'),
					array('title' => 'test2')
				)
			)
		);
	}

	/**
	 * providerTestDisplayBreadcrumb
	 *
	 * Data provider for testDisplayBreadcrumb
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */
	public function providerTestDisplayBreadcrumb()
	{
		return array(
			/* test 0 */
			/* Breadcrumb trail = 1 node */
			array(
				array(
					'title' => '',
					'fullRoute' => 'test',
					'firstParameter' => 'test',
					'secondParameter' => '',
					'thirdParameter' => '',
					'adminParameter' => '',
					'tableParameter' => '',
					'lastParameter' => 'test',
					'firstTable' => 'categories',
					'secondTable' => '',
					'thirdTable' => '',
					'lastTable' => 'categories',
					'lastId' => '2'
				),
				'<ul class="list_breadcrumb"><li>test</li></ul>'
			),
			/* test 1 */
			/* Breadcrumb trail = 2 nodes */
			array(
				array(
					'title' => '',
					'fullRoute' => 'test/sub-test',
					'firstParameter' => 'test',
					'secondParameter' => 'sub-test',
					'thirdParameter' => '',
					'adminParameter' => '',
					'tableParameter' => '',
					'lastParameter' => 'sub-test',
					'firstTable' => 'categories',
					'secondTable' => 'categories',
					'thirdTable' => '',
					'lastTable' => 'categories',
					'lastId' => '3'
				),
				'<ul class="list_breadcrumb"><li><a>test</a></li>'
				. '<li class="divider">divider</li><li>sub-test</li></ul>'
			),
			/* test 2 */
			/* Breadcrumb trail= 3 nodes */
			array(
				array(
					'title' => '',
					'fullRoute' => 'test/sub-test/test2',
					'firstParameter' => 'test',
					'secondParameter' => 'sub-test',
					'thirdParameter' => 'test2',
					'adminParameter' => '',
					'tableParameter' => '',
					'lastParameter' => 'test2',
					'firstTable' => 'categories',
					'secondTable' => 'categories',
					'thirdTable' => 'articles',
					'lastTable' => 'articles',
					'lastId' => '3'
				),
				'<ul class="list_breadcrumb"><li><a>test</a></li>'
				. '<li class="divider">divider</li>'
				. '<li><a>sub-test</a></li><li class="divider">divider</li>'
				. '<li>test2</li></ul>'
			)
		);
	}

	/**
	 * setUp
	 *
	 * Gets an instance of the constants class to be used in each test
	 *
	 * @since 2.1.0
	 */
	protected function setUp()
	{
		$this->_constants = Redaxscript_Constants::getInstance();
	}

	/**
	 * testGetArray
	 *
	 * Test for the getArray method
	 *
	 * @since 2.1.0
	 *
	 * @dataProvider providerTestGetArray
	 */

	public function testGetArray($constants, $expectedResult)
	{
		$this->_constants->init($constants);
		$breadcrumb = new Redaxscript_Breadcrumb($this->_constants);
		$breadcrumb->init($this->_constants);
		$result = $breadcrumb->getArray();
		/* test result */
		$this->assertEquals($expectedResult, $result);
	}

	/**
	 * testDisplayBreadcrumb
	 *
	 * Test for the displayBreadcrumb method
	 *
	 * @since 2.1.0
	 *
	 * @dataProvider providerTestDisplayBreadcrumb
	 */

	public function testDisplayBreadcrumb($constants, $expectedResult)
	{
		$this->_constants->init($constants);
		$breadcrumb = new Redaxscript_Breadcrumb($this->_constants);
		$breadcrumb->init($this->_constants);
		$result = $breadcrumb->displayBreadcrumb();
		/* test result */
		$this->assertEquals($expectedResult, $result);
	}

}
?>