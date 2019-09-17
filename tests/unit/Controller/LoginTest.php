<?php
namespace Redaxscript\Tests\Controller;

use Redaxscript\Controller;
use Redaxscript\Db;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * LoginTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 *
 * @covers Redaxscript\Controller\ControllerAbstract
 * @covers Redaxscript\Controller\Login
 */

class LoginTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$optionArray = $this->getOptionArray();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
		$installer->insertUsers($optionArray);
		$setting = $this->settingFactory();
		$setting->set('captcha', 1);
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
	 * testProcess
	 *
	 * @since 3.0.0
	 *
	 * @param array $postArray
	 * @param array $userArray
	 * @param string $method
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testProcess(array $postArray = [], array $userArray = [], string $method = null, string $expect = null) : void
	{
		/* setup */

		Db::forTablePrefix('users')
			->whereIdIs(1)
			->findOne()
			->set('status', $userArray['status'])
			->save();
		$this->_request->set('post', $postArray);
		if ($method)
		{
			$loginController = $this
				->getMockBuilder('Redaxscript\Controller\Login')
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

			$loginController
				->expects($this->any())
				->method($method);
		}
		else
		{
			$loginController = new Controller\Login($this->_registry, $this->_request, $this->_language, $this->_config);
		}

		/* actual */

		$actual = $loginController->process();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
