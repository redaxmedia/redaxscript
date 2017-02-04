<?php
namespace Redaxscript\Tests\Filter;

use Redaxscript\Purifier;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * PurifierTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class PurifierTest extends TestCaseAbstract
{
	/**
	 * providerPurifier
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerPurifier()
	{
		return $this->getProvider('tests/provider/purifier.json');
	}

	/**
	 * testPurifier
	 *
	 * @since 3.0.0
	 *
	 * @param string $html
	 * @param string $expect
	 *
	 * @dataProvider providerPurifier
	 */

	public function testPurifier($html = null, $expect = null)
	{
		/* setup */

		$purifier = new Purifier();

		/* actual */

		$actual = $purifier->purify($html);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
