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
	 * providerEmail
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerEmail()
	{
		return $this->getProvider('tests/provider/Validator/email.json');
	}

	/**
	 * testEmail
	 *
	 * @since 2.2.0
	 *
	 * @param string $email
	 * @param integer $expect
	 *
	 * @dataProvider providerEmail
	 */

	public function testEmail($email = null, $expect = null)
	{
		/* setup */

		$validator = new Validator\Email();

		/* actual */

		$actual = $validator->validate($email);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
