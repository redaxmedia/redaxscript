<?php
namespace Redaxscript\Tests\Content;

use Redaxscript\Content;
use Redaxscript\Module;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ParserTest
 *
 * @since 2.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Content\Parser
 * @covers Redaxscript\Content\ParserAbstract
 * @covers Redaxscript\Content\Tag\Code
 * @covers Redaxscript\Content\Tag\Language
 * @covers Redaxscript\Content\Tag\Module
 * @covers Redaxscript\Content\Tag\More
 * @covers Redaxscript\Content\Tag\Registry
 * @covers Redaxscript\Content\Tag\TagAbstract
 * @covers Redaxscript\Content\Tag\Template
 */

class ParserTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp() : void
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
		$this->installTestDummy();
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown() : void
	{
		$this->uninstallTestDummy();
		$this->dropDatabase();
	}

	/**
	 * testCode
	 *
	 * @since 3.0.0
	 *
	 * @param string $content
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testCode(string $content = null, string $expect = null) : void
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
	 * @dataProvider providerAutoloader
	 */

	public function testLanguage(string $language = null, string $content = null, string $expect = null) : void
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
	 * @dataProvider providerAutoloader
	 */

	public function testModule(string $content = null, string $expect = null) : void
	{
		/* setup */

		Module\Hook::construct($this->_registry, $this->_request, $this->_language, $this->_config);
		Module\Hook::init();
		$parser = new Content\Parser($this->_registry, $this->_request, $this->_language, $this->_config);
		$parser->process($content);

		/* actual */

		$actual = $parser->getOutput();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testMore
	 *
	 * @since 3.0.0
	 *
	 * @param array $registryArray
	 * @param string $content
	 * @param string $route
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testMore(array $registryArray = [], string $content = null, string $route = null, string $expect = null) : void
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
	 * @dataProvider providerAutoloader
	 */

	public function testRegistry(array $registryArray = [], string $content = null, string $expect = null) : void
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
	 * @dataProvider providerAutoloader
	 */

	public function testTemplate(string $content = null, string $expect = null) : void
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
