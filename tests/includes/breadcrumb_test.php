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
include_once (dirname(__FILE__) . '/../stubs.php');

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

	private $_registry;

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
				/* inputs */
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
				/* outputs */
				array(
					array('title' => 'title')
				)
			),
			/* test 1 */
			/* single node, home */
			array(
				/* inputs */
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
				/* outputs */
				array(
					array('title' => 'home')
				)
			),
			/* test 2 */
			/* single node, admin */
			array(
				/* inputs */
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
				/* outputs */
				array(
					array('title' => 'administration')
				)
			),
			/* test 3 */
			/* Three nodes, admin + view + e.g. categories */
			array(
				/* inputs */
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
				/* outputs */
				array(
					array('title' => 'administration', 'route' => 'admin'),
					array('title' => 'view', 'route' => 'admin/view/categories'),
					array('title' => 'categories')
				)
			),
			/* test 4 */
			/* single node, has default alias, e.g. login */
			array(
				/* inputs */
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
				/* outputs */
				array(
					array('title' => 'login')
				)
			),
			/* test 5 */
			/* single node, error */
			array(
				/* inputs */
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
				/* outputs */
				array(
					array('title' => 'error')
				)
			),
			/* test 6 */
			/* single node, category */
			array(
				/* inputs */
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
				/* outputs */
				array(
					array('title' => 'test')
				)
			),
			/* test 7 */
			/* Two nodes, category + sub-category */
			array(
				/* inputs */
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
				/* outputs */
				array(
					array('title' => 'test', 'route' => 'test'),
					array('title' => 'sub-test')
				)
			),
			/* test 8 */
			/* Three nodes, category + sub-category + article */
			array(
				/* inputs */
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
				/* outputs */
				array(
					array('title' => 'test', 'route' => 'test'),
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
		$output0 = '<ul class="list_breadcrumb"><li>test</li></ul>';

		$output1 = '<ul class="list_breadcrumb"><li><a>test</a></li>';
		$output1 .= '<li class="divider">divider</li><li>sub-test</li></ul>';

		$output2 = '<ul class="list_breadcrumb"><li><a>test</a></li>';
		$output2 .= '<li class="divider">divider</li>';
		$output2 .= '<li><a>sub-test</a></li><li class="divider">divider</li>';
		$output2 .= '<li>test2</li></ul>';

		return array(
			/* test 0 */
			/* Breadcrumb trail = 1 node */
			array(
				/* inputs */
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
				/* outputs */
				$output0
			),
			/* test 1 */
			/* Breadcrumb trail = 2 nodes */
			array(
				/* inputs */
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
				/* outputs */
				$output1
			),
			/* test 2 */
			/* Breadcrumb trail = 3 nodes */
			array(
				/* inputs */
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
				/* outputs */
				$output2
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
		$this->_registry = Redaxscript_Registry::instance();
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

	public function testGetArray($registry, $expectedResult)
	{
		$this->_registry->init($registry);
		$breadcrumb = new Redaxscript_Breadcrumb($this->_registry);
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

	public function testDisplayBreadcrumb($registry, $expectedResult)
	{
		$this->_registry->init($registry);
		$breadcrumb = new Redaxscript_Breadcrumb($this->_registry);
		$result = $breadcrumb->displayBreadcrumb();
		/* test result */
		$this->assertEquals($expectedResult, $result);
	}

}
?>