<?php
namespace Redaxscript\Tests\Filter;

use Redaxscript\Filter;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * PasswordTest
 *
 * @since 4.3.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Filter\Password
 */

class PasswordTest extends TestCaseAbstract
{
	/**
	 * testSanitize
	 *
	 * @since 4.3.0
	 *
	 * @param string $password
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testSanitize(string $password = null, string $expect = null) : void
	{
		/* setup */

		$filter = new Filter\Password();

		/* actual */

		$actual = $filter->sanitize($password);

		/* compare */

		$this->assertSame($expect, $actual);
	}
}
