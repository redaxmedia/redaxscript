<?php

/**
 * Redaxscript Validator_Access Test
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Sven Weingartner
 */
class Redaxscript_Validator_Access_Test extends PHPUnit_Framework_TestCase
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
		$contents = file_get_contents('tests/provider/validator_access.json');
		$output = json_decode($contents, true);
		return $output;
	}

	/**
	 * testAccess
	 *
	 * @since 2.2.0
	 *
	 * @dataProvider provider
	 */

	public function testAccess($access = '', $groups = '', $expect = '')
	{
		$validator = new Redaxscript_Validator_Access();
		$result = $validator->validate($access, $groups);

		$this->assertEquals($expect, $result);
	}
}
