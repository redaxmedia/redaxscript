<?php
namespace Redaxscript\Tests\Filter;

use Redaxscript\Filter;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * NameTest
 *
 * @since 4.3.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Filter\Name
 */

class NameTest extends TestCaseAbstract
{
	/**
	 * testSanitize
	 *
	 * @since 4.3.0
	 *
	 * @param string $name
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testSanitize(string $name = null, string $expect = null) : void
	{
		/* setup */

		$filter = new Filter\Name();

		/* actual */

		$actual = $filter->sanitize($name);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
