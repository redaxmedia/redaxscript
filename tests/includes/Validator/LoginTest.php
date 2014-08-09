<?php

/**
 * Redaxscript Validator_Login Test
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Sven Weingartner
 */
class Redaxscript_Validator_Login_Test extends PHPUnit_Framework_TestCase
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
		$contents = file_get_contents('tests/provider/validator_login.json');
		$output = json_decode($contents, true);
		return $output;
	}

	/**
	 * testLogin
	 *
	 * @since 2.2.0
	 *
	 * @dataProvider provider
	 */

	public function testLogin($login = '', $expect = '')
	{
		$validator = new Redaxscript_Validator_Login();
		$result = $validator->validate($login);

		$this->assertEquals($expect, $result);
	}
}
