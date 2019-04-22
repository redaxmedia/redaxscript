<?php
namespace Redaxscript\Tests\View\Helper;

use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\View\Helper\Pagination;

/**
 * PaginationTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\View\Helper\Pagination
 */

class PaginationTest extends TestCaseAbstract
{
	/**
	 * testRender
	 *
	 * @since 4.0.0
	 *
	 * @param string $route
	 * @param int $current
	 * @param int $total
	 * @param int $range
	 * @param array $optionArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRender(string $route = null, int $current = null, int $total = null, int $range = null, array $optionArray = [], string $expect = null) : void
	{
		/* setup */

		$pagination = new Pagination($this->_registry, $this->_language);
		$pagination->init($optionArray);

		/* actual */

		$actual = $pagination->render($route, $current, $total, $range);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
