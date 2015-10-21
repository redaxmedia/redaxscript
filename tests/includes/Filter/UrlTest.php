<?php
namespace Redaxscript\Tests\Filter;

use Redaxscript\Filter;
use Redaxscript\Tests\TestCase;

/**
 * UrlTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class UrlTest extends TestCase
{
	/**
	 * providerUrl
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerUrl()
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

	public function testUrl($url = null, $expect = null)
	{
		/* setup */

		$filter = new Filter\Url();

		/* actual */

		$actual = $filter->sanitize($url);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
