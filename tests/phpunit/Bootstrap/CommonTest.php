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
	 * providerCommon
	 *
	 * @since 3.1.0
	 *
	 * @return array
	 */

	public function providerCommon()
	{
		return $this->getProvider('tests/provider/Bootstrap/common.json');
	}

	/**
	 * testCommon
	 *
	 * @since 3.1.0
	 *
	 * @param string $userAgent
	 * @param array $expectArray
	 *
	 * @dataProvider providerCommon
	 */

	public function testCommon($userAgent = null, $expectArray = [])
	{
		/* setup */

		$this->_request->set('server',
		[
			'HTTP_HOST' => 'localhost',
			'HTTP_USER_AGENT' => $userAgent,
			'REMOTE_ADDR' => 'localhost',
			'SCRIPT_NAME' => '/redaxscript/index.php'
		]);
		putenv('REDIRECT_mod_deflate=on');
		putenv('REDIRECT_mod_headers=on');
		putenv('REDIRECT_mod_rewrite=on');
		new Bootstrap\Common($this->_registry, $this->_request);

		/* actual */

		$actualArray =
		[
			'file' => $this->_registry->get('file'),
			'root' => $this->_registry->get('root'),
			'host' => $this->_registry->get('host'),
			'token' => $this->_registry->get('token'),
			'myBrowser' => $this->_registry->get('myBrowser'),
			'myBrowserVersion' => $this->_registry->get('myBrowserVersion'),
			'myEngine' => $this->_registry->get('myEngine'),
			'myMobile' => $this->_registry->get('myMobile'),
			'myTablet' => $this->_registry->get('myTablet'),
			'myDesktop' => $this->_registry->get('myDesktop'),
			'moduleArray' => $this->_registry->get('moduleArray')
		];

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}
}
