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
 * @runTestsInSeparateProcesses
 */

class SessionTest extends TestCaseAbstract
{
	/**
	 * providerSession
	 *
	 * @since 3.1.0
	 *
	 * @return array
	 */

	public function providerSession()
	{
		return $this->getProvider('tests/provider/Bootstrap/session.json');
	}

	/**
	 * testSession
	 *
	 * @since 3.1.0
	 *
	 * @param array $expectArray
	 *
	 * @dataProvider providerSession
	 */

	public function testSession($expectArray = [])
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
				'regenerateId' => $this->_request->getSession('regenerateId')
			]
		];

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}
}
