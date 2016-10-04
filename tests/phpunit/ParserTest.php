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

class ParserTest extends TestCaseAbstract
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

	public function setUp()
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
	 * providerTemplate
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerTemplate()
	{
		return $this->getProvider('tests/provider/parser_template.json');
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
	 * @param array $registryArray
	 * @param string $content
	 * @param array $optionArray
	 * @param string $expect
	 *
	 * @dataProvider providerReadmore
	 */

	public function testReadmore($registryArray = [], $content = null, $optionArray = [], $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);
		$parser = new Parser($this->_registry, $this->_language);
		$parser->init($content, $optionArray);

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
	 * @param string $content
	 * @param string $expect
	 *
	 * @dataProvider providerCodequote
	 */

	public function testCodequote($content = null, $expect = null)
	{
		/* setup */

		$parser = new Parser($this->_registry, $this->_language);
		$parser->init($content);

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
	 * @param string $content
	 * @param string $expect
	 *
	 * @dataProvider providerLanguage
	 */

	public function testLanguage($language = null, $content = null, $expect = null)
	{
		/* setup */

		$this->_language->init($language);
		$parser = new Parser($this->_registry, $this->_language);
		$parser->init($content);

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
	 * @param array $registryArray
	 * @param string $content
	 * @param string $expect
	 *
	 * @dataProvider providerRegistry
	 */

	public function testRegistry($registryArray = [], $content = null, $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);
		$parser = new Parser($this->_registry, $this->_language);
		$parser->init($content);

		/* actual */

		$actual = $parser->getOutput();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testTemplate
	 *
	 * @since 3.0.0
	 *
	 * @param string $content
	 * @param string $expect
	 *
	 * @dataProvider providerTemplate
	 */

	public function testTemplate($content = null, $expect = null)
	{
		/* setup */

		$parser = new Parser($this->_registry, $this->_language);
		$parser->init($content);

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
	 * @param string $content
	 * @param string $expect
	 *
	 * @dataProvider providerModule
	 */

	public function testModule($content = null, $expect = null)
	{
		/* setup */

		$parser = new Parser($this->_registry, $this->_language);
		$parser->init($content);

		/* actual */

		$actual = $parser->getOutput();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
