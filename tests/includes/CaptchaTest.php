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
	 * testGetTask
	 *
	 * @since 2.2.0
	 */

	public function testGetTask()
	{
		/* setup */

		$captcha = new Captcha($this->_language);
		$captcha->init();

		/* actual */

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
		$captcha->init();

		/* actual */

		$raw = $captcha->getSolution('raw');
		$hash = $captcha->getSolution('hash');

		/* compare */

		$this->assertEquals($hash, sha1($raw));
	}

	/**
	 * testGetRange
	 *
	 * @since 2.6.0
	 */

	public function testGetRange()
	{
		/* setup */

		$captcha = new Captcha($this->_language);
		$captcha->init();

		/* actual */

		$min = $captcha->getMin();
		$max = $captcha->getMax();

		/* compare */

		$this->assertTrue($min < $max);
	}

	/**
	 * testPlus
	 *
	 * @since 2.2.0
	 */

	public function testPlus()
	{
		/* setup */

		$captcha = new Captcha($this->_language);
		$captcha->init(2);

		/* actual */

		$actual = $captcha->getSolution('hash');

		/* compare */

		$this->assertTrue(is_string($actual));
	}

	/**
	 * testMinus
	 *
	 * @since 2.2.0
	 */

	public function testMinus()
	{
		/* setup */

		$captcha = new Captcha($this->_language);
		$captcha->init(3);

		/* actual */

		$actual = $captcha->getSolution('hash');

		/* compare */

		$this->assertTrue(is_string($actual));
	}
}
