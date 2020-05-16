<?php
namespace Redaxscript\Tests\Validator;

use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\Validator;

/**
 * AliasTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Sven Weingartner
 *
 * @covers Redaxscript\Validator\Alias
 */

class AliasTest extends TestCaseAbstract
{
	/**
	 * testGetPattern
	 *
	 * @since 4.3.0
	 */

	public function testGetPattern() : void
	{
		/* setup */

		$validator = new Validator\Alias();

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
	 * @param string $alias
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testValidate(string $alias = null, bool $expect = null) : void
	{
		/* setup */

		$validator = new Validator\Alias();

		/* actual */

		$actual = $validator->validate($alias);

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testMatchSystem
	 *
	 * @since 4.3.0
	 *
	 * @param string $alias
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testMatchSystem(string $alias = null, bool $expect = null) : void
	{
		/* setup */

		$validator = new Validator\Alias();

		/* actual */

		$actual = $validator->matchSystem($alias);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
