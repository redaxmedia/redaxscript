<?php
namespace Redaxscript\Tests\Content;

use Redaxscript\Config;
use Redaxscript\Language;
use Redaxscript\Content;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Tests\TestCaseAbstract;

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
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

	/**
	 * instance of the config class
	 *
	 * @var object
	 */

	protected $_config;

	/**
	 * setUp
	 *
	 * @since 2.1.0
	 */

	public function setUp()
	{
		$this->_registry = Registry::getInstance();
		$this->_request = Request::getInstance();
		$this->_language = Language::getInstance();
		$this->_config = Config::getInstance();
	}

	/**
	 * providerBlockcode
	 *
	 * @since 2.5.0
	 *
	 * @return array
	 */

	public function providerBlockcode()
	{
		return $this->getProvider('tests/provider/Content/parser_blockcode.json');
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
		return $this->getProvider('tests/provider/Content/parser_language.json');
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
		return $this->getProvider('tests/provider/Content/parser_module.json');
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
		return $this->getProvider('tests/provider/Content/parser_readmore.json');
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
		return $this->getProvider('tests/provider/Content/parser_registry.json');
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
		return $this->getProvider('tests/provider/Content/parser_template.json');
	}

	/**
	 * testBlockcode
	 *
	 * @since 3.0.0
	 *
	 * @param string $content
	 * @param string $expect
	 *
	 * @dataProvider providerBlockcode
	 */

	public function testBlockcode($content = null, $expect = null)
	{
		/* setup */

		$parser = new Content\Parser($this->_registry, $this->_request, $this->_language, $this->_config);
		$parser->process($content);

		/* actual */

		$actual = $parser->getOutput();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testLanguage
	 *
	 * @since 3.0.0
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
		$parser = new Content\Parser($this->_registry, $this->_request, $this->_language, $this->_config);
		$parser->process($content);

		/* actual */

		$actual = $parser->getOutput();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testModule
	 *
	 * @since 3.0.0
	 *
	 * @param string $content
	 * @param string $expect
	 *
	 * @dataProvider providerModule
	 */

	public function testModule($content = null, $expect = null)
	{
		/* setup */

		$parser = new Content\Parser($this->_registry, $this->_request, $this->_language, $this->_config);
		$parser->process($content);

		/* actual */

		$actual = $parser->getOutput();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testReadmore
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param string $content
	 * @param string $route
	 * @param string $expect
	 *
	 * @dataProvider providerReadmore
	 */

	public function testReadmore($registryArray = [], $content = null, $route = null, $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);
		$parser = new Content\Parser($this->_registry, $this->_request, $this->_language, $this->_config);
		$parser->process($content, $route);

		/* actual */

		$actual = $parser->getOutput();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testRegistry
	 *
	 * @since 3.0.0
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
		$parser = new Content\Parser($this->_registry, $this->_request, $this->_language, $this->_config);
		$parser->process($content);

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

		$parser = new Content\Parser($this->_registry, $this->_request, $this->_language, $this->_config);
		$parser->process($content);

		/* actual */

		$actual = $parser->getOutput();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
