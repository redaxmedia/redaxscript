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

	public function providerRender()
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

	public function providerError()
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

	public function providerWarning()
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

	public function testRender($registryArray = [], $expect = null)
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
	 * @param string $expect
	 *
	 * @dataProvider providerError
	 */

	public function testValidateError($registryArray = [], $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);
		$systemStatus = new View\SystemStatus($this->_registry, $this->_language);

		/* actual */

		$actual = $this->callMethod($systemStatus, '_validateError');

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param string $expect
	 *
	 * @dataProvider providerWarning
	 */

	public function testValidateWarning($registryArray = [], $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);
		$systemStatus = new View\SystemStatus($this->_registry, $this->_language);

		/* actual */

		$actual = $this->callMethod($systemStatus, '_validateWarning');

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
