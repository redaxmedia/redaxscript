<?php
namespace Redaxscript\Tests\Filter;

use Redaxscript\Filter;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * SearchTest
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Filter\Search
 */

class SearchTest extends TestCaseAbstract
{
	/**
	 * testSanitize
	 *
	 * @since 3.1.0
	 *
	 * @param string $search
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testSanitize(string $search = null, string $expect = null) : void
	{
		/* setup */

		$filter = new Filter\Search();

		/* actual */

		$actual = $filter->sanitize($search);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
