<?php
namespace Redaxscript\Tests\Validator;

use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\Validator;

/**
 * UserTest
 *
 * @since 4.3.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Validator\Name
 */

class NameTest extends TestCaseAbstract
{
	/**
	 * testGetPattern
	 *
	 * @since 4.3.0
	 */

	public function testGetPattern() : void
	{
		/* setup */

		$validator = new Validator\Name();

		/* actual */

		$actual = $validator->getPattern();

		/* compare */

		$this->assertIsString($actual);
	}

	/**
	 * testValidate
	 *
	 * @since 4.3.0
	 *
	 * @param string $name
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testValidate(string $name = null, bool $expect = null) : void
	{
		/* setup */

		$validator = new Validator\Name();

		/* actual */

		$actual = $validator->validate($name);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
