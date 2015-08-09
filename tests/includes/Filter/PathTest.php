<?php
namespace Redaxscript\Tests\Filter;

use Redaxscript\Filter;
use Redaxscript\Tests\TestCase;

/**
 * PathTest
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class PathTest extends TestCase
{
	/**
	 * providerFilterPath
	 *
	 * @since 2.6.0
	 *
	 * @return array
	 */

	public function providerFilterPath()
	{
		return $this->getProvider('tests/provider/Filter/path.json');
	}

	/**
	 * testPath
	 *
	 * @since 2.6.0
	 *
	 * @param string $path
	 * @param string $expect
	 *
	 * @dataProvider providerFilterPath
	 */

	public function testUrl($path = null, $expect = null)
	{
		/* setup */

		$filter = new Filter\Path();

		/* actual */

		$actual = $filter->sanitize($path);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
