<?php
use Redaxscript\Validator;

/**
 * ValidatorAliasTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Sven Weingartner
 */
class ValidatorAliasTest extends PHPUnit_Framework_TestCase
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
		$validator = new Validator\Alias();
		$result = $validator->validate($alias, $mode);

		$this->assertEquals($expect, $result);
	}
}
