<?php
namespace Redaxscript\Tests\Filter;

use Redaxscript\Filter;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * AliasTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Filter\Alias
 */

class AliasTest extends TestCaseAbstract
{
	/**
	 * testSanitize
	 *
	 * @since 2.2.0
	 *
	 * @param string $alias
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testSanitize(string $alias = null, string $expect = null) : void
	{
		/* setup */

		$filter = new Filter\Alias();

		/* actual */

		$actual = $filter->sanitize($alias);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
