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
	 * testUrl
	 *
	 * @since 2.2.0
	 *
	 * @param string $url
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testUrl(string $url = null, string $expect = null) : void
	{
		/* setup */

		$filter = new Filter\Url();

		/* actual */

		$actual = $filter->sanitize($url);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
