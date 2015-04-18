<?php
namespace Redaxscript\Tests;

use Redaxscript\Template;
use org\bovigo\vfs\vfsStream as Stream;
use org\bovigo\vfs\vfsStreamFile as StreamFile;
use org\bovigo\vfs\vfsStreamWrapper as StreamWrapper;

/**
 * ConfigTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class TemplateTest extends TestCase
{
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

		$actual = Template::partial(Stream::url('root/partial.phtml'));

		/* compare */

		$this->assertTrue(is_string($actual));
	}
}
