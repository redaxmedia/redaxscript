<?php
namespace Redaxscript\Tests\Filter;

use Redaxscript\Filter;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ToggleTest
 *
 * @since 4.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Filter\Toggle
 */

class ToggleTest extends TestCaseAbstract
{
	/**
	 * testSanitize
	 *
	 * @since 4.2.0
	 *
	 * @param string $toggle
	 * @param int $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testSanitize(string $toggle = null, int $expect = null) : void
	{
		/* setup */

		$filter = new Filter\Toggle();

		/* actual */

		$actual = $filter->sanitize($toggle);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
