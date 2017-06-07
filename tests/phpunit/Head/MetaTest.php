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
 */

class MetaTest extends TestCaseAbstract
{
	/**
	 * providerAppend
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerAppend()
	{
		return $this->getProvider('tests/provider/Head/meta_append.json');
	}

	/**
	 * providerPrepend
	 *
	 * @since 3.1.0
	 *
	 * @return array
	 */

	public function providerPrepend()
	{
		return $this->getProvider('tests/provider/Head/meta_prepend.json');
	}

	/**
	 * testAppend
	 *
	 * @since 3.0.0
	 *
	 * @param array $metaArray
	 * @param string $expect
	 *
	 * @dataProvider providerAppend
	 */

	public function testAppend($metaArray = [], $expect = null)
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
	 * @dataProvider providerPrepend
	 */

	public function testPrepend($metaArray = [], $expect = null)
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

	public function testInvalid()
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
