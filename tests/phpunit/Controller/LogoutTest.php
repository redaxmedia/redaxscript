<?php
namespace Redaxscript\Tests\Controller;

use Redaxscript\Auth;
use Redaxscript\Controller;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * LogoutTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 *
 * @covers Redaxscript\Controller\ControllerAbstract
 * @covers Redaxscript\Controller\Logout
 */

class LogoutTest extends TestCaseAbstract
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
		$installer->insertUsers($optionArray);
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
	 * @param array $authArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testProcess(array $authArray = [], string $expect = null)
	{
		/* setup */

		$auth = new Auth($this->_request);
		$logoutController = new Controller\Logout($this->_registry, $this->_request, $this->_language, $this->_config);
		if ($authArray['login'])
		{
			$auth->login(1);
		}
		if ($authArray['logout'])
		{
			$auth->logout();
		}

		/* actual */

		$actual = $logoutController->process();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
