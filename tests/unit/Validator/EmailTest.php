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
 *
 * @covers Redaxscript\Validator\Email
 */

class EmailTest extends TestCaseAbstract
{
	/**
	 * testValidate
	 *
	 * @since 2.2.0
	 *
	 * @param string $email
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testValidate(string $email = null, bool $expect = null) : void
	{
		/* setup */

		$validator = new Validator\Email();

		/* actual */

		$actual = $validator->validate($email);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
