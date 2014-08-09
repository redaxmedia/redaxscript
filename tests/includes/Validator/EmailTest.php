<?php

/**
 * Redaxscript Validator_Email Test
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Sven Weingartner
 */
class Redaxscript_Validator_Email_Test extends PHPUnit_Framework_TestCase
{

	/**
	 * provider
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function provider()
	{
		$contents = file_get_contents('tests/provider/validator_email.json');
		$output = json_decode($contents, true);
		return $output;
	}

	/**
	 * testEmail
	 *
	 * @since 2.2.0
	 *
	 * @dataProvider provider
	 */

	public function testEmail($email = '', $expect = '')
	{
		$validator = new Redaxscript_Validator_Email();
		$result = $validator->validate($email);

		$this->assertEquals($expect, $result);
	}
}
