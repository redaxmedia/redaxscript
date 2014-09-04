<?php
namespace Redaxscript\Tests\Filter;

use Redaxscript\Filter;
use Redaxscript\Tests\TestCase;

/**
 * ScriptTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ScriptTest extends TestCase
{
	/**
	 * providerFilterScript
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerFilterScript()
	{
		return $this->getProvider('tests/provider/Filter/script.json');
	}

	/**
	 * testScript
	 *
	 * @since 2.2.0
	 *
	 * @param string $script
	 * @param string $expect
	 *
	 * @dataProvider providerFilterScript
	 */

	public function testScript($script = null, $expect = null)
	{
		/* setup */

		$filter = new Filter\Script();

		/* result */

		$result = $filter->sanitize($script);

		/* compare */

		$this->assertEquals($expect, $result);
	}
}
