<?php
namespace Redaxscript\Tests\Filter;

use Redaxscript\Filter;
use Redaxscript\Tests\TestCase;

/**
 * SpecialTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class SpecialTest extends TestCase
{
	/**
	 * providerFilterSpecial
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerFilterSpecial()
	{
		return $this->getProvider('tests/provider/Filter/special.json');
	}

	/**
	 * testSpecial
	 *
	 * @since 2.2.0
	 *
	 * @param string $special
	 * @param string $expect
	 *
	 * @dataProvider providerFilterSpecial
	 */

	public function testSpecial($special = null, $expect = null)
	{
		/* setup */

		$filter = new Filter\Special();

		/* actual */

		$actual = $filter->sanitize($special);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
