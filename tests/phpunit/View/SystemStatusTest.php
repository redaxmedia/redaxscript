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
 */

class SystemStatusTest extends TestCaseAbstract
{
	/**
	 * providerRender
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerRender() : array
	{
		return $this->getProvider('tests/provider/View/system_status_render.json');
	}

	/**
	 * providerError
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerError() : array
	{
		return $this->getProvider('tests/provider/View/system_status_validate_error.json');
	}

	/**
	 * providerWarning
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerWarning() : array
	{
		return $this->getProvider('tests/provider/View/system_status_validate_warning.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param string $expect
	 *
	 * @dataProvider providerRender
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
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerError
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
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerWarning
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
