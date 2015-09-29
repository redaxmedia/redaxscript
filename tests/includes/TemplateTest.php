<?php
namespace Redaxscript\Tests;

use Redaxscript\Language;
use Redaxscript\Template;
use Redaxscript\Registry;
use org\bovigo\vfs\vfsStream as Stream;
use org\bovigo\vfs\vfsStreamFile as StreamFile;
use org\bovigo\vfs\vfsStreamWrapper as StreamWrapper;

/**
 * TemplateTest
 *
 * @since 2.3.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class TemplateTest extends TestCase
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
	 * @since 2.6.0
	 */

	protected function setUp()
	{
		$this->_registry = Registry::getInstance();
		$this->_language = Language::getInstance();
	}

	/**
	 * testBreadcrumb
	 *
	 * @since 2.3.0
	 */

	public function testBreadcrumb()
	{
		/* actual */

		$actual = Template::breadcrumb();

		/* compare */

		$this->assertTrue(is_string($actual));
	}

	/**
	 * testHelperClass
	 *
	 * @since 2.3.0
	 */

	public function testHelperClass()
	{
		/* actual */

		$actual = Template::helperClass();

		/* compare */

		$this->assertTrue(is_string($actual));
	}

	/**
	 * testHelperSubset
	 *
	 * @since 2.3.0
	 */

	public function testHelperSubset()
	{
		/* actual */

		$actual = Template::HelperSubset();

		/* compare */

		$this->assertTrue(is_string($actual));
	}

	/**
	 * testLanguage
	 *
	 * @since 2.6.0
	 */

	public function testLanguage()
	{
		/* setup */

		$this->_language->set('testKey', 'testValue');

		/* actual */

		$actual = Template::language('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testPartial
	 *
	 * @since 2.3.0
	 */

	public function testPartial()
	{
		/* setup */

//		Stream::setup('root');
//		$file = new StreamFile('partial.phtml');
//		StreamWrapper::getRoot()->addChild($file);

		/* actual */

//		$actual = Template::partial(Stream::url('root/partial.phtml'));

		/* compare */

//		$this->assertTrue(is_string($actual));
	}

	/**
	 * testRegistry
	 *
	 * @since 2.6.0
	 */

	public function testRegistry()
	{
		/* setup */

		$this->_registry->set('testKey', 'testValue');

		/* actual */

		$actual = Template::registry('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
	}

	/**
	 * testSetting
	 *
	 * @since 2.6.0
	 */

	public function testSetting()
	{
		/* actual */

		$actual = Template::setting('charset');

		/* compare */

		$this->assertEquals('utf-8', $actual);
	}
}
