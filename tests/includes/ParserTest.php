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
	 * providerReadmore
	 *
	 * @since 2.5.0
	 *
	 * @return array
	 */

	public function providerReadmore()
	{
		return $this->getProvider('tests/provider/parser_readmore.json');
	}

	/**
	 * providerCodequote
	 *
	 * @since 2.5.0
	 *
	 * @return array
	 */

	public function providerCodequote()
	{
		return $this->getProvider('tests/provider/parser_codequote.json');
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
	 * testReadmore
	 *
	 * @since 2.5.0
	 *
	 * @param array $registry
	 * @param string $input
	 * @param array $options
	 * @param string $expect
	 *
	 * @dataProvider providerReadmore
	 */

	public function testReadmore($registry = array(), $input = null, $options = array(), $expect = null)
	{
		/* setup */

		$this->_registry->init($registry);
		$parser = new Parser($this->_registry, $this->_language);
		$parser->init($input, $options);

		/* actual */

		$actual = $parser->getOutput();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testCodeQuote
	 *
	 * @since 2.5.0
	 *
	 * @param string $input
	 * @param string $expect
	 *
	 * @dataProvider providerCodequote
	 */

	public function testCodequote($input = null, $expect = null)
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
