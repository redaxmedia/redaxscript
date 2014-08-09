<?php

/**
 * Redaxscript Validator_Url Test
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Sven Weingartner
 */
class Redaxscript_Validator_Url_Test extends PHPUnit_Framework_TestCase
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
		$contents = file_get_contents('tests/provider/validator_url.json');
		$output = json_decode($contents, true);
		return $output;
	}

	/**
	 * testUrl
	 *
	 * @since 2.2.0
	 *
	 * @dataProvider provider
	 */

	public function testUrl($url = '', $expect = '')
	{
		$validator = new Redaxscript_Validator_Url();
		$result = $validator->validate($url);

		$this->assertEquals($expect, $result);
	}
}
