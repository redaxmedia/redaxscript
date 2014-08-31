<?php
namespace Redaxscript\Tests\Filter;

use Redaxscript\Filter;
use Redaxscript\Tests\TestCase;

/**
 * EmailTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class EmailTest extends TestCase
{
	/**
	 * providerFilterEmail
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerFilterEmail()
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
	 * @dataProvider providerFilterEmail
	 */

	public function testEmail($email = null, $expect = null)
	{
		/* setup */

		$filter = new Filter\Email();

		/* result */

		$result = $filter->filter($email);

		/* compare */

		$this->assertEquals($expect, $result);
	}
}
