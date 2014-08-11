<?php
namespace Redaxscript\Tests;
use Redaxscript\Captcha;
use Redaxscript\Db;
use Redaxscript\Language;

/**
 * CaptchaTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Gary Aylward
 */

class CaptchaTest extends TestCase
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
		$this->_language = Language::getInstance();
	}

	/**
	 * tearDown
	 *
	 * @since 2.2.0
	 */

	protected function tearDown()
	{
		Db::forPrefixTable('settings')->where('name', 'captcha')->findOne()->set('value', 0)->save();
	}

	/**
	 * testGetTask
	 *
	 * @since 2.2.0
	 */

	public function testGetTask()
	{
		/* setup */

		$captcha = new Captcha($this->_language);

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

		$captcha = new Captcha($this->_language);

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

		Db::forPrefixTable('settings')->where('name', 'captcha')->findOne()->set('value', 2)->save();

		/* result */

		$result = new Captcha($this->_language);

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

		Db::forPrefixTable('settings')->where('name', 'captcha')->findOne()->set('value', 3)->save();

		/* result */

		$result = new Captcha($this->_language);

		/* compare */

		$this->assertTrue(is_object($result));
	}
}
