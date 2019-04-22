<?php
namespace Redaxscript\Tests;

use Redaxscript\Hash;
use function defined;

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
	 * testGetHash
	 *
	 * @since 2.6.0
	 *
	 * @param string $raw
	 * @param array $hashArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetHash(string $raw = null, array $hashArray = []) : void
	{
		/* setup */

		$hash = new Hash();
		$hash->init($raw);

		/* expect and actual */

		$expect = defined('PASSWORD_ARGON2I') ? $hashArray['argon2i'][0] : $hashArray['default'][0];
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
	 * @dataProvider providerAutoloader
	 */

	public function testValidate(string $raw = null, array $hashArray = []) : void
	{
		/* setup */

		$hash = new Hash();
		$hash->init($raw);

		/* actual */

		$actual = $hash->validate($raw, defined('PASSWORD_ARGON2I') ? $hashArray['argon2i'][1] : $hashArray['default'][1]);

		/* compare */

		$this->assertTrue($actual);
	}
}
