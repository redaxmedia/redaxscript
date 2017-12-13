<?php
namespace Redaxscript\Tests\Validator;

use Redaxscript\Tests\TestCaseAbstract;
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

class EmailTest extends TestCaseAbstract
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
	 * @param int $expect
	 *
	 * @dataProvider providerEmail
	 */

	public function testEmail(string $email = null, int $expect = null)
	{
		/* setup */

		$validator = new Validator\Email();

		/* actual */

		$actual = $validator->validate($email);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
