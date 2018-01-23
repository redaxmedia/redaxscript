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

	public function providerPassword() : array
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
	 * @param int $expect
	 *
	 * @dataProvider providerPassword
	 */

	public function testPassword(string $password = null, string $hash = null, int $expect = null)
	{
		/* setup */

		$validator = new Validator\Password();

		/* actual */

		$actual = $validator->validate($password, $hash);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
