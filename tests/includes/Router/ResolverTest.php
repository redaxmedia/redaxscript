<?php
namespace Redaxscript\Tests;

use Redaxscript\Request;
use Redaxscript\Router;

/**
 * ResolverTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ResolverTest extends TestCase
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
	 * providerResolver
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerResolver()
	{
		return $this->getProvider('tests/provider/Router/resolver.json');
	}

	/**
	 * testGetLite
	 *
	 * @since 3.0.0
	 *
	 * @param string $route
	 * @param array $expect
	 *
	 * @dataProvider providerResolver
	 */

	public function testGetLite($route = null, $expect = array())
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$resolver = new Router\Resolver($this->_request);
		$resolver->init();

		/* actual */

		$actual = $resolver->getLite();

		/* compare */

		$this->assertEquals($expect['lite'], $actual);
	}

	/**
	 * testGetFull
	 *
	 * @since 3.0.0
	 *
	 * @param string $route
	 * @param array $expect
	 *
	 * @dataProvider providerResolver
	 */

	public function testGetFull($route = null, $expect = array())
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$resolver = new Router\Resolver($this->_request);
		$resolver->init();

		/* actual */

		$actual = $resolver->getFull();

		/* compare */

		$this->assertEquals($expect['full'], $actual);
	}
}