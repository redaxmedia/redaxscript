<?php
namespace Redaxscript\Tests\Bootstrap;

use Redaxscript\Auth;
use Redaxscript\Bootstrap;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * StatusTest
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @runTestsInSeparateProcesses
 */

class StatusTest extends TestCaseAbstract
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
		$installer->insertGroups();
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
	 * providerStatus
	 *
	 * @since 3.1.0
	 *
	 * @return array
	 */

	public function providerStatus() : array
	{
		return $this->getProvider('tests/provider/Bootstrap/status.json');
	}

	/**
	 * testStatus
	 *
	 * @since 3.1.0
	 *
	 * @param int $userId
	 * @param array $registryArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerStatus
	 */

	public function testStatus(int $userId = null, array $registryArray = [], array $expectArray = [])
	{
		/* setup */

		$auth = new Auth($this->_request);
		$auth->login($userId);
		$this->_registry->init($registryArray);
		new Bootstrap\Status($this->_registry, $this->_request);

		/* actual */

		$actualArray =
		[
			'dbStatus' => $this->_registry->get('dbStatus'),
			'authStatus' => $this->_registry->get('authStatus'),
			'token' => $this->_registry->get('token'),
			'loggedIn' => $this->_registry->get('loggedIn')
		];

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}
}
