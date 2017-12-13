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
 */

class SearchTest extends TestCaseAbstract
{
	/**
	 * providerSearch
	 *
	 * @since 3.1.0
	 *
	 * @return array
	 */

	public function providerSearch() : array
	{
		return $this->getProvider('tests/provider/Filter/search.json');
	}

	/**
	 * testAlias
	 *
	 * @since 3.1.0
	 *
	 * @param string $search
	 * @param string $expect
	 *
	 * @dataProvider providerSearch
	 */

	public function testAlias(string $search = null, string $expect = null)
	{
		/* setup */

		$filter = new Filter\Search();

		/* actual */

		$actual = $filter->sanitize($search);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
