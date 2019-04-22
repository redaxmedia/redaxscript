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
 *
 * @covers Redaxscript\Console\Parser
 */

class ParserTest extends TestCaseAbstract
{
	/**
	 * tearDown
	 *
	 * @since 3.0.0
	 */

	public function tearDown() : void
	{
		$this->_request->setServer('argv', null);
	}

	/**
	 * testInit
	 *
	 * @since 3.0.0
	 */

	public function testInit() : void
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

	public function testGetAndSetArgument() : void
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
	 * @dataProvider providerAutoloader
	 */

	public function testGetArgument(array $argumentArray = [], array $expectArray = []) : void
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

	public function testGetArgumentInvalid() : void
	{
		/* setup */

		$parser = new Console\Parser($this->_request);
		$parser->init('cli');

		/* actual */

		$actual = $parser->getArgument('invalidArgument');

		/* compare */

		$this->assertNull($actual);
	}

	/**
	 * testGetAndSetOption
	 *
	 * @since 3.0.0
	 */

	public function testGetAndSetOption() : void
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
	 * @dataProvider providerAutoloader
	 */

	public function testGetOption(array $argumentArray = [], array $expectArray = []) : void
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

	public function testGetOptionInvalid() : void
	{
		/* setup */

		$parser = new Console\Parser($this->_request);
		$parser->init('cli');

		/* actual */

		$actual = $parser->getOption('invalidOption');

		/* compare */

		$this->assertNull($actual);
	}
}
