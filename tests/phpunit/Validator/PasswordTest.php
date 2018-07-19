<?php
namespace Redaxscript\Tests\Validator;

use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\Validator;

/**
 * PasswordTest
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Validator\Password
 */

class PasswordTest extends TestCaseAbstract
{
	/**
	 * testPassword
	 *
	 * @since 2.6.0
	 *
	 * @param string $password
	 * @param string $hash
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testPassword(string $password = null, string $hash = null, bool $expect = null)
	{
		/* setup */

		$validator = new Validator\Password();

		/* actual */

		$actual = $validator->validate($password, $hash);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
