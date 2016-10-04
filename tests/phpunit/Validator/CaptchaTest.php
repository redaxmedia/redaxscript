<?php
namespace Redaxscript\Tests\Validator;

use Redaxscript\Db;
use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\Validator;

/**
 * CaptchaTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Sven Weingartner
 */

class CaptchaTest extends TestCaseAbstract
{
	/**
	 * setUpBeforeClass
	 *
	 * @since 2.6.0
	 */

	public static function setUpBeforeClass()
	{
		Db::setSetting('captcha', 1);
	}

	/**
	 * tearDownAfterClass
	 *
	 * @since 2.6.0
	 */

	public static function tearDownAfterClass()
	{
		Db::setSetting('captcha', 0);
	}

	/**
	 * providerCaptcha
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerCaptcha()
	{
		return $this->getProvider('tests/provider/Validator/captcha.json');
	}

	/**
	 * testCaptcha
	 *
	 * @since 2.6.0
	 *
	 * @param string $task
	 * @param array $hashArray
	 * @param integer $expect
	 *
	 * @dataProvider providerCaptcha
	 */

	public function testCaptcha($task = null, $hashArray = [], $expect = null)
	{
		/* setup */

		$validator = new Validator\Captcha();

		/* actual */

		$actual = $validator->validate($task, function_exists('password_verify') ? $hashArray[0] : $hashArray[1]);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
