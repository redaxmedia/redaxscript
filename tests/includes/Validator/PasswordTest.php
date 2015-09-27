<?php
namespace Redaxscript\Tests\Validator;

use Redaxscript\Tests\TestCase;
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

class PasswordTest extends TestCase
{
	/**
	 * providerValidatorPassword
	 *
	 * @since 2.6.0
	 *
	 * @return array
	 */

	public function providerValidatorPassword()
	{
		return $this->getProvider('tests/provider/Validator/password.json');
	}

	/**
	 * testPassword
	 *
	 * @since 2.6.0
	 *
	 * @param string $password
	 * @param string $hash
	 * @param integer $expect
	 *
	 * @dataProvider providerValidatorPassword
	 */

	public function testLogin($password = null, $hash = null, $expect = null)
	{
		/* setup */

		$validator = new Validator\Password();

		/* actual */

		$actual = $validator->validate($password, $hash);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
