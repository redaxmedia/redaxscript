<?php
namespace Redaxscript\Tests\Bootstrap;

use Redaxscript\Bootstrap;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * SessionTest
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Bootstrap\BootstrapAbstract
 * @covers Redaxscript\Bootstrap\Session
 *
 * @runTestsInSeparateProcesses
 */

class SessionTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 5.0.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$this->_request->init();
	}

	/**
	 * testSession
	 *
	 * @since 3.1.0
	 *
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testSession(array $expectArray = []) : void
	{
		/* setup */

		new Bootstrap\Session($this->_registry, $this->_request);

		/* actual */

		$actualArray =
		[
			'registry' =>
			[
				'sessionStatus' => $this->_registry->get('sessionStatus')
			],
			'session' =>
			[
				'sessionGuard' => $this->_request->getSession('sessionGuard')
			]
		];

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}
}
