<?php
namespace Redaxscript\Tests\Validator;
use Redaxscript\Tests\TestCase;
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

class LoginTest extends TestCase
{
	/**
	 * providerValidatorLogin
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerValidatorLogin()
	{
		return $this->getProvider('tests/provider/validator_login.json');
	}

	/**
	 * testLogin
	 *
	 * @since 2.2.0
	 *
	 * @param string $login
	 * @param integer $expect
	 *
	 * @dataProvider providerValidatorLogin
	 */

	public function testLogin($login = null, $expect = null)
	{
		/* setup */

		$validator = new Validator\Login();

		/* result */

		$result = $validator->validate($login);

		/* compare */

		$this->assertEquals($expect, $result);
	}
}
