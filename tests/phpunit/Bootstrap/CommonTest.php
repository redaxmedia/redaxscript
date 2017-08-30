<?php
namespace Redaxscript\Tests\Bootstrap;

use Redaxscript\Bootstrap;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * CommonTest
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @runTestsInSeparateProcesses
 */

class CommonTest extends TestCaseAbstract
{
	/**
	 * providerCommonServer
	 *
	 * @since 3.2.3
	 *
	 * @return array
	 */

	public function providerCommonServer()
	{
		return $this->getProvider('tests/provider/Bootstrap/common_server.json');
	}

	/**
	 * providerCommonClient
	 *
	 * @since 3.2.3
	 *
	 * @return array
	 */

	public function providerCommonClient()
	{
		return $this->getProvider('tests/provider/Bootstrap/common_client.json');
	}

	/**
	 * testCommonServer
	 *
	 * @since 3.2.3
	 *
	 * @param string $userAgent
	 * @param array $expectArray
	 *
	 * @dataProvider providerCommonServer
	 */

	public function testCommonServer($userAgent = null, $expectArray = [])
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
	 * testCommonClient
	 *
	 * @since 3.2.3
	 *
	 * @param string $userAgent
	 * @param array $expectArray
	 *
	 * @dataProvider providerCommonClient
	 */

	public function testCommonClient($userAgent = null, $expectArray = [])
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
	 * testCommonDriver
	 *
	 * @since 3.2.3
	 */

	public function testCommonDriver()
	{
		/* setup */

		new Bootstrap\Common($this->_registry, $this->_request);

		/* actual */

		$actualArray = $this->_registry->get('driverArray');

		/* compare */

		$this->assertNotNull($actualArray);
	}

	/**
	 * testCommonModule
	 *
	 * @since 3.2.3
	 */

	public function testCommonModule()
	{
		/* setup */

		putenv('REDIRECT_mod_deflate=on');
		putenv('REDIRECT_mod_headers=on');
		putenv('REDIRECT_mod_rewrite=on');
		new Bootstrap\Common($this->_registry, $this->_request);

		/* expect and actual */

		$expectArray =
		[
			'mod_deflate' => true,
			'mod_headers' => true,
			'mod_rewrite' => true
		];
		$actualArray = $this->_registry->get('moduleArray');

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testCommonPhp
	 *
	 * @since 3.2.3
	 */

	public function testCommonPhp()
	{
		/* setup */

		new Bootstrap\Common($this->_registry, $this->_request);

		/* actual */

		$actualArray =
		[
			'phpOs' => $this->_registry->get('phpOs'),
			'phpVersion' => $this->_registry->get('phpVersion')
		];

		/* compare */

		$this->assertString($actualArray['phpOs']);
		$this->assertString($actualArray['phpVersion']);
	}
}
