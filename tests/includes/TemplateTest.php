<?php
namespace Redaxscript\Tests;

use Redaxscript\Template;

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
		/* actual */

		$actual = Template::partial('tests/stubs/partial.phtml');

		/* compare */

		$this->assertTrue(is_string($actual));
	}
}
