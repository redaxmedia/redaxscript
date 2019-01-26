<?php
namespace Redaxscript\Tests\View;

use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\View;

/**
 * SystemStatusTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\View\SystemStatus
 * @covers Redaxscript\View\ViewAbstract
 */

class SystemStatusTest extends TestCaseAbstract
{
	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRender(array $registryArray = [], string $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);
		$systemStatus = new View\SystemStatus($this->_registry, $this->_language);

		/* actual */

		$actual = $systemStatus->render();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testValidateError
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testValidateError(array $registryArray = [], array $expectArray = [])
	{
		/* setup */

		$this->_registry->init($registryArray);
		$systemStatus = new View\SystemStatus($this->_registry, $this->_language);

		/* actual */

		$actualArray = $this->callMethod($systemStatus, '_validateError');

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testValidateWarning
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testValidateWarning(array $registryArray = [], array $expectArray = [])
	{
		/* setup */

		$this->_registry->init($registryArray);
		$systemStatus = new View\SystemStatus($this->_registry, $this->_language);

		/* actual */

		$actualArray = $this->callMethod($systemStatus, '_validateWarning');

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}
}
