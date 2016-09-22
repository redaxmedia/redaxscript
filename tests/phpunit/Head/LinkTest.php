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
	 * providerFileAppend
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerFileAppend()
	{
		return $this->getProvider('tests/provider/Head/link_file_append.json');
	}

	/**
	 * providerFilePrepend
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerFilePrepend()
	{
		return $this->getProvider('tests/provider/Head/link_file_prepend.json');
	}

	/**
	 * providerRemove
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerRemove()
	{
		return $this->getProvider('tests/provider/Head/link_remove.json');
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
			$link->append($value);
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
			$link->prepend($value);
		}

		/* actual */

		$actual = $link->render();

		/* compare */

		$this->assertEquals($this->normalizeEOL($expect), $actual);
	}

	/**
	 * testAppend
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerFileAppend
	 *
	 * @param array $linkArray
	 * @param string $expect
	 */

	public function testFileAppend($linkArray = [], $expect = null)
	{
		/* setup */

		$link= Head\Link::getInstance();
		foreach ($linkArray as $value)
		{
			$link->appendFile($value);
		}

		/* actual */

		$actual = $link->render();

		/* compare */

		$this->assertEquals($this->normalizeEOL($expect), $actual);
	}

	/**
	 * testFilePrepend
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerFilePrepend
	 *
	 * @param array $linkArray
	 * @param string $expect
	 */

	public function testFilePrepend($linkArray = [], $expect = null)
	{
		/* setup */

		$link= Head\Link::getInstance();
		foreach ($linkArray as $value)
		{
			$link->prependFile($value);
		}

		/* actual */

		$actual = $link->render();

		/* compare */

		$this->assertEquals($this->normalizeEOL($expect), $actual);
	}

	/**
	 * testRemove
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerRemove
	 *
	 * @param array $linkArray
	 * @param string $remove
	 * @param string $expect
	 */

	public function testRemove($linkArray = [], $remove = null, $expect = null)
	{
		/* setup */

		$link= Head\Link::getInstance();
		foreach ($linkArray as $key => $value)
		{
			$link->append($value);
		}
		$link->removeFile($remove);

		/* actual */

		$actual = $link->render();

		/* compare */

		$this->assertEquals($this->normalizeEOL($expect), $actual);
	}
}
