<?php
namespace Redaxscript\Tests\Head;

use Redaxscript\Head;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * MetaTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Balázs Szilágyi
 *
 * @covers Redaxscript\Head\HeadAbstract
 * @covers Redaxscript\Head\Meta
 */

class MetaTest extends TestCaseAbstract
{
	/**
	 * testAppend
	 *
	 * @since 3.0.0
	 *
	 * @param array $metaArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testAppend(array $metaArray = [], string $expect = null) : void
	{
		/* setup */

		$meta = Head\Meta::getInstance();
		$meta->init('append');

		/* process meta */

		foreach ($metaArray as $value)
		{
			$meta->append($value);
		}

		/* actual */

		$actual = $meta;

		/* compare */

		$this->assertEquals($expect, $this->normalizeNewline($actual));
	}

	/**
	 * testPrepend
	 *
	 * @since 3.1.0
	 *
	 * @param array $metaArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testPrepend(array $metaArray = [], string $expect = null) : void
	{
		/* setup */

		$meta = Head\Meta::getInstance();
		$meta->init('prepend');

		/* process meta */

		foreach ($metaArray as $value)
		{
			$meta->prepend($value);
		}

		/* actual */

		$actual = $meta;

		/* compare */

		$this->assertEquals($expect, $this->normalizeNewline($actual));
	}

	/**
	 * testInvalid
	 *
	 * @since 3.0.0
	 */

	public function testInvalid() : void
	{
		/* setup */

		$meta = Head\Meta::getInstance();
		$meta->init('invalid');

		/* actual */

		$actual = $meta;

		/* compare */

		$this->assertEquals('<!-- Redaxscript\Head\Meta\Invalid -->', $actual);
	}
}
