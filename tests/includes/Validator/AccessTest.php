<?php
use Redaxscript\Validator;

/**
 * AccessTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Sven Weingartner
 */

class AccessTest extends PHPUnit_Framework_TestCase
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
		$contents = file_get_contents('tests/provider/validator_access.json');
		$output = json_decode($contents, true);
		return $output;
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
