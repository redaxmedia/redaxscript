<?php
namespace Redaxscript\Tests\Validator;

use Redaxscript\Tests\TestCase;
use Redaxscript\Validator;

/**
 * EmailTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Sven Weingartner
 */

class EmailTest extends TestCase
{
	/**
	 * providerValidatorEmail
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerValidatorEmail()
	{
		return $this->getProvider('tests/provider/validator_email.json');
	}

	/**
	 * testEmail
	 *
	 * @since 2.2.0
	 *
	 * @param string $email
	 * @param integer $expect
	 *
	 * @dataProvider providerValidatorEmail
	 */

	public function testEmail($email = null, $expect = null)
	{
		/* setup */

		$validator = new Validator\Email();

		/* result */

		$result = $validator->validate($email);

		/* compare */

		$this->assertEquals($expect, $result);
	}
}
