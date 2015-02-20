<?php
namespace Redaxscript\Tests\Server;

use Redaxscript\Request;
use Redaxscript\Server;
use Redaxscript\Tests\TestCase;

/**
 * ClientTest
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ServerTest extends TestCase
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

	protected function setUp()
	{
		$this->_request = Request::getInstance();
		$this->_request->init();
		$this->_request->set('_SERVER', array(
			'HTTP_HOST' => 'localhost',
			'HTTPS' => 'off',
			'SCRIPT_NAME' => '/tests/includes/Server/ServerTest.php'
		));
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

		/* result */

		$result = $directory->getOutput();

		/* compare */

		$this->assertEquals('/tests/includes/Server', $result);
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

		/* result */

		$result = $file->getOutput();

		/* compare */

		$this->assertEquals('ServerTest.php', $result);
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

		/* result */

		$result = $host->getOutput();

		/* compare */

		$this->assertEquals('localhost', $result);
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

		/* result */

		$result = $protocol->getOutput();

		/* compare */

		$this->assertEquals('http', $result);
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

		/* result */

		$result = $root->getOutput();

		/* compare */

		$this->assertEquals('http://localhost/tests/includes/Server', $result);
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

		/* result */

		$result = $token->getOutput();

		/* compare */

		$this->assertNotEmpty($result);
	}
}
