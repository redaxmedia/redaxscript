<?php
namespace Redaxscript\Tests\Validator;

use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\Validator;

/**
 * AccessTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Sven Weingartner
 *
 * @covers Redaxscript\Validator\Access
 */

class AccessTest extends TestCaseAbstract
{
	/**
	 * testValidate
	 *
	 * @since 2.2.0
	 *
	 * @param string $access
	 * @param string $groups
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testValidate(string $access = null, string $groups = null, bool $expect = null) : void
	{
		/* setup */

		$validator = new Validator\Access();

		/* actual */

		$actual = $validator->validate($access, $groups);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
