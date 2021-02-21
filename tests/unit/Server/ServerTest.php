<?php
namespace Redaxscript\Tests\Server;

use Redaxscript\Server;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ServerTest
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Server\Directory
 * @covers Redaxscript\Server\File
 * @covers Redaxscript\Server\Host
 * @covers Redaxscript\Server\Protocol
 * @covers Redaxscript\Server\Root
 * @covers Redaxscript\Server\ServerAbstract
 * @covers Redaxscript\Server\Token
 */

class ServerTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 2.4.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$this->_request->init();
		$this->_request->set('server',
		[
			'HTTP_HOST' => 'localhost',
			'HTTPS' => 'off',
			'SCRIPT_NAME' => '/redaxscript/index.php'
		]);
	}

	/**
	 * testDirectory
	 *
	 * @since 2.4.0
	 */

	public function testDirectory() : void
	{
		/* setup */

		$directory = new Server\Directory($this->_request);

		/* actual */

		$actual = $directory->getOutput();

		/* compare */

		$this->assertEquals('/redaxscript', $actual);
	}

	/**
	 * testFile
	 *
	 * @since 2.4.0
	 */

	public function testFile() : void
	{
		/* setup */

		$file = new Server\File($this->_request);

		/* actual */

		$actual = $file->getOutput();

		/* compare */

		$this->assertEquals('index.php', $actual);
	}

	/**
	 * testHost
	 *
	 * @since 2.4.0
	 */

	public function testHost() : void
	{
		/* setup */

		$host = new Server\Host($this->_request);

		/* actual */

		$actual = $host->getOutput();

		/* compare */

		$this->assertEquals('localhost', $actual);
	}

	/**
	 * testProtocol
	 *
	 * @since 2.4.0
	 */

	public function testProtocol() : void
	{
		/* setup */

		$protocol = new Server\Protocol($this->_request);

		/* actual */

		$actual = $protocol->getOutput();

		/* compare */

		$this->assertEquals('http', $actual);
	}

	/**
	 * testRoot
	 *
	 * @since 2.4.0
	 */

	public function testRoot() : void
	{
		/* setup */

		$root = new Server\Root($this->_request);

		/* actual */

		$actual = $root->getOutput();

		/* compare */

		$this->assertEquals('http://localhost/redaxscript', $actual);
	}

	/**
	 * testToken
	 *
	 * @since 2.4.0
	 */

	public function testToken() : void
	{
		/* setup */

		$token = new Server\Token($this->_request);

		/* actual */

		$actual = $token->getOutput();

		/* compare */

		$this->assertNotEmpty($actual);
	}
}
