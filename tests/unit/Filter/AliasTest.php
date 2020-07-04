<?php
namespace Redaxscript\Tests\Filter;

use Redaxscript\Filter;
use Redaxscript\Tests\TestCaseAbstract;
use function setlocale;

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
 *
 * @requires OS Linux
 */

class AliasTest extends TestCaseAbstract
{
	/**
	 * testSanitize
	 *
	 * @since 2.2.0
	 *
	 * @param string $locale
	 * @param string $alias
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testSanitize(string $locale = null, string $alias = null, string $expect = null) : void
	{
		/* setup */

		setlocale(LC_ALL, $locale);
		$filter = new Filter\Alias();

		/* actual */

		$actual = $filter->sanitize($alias);

		/* compare */

		$this->assertSame($expect, $actual);
	}
}
