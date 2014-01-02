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

/* Include module to test */
include(dirname(__FILE__).'\..\..\includes\breadcrumb.php');
/* Include stubs */
include (dirname(__FILE__).'\..\stubs.php');

/* Define needed constants */
// Test 1
//define('TITLE', 'title');
// Test 2
//define('FULL_ROUTE', '');
// Test 3
//define('FULL_ROUTE','admin');
//define('FIRST_PARAMETER', 'admin');
//define('ADMIN_PARAMETER', '');
//define('LAST_PARAMETER', 'admin');
// Test 4
//define('FULL_ROUTE','admin/view/categories');
//define('FIRST_PARAMETER', 'admin');
//define('SECOND_PARAMETER', 'view');
//define('THIRD_PARAMETER', 'categories');
//define('ADMIN_PARAMETER', 'view');
//define('TABLE_PARAMETER', 'categories');
//define('LAST_PARAMETER', 'categories');
// Test 5
//define('FULL_ROUTE','login');
//define('FIRST_PARAMETER', 'login');
//define('LAST_PARAMETER', 'login');
// Test 6
//define('FULL_ROUTE','test');
//define('FIRST_PARAMETER', 'test');
//define('LAST_PARAMETER', 'test');
//define('LAST_ID', '');
// Test 7
//define('FULL_ROUTE','test');
//define('FIRST_PARAMETER', 'test');
//define('LAST_PARAMETER', 'test');
//define('FIRST_TABLE', 'categories');
//define('LAST_TABLE', 'categories');
//define('LAST_ID', '2');
// Test 8
//define('FULL_ROUTE','test/sub-test');
//define('FIRST_PARAMETER', 'test');
//define('SECOND_PARAMETER', 'sub-test');
//define('LAST_PARAMETER', 'sub-test');
//define('FIRST_TABLE', 'categories');
//define('SECOND_TABLE', 'categories');
//define('LAST_TABLE', 'categories');
//define('LAST_ID', '3');
// Test 8
define('TITLE', '');
define('FULL_ROUTE', 'test/sub-test');
define('FIRST_PARAMETER', 'test');
define('SECOND_PARAMETER', 'sub-test');
define('THIRD_PARAMETER', 'test2');
define('LAST_PARAMETER', 'test2');
define('FIRST_TABLE', 'categories');
define('SECOND_TABLE', 'categories');
define('THIRD_TABLE', 'articles');
define('LAST_TABLE', 'articles');
define('LAST_ID', '3');

/* Define expected result */
// Test 1
//$expectedResult1 = array(array('title' => 'title'));
//$expectedResult2 = '<ul class="list_breadcrumb"><li>title</li></ul>';
// Test 2
//$expectedResult1 = array(array('title' => 'home'));
//$expectedResult2 = '<ul class="list_breadcrumb"><li>home</li></ul>';
// Test 3
//$expectedResult1 = array(array('title' => 'administration'));
//$expectedResult2 = '<ul class="list_breadcrumb"><li>administration</li></ul>';
// Test 4
//$expectedResult1 = array(array('title' => 'administration', 'route' => 'admin'),
//		array('title' => 'view', 'route' => 'admin/view/categories'),
//		array('title' => 'categories'));
//$expectedResult2 = '<ul class="list_breadcrumb"><li><a>administration</a></li><li class="divider">divider</li>';
//$expectedResult2 .= '<li><a>view</a></li><li class="divider">divider</li><li>categories</li></ul>';
// Test 5
//$expectedResult1 = array(array('title' => 'login'));
//$expectedResult2 = '<ul class="list_breadcrumb"><li>login</li></ul>';
// Test 6
//$expectedResult1 = array(array('title' => 'error'));
//$expectedResult2 = '<ul class="list_breadcrumb"><li>error</li></ul>';
// Test 7
//$expectedResult1 = array(array('title' => 'test'));
//$expectedResult2 = '<ul class="list_breadcrumb"><li>test</li></ul>';
// Test 8
//$expectedResult1 = array(array('title' => 'test', 'route' => 'test'), array('title' => 'sub-test'));
//$expectedResult2 = '<ul class="list_breadcrumb"><li><a>test</a></li><li class="divider">divider</li><li>sub-test</li></ul>';
// Test 9
$expectedResult1 = array(array('title' => 'test', 'route' => 'test'),
	array('title' => 'sub-test', 'route' => 'test/sub-test'),
	array('title' => 'test2'));
$expectedResult2 = '<ul class="list_breadcrumb"><li><a>test</a></li><li class="divider">divider</li>';
$expectedResult2 .= '<li><a>sub-test</a></li><li class="divider">divider</li><li>test2</li></ul>';

class Redaxscript_Breadcrumb_Test extends PHPUnit_Framework_TestCase
{
	/*
	  public function testBuildBreadcrumb()
	  {
	  global $expectedResult1;
	  $result = build_breadcrumb();
	  $this->assertEquals($expectedResult1, $result);
	  }
	 */

	public function testBreadcrumb()
	{
		global $expectedResult2;
		$breadcrumb = new Redaxscript_Breadcrumb;
		$result = $breadcrumb->displayBreadcrumb();
		$this->assertEquals($expectedResult2, $result);
	}

}
?>