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
	 * testGetFormPattern
	 *
	 * @since 4.1.0
	 */

	public function testGetFormPattern() : void
	{
		/* setup */

		$validator = new Validator\Login();

		/* actual */

		$actual = $validator->getFormPattern();

		/* compare */

		$this->assertEquals('[a-zA-Z0-9-]{3,50}', $actual);
	}

	/**
	 * testValidate
	 *
	 * @since 2.2.0
	 *
	 * @param string $login
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testValidate(string $login = null, bool $expect = null) : void
	{
		/* setup */

		$validator = new Validator\Login();

		/* actual */

		$actual = $validator->validate($login);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
