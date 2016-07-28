<?php
namespace Redaxscript\Tests\Server;

use Redaxscript\Request;
use Redaxscript\Server;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ClientTest
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ServerTest extends TestCaseAbstract
{
	/**
	 * instance of the request class
	 *
	 * @var object
	 */

	protected $_request;

	/**
	 * setUp
	 *
	 * @since 2.4.0
	 */

	public function setUp()
	{
		$this->_request = Request::getInstance();
		$this->_request->set('server',
		[
			'HTTP_HOST' => 'localhost',
			'HTTPS' => 'off',
			'SCRIPT_NAME' => '/tests/includes/Server/ServerTest.php'
		]);
	}

	/**
	 * testDirectory
	 *
	 * @since 2.4.0
	 */

	public function testDirectory()
	{
		/* setup */

		$directory = new Server\Directory($this->_request);

		/* actual */

		$actual = $directory->getOutput();

		/* compare */

		$this->assertEquals('/tests/includes/Server', $actual);
	}

	/**
	 * testFile
	 *
	 * @since 2.4.0
	 */

	public function testFile()
	{
		/* setup */

		$file = new Server\File($this->_request);

		/* actual */

		$actual = $file->getOutput();

		/* compare */

		$this->assertEquals('ServerTest.php', $actual);
	}

	/**
	 * testHost
	 *
	 * @since 2.4.0
	 */

	public function testHost()
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

	public function testProtocol()
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

	public function testRoot()
	{
		/* setup */

		$root = new Server\Root($this->_request);

		/* actual */

		$actual = $root->getOutput();

		/* compare */

		$this->assertEquals('http://localhost/tests/includes/Server', $actual);
	}

	/**
	 * testToken
	 *
	 * @since 2.4.0
	 */

	public function testToken()
	{
		/* setup */

		$token = new Server\Token($this->_request);

		/* actual */

		$actual = $token->getOutput();

		/* compare */

		$this->assertNotEmpty($actual);
	}
}
