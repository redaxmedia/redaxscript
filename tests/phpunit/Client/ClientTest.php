<?php
namespace Redaxscript\Tests\Client;

use Redaxscript\Client;
use Redaxscript\Request;
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
	 * @param array $expectArray
	 *
	 * @dataProvider providerClient
	 */

	public function testBrowser($userAgent = null, $expectArray = array())
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

	public function testDesktop($userAgent = null, $expectArray = array())
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

	public function testEngine($userAgent = null, $expectArray = array())
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

	public function testMobile($userAgent = null, $expectArray = array())
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

	public function testTablet($userAgent = null, $expectArray = array())
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

	public function testVersion($userAgent = null, $expectArray = array())
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
