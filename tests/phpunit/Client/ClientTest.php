<?php
namespace Redaxscript\Tests\Client;

use Redaxscript\Client;
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

class ClientTest extends TestCaseAbstract
{
	/**
	 * providerClient
	 *
	 * @since 2.4.0
	 *
	 * @return array
	 */

	public function providerClient() : array
	{
		return $this->getProvider('tests/provider/Client/client.json');
	}

	/**
	 * testBrowser
	 *
	 * @since 2.4.0
	 *
	 * @param string $userAgent
	 * @param array $expectArray
	 *
	 * @dataProvider providerClient
	 */

	public function testBrowser(string $userAgent = null, array $expectArray = [])
	{
		/* setup */

		$this->_request->setServer('HTTP_USER_AGENT', $userAgent);
		$browser = new Client\Browser($this->_request);

		/* actual */

		$actual = $browser->getOutput();

		/* compare */

		$this->assertEquals($expectArray['browser'], $actual);
	}

	/**
	 * testDesktop
	 *
	 * @since 2.4.0
	 *
	 * @param string $userAgent
	 * @param array $expectArray
	 *
	 * @dataProvider providerClient
	 */

	public function testDesktop(string $userAgent = null, array $expectArray = [])
	{
		/* setup */

		$this->_request->setServer('HTTP_USER_AGENT', $userAgent);
		$desktop = new Client\Desktop($this->_request);

		/* actual */

		$actual = $desktop->getOutput();

		/* compare */

		$this->assertEquals($expectArray['desktop'], $actual);
	}

	/**
	 * testEngine
	 *
	 * @since 2.4.0
	 *
	 * @param string $userAgent
	 * @param array $expectArray
	 *
	 * @dataProvider providerClient
	 */

	public function testEngine(string $userAgent = null, array $expectArray = [])
	{
		/* setup */

		$this->_request->setServer('HTTP_USER_AGENT', $userAgent);
		$engine = new Client\Engine($this->_request);

		/* actual */

		$actual = $engine->getOutput();

		/* compare */

		$this->assertEquals($expectArray['engine'], $actual);
	}

	/**
	 * testMobile
	 *
	 * @since 2.4.0
	 *
	 * @param string $userAgent
	 * @param array $expectArray
	 *
	 * @dataProvider providerClient
	 */

	public function testMobile(string $userAgent = null, array $expectArray = [])
	{
		/* setup */

		$this->_request->setServer('HTTP_USER_AGENT', $userAgent);
		$mobile = new Client\Mobile($this->_request);

		/* actual */

		$actual = $mobile->getOutput();

		/* compare */

		$this->assertEquals($expectArray['mobile'], $actual);
	}

	/**
	 * testTablet
	 *
	 * @since 2.4.0
	 *
	 * @param string $userAgent
	 * @param array $expectArray
	 *
	 * @dataProvider providerClient
	 */

	public function testTablet(string $userAgent = null, array $expectArray = [])
	{
		/* setup */

		$this->_request->setServer('HTTP_USER_AGENT', $userAgent);
		$tablet = new Client\Tablet($this->_request);

		/* actual */

		$actual = $tablet->getOutput();

		/* compare */

		$this->assertEquals($expectArray['tablet'], $actual);
	}

	/**
	 * testVersion
	 *
	 * @since 2.4.0
	 *
	 * @param string $userAgent
	 * @param array $expectArray
	 *
	 * @dataProvider providerClient
	 */

	public function testVersion(string $userAgent = null, array $expectArray = [])
	{
		/* setup */

		$this->_request->setServer('HTTP_USER_AGENT', $userAgent);
		$version = new Client\Version($this->_request);

		/* actual */

		$actual = $version->getOutput();

		/* compare */

		$this->assertEquals($expectArray['version'], $actual);
	}
}
