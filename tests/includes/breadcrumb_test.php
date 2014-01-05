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

	public function providerTestGetArray()
	{
		return array(
			/* test 1 */
			array(
				array(
					'title' => 'title',
					'fullRoute' => '',
					'adminParameter' => '',
					'tableParameter' => '',
				),
				array(array('title' => 'title'))
			),
			/* test 2 */
			array(
				array(
					'title' => '',
					'fullRoute' => '',
					'adminParameter' => '',
					'tableParameter' => '',
				),
				array(array('title' => 'home'))
			),
			/* test 3 */
			array(
				array(
					'title' => '',
					'fullRoute' => 'admin',
					'firstParameter' => 'admin',
					'adminParameter' => '',
					'tableParameter' => '',
					'lastParameter' => 'admin'
				),
				array(array('title' => 'administration'))
			),
			/* test 4 */
			array(
				array(
					'title' => '',
					'fullRoute' => 'admin/view/categories',
					'firstParameter' => 'admin',
					'secondParameter' => 'view',
					'thirdParameter' => 'categories',
					'adminParameter' => 'view',
					'tableParameter' => 'categories',
					'lastParameter' => 'categories'
				),
				array(array('title' => 'administration', 'route' => 'admin'),
					array('title' => 'view', 'route' => 'admin/view/categories'),
					array('title' => 'categories')
				)
			),
			/* test 5 */
			array(
				array(
					'title' => '',
					'fullRoute' => 'login',
					'firstParameter' => 'login',
					'secondParameter' => '',
					'thirdParameter' => '',
					'adminParameter' => '',
					'tableParameter' => '',
					'lastParameter' => 'login'
				),
				array(array('title' => 'login'))
			),
			/* test 6 */
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
					'lastId' => ''
				),
				array(array('title' => 'error'))
			),
			/* test 7 */
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
					'lastTable' => 'categories',
					'lastId' => '2'
				),
				array(array('title' => 'test'))
			),
			/* test 8 */
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
					'lastTable' => 'categories',
					'lastId' => '3'
				),
				array(array('title' => 'test', 'route' => 'test'),
					array('title' => 'sub-test')
				)
			),
			/* test 9 */
			array(
				array(
					'title' => '',
					'fullRoute' => 'test/sub-test',
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

	public function providerTestDisplayBreadcrumb()
	{
		return array(
			/* test 1 */
			array(
				array(
					'title' => 'title',
					'fullRoute' => '',
					'adminParameter' => '',
					'tableParameter' => '',
				),
				'<ul class="list_breadcrumb"><li>title</li></ul>'
			),
			/* test 2 */
			array(
				array(
					'title' => '',
					'fullRoute' => '',
					'adminParameter' => '',
					'tableParameter' => '',
				),
				'<ul class="list_breadcrumb"><li>home</li></ul>'
			),
			/* test 3 */
			array(
				array(
					'title' => '',
					'fullRoute' => 'admin',
					'firstParameter' => 'admin',
					'adminParameter' => '',
					'tableParameter' => '',
					'lastParameter' => 'admin'
				),
				'<ul class="list_breadcrumb"><li>administration</li></ul>'
			),
			/* test 4 */
			array(
				array(
					'title' => '',
					'fullRoute' => 'admin/view/categories',
					'firstParameter' => 'admin',
					'secondParameter' => 'view',
					'thirdParameter' => 'categories',
					'adminParameter' => 'view',
					'tableParameter' => 'categories',
					'lastParameter' => 'categories'
				),
				'<ul class="list_breadcrumb"><li><a>administration</a></li>'
				. '<li class="divider">divider</li>'
				. '<li><a>view</a></li><li class="divider">divider</li>'
				. '<li>categories</li></ul>'
			),
			/* test 5 */
			array(
				array(
					'title' => '',
					'fullRoute' => 'login',
					'firstParameter' => 'login',
					'secondParameter' => '',
					'thirdParameter' => '',
					'adminParameter' => '',
					'tableParameter' => '',
					'lastParameter' => 'login'
				),
				'<ul class="list_breadcrumb"><li>login</li></ul>'
			),
			/* test 6 */
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
					'lastId' => ''
				),
				'<ul class="list_breadcrumb"><li>error</li></ul>'
			),
			/* test 7 */
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
					'lastTable' => 'categories',
					'lastId' => '2'
				),
				'<ul class="list_breadcrumb"><li>test</li></ul>'
			),
			/* test 8 */
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
					'lastTable' => 'categories',
					'lastId' => '3'
				),
				'<ul class="list_breadcrumb"><li><a>test</a></li>'
				. '<li class="divider">divider</li><li>sub-test</li></ul>'
			),
			/* test 9 */
			array(
				array(
					'title' => '',
					'fullRoute' => 'test/sub-test',
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


	protected function setUp()
	{
		$this->_constants = new Redaxscript_Constants(array());
	}


	/**
	 * testGetArray
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