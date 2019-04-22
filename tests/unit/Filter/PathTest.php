<?php
namespace Redaxscript\Tests\Filter;

use Redaxscript\Filter;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * PathTest
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Filter\Path
 */

class PathTest extends TestCaseAbstract
{
	/**
	 * testPath
	 *
	 * @since 2.6.0
	 *
	 * @param string $path
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testPath(string $path = null, string $expect = null) : void
	{
		/* setup */

		$filter = new Filter\Path();

		/* actual */

		$actual = $filter->sanitize($path, '/');

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
