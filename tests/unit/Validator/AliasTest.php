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
	 * testGetFormPattern
	 *
	 * @since 4.1.0
	 */

	public function testGetFormPattern() : void
	{
		/* setup */

		$validator = new Validator\Alias();

		/* actual */

		$actual = $validator->getFormPattern();

		/* compare */

		$this->assertEquals('[a-z0-9-].{3,100}', $actual);
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
}
