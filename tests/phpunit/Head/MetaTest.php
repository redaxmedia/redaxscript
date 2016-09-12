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
	 * testAppend
	 *
	 * @since 3.0.0
	 *
	 * @dataProvider providerAppend
	 *
	 * @param array $metaArray
	 * @param string $expect
	 */

	public function testAppend($metaArray = [], $expect = null)
	{
		/* setup */

		$metaHead = Head\Meta::getInstance();
		foreach ($metaArray as $key => $value)
		{
			$metaHead->append($value);
		}

		/* actual */

		$actual = $metaHead->render();

		/* compare */

		/*@todo: enable this once clear() in HeadAbstract was refactored */
		//$this->assertEquals($expect, $actual);
	}
}
