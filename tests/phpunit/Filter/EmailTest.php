<?php
namespace Redaxscript\Tests\Filter;

use Redaxscript\Filter;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * EmailTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class EmailTest extends TestCaseAbstract
{
	/**
	 * providerEmail
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerEmail()
	{
		return $this->getProvider('tests/provider/Filter/email.json');
	}

	/**
	 * testEmail
	 *
	 * @since 2.2.0
	 *
	 * @param string $email
	 * @param string $expect
	 *
	 * @dataProvider providerEmail
	 */

	public function testEmail($email = null, $expect = null)
	{
		/* setup */

		$filter = new Filter\Email();

		/* actual */

		$actual = $filter->sanitize($email);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
