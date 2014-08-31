<?php
namespace Redaxscript\Tests\Filter;

use Redaxscript\Filter;
use Redaxscript\Tests\TestCase;

/**
 * AliasTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class AliasTest extends TestCase
{
	/**
	 * providerFilterAlias
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerFilterAlias()
	{
		return $this->getProvider('tests/provider/Filter/alias.json');
	}

	/**
	 * testAlias
	 *
	 * @since 2.2.0
	 *
	 * @param string $alias
	 * @param string $expect
	 *
	 * @dataProvider providerFilterAlias
	 */

	public function testAlias($alias = null, $expect = null)
	{
		/* setup */

		$filter = new Filter\Alias();

		/* result */

		$result = $filter->filter($alias);

		/* compare */

		$this->assertEquals($expect, $result);
	}
}
