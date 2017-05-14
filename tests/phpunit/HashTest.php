<?php
namespace Redaxscript\Tests;

use Redaxscript\Hash;

/**
 * HashTest
 *
 * @since 2.6.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class HashTest extends TestCaseAbstract
{
	/**
	 * providerHash
	 *
	 * @since 2.6.0
	 *
	 * @return array
	 */

	public function providerHash()
	{
		return $this->getProvider('tests/provider/hash.json');
	}

	/**
	 * testInit
	 *
	 * @since 2.6.0
	 *
	 * @param string $raw
	 *
	 * @dataProvider providerHash
	 */

	public function testInit($raw = null)
	{
		/* setup */

		$hash = new Hash($this->_config);
		$hash->init($raw);

		/* compare */

		$this->assertNotEquals($hash->getRaw(), $hash->getHash());
	}

	/**
	 * testGetRaw
	 *
	 * @since 2.6.0
	 *
	 * @param string $raw
	 *
	 * @dataProvider providerHash
	 */

	public function testGetRaw($raw = null)
	{
		/* setup */

		$hash = new Hash($this->_config);
		$hash->init($raw);

		/* expect and actual */

		$expect = $raw;
		$actual = $hash->getRaw();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testGetHash
	 *
	 * @since 2.6.0
	 *
	 * @param string $raw
	 * @param array $hashArray
	 *
	 * @dataProvider providerHash
	 */

	public function testGetHash($raw = null, $hashArray = [])
	{
		/* setup */

		$hash = new Hash($this->_config);
		$hash->init($raw);

		/* expect and actual */

		$expect = function_exists('password_hash') ? $hashArray[0][0] : $hashArray[1];
		$actual = $hash->getHash();

		/* compare */

		if (function_exists('password_hash'))
		{
			$this->assertStringStartsWith($expect, $actual);
		}
		else
		{
			$this->assertEquals($expect, $actual);
		}
	}

	/**
	 * testValidate
	 *
	 * @since 2.6.0
	 *
	 * @param string $raw
	 * @param array $hashArray
	 *
	 * @dataProvider providerHash
	 */

	public function testValidate($raw = null, $hashArray = [])
	{
		/* setup */

		$hash = new Hash($this->_config);
		$hash->init($raw);

		/* actual */

		$actual = $hash->validate($raw, function_exists('password_verify') ? $hashArray[0][1] : $hashArray[1]);

		/* compare */

		$this->assertTrue($actual);
	}
}
