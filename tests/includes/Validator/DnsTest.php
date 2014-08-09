<?php

/**
 * Redaxscript Validator_Dns Test
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Sven Weingartner
 */
class Redaxscript_Validator_Dns_Test extends PHPUnit_Framework_TestCase
{

	/**
	 * providerDns
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerDns()
	{
		$contents = file_get_contents('tests/provider/validator_dns.json');
		$output = json_decode($contents, true);
		return $output;
	}

	/**
	 * testDns
	 *
	 * @since 2.2.0
	 *
	 * @dataProvider providerDns
	 */

	public function testDns($host = '', $type = '', $expect = '')
	{
		// Note: The checkdnsrr function is not implemented on the Windows platform
		if (!function_exists('checkdnsrr'))
		{
			return;
		}

		$validator = new Redaxscript_Validator_Dns();
		$result = $validator->validate($host, $type);

		$this->assertEquals($expect, $result);
	}
}
