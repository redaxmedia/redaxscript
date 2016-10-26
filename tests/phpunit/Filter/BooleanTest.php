<?php
namespace Redaxscript\Tests\Filter;

use Redaxscript\Filter;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * BooleanTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class BooleanTest extends TestCaseAbstract
{
	/**
	 * providerBoolean
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerBoolean()
	{
		return $this->getProvider('tests/provider/Filter/boolean.json');
	}

	/**
	 * testBoolean
	 *
	 * @since 3.0.0
	 *
	 * @param string $boolean
	 * @param string $expect
	 *
	 * @dataProvider providerBoolean
	 */

	public function testBoolean($boolean = null, $expect = null)
	{
		/* setup */

		$filter = new Filter\Boolean();

		/* actual */

		$actual = $filter->sanitize($boolean);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
