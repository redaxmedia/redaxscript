<?php
namespace Redaxscript\Tests\Console;

use Redaxscript\Console;
use Redaxscript\Request;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ParserTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ParserTest extends TestCaseAbstract
{
	/**
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	protected function setUp()
	{
		$this->_request = Request::getInstance();
	}

	/**
	 * tearDown
	 *
	 * @since 3.0.0
	 */

	protected function tearDown()
	{
		$this->_request->setServer('argv', null);
	}

	/**
	 * providerGetArgument
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerGetArgument()
	{
		return $this->getProvider('tests/provider/Console/parser_get_argument.json');
	}

	/**
	 * providerGetOption
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerGetOption()
	{
		return $this->getProvider('tests/provider/Console/parser_get_option.json');
	}

	/**
	 * testInit
	 *
	 * @since 3.0.0
	 */

	public function testInit()
	{
		/* setup */

		$parser = new Console\Parser($this->_request);
		$parser->init('cli');

		/* compare */

		$this->assertEmpty($parser->getArgument());
	}

	/**
	 * testSetAndGetArgument
	 *
	 * @since 3.0.0
	 */

	public function testSetAndGetArgument()
	{
		/* setup */

		$parser = new Console\Parser($this->_request);
		$parser->setArgument('test', 'test');

		/* actual */

		$actual = $parser->getArgument('test');

		/* compare */

		$this->assertEquals('test', $actual);
	}

	/**
	 * testSetAndGetOption
	 *
	 * @since 3.0.0
	 */

	public function testSetAndGetOption()
	{
		/* setup */

		$parser = new Console\Parser($this->_request);
		$parser->setOption('test', 'test');

		/* actual */

		$actual = $parser->getOption('test');

		/* compare */

		$this->assertEquals('test', $actual);
	}

	/**
	 * testGetArgument
	 *
	 * @since 3.0.0
	 *
	 * @param array $argumentArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerGetArgument
	 */

	public function testGetArgument($argumentArray = array(), $expectArray = array())
	{
		/* setup */

		$this->_request->setServer('argv', $argumentArray);
		$parser = new Console\Parser($this->_request);
		$parser->init('cli');

		/* actual */

		$actualArray = $parser->getArgument();

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testGetOption
	 *
	 * @since 3.0.0
	 *
	 * @param array $argumentArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerGetOption
	 */

	public function testGetOption($argumentArray = array(), $expectArray = array())
	{
		/* setup */

		$this->_request->setServer('argv', $argumentArray);
		$parser = new Console\Parser($this->_request);
		$parser->init('cli');

		/* actual */

		$actualArray = $parser->getOption();

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testGetArgumentInvalid
	 *
	 * @since 3.0.0
	 */

	public function testGetArgumentInvalid()
	{
		/* setup */

		$parser = new Console\Parser($this->_request);
		$parser->init('cli');

		/* actual */

		$actual = $parser->getArgument('invalid');

		/* compare */

		$this->assertFalse($actual);
	}
	
	/**
	 * testGetOptionInvalid
	 *
	 * @since 3.0.0
	 */

	public function testGetOptionInvalid()
	{
		/* setup */

		$parser = new Console\Parser($this->_request);
		$parser->init('cli');

		/* actual */

		$actual = $parser->getOption('invalid');

		/* compare */

		$this->assertFalse($actual);
	}
}
