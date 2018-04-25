<?php
namespace Redaxscript\Tests\Content;

use Redaxscript\Content;
use Redaxscript\Module\Hook;
use Redaxscript\Modules\TestDummy;
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
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp()
	{
		parent::setUp();
		$optionArray =
		[
			'adminName' => 'Test',
			'adminUser' => 'test',
			'adminPassword' => 'test',
			'adminEmail' => 'test@test.com'
		];
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
		$testDummy = new TestDummy\TestDummy($this->_registry, $this->_request, $this->_language, $this->_config);
		$testDummy->install();
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown()
	{
		$testDummy = new TestDummy\TestDummy($this->_registry, $this->_request, $this->_language, $this->_config);
		$testDummy->uninstall();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawDrop();
	}

	/**
	 * providerBlockcode
	 *
	 * @since 2.5.0
	 *
	 * @return array
	 */

	public function providerBlockcode() : array
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

	public function providerLanguage() : array
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

	public function providerModule() : array
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

	public function providerReadmore() : array
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

	public function providerRegistry() : array
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

	public function providerTemplate() : array
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

	public function testBlockcode(string $content = null, string $expect = null)
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

	public function testLanguage(string $language = null, string $content = null, string $expect = null)
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

	public function testModule(string $content = null, string $expect = null)
	{
		/* setup */

		Hook::construct($this->_registry, $this->_request, $this->_language, $this->_config);
		Hook::init();
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

	public function testReadmore(array $registryArray = [], string $content = null, string $route = null, string $expect = null)
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

	public function testRegistry(array $registryArray = [], string $content = null, string $expect = null)
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

	public function testTemplate(string $content = null, string $expect = null)
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
