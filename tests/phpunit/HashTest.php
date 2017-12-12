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

	public function providerHash() : array
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

	public function testInit(string $raw = null)
	{
		/* setup */

		$hash = new Hash();
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

	public function testGetRaw(string $raw = null)
	{
		/* setup */

		$hash = new Hash();
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

	public function testGetHash(string $raw = null, array $hashArray = [])
	{
		/* setup */

		$hash = new Hash();
		$hash->init($raw);

		/* expect and actual */

		$expect = $hashArray[0];
		$actual = $hash->getHash();

		/* compare */

		$this->assertStringStartsWith($expect, $actual);
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

	public function testValidate(string $raw = null, array $hashArray = [])
	{
		/* setup */

		$hash = new Hash();
		$hash->init($raw);

		/* actual */

		$actual = $hash->validate($raw, $hashArray[1]);

		/* compare */

		$this->assertTrue($actual);
	}
}
