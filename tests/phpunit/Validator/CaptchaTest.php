<?php
namespace Redaxscript\Tests\Validator;

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
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp()
	{
		parent::setUp();
		$optionArray =
		[
			'adminName' => 'Test',
			'adminUser' => 'test',
			'adminPassword' => 'test',
			'adminEmail' => 'test@test.com'
		];
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown()
	{
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawDrop();
	}

	/**
	 * providerCaptcha
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerCaptcha() : array
	{
		return $this->getProvider('tests/provider/Validator/captcha.json');
	}

	/**
	 * testCaptcha
	 *
	 * @since 2.6.0
	 *
	 * @param string $task
	 * @param string $hash
	 * @param int $expect
	 *
	 * @dataProvider providerCaptcha
	 */

	public function testCaptcha(string $task = null, string $hash = null, int $expect = null)
	{
		/* setup */

		$validator = new Validator\Captcha();

		/* actual */

		$actual = $validator->validate($task, $hash);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
