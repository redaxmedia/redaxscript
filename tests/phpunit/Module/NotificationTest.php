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
		$module->setNotification('error', 'testValue');

		/* actual */

		$actualArray = $module->getNotification('error');

		/* compare */

		$this->assertEquals('testValue', $actualArray['Test Dummy'][0]);
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
		$module->setNotification('success', 'testValue');
		$module->setNotification('error', 'testValue');

		/* actual */

		$actualArray = $module->getNotification();

		/* compare */

		$this->assertArrayHasKey('success', $actualArray);
		$this->assertArrayHasKey('error', $actualArray);
	}

	/**
	 * testGetInvalidNotification
	 *
	 * @since 2.1.0
	 */

	public function testGetInvalidNotification()
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

		$this->assertFalse($actual);
	}
}
