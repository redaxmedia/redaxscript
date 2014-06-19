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
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

	/**
	 * setUp
	 *
	 * @since 2.1.0
	 */

	protected function setUp()
	{
		$this->_language = Redaxscript_Language::getInstance();
	}

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
	 * providerCaptcha
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerCaptcha()
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
	 * @param string $expectTask
	 * @param string $expectRaw
	 * @param string $expectHash
	 *
	 * @dataProvider providerCaptcha
	 */

	public function testCaptcha($expectTask = null, $expectRaw = null, $expectHash = null)
	{
		/* setup */

		$captcha = new Redaxscript_Captcha($this->_language);

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
