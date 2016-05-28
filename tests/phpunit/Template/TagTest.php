<?php
namespace Redaxscript\Tests\Template;

use Redaxscript\Language;
use Redaxscript\Registry;
use Redaxscript\Request;
use Redaxscript\Template;
use Redaxscript\Tests\TestCaseAbstract;
use org\bovigo\vfs\vfsStream as Stream;
use org\bovigo\vfs\vfsStreamFile as StreamFile;
use org\bovigo\vfs\vfsStreamWrapper as StreamWrapper;

/**
 * TagTest
 *
 * @since 2.3.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class TagTest extends TestCaseAbstract
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
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * setUp
	 *
	 * @since 2.6.0
	 */

	public function setUp()
	{
		$this->_registry = Registry::getInstance();
		$this->_language = Language::getInstance();
		$this->_request = Request::getInstance();
	}

	/**
	 * testBreadcrumb
	 *
	 * @since 2.3.0
	 */

	public function testBreadcrumb()
	{
		/* actual */

		$actual = Template\Tag::breadcrumb();

		/* compare */

		$this->assertString($actual);
	}

	/**
	 * testConsole
	 *
	 * @since 3.0.0
	 */

	public function testConsole()
	{
		/* setup */

		$this->_request->setPost('argv', 'help');

		/* actual */

		$actual = Template\Tag::console();

		/* compare */

		$this->assertString($actual);
	}

	/**
	 * testSearch
	 *
	 * @since 3.0.0
	 */

	public function testSearch()
	{
		/* actual */

		$actual = Template\Tag::search();

		/* compare */

		$this->assertString($actual);
	}

	/**
	 * testPartial
	 *
	 * @since 2.3.0
	 */

	public function testPartial()
	{
		/* setup */

		Stream::setup('root');
		$file = new StreamFile('partial.phtml');
		StreamWrapper::getRoot()->addChild($file);

		/* actual */

		$actual = Template\Tag::partial(Stream::url('root/partial.phtml'));

		/* compare */

		$this->assertString($actual);
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

		$actual = Template\Tag::registry('testKey');

		/* compare */

		$this->assertEquals('testValue', $actual);
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

		$actual = Template\Tag::language('testKey');

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

		$actual = Template\Tag::setting('charset');

		/* compare */

		$this->assertEquals('utf-8', $actual);
	}
}
