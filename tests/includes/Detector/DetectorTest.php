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
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * setUp
	 *
	 * @since 2.1.0
	 */

	protected function setUp()
	{
		$this->_registry = Registry::getInstance();
		$this->_request = Request::getInstance();
	}

	/**
	 * providerLanguage
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerLanguage()
	{
		return $this->getProvider('tests/provider/Detector/language.json');
	}

	/**
	 * providerTemplate
	 *
	 * @since 2.1.0
	 *
	 * @return array
	 */

	public function providerTemplate()
	{
		return $this->getProvider('tests/provider/Detector/template.json');
	}

	/**
	 * testLanguage
	 *
	 * @since 2.1.0
	 *
	 * @param array $registry
	 * @param string $expect
	 *
	 * @dataProvider providerLanguage
	 */

	public function testLanguage($registry = array(), $expect = null)
	{
		/* setup */

		$this->_registry->init($registry);
		$detector = new Detector\Language($this->_registry, $this->_request);

		/* actual */

		$actual = $detector->getOutput();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testTemplate
	 *
	 * @since 2.1.0
	 *
	 * @param array $registry
	 * @param string $expect
	 *
	 * @dataProvider providerTemplate
	 */

	public function testTemplate($registry = array(), $expect = null)
	{
		/* setup */

		$this->_registry->init($registry);
		$detector = new Detector\Template($this->_registry, $this->_request);

		/* actual */

		$actual = $detector->getOutput();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testQuery
	 *
	 * @since 2.2.0
	 */

	public function testQuery()
	{
		/* setup */

		$this->_request->setQuery('l', 'en');
		$this->_request->setQuery('t', 'default');
		$detectorLanguage = new Detector\Language($this->_registry, $this->_request);
		$detectorTemplate = new Detector\Template($this->_registry, $this->_request);

		/* actual */

		$actualLanguage = $detectorLanguage->getOutput();
		$actualTemplate = $detectorTemplate->getOutput();

		/* compare */

		$this->assertEquals('en', $actualLanguage);
		$this->assertEquals('default', $actualTemplate);
	}
}