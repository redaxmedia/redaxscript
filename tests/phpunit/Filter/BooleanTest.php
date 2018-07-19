<?php
namespace Redaxscript\Tests\Filter;

use Redaxscript\Filter;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * BooleanTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Filter\Boolean
 */

class BooleanTest extends TestCaseAbstract
{
	/**
	 * testBoolean
	 *
	 * @since 3.0.0
	 *
	 * @param string $boolean
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testBoolean(string $boolean = null, bool $expect = null)
	{
		/* setup */

		$filter = new Filter\Boolean();

		/* actual */

		$actual = $filter->sanitize($boolean);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
