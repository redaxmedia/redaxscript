<?php
namespace Redaxscript\Tests\Validator;

use Redaxscript\Tests\TestCase;
use Redaxscript\Validator;

/**
 * AliasTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Sven Weingartner
 */

class AliasTest extends TestCase
{
	/**
	 * providerAlias
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerAlias()
	{
		return $this->getProvider('tests/provider/Validator/alias.json');
	}

	/**
	 * testAlias
	 *
	 * @since 2.2.0
	 *
	 * @param string $alias
	 * @param integer $mode
	 * @param integer $expect
	 *
	 * @dataProvider providerAlias
	 */

	public function testAlias($alias = null, $mode = null, $expect = null)
	{
		/* setup */

		$validator = new Validator\Alias();

		/* actual */

		$actual = $validator->validate($alias, $mode);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
