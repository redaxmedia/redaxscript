<?php
namespace Redaxscript\Tests;

use Redaxscript\Request;
use Redaxscript\Router;

/**
 * RouterTest
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class RouterTest extends TestCase
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
	 * providerRouter
	 *
	 * @since 2.4.0
	 *
	 * @return array
	 */

	public function providerRouter()
	{
		return $this->getProvider('tests/provider/router.json');
	}

	/**
	 * testGetLite
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expect
	 *
	 * @dataProvider providerRouter
	 */

	public function testGetLite($route = null, $expect = array())
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$router = new Router($this->_request);

		/* result */

		$result = $router->getLite();

		/* compare */

		$this->assertEquals($expect['lite'], $result);
	}

	/**
	 * testGetFull
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expect
	 *
	 * @dataProvider providerRouter
	 */

	public function testGetFull($route = null, $expect = array())
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$router = new Router($this->_request);

		/* result */

		$result = $router->getFull();

		/* compare */

		$this->assertEquals($expect['full'], $result);
	}
}