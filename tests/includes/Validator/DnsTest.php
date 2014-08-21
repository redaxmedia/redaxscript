<?php
namespace Redaxscript\Tests\Validator;
use Redaxscript\Tests\TestCase;
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

class DnsTest extends TestCase
{
	/**
	 * providerValidatorDns
	 *
	 * @since 2.2.0
	 *
	 * @return array
	 */

	public function providerValidatorDns()
	{
		return $this->getProvider('tests/provider/validator_dns.json');
	}

	/**
	 * testDns
	 *
	 * @since 2.2.0
	 *
	 * @param string $host
	 * @param string $type
	 * @param integer $expect
	 *
	 * @dataProvider providerValidatorDns
	 */

	public function testDns($host = null, $type = null, $expect = null)
	{
		/* setup */

		$validator = new Validator\Dns();

		/* result */

		$result = $validator->validate($host, $type);

		/* compare */

		$this->assertEquals($expect, $result);
	}
}
