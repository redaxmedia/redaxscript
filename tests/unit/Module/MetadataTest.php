<?php
namespace Redaxscript\Tests\Module;

use Redaxscript\Module;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * MetadataTest
 *
 * @since 4.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Module\Metadata
 *
 * @runTestsInSeparateProcesses
 */

class MetadataTest extends TestCaseAbstract
{
	/**
	 * testGetAndSetDashboard
	 *
	 * @since 4.1.0
	 */

	public function testGetAndSetDashboard() : void
	{
		/* setup */

		$module = new Module\Metadata($this->_registry, $this->_request, $this->_language, $this->_config);
		$module->init(
		[
			'name' => 'Test Dummy',
			'alias' => 'TestDummy'
		]);
		$module->setDashboard('Test Dummy', 2);

		/* expect and actual */

		$expectArray =
		[
			'Test Dummy' =>
			[
				[
					'column' => 2,
					'content' => 'Test Dummy'
				]
			]
		];
		$actualArray = $module->getDashboardArray();

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testGetAndSetNotification
	 *
	 * @since 3.0.0
	 */

	public function testGetAndSetNotification() : void
	{
		/* setup */

		$module = new Module\Metadata($this->_registry, $this->_request, $this->_language, $this->_config);
		$module->init(
		[
			'name' => 'Test Dummy',
			'alias' => 'TestDummy'
		]);
		$module->setNotification('success', 'Success');
		$module->setNotification('error', 'Error');

		/* expect and actual */

		$expectArray =
		[
			'success' =>
			[
				'Test Dummy' =>
				[
					'Success'
				]
			],
			'error' =>
			[
				'Test Dummy' =>
				[
					'Error'
				]
			]
		];
		$actualArray = $module->getNotificationArray();

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}
}
