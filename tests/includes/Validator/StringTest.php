<?php

/**
 * Redaxscript Validator_String Test
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Sven Weingartner
 */
class Redaxscript_Validator_String_Test extends PHPUnit_Framework_TestCase
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
		$contents = file_get_contents('tests/provider/validator_string.json');
		$output = json_decode($contents, true);
		return $output;
	}

	/**
	 * testString
	 *
	 * @since 2.2.0
	 *
	 * @dataProvider provider
	 */

	public function testString($string = '', $minLength = 0, $maxLength = 999, $expect = '')
	{
		$validator = new Redaxscript_Validator_String();
		$result = $validator->validate($string, $minLength, $maxLength);

		$this->assertEquals($expect, $result);
	}
}
