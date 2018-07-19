<?php
namespace Redaxscript\Tests\Validator;

use Redaxscript\Tests\TestCaseAbstract;
use Redaxscript\Validator;

/**
 * DnsTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 * @author Sven Weingartner
 *
 * @covers Redaxscript\Validator\Dns
 */

class DnsTest extends TestCaseAbstract
{
	/**
	 * testDns
	 *
	 * @since 2.2.0
	 *
	 * @param string $host
	 * @param string $type
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testDns(string $host = null, string $type = null, bool $expect = null)
	{
		/* setup */

		$validator = new Validator\Dns();

		/* actual */

		$actual = $validator->validate($host, $type);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
