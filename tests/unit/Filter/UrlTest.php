<?php
namespace Redaxscript\Tests\Filter;

use Redaxscript\Filter;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * UrlTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Filter\Url
 */

class UrlTest extends TestCaseAbstract
{
	/**
	 * testSanitize
	 *
	 * @since 2.2.0
	 *
	 * @param string $url
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testSanitize(string $url = null, string $expect = null) : void
	{
		/* setup */

		$filter = new Filter\Url();

		/* actual */

		$actual = $filter->sanitize($url);

		/* compare */

		$this->assertSame($expect, $actual);
	}
}
