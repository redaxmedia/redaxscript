<?php
namespace Redaxscript\Tests\View;

use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\View;

/**
 * InstallStatusTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class InstallStatusTest extends TestCaseAbstract
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;
	/**
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp()
	{
		$this->_registry = Registry::getInstance();
		$this->_language = Language::getInstance();
	}

	/**
	 * providerRender
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerRender()
	{
		return $this->getProvider('tests/provider/View/install_status_render.json');
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
		return $this->getProvider('tests/provider/View/install_status_validate_error.json');
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
		return $this->getProvider('tests/provider/View/install_status_validate_warning.json');
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerRender
	 *
	 * @param array $registryArray
	 * @param null $expect
	 */

	public function testRender($registryArray = array(), $expect = null)
	{
		$this->_registry->init($registryArray);
		$installForm = new View\InstallStatus($this->_registry, $this->_language);

		/* actual */

		$actual = $installForm->render();

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerError
	 *
	 * @param array $registryArray
	 * @param null $expect
	 *
	 * @internal param null $dbStatus
	 */

	public function testValidateError($registryArray = array(), $expect = null)
	{
		$this->_registry->init($registryArray);

		$installStatus = new View\InstallStatus ($this->_registry, $this->_language, $this->_request);

		/* actual */

		$actual = $this->callMethod($installStatus, '_validateError');

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testRender
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerWarning
	 *
	 * @param array $registryArray
	 * @param string $expect
	 */

	public function testValidateWarning($registryArray = array(), $expect = null)
	{
		$this->_registry->init($registryArray);

		$installStatus = new View\InstallStatus ($this->_registry, $this->_language, $this->_request);

		/* actual */

		$actual = $this->callMethod($installStatus, '_validateWarning');

		$this->assertEquals($expect, $actual);
	}
}
