<?php
namespace Redaxscript\Tests\Assetic;

use Redaxscript\Assetic;
use Redaxscript\Registry;
use Redaxscript\Language;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * TransportTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class TransportTest extends TestCaseAbstract
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
		return $this->getProvider('tests/provider/Assetic/transport_render.json');
	}

	/**
	 * testGetArray
	 *
	 * @since 3.0.0
	 */

	public function testGetArray()
	{
		/* setup */

		$transport = new Assetic\Transport($this->_registry, $this->_language);

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
	 * @dataProvider providerRender
	 */

	public function testRender($transportArray = [], $expect = null)
	{
		/* setup */

		$transport = new Assetic\Transport($this->_registry, $this->_language);

		/* actual */

		$actual = $transport->render($transportArray['key'], $transportArray['value']);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
