<?php
namespace Redaxscript\Tests\Console;

use Redaxscript\Assetic;
use Redaxscript\Registry;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * TransportTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Balázs Szilágyi
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
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp()
	{
		$this->_registry = Registry::getInstance();
		//todo: i guess this was for testing and is not needed
		Registry::clearInstance();
	}

	/**
	 * providerTransport
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerTransport()
	{
		return $this->getProvider('tests/provider/Assetic/transport.json');
	}

	/**
	 * testTransport
	 *
	 * @since 3.0.0
	 *
	 * @param array $expectArrayStart
	 * @param array $expectArrayEnd
	 *
	 * @dataProvider providerTransport
	 */

	//todo: refactor to check for an data array and not a set of strings
	public function testTransport($expectArrayStart = [], $expectArrayEnd = [])
	{
		/* actual */

		//switch to new Asetic\Transport
		//$actual = Assetic\Transport::getArray();

		/* compare */

		//$this->assertEquals($expectArray, $actual);
		//$this->assertStringStartsWith($expectArrayStart.$this->toString(), $actual.$this->toString());
		//$this->assertStringEndsWith($expectArrayEnd.$this->toString(), $actual.$this->toString());
	}

}
