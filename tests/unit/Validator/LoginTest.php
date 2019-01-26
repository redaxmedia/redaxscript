<?php
namespace Redaxscript\Tests\Validator;

use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\Validator;

/**
 * LoginTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Sven Weingartner
 *
 * @covers Redaxscript\Validator\Login
 */

class LoginTest extends TestCaseAbstract
{
	/**
	 * testLogin
	 *
	 * @since 2.2.0
	 *
	 * @param string $login
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testLogin(string $login = null, bool $expect = null)
	{
		/* setup */

		$validator = new Validator\Login();

		/* actual */

		$actual = $validator->validate($login);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
