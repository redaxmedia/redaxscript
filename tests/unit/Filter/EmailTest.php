<?php
namespace Redaxscript\Tests\Filter;

use Redaxscript\Filter;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * EmailTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Filter\Email
 */

class EmailTest extends TestCaseAbstract
{
	/**
	 * testEmail
	 *
	 * @since 2.2.0
	 *
	 * @param string $email
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testEmail(string $email = null, string $expect = null) : void
	{
		/* setup */

		$filter = new Filter\Email();

		/* actual */

		$actual = $filter->sanitize($email);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
