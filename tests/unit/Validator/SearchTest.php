<?php
namespace Redaxscript\Tests\Validator;

use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\Validator;

/**
 * SearchTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Validator\Search
 */

class SearchTest extends TestCaseAbstract
{
	/**
	 * testSearch
	 *
	 * @since 4.0.0
	 *
	 * @param string $search
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testSearch(string $search = null, bool $expect = null) : void
	{
		/* setup */

		$validator = new Validator\Search();

		/* actual */

		$actual = $validator->validate($search);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
