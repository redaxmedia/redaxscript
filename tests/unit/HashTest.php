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
 *
 * @covers Redaxscript\Hash
 */

class HashTest extends TestCaseAbstract
{
	/**
	 * testInit
	 *
	 * @since 2.6.0
	 *
	 * @param string $raw
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testInit(string $raw = null) : void
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
	 * @dataProvider providerAutoloader
	 */

	public function testGetRaw(string $raw = null) : void
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
	 * testGetAlgorithmAndGetHash
	 *
	 * @since 4.3.0
	 *
	 * @param string $raw
	 * @param array $hashArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetAlgorithmAndGetHash(string $raw = null, array $hashArray = []) : void
	{
		/* setup */

		$hash = new Hash();
		$hash->init($raw);
		$hashAlgorithm = $hash->getAlgorithm();

		/* expect and actual */

		$expect = $hashArray[$hashAlgorithm][0];
		$actual = $hash->getHash();

		/* compare */

		$this->assertIsString($hashAlgorithm);
		$this->assertStringStartsWith($expect, $actual);
	}

	/**
	 * testValidate
	 *
	 * @since 4.3.0
	 *
	 * @param string $raw
	 * @param array $hashArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testValidate(string $raw = null, array $hashArray = []) : void
	{
		/* setup */

		$hash = new Hash();
		$hash->init($raw);
		$hashAlgorithm = $hash->getAlgorithm();

		/* actual */

		$actual = $hash->validate($raw, $hashArray[$hashAlgorithm][1]);

		/* compare */

		$this->assertTrue($actual);
	}
}
