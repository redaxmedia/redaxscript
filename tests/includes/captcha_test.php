<?php

/**
 * captcha test
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Gary Aylward
 */

/* Include stubs */
include_once (dirname(__FILE__) . '/../stubs.php');

/**
 * Redaxscript_Captcha_Test
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Gary Aylward
 */

class Redaxscript_Captcha_Test extends PHPUnit_Framework_TestCase
{
	/**
	 * setUpBeforeClass
	 *
	 * seed random number generator before start of tests so that we
	 * always get the same sequence
	 *
	 * @since 2.1.0
	 */

	public static function setUpBeforeClass()
	{
		mt_srand(0);
	}

	/**
	 * providerTestCaptcha
	 *
	 * data provider for testCaptha method
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerTestCaptcha()
	{
		return array(
			/* test 0 */
			array(
				'6 plus 3',
				'9',
				sha1(9),
			),
			/* test 1 */
			array(
				'8 minus 2',
				'6',
				sha1(6),
			),
			/* test 2 */
			array(
				'7 plus 6',
				'13',
				sha1(13),
			),
		);
	}

	/**
	 * testCaptcha
	 *
	 * tests all the public methods of the captcha class
	 *
	 * @since 2.1.0
	 *
	 * @dataProvider providerTestCaptcha
	 */

	public function testCaptcha($expectedTask, $expectedRaw, $expectedHash)
	{
		$captcha = new Redaxscript_Captcha();
		$task = $captcha->getTask();
		/* test task */
		$this->assertEquals($expectedTask, $task);
		$raw = $captcha->getSolution('raw');
		/* test raw solution */
		$this->assertEquals($expectedRaw, $raw);
		$hash = $captcha->getSolution('hash');
		/* test hashed solution */
		$this->assertEquals($expectedHash, $hash);
	}

}

?>