<?php
namespace Redaxscript\Tests\View\Helper;

use Redaxscript\View\Helper;
use Redaxscript\Tests\TestCase;

/**
 * OptionTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class OptionTest extends TestCase
{
	/**
	 * providerOption
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerOption()
	{
		return $this->getProvider('tests/provider/View/Helper/option.json');
	}

	/**
	 * testGetDatabaseArray
	 *
	 * @since 3.0.0
	 *
	 * @param array $expect
	 *
	 * @dataProvider providerOption
	 */

	public function testGetDatabaseArray($expect = array())
	{
		/* actual */

		$actual = Helper\Option::getDatabaseArray();

		/* compare */

		$this->assertArrayHasKey(key($actual), $expect['database']);
	}
}
