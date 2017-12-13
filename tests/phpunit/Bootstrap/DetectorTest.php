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
 * @runTestsInSeparateProcesses
 */

class DetectorTest extends TestCaseAbstract
{
	/**
	 * providerDetector
	 *
	 * @since 3.1.0
	 *
	 * @return array
	 */

	public function providerDetector() : array
	{
		return $this->getProvider('tests/provider/Bootstrap/detector.json');
	}

	/**
	 * testDetector
	 *
	 * @since 3.1.0
	 *
	 * @param array $expectArray
	 *
	 * @dataProvider providerDetector
	 */

	public function testDetector(array $expectArray = [])
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
