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
 */

class UrlTest extends TestCaseAbstract
{
	/**
	 * providerUrl
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerUrl() : array
	{
		return $this->getProvider('tests/provider/Filter/url.json');
	}

	/**
	 * testUrl
	 *
	 * @since 2.2.0
	 *
	 * @param string $url
	 * @param string $expect
	 *
	 * @dataProvider providerUrl
	 */

	public function testUrl(string $url = null, string $expect = null)
	{
		/* setup */

		$filter = new Filter\Url();

		/* actual */

		$actual = $filter->sanitize($url);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
