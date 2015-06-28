<?php
namespace Redaxscript\Tests;

use Redaxscript\Language;
use Redaxscript\Parser;
use Redaxscript\Registry;

/**
 * ParserTest
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ParserTest extends TestCase
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
	 * @since 2.1.0
	 */

	protected function setUp()
	{
		$this->_registry = Registry::getInstance();
		$this->_language = Language::getInstance();
	}

	/**
	 * providerBreak
	 *
	 * @since 2.5.0
	 *
	 * @return array
	 */

	public function providerBreak()
	{
		return $this->getProvider('tests/provider/parser_break.json');
	}

	/**
	 * providerQuote
	 *
	 * @since 2.5.0
	 *
	 * @return array
	 */

	public function providerQuote()
	{
		return $this->getProvider('tests/provider/parser_quote.json');
	}

	/**
	 * providerLanguage
	 *
	 * @since 2.5.0
	 *
	 * @return array
	 */

	public function providerLanguage()
	{
		return $this->getProvider('tests/provider/parser_language.json');
	}

	/**
	 * providerRegistry
	 *
	 * @since 2.5.0
	 *
	 * @return array
	 */

	public function providerRegistry()
	{
		return $this->getProvider('tests/provider/parser_registry.json');
	}

	/**
	 * providerFunction
	 *
	 * @since 2.5.0
	 *
	 * @return array
	 */

	public function providerFunction()
	{
		return $this->getProvider('tests/provider/parser_function.json');
	}

	/**
	 * providerModule
	 *
	 * @since 2.5.0
	 *
	 * @return array
	 */

	public function providerModule()
	{
		return $this->getProvider('tests/provider/parser_module.json');
	}

	/**
	 * testBreak
	 *
	 * @since 2.5.0
	 *
	 * @param array $registry
	 * @param string $input
	 * @param string $route
	 * @param string $expect
	 *
	 * @dataProvider providerBreak
	 */

	public function testBreak($registry = array(), $input = null, $route = null, $expect = null)
	{
		/* setup */

		$this->_registry->init($registry);
		$parser = new Parser($this->_registry, $this->_language);
		$parser->init($input, $route);

		/* actual */

		$actual = $parser->getOutput();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testQuote
	 *
	 * @since 2.5.0
	 *
	 * @param string $input
	 * @param string $expect
	 *
	 * @dataProvider providerQuote
	 */

	public function testQuote($input = null, $expect = null)
	{
		/* setup */

		$parser = new Parser($this->_registry, $this->_language);
		$parser->init($input);

		/* actual */

		$actual = $parser->getOutput();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testLanguage
	 *
	 * @since 2.5.0
	 *
	 * @param string $language
	 * @param string $input
	 * @param string $expect
	 *
	 * @dataProvider providerLanguage
	 */

	public function testLanguage($language = null, $input = null, $expect = null)
	{
		/* setup */

		$this->_language->init($language);
		$parser = new Parser($this->_registry, $this->_language);
		$parser->init($input);

		/* actual */

		$actual = $parser->getOutput();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testRegistry
	 *
	 * @since 2.5.0
	 *
	 * @param array $registry
	 * @param string $input
	 * @param string $expect
	 *
	 * @dataProvider providerRegistry
	 */

	public function testRegistry($registry = array(), $input = null, $expect = null)
	{
		/* setup */

		$this->_registry->init($registry);
		$parser = new Parser($this->_registry, $this->_language);
		$parser->init($input);

		/* actual */

		$actual = $parser->getOutput();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testFunction
	 *
	 * @since 2.5.0
	 *
	 * @param string $input
	 * @param string $expect
	 *
	 * @dataProvider providerFunction
	 */

	public function testFunction($input = null, $expect = null)
	{
		/* setup */

		$parser = new Parser($this->_registry, $this->_language);
		$parser->init($input);

		/* actual */

		$actual = $parser->getOutput();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testModule
	 *
	 * @since 2.5.0
	 *
	 * @param string $input
	 * @param string $expect
	 *
	 * @dataProvider providerModule
	 */

	public function testModule($input = null, $expect = null)
	{
		/* setup */

		$parser = new Parser($this->_registry, $this->_language);
		$parser->init($input);

		/* actual */

		$actual = $parser->getOutput();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
