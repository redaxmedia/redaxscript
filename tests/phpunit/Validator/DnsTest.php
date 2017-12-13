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
 */

class DnsTest extends TestCaseAbstract
{
	/**
	 * providerDns
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerDns() : array
	{
		return $this->getProvider('tests/provider/Validator/dns.json');
	}

	/**
	 * testDns
	 *
	 * @since 2.2.0
	 *
	 * @param string $host
	 * @param string $type
	 * @param int $expect
	 *
	 * @dataProvider providerDns
	 */

	public function testDns(string $host = null, string $type = null, int $expect = null)
	{
		/* setup */

		$validator = new Validator\Dns();

		/* actual */

		$actual = $validator->validate($host, $type);

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
