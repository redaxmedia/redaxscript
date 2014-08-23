<?php
namespace Redaxscript\Tests\Validator;

use Redaxscript\Tests\TestCase;
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

class CaptchaTest extends TestCase
{
	/**
	 * providerValidatorCaptcha
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerValidatorCaptcha()
	{
		return $this->getProvider('tests/provider/Validator/captcha.json');
	}

	/**
	 * testCaptcha
	 *
	 * @since 2.2.0
	 *
	 * @param string $task
	 * @param string $solution
	 * @param integer $expect
	 *
	 * @dataProvider providerValidatorCaptcha
	 */

	public function testCaptcha($task = null, $solution = null, $expect = null)
	{
		/* setup */

		$validator = new Validator\Captcha();

		/* result */

		$result = $validator->validate($task, $solution);

		/* compare */

		$this->assertEquals($expect, $result);
	}
}
