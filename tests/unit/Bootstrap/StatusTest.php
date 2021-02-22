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
 * @covers Redaxscript\Bootstrap\BootstrapAbstract
 * @covers Redaxscript\Bootstrap\Status
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

	public function setUp() : void
	{
		parent::setUp();
		$this->_request->init();
		$optionArray = $this->getOptionArray();
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

	public function tearDown() : void
	{
		$this->dropDatabase();
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
	 * @dataProvider providerAutoloader
	 */

	public function testStatus(int $userId = null, array $registryArray = [], array $expectArray = []) : void
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
