<?php
namespace Redaxscript\Tests\Filter;

use Redaxscript\Filter;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * NumberTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Filter\Number
 */

class NumberTest extends TestCaseAbstract
{
	/**
	 * testSanitize
	 *
	 * @since 2.2.0
	 *
	 * @param string $number
	 * @param int $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testSanitize(string $number = null, int $expect = null) : void
	{
		/* setup */

		$filter = new Filter\Number();

		/* actual */

		$actual = $filter->sanitize($number);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
