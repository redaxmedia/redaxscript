<?php
namespace Redaxscript\Tests\Filter;

use Redaxscript\Filter;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * SpecialTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class SpecialTest extends TestCaseAbstract
{
	/**
	 * providerSpecial
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerSpecial() : array
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
	 * @dataProvider providerSpecial
	 */

	public function testSpecial(string $special = null, string $expect = null)
	{
		/* setup */

		$filter = new Filter\Special();

		/* actual */

		$actual = $filter->sanitize($special);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
