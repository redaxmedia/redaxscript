<?php
namespace Redaxscript\Tests;
use Redaxscript\Detection;
use Redaxscript\Registry;
use Redaxscript\Request;

/**
 * DetectionTest
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class DetectionTest extends TestCase
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * setUp
	 *
	 * @since 2.1.0
	 */

	protected function setUp()
	{
		$this->_registry = Registry::getInstance();
	}

	/**
	 * providerDetectionLanguage
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerDetectionLanguage()
	{
		return $this->getProvider('tests/provider/detection_language.json');
	}

	/**
	 * providerDetectionTemplate
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerDetectionTemplate()
	{
		return $this->getProvider('tests/provider/detection_template.json');
	}

	/**
	 * testDetectionLanguage
	 *
	 * @since 2.1.0
	 *
	 * @param array $registry
	 * @param string $expect
	 *
	 * @dataProvider providerDetectionLanguage
	 */

	public function testDetectionLanguage($registry = array(), $expect = null)
	{
		/* setup */

		$this->_registry->init($registry);
		$detection = new Detection\Language($this->_registry);

		/* result */

		$result = $detection->getOutput();

		/* compare */

		$this->assertEquals($expect, $result);
	}

	/**
	 * testDetectionTemplate
	 *
	 * @since 2.1.0
	 *
	 * @param array $registry
	 * @param string $expect
	 *
	 * @dataProvider providerDetectionTemplate
	 */

	public function testDetectionTemplate($registry = array(), $expect = null)
	{
		/* setup */

		$this->_registry->init($registry);
		$detection = new Detection\Template($this->_registry);

		/* result */

		$result = $detection->getOutput();

		/* compare */

		$this->assertEquals($expect, $result);
	}

	/**
	 * testDetectionTemplate
	 *
	 * @since 2.2.0
	 *
	 * @param string $expect
	 */

	public function testDetectionQuery()
	{
		/* setup */

		Request::setQuery('l', 'en');
		Request::setQuery('t', 'default');
		$this->_registry->init();
		$detectionLanguage = new Detection\Language($this->_registry);
		$detectionTemplate = new Detection\Template($this->_registry);

		/* result */

		$resultLanguage = $detectionLanguage->getOutput();
		$resultTemplate = $detectionTemplate->getOutput();

		/* compare */

		$this->assertEquals('en', $resultLanguage);
		$this->assertEquals('default', $resultTemplate);
	}
}