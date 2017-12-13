<?php
namespace Redaxscript\Tests\Validator;

use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\Validator;

/**
 * AccessTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Sven Weingartner
 */

class AccessTest extends TestCaseAbstract
{
	/**
	 * providerAccess
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerAccess() : array
	{
		return $this->getProvider('tests/provider/Validator/access.json');
	}

	/**
	 * testAccess
	 *
	 * @since 2.2.0
	 *
	 * @param string $access
	 * @param string $groups
	 * @param int $expect
	 *
	 * @dataProvider providerAccess
	 */

	public function testAccess(string $access = null, string $groups = null, int $expect = null)
	{
		/* setup */

		$validator = new Validator\Access();

		/* actual */

		$actual = $validator->validate($access, $groups);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
