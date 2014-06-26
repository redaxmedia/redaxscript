<?php
include_once('tests/stubs.php');

/**
 * Redaxscript Captcha Test
 *
 * @since 2.2.0
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
	 * testCaptcha
	 *
	 * @since 2.2.0
	 */

	public function testCaptcha()
	{
		/* setup */

		$captcha = new Redaxscript_Captcha($this->_language);

		/* result */

		$raw = $captcha->getSolution('raw');
		$hash = $captcha->getSolution('hash');

		/* compare */

		$this->assertEquals($hash, sha1($raw));
	}
}
