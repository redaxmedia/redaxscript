<?php
namespace Redaxscript\Tests\Detector;

use Redaxscript\Detector;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Tests\TestCase;

/**
 * DetectorTest
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class DetectorTest extends TestCase
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
	 * providerDetectorLanguage
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerDetectorLanguage()
	{
		return $this->getProvider('tests/provider/Detector/language.json');
	}

	/**
	 * providerDetectorTemplate
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerDetectorTemplate()
	{
		return $this->getProvider('tests/provider/Detector/template.json');
	}

	/**
	 * testDetectorLanguage
	 *
	 * @since 2.1.0
	 *
	 * @param array $registry
	 * @param string $expect
	 *
	 * @dataProvider providerDetectorLanguage
	 */

	public function testDetectorLanguage($registry = array(), $expect = null)
	{
		/* setup */

		$this->_registry->init($registry);
		$detector = new Detector\Language($this->_registry);

		/* result */

		$result = $detector->getOutput();

		/* compare */

		$this->assertEquals($expect, $result);
	}

	/**
	 * testDetectorTemplate
	 *
	 * @since 2.1.0
	 *
	 * @param array $registry
	 * @param string $expect
	 *
	 * @dataProvider providerDetectorTemplate
	 */

	public function testDetectorTemplate($registry = array(), $expect = null)
	{
		/* setup */

		$this->_registry->init($registry);
		$detector = new Detector\Template($this->_registry);

		/* result */

		$result = $detector->getOutput();

		/* compare */

		$this->assertEquals($expect, $result);
	}

	/**
	 * testDetectorTemplate
	 *
	 * @since 2.2.0
	 */

	public function testDetectorQuery()
	{
		/* setup */

		Request::setQuery('l', 'en');
		Request::setQuery('t', 'default');
		$this->_registry->init();
		$detectorLanguage = new Detector\Language($this->_registry);
		$detectorTemplate = new Detector\Template($this->_registry);

		/* result */

		$resultLanguage = $detectorLanguage->getOutput();
		$resultTemplate = $detectorTemplate->getOutput();

		/* compare */

		$this->assertEquals('en', $resultLanguage);
		$this->assertEquals('default', $resultTemplate);
	}
}