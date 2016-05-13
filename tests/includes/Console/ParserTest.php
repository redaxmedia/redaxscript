<?php
namespace Redaxscript\Tests\Console;

use Redaxscript\Console;
use Redaxscript\Tests\TestCase;
use Redaxscript\Request;

/**
 * ParserTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ParserTest extends TestCase
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
		$parser->init();

		/* compare */

		$this->assertArrayHasKey(0, $parser->getArgument());
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
	 * @param array $arguments
	 * @param array $expect
	 *
	 * @dataProvider providerGetArgument
	 */

	public function testGetArgument($arguments = array(), $expect = array())
	{
		/* setup */

		$this->_request->setServer('argv', $arguments);
		$parser = new Console\Parser($this->_request);
		$parser->init();

		/* actual */

		$actual = $parser->getArgument();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testGetOption
	 *
	 * @since 3.0.0
	 *
	 * @param array $arguments
	 * @param array $expect
	 *
	 * @dataProvider providerGetOption
	 */

	public function testGetOption($arguments = array(), $expect = array())
	{
		/* setup */

		$this->_request->setServer('argv', $arguments);
		$parser = new Console\Parser($this->_request);
		$parser->init();

		/* actual */

		$actual = $parser->getOption();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
