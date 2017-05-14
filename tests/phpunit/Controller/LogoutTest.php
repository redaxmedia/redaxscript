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
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertUsers(
		[
			'adminName' => 'Test',
			'adminUser' => 'test',
			'adminPassword' => 'test',
			'adminEmail' => 'test@test.com'
		]);
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
	 * providerProcess
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerProcess()
	{
		return $this->getProvider('tests/provider/Controller/logout_process.json');
	}

	/**
	 * testProcess
	 *
	 * @since 3.0.0
	 *
	 * @param array $authArray
	 * @param string $expect
	 *
	 * @dataProvider providerProcess
	 */

	public function testProcess($authArray = [], $expect = null)
	{
		/* setup */

		$auth = new Auth($this->_request);
		$logoutController = new Controller\Logout($this->_registry, $this->_request, $this->_language);
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
