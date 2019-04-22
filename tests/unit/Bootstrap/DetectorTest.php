<?php
namespace Redaxscript\Tests\Bootstrap;

use Redaxscript\Bootstrap;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * DetectorTest
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Bootstrap\BootstrapAbstract
 * @covers Redaxscript\Bootstrap\Detector
 *
 * @runTestsInSeparateProcesses
 */

class DetectorTest extends TestCaseAbstract
{
	/**
	 * testDetector
	 *
	 * @since 3.1.0
	 *
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testDetector(array $expectArray = []) : void
	{
		/* setup */

		new Bootstrap\Detector($this->_registry, $this->_request);

		/* actual */

		$actualArray =
		[
			'language' => $this->_registry->get('language'),
			'template' => $this->_registry->get('template')
		];

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}
}
