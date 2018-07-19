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
 *
 * @covers Redaxscript\Validator\Captcha
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
		$this->dropDatabase();
	}

	/**
	 * testCaptcha
	 *
	 * @since 2.6.0
	 *
	 * @param string $task
	 * @param string $hash
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testCaptcha(string $task = null, string $hash = null, bool $expect = null)
	{
		/* setup */

		$validator = new Validator\Captcha();

		/* actual */

		$actual = $validator->validate($task, $hash);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
