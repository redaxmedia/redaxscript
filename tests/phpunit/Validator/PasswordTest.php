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
 */

class PasswordTest extends TestCaseAbstract
{
	/**
	 * providerPassword
	 *
	 * @since 2.6.0
	 *
	 * @return array
	 */

	public function providerPassword()
	{
		return $this->getProvider('tests/provider/Validator/password.json');
	}

	/**
	 * testPassword
	 *
	 * @since 2.6.0
	 *
	 * @param string $password
	 * @param array $hashArray
	 * @param integer $expect
	 *
	 * @dataProvider providerPassword
	 */

	public function testPassword($password = null, $hashArray = array(), $expect = null)
	{
		/* setup */

		$validator = new Validator\Password();

		/* actual */

		$actual = $validator->validate($password, function_exists('password_verify') ? $hashArray[0] : $hashArray[1]);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
