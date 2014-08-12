<?php

/**
 * Redaxscript Validator_Captcha Test
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Sven Weingartner
 */

class Redaxscript_Validator_Captcha_Test extends PHPUnit_Framework_TestCase
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
		$contents = file_get_contents('tests/provider/validator_captcha.json');
		$output = json_decode($contents, true);
		return $output;
	}

	/**
	 * testCaptcha
	 *
	 * @since 2.2.0
	 *
	 * @dataProvider provider
	 */

	public function testCaptcha($task = '', $solution = '', $expect = '')
	{
		$validator = new Redaxscript_Validator_Captcha();
		$result = $validator->validate($task, $solution);

		$this->assertEquals($expect, $result);
	}
}
