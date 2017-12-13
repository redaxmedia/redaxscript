<?php
namespace Redaxscript\Tests\Console;

use Redaxscript\Console;
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
	 * tearDown
	 *
	 * @since 3.0.0
	 */

	public function tearDown()
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

	public function providerGetArgument() : array
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

	public function providerGetOption() : array
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
	 * testGetAndSetArgument
	 *
	 * @since 3.0.0
	 */

	public function testGetAndSetArgument()
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
	 * testGetArgument
	 *
	 * @since 3.0.0
	 *
	 * @param array $argumentArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerGetArgument
	 */

	public function testGetArgument($argumentArray = [], array $expectArray = [])
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

		$actual = $parser->getArgument('invalidArgument');

		/* compare */

		$this->assertFalse($actual);
	}

	/**
	 * testGetAndSetOption
	 *
	 * @since 3.0.0
	 */

	public function testGetAndSetOption()
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
	 * testGetOption
	 *
	 * @since 3.0.0
	 *
	 * @param array $argumentArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerGetOption
	 */

	public function testGetOption($argumentArray = [], array $expectArray = [])
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

		$actual = $parser->getOption('invalidOption');

		/* compare */

		$this->assertFalse($actual);
	}
}
