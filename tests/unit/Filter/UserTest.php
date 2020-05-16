<?php
namespace Redaxscript\Tests\Filter;

use Redaxscript\Filter;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * UserTest
 *
 * @since 4.3.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Filter\User
 */

class UserTest extends TestCaseAbstract
{
	/**
	 * testSanitize
	 *
	 * @since 4.3.0
	 *
	 * @param string $user
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testSanitize(string $user = null, string $expect = null) : void
	{
		/* setup */

		$filter = new Filter\User();

		/* actual */

		$actual = $filter->sanitize($user);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
