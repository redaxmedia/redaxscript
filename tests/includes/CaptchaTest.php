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
	 * tearDown
	 *
	 * @since 2.2.0
	 */

	protected function tearDown()
	{
		Redaxscript_Db::forPrefixTable('settings')->where('name', 'captcha')->findOne()->set('value', 0)->save();
	}

	/**
	 * testGetTask
	 *
	 * @since 2.2.0
	 */

	public function testGetTask()
	{
		/* setup */

		$captcha = new Redaxscript_Captcha($this->_language);

		/* result */

		$task = $captcha->getTask();

		/* compare */

		$this->assertTrue(is_string($task));
	}

	/**
	 * testGetSolution
	 *
	 * @since 2.2.0
	 */

	public function testGetSolution()
	{
		/* setup */

		$captcha = new Redaxscript_Captcha($this->_language);

		/* result */

		$raw = $captcha->getSolution('raw');
		$hash = $captcha->getSolution('hash');

		/* compare */

		$this->assertEquals($hash, sha1($raw));
	}

	/**
	 * testPlus
	 *
	 * @since 2.2.0
	 */

	public function testPlus()
	{
		/* setup */

		Redaxscript_Db::forPrefixTable('settings')->where('name', 'captcha')->findOne()->set('value', 2)->save();

		/* result */

		$result = new Redaxscript_Captcha($this->_language);

		/* compare */

		$this->assertTrue(is_object($result));
	}

	/**
	 * testMinus
	 *
	 * @since 2.2.0
	 */

	public function testMinus()
	{
		/* setup */

		Redaxscript_Db::forPrefixTable('settings')->where('name', 'captcha')->findOne()->set('value', 3)->save();

		/* result */

		$result = new Redaxscript_Captcha($this->_language);

		/* compare */

		$this->assertTrue(is_object($result));
	}
}
