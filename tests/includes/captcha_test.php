<?php
include_once('tests/stubs.php');

/**
 * Redaxscript Captcha Test
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Gary Aylward
 */

class Redaxscript_Captcha_Test extends PHPUnit_Framework_TestCase
{
	/**
	 * setUpBeforeClass
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
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerTestCaptcha()
	{
		$contents = file_get_contents('tests/provider/captcha.json');
		$output = json_decode($contents, true);
		return $output;
	}

	/**
	 * testCaptcha
	 *
	 * @since 2.1.0
	 *
	 * @dataProvider providerTestCaptcha
	 */

	public function testCaptcha($expectTask = null, $expectRaw = null, $expectHash = null)
	{
		/* setup */

		$captcha = new Redaxscript_Captcha();

		/* result */

		$task = $captcha->getTask();
		$raw = $captcha->getSolution('raw');
		$hash = $captcha->getSolution('hash');

		/* compare */

		$this->assertEquals($expectTask, $task);
		$this->assertEquals($expectRaw, $raw);
		$this->assertEquals($expectHash, $hash);
	}
}
?>