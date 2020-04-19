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
	 * testGetFormPattern
	 *
	 * @since 4.3.0
	 */

	public function testGetFormPattern() : void
	{
		/* setup */

		$validator = new Validator\Password();

		/* actual */

		$actual = $validator->getFormPattern();

		/* compare */

		$this->assertEquals('(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]){10,100}', $actual);
	}

	/**
	 * testValidate
	 *
	 * @since 4.3.0
	 *
	 * @param string $password
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testValidate(string $password = null, bool $expect = null) : void
	{
		/* setup */

		$validator = new Validator\Password();

		/* actual */

		$actual = $validator->validate($password);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testMatchHash
	 *
	 * @since 4.3.0
	 *
	 * @param string $password
	 * @param string $hash
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testMatchHash(string $password = null, string $hash = null, bool $expect = null) : void
	{
		/* setup */

		$validator = new Validator\Password();

		/* actual */

		$actual = $validator->matchHash($password, $hash);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
