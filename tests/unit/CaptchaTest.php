<?php
namespace Redaxscript\Tests;

use Redaxscript\Captcha;

/**
 * CaptchaTest
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Gary Aylward
 *
 * @covers Redaxscript\Captcha
 */

class CaptchaTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp() : void
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

	public function tearDown() : void
	{
		$this->dropDatabase();
	}

	/**
	 * testGetTask
	 *
	 * @since 2.2.0
	 */

	public function testGetTask() : void
	{
		/* setup */

		$captcha = new Captcha($this->_language);
		$captcha->init();

		/* actual */

		$task = $captcha->getTask();

		/* compare */

		$this->assertIsString($task);
	}

	/**
	 * testGetSolution
	 *
	 * @since 2.2.0
	 */

	public function testGetSolution() : void
	{
		/* setup */

		$captcha = new Captcha($this->_language);
		$captcha->init();

		/* actual */

		$solution = $captcha->getSolution();

		/* compare */

		$this->assertIsInt($solution);
	}

	/**
	 * testGetRange
	 *
	 * @since 2.6.0
	 */

	public function testGetRange() : void
	{
		/* setup */

		$captcha = new Captcha($this->_language);
		$captcha->init();

		/* actual */

		$actual = $captcha->getMin() < $captcha->getMax();

		/* compare */

		$this->assertTrue($actual);
	}

	/**
	 * testPlus
	 *
	 * @since 2.2.0
	 */

	public function testPlus() : void
	{
		/* setup */

		$captcha = new Captcha($this->_language);
		$captcha->init(2);

		/* actual */

		$actual = $captcha->getSolution();

		/* compare */

		$this->assertIsInt($actual);
	}

	/**
	 * testMinus
	 *
	 * @since 2.2.0
	 */

	public function testMinus() : void
	{
		/* setup */

		$captcha = new Captcha($this->_language);
		$captcha->init(3);

		/* actual */

		$actual = $captcha->getSolution();

		/* compare */

		$this->assertIsInt($actual);
	}
}
