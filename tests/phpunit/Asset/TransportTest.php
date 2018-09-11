<?php
namespace Redaxscript\Tests\Asset;

use Redaxscript\Asset;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * TransportTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Asset\Transport
 */

class TransportTest extends TestCaseAbstract
{
	/**
	 * testGetArray
	 *
	 * @since 3.0.0
	 */

	public function testGetArray()
	{
		/* setup */

		$transport = new Asset\Transport($this->_registry, $this->_language);

		/* actual */

		$actualArray = $transport->getArray();

		/* compare */

		$this->assertArrayHasKey('baseURL', $actualArray);
		$this->assertArrayHasKey('generator', $actualArray);
		$this->assertArrayHasKey('language', $actualArray);
		$this->assertArrayHasKey('registry', $actualArray);
		$this->assertArrayHasKey('version', $actualArray);
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $transportArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRender(array $transportArray = [], string $expect = null)
	{
		/* setup */

		$transport = new Asset\Transport($this->_registry, $this->_language);

		/* actual */

		$actual = $transport->render($transportArray['key'], $transportArray['value']);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
