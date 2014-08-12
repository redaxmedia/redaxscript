<?php
namespace Redaxscript\Tests\Validator;
use Redaxscript\Tests\TestCase;
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
 */

class AccessTest extends TestCase
{

	/**
	 * providerValidatorAccess
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerValidatorAccess()
	{
		return $this->getProvider('tests/provider/validator_access.json');
	}

	/**
	 * testAccess
	 *
	 * @since 2.2.0
	 *
	 * @dataProvider providerValidatorAccess
	 */

	public function testAccess($access = null, $groups = null, $expect = null)
	{
		/* setup */

		$validator = new Validator\Access();

		/* result */

		$result = $validator->validate($access, $groups);

		/* compare */

		$this->assertEquals($expect, $result);
	}
}
