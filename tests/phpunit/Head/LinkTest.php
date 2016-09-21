<?php
namespace Redaxscript\Tests\Head;

use Redaxscript\Head;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * LinkTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Balázs Szilágyi
 */

class LinkTest extends TestCaseAbstract
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
		return $this->getProvider('tests/provider/Head/link_append.json');
	}

	/**
	 * providerPrepend
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerPrepend()
	{
		return $this->getProvider('tests/provider/Head/link_prepend.json');
	}

	/**
	 * testAppend
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerAppend
	 *
	 * @param array $linkArray
	 * @param string $expect
	 */

	public function testAppend($linkArray = [], $expect = null)
	{
		/* setup */

		$link= Head\Link::getInstance();
		foreach ($linkArray as $key => $value)
		{
			$link->appendFile($value);
		}

		/* actual */

		$actual = $link->render();

		/* compare */

		$this->assertEquals($this->normalizeEOL($expect), $actual);
	}

	/**
	 * testPrepend
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerPrepend
	 *
	 * @param array $linkArray
	 * @param string $expect
	 */

	public function testPrepend($linkArray = [], $expect = null)
	{
		/* setup */

		$link= Head\Link::getInstance();
		foreach ($linkArray as $key => $value)
		{
			$link->prependFile($value);
		}

		/* actual */

		$actual = $link->render();

		/* compare */

		$this->assertEquals($this->normalizeEOL($expect), $actual);
	}
}
