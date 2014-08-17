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
	 * providerValidatorAccess
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerValidatorAlias()
	{
		return $this->getProvider('tests/provider/validator_alias.json');
	}

	/**
	 * testAlias
	 *
	 * @since 2.2.0
	 *
	 * @dataProvider providerValidatorAlias
	 */

	public function testAlias($alias = null, $mode = null, $expect = null)
	{
		/* setup */

		$validator = new Validator\Alias();

		/* result */

		$result = $validator->validate($alias, $mode);

		/* compare */

		$this->assertEquals($expect, $result);
	}
}
