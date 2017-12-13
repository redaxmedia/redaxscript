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
 */

class LoginTest extends TestCaseAbstract
{
	/**
	 * providerLogin
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerLogin() : array
	{
		return $this->getProvider('tests/provider/Validator/login.json');
	}

	/**
	 * testLogin
	 *
	 * @since 2.2.0
	 *
	 * @param string $login
	 * @param int $expect
	 *
	 * @dataProvider providerLogin
	 */

	public function testLogin(string $login = null, int $expect = null)
	{
		/* setup */

		$validator = new Validator\Login();

		/* actual */

		$actual = $validator->validate($login);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
