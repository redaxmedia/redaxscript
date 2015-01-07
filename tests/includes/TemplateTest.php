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
		/* result */

		$result = Template::breadcrumb();

		/* compare */

		$this->assertTrue(is_string($result));
	}

	/**
	 * testHelperClass
	 *
	 * @since 2.3.0
	 */

	public function testHelperClass()
	{
		/* result */

		$result = Template::helperClass();

		/* compare */

		$this->assertTrue(is_string($result));
	}

	/**
	 * testHelperSubset
	 *
	 * @since 2.3.0
	 */

	public function testHelperSubset()
	{
		/* result */

		$result = Template::HelperSubset();

		/* compare */

		$this->assertTrue(is_string($result));
	}

	/**
	 * testPartial
	 *
	 * @since 2.3.0
	 */

	public function testPartial()
	{
		/* result */

		$result = Template::partial('tests/stubs/partial.phtml');

		/* compare */

		$this->assertTrue(is_string($result));
	}
}
