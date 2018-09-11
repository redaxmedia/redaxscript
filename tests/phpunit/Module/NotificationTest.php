<?php
namespace Redaxscript\Tests\Module;

use Redaxscript\Module;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * NotificationTest
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Module\Notification
 */

class NotificationTest extends TestCaseAbstract
{
	/**
	 * testGetAndSetNotification
	 *
	 * @since 3.0.0
	 */

	public function testGetAndSetNotification()
	{
		/* setup */

		$module = new Module\Notification($this->_registry, $this->_request, $this->_language, $this->_config);
		$module->init(
		[
			'name' => 'Test Dummy',
			'alias' => 'TestDummy'
		]);
		$module->setNotification('error', 'Error');

		/* actual */

		$actualArray = $module->getNotification('error');

		/* teardown */

		$module->clearNotification('error');

		/* compare */

		$this->assertEquals('Error', $actualArray['Test Dummy'][0]);
	}

	/**
	 * testGetAllNotification
	 *
	 * @since 3.0.0
	 */

	public function testGetAllNotification()
	{
		/* setup */

		$module = new Module\Notification($this->_registry, $this->_request, $this->_language, $this->_config);
		$module->init(
		[
			'name' => 'Test Dummy',
			'alias' => 'TestDummy'
		]);
		$module->setNotification('success', 'Success');
		$module->setNotification('error', 'Error');

		/* actual */

		$actualArray = $module->getNotification();

		/* teardown */

		$module->clearNotification('success');
		$module->clearNotification('error');

		/* compare */

		$this->assertEquals('Error', $actualArray['error']['Test Dummy'][0]);
		$this->assertEquals('Success', $actualArray['success']['Test Dummy'][0]);
	}

	/**
	 * testGetInvalid
	 *
	 * @since 2.1.0
	 */

	public function testGetInvalid()
	{
		/* setup */

		$module = new Module\Notification($this->_registry, $this->_request, $this->_language, $this->_config);
		$module->init(
		[
			'name' => 'Test Dummy',
			'alias' => 'TestDummy'
		]);

		/* actual */

		$actual = $module->getNotification('invalidKey');

		/* compare */

		$this->assertNull($actual);
	}
}
