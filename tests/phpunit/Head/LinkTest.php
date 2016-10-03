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
 * @author Henry Ruhs
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
	 * @param array $coreArray
	 * @param array $moduleArray
	 * @param string $expect
	 *
	 * @dataProvider providerAppend
	 */

	public function testAppend($coreArray = [], $moduleArray = [], $expect = null)
	{
		/* setup */

		$link = Head\Link::getInstance();
		$link->init('append');
		foreach ($coreArray as $key => $value)
		{
			$link->append($value);
		}
		foreach ($moduleArray as $key => $value)
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
	 * @param array $coreArray
	 * @param array $moduleArray
	 * @param string $expect
	 *
	 * @dataProvider providerPrepend
	 */

	public function testPrepend($coreArray = [], $moduleArray = [], $expect = null)
	{
		/* setup */

		$link = Head\Link::getInstance();
		$link->init('prepend');
		foreach ($coreArray as $value)
		{
			$link->prepend($value);
		}
		foreach ($moduleArray as $key => $value)
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
	 * @param array $coreArray
	 * @param string $deleteFile
	 * @param string $expect
	 *
	 * @dataProvider providerRemove
	 */

	public function testRemove($coreArray = [], $deleteFile = null, $expect = null)
	{
		/* setup */

		$link = Head\Link::getInstance();
		$link->init('remove');
		foreach ($coreArray as $key => $value)
		{
			$link->append($value);
		}
		$link->removeFile($deleteFile);

		/* actual */

		$actual = $link->render();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
