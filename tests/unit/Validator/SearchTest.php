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
	 * testGetFormPattern
	 *
	 * @since 4.1.0
	 */

	public function testGetFormPattern() : void
	{
		/* setup */

		$validator = new Validator\Search();

		/* actual */

		$actual = $validator->getFormPattern();

		/* compare */

		$this->assertEquals('[a-zA-Z0-9-]{3,100}', $actual);
	}

	/**
	 * testValidate
	 *
	 * @since 4.0.0
	 *
	 * @param string $search
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testValidate(string $search = null, bool $expect = null) : void
	{
		/* setup */

		$validator = new Validator\Search();

		/* actual */

		$actual = $validator->validate($search);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
