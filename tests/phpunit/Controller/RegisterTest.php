<?php
namespace Redaxscript\Tests\Controller;

use Redaxscript\Controller;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * RegisterTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 *
 * @covers Redaxscript\Controller\ControllerAbstract
 * @covers Redaxscript\Controller\Register
 */

class RegisterTest extends TestCaseAbstract
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
		$installer->insertUsers($optionArray);
		$setting = $this->settingFactory();
		$setting->set('captcha', 1);
		$setting->set('notification', 1);
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
	 * testProcess
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testProcess(array $postArray = [], string $expect = null)
	{
		/* setup */

		$this->_request->set('post', $postArray);
		$registerController = new Controller\Register($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $registerController->process();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testProcessFailure
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray
	 * @param string $method
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testProcessFailure(array $postArray = [], string $method = null, string $expect = null)
	{
		/* setup */

		$this->_request->set('post', $postArray);
		$stub = $this
			->getMockBuilder('Redaxscript\Controller\Register')
			->setConstructorArgs(
			[
				$this->_registry,
				$this->_request,
				$this->_language,
				$this->_config
			])
			->setMethods(
			[
				$method
			])
			->getMock();

		/* override */

		$stub
			->expects($this->any())
			->method($method)
			->will($this->returnValue(false));

		/* actual */

		$actual = $stub->process();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
