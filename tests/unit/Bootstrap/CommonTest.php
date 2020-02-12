<?php
namespace Redaxscript\Tests\Bootstrap;

use Redaxscript\Bootstrap;
use Redaxscript\Tests\TestCaseAbstract;
use function putenv;

/**
 * CommonTest
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Bootstrap\Common
 * @covers Redaxscript\Bootstrap\BootstrapAbstract
 *
 * @runTestsInSeparateProcesses
 */

class CommonTest extends TestCaseAbstract
{
	/**
	 * testServer
	 *
	 * @since 3.2.3
	 *
	 * @param string $userAgent
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testServer(string $userAgent = null, array $expectArray = []) : void
	{
		/* setup */

		$this->_request->set('server',
		[
			'HTTP_HOST' => 'localhost',
			'HTTP_USER_AGENT' => $userAgent,
			'REMOTE_ADDR' => 'localhost',
			'SCRIPT_NAME' => '/redaxscript/index.php'
		]);
		new Bootstrap\Common($this->_registry, $this->_request);

		/* actual */

		$actualArray =
		[
			'file' => $this->_registry->get('file'),
			'root' => $this->_registry->get('root'),
			'host' => $this->_registry->get('host'),
			'token' => $this->_registry->get('token')
		];

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testClient
	 *
	 * @since 3.2.3
	 *
	 * @param string $userAgent
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testClient(string $userAgent = null, array $expectArray = []) : void
	{
		/* setup */

		$this->_request->setServer('HTTP_USER_AGENT', $userAgent);
		new Bootstrap\Common($this->_registry, $this->_request);

		/* actual */

		$actualArray =
		[
			'myBrowser' => $this->_registry->get('myBrowser'),
			'myBrowserVersion' => $this->_registry->get('myBrowserVersion'),
			'myEngine' => $this->_registry->get('myEngine'),
			'myMobile' => $this->_registry->get('myMobile'),
			'myTablet' => $this->_registry->get('myTablet'),
			'myDesktop' => $this->_registry->get('myDesktop')
		];

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testDriver
	 *
	 * @since 3.2.3
	 */

	public function testDriver() : void
	{
		/* setup */

		new Bootstrap\Common($this->_registry, $this->_request);

		/* actual */

		$actualArray = $this->_registry->get('driverArray');

		/* compare */

		$this->assertNotNull($actualArray);
	}

	/**
	 * testModule
	 *
	 * @since 3.2.3
	 */

	public function testModule() : void
	{
		/* setup */

		putenv('REDIRECT_MOD_DEFLATE=on');
		putenv('REDIRECT_MOD_BROTLI=on');
		putenv('REDIRECT_MOD_SECURITY=on');
		putenv('REDIRECT_MOD_REWRITE=on');
		putenv('REDIRECT_MOD_HEADERS=on');
		new Bootstrap\Common($this->_registry, $this->_request);

		/* expect and actual */

		$expectArray =
		[
			'mod_deflate' => true,
			'mod_brotli' => true,
			'mod_security' => true,
			'mod_rewrite' => true,
			'mod_headers' => true
		];
		$actualArray = $this->_registry->get('moduleArray');

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testPhp
	 *
	 * @since 3.2.3
	 */

	public function testPhp() : void
	{
		/* setup */

		new Bootstrap\Common($this->_registry, $this->_request);

		/* actual */

		$actualArray =
		[
			'phpOs' => $this->_registry->get('phpOs'),
			'phpVersion' => $this->_registry->get('phpVersion'),
			'phpStatus' => $this->_registry->get('phpStatus')
		];

		/* compare */

		$this->assertIsString($actualArray['phpOs']);
		$this->assertIsString($actualArray['phpVersion']);
		$this->assertTrue($actualArray['phpStatus']);
	}
}
