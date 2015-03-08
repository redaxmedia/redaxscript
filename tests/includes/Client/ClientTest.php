<?php
namespace Redaxscript\Tests\Client;

use Redaxscript\Client;
use Redaxscript\Request;
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

class ClientTest extends TestCase
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
	}

	/**
	 * providerClient
	 *
	 * @since 2.4.0
	 *
	 * @return array
	 */

	public function providerClient()
	{
		return $this->getProvider('tests/provider/Client/client.json');
	}

	/**
	 * testBrowser
	 *
	 * @since 2.4.0
	 *
	 * @param string $userAgent
	 * @param array $expect
	 *
	 * @dataProvider providerClient
	 */

	public function testBrowser($userAgent = null, $expect = array())
	{
		/* setup */

		$this->_request->setServer('HTTP_USER_AGENT', $userAgent);
		$browser = new Client\Browser($this->_request);

		/* result */

		$result = $browser->getOutput();

		/* compare */

		$this->assertEquals($expect['browser'], $result);
	}

	/**
	 * testDesktop
	 *
	 * @since 2.4.0
	 *
	 * @param string $userAgent
	 * @param array $expect
	 *
	 * @dataProvider providerClient
	 */

	public function testDesktop($userAgent = null, $expect = array())
	{
		/* setup */

		$this->_request->setServer('HTTP_USER_AGENT', $userAgent);
		$desktop = new Client\Desktop($this->_request);

		/* result */

		$result = $desktop->getOutput();

		/* compare */

		$this->assertEquals($expect['desktop'], $result);
	}

	/**
	 * testEngine
	 *
	 * @since 2.4.0
	 *
	 * @param string $userAgent
	 * @param array $expect
	 *
	 * @dataProvider providerClient
	 */

	public function testEngine($userAgent = null, $expect = array())
	{
		/* setup */

		$this->_request->setServer('HTTP_USER_AGENT', $userAgent);
		$engine = new Client\Engine($this->_request);

		/* result */

		$result = $engine->getOutput();

		/* compare */

		$this->assertEquals($expect['engine'], $result);
	}

	/**
	 * testMobile
	 *
	 * @since 2.4.0
	 *
	 * @param string $userAgent
	 * @param array $expect
	 *
	 * @dataProvider providerClient
	 */

	public function testMobile($userAgent = null, $expect = array())
	{
		/* setup */

		$this->_request->setServer('HTTP_USER_AGENT', $userAgent);
		$mobile = new Client\Mobile($this->_request);

		/* result */

		$result = $mobile->getOutput();

		/* compare */

		$this->assertEquals($expect['mobile'], $result);
	}

	/**
	 * testTablet
	 *
	 * @since 2.4.0
	 *
	 * @param string $userAgent
	 * @param array $expect
	 *
	 * @dataProvider providerClient
	 */

	public function testTablet($userAgent = null, $expect = array())
	{
		/* setup */

		$this->_request->setServer('HTTP_USER_AGENT', $userAgent);
		$tablet = new Client\Tablet($this->_request);

		/* result */

		$result = $tablet->getOutput();

		/* compare */

		$this->assertEquals($expect['tablet'], $result);
	}

	/**
	 * testVersion
	 *
	 * @since 2.4.0
	 *
	 * @param string $userAgent
	 * @param array $expect
	 *
	 * @dataProvider providerClient
	 */

	public function testVersion($userAgent = null, $expect = array())
	{
		/* setup */

		$this->_request->setServer('HTTP_USER_AGENT', $userAgent);
		$version = new Client\Version($this->_request);

		/* result */

		$result = $version->getOutput();

		/* compare */

		$this->assertEquals($expect['version'], $result);
	}
}
