<?php

/**
 * Redaxscript Validator_Alias Test
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Test
 * @author Sven Weingartner
 */
class Redaxscript_Validator_Alias_Test extends PHPUnit_Framework_TestCase
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
		$contents = file_get_contents('tests/provider/validator_alias.json');
		$output = json_decode($contents, true);
		return $output;
	}

	/**
	 * testAlias
	 *
	 * @since 2.2.0
	 *
	 * @dataProvider provider
	 */

	public function testAlias($alias = '', $mode = '', $expect = '')
	{
		$validator = new Redaxscript_Validator_Alias();
		$result = $validator->validate($alias, $mode);

		$this->assertEquals($expect, $result);
	}
}
