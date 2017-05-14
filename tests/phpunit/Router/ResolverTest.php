<?php
namespace Redaxscript\Tests\Router;

use Redaxscript\Router;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ResolverTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ResolverTest extends TestCaseAbstract
{
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
	 * @param array $expectArray
	 *
	 * @dataProvider providerResolver
	 */

	public function testGetLite($route = null, $expectArray = [])
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$resolver = new Router\Resolver($this->_request);
		$resolver->init();

		/* actual */

		$actual = $resolver->getLite();

		/* compare */

		$this->assertEquals($expectArray['lite'], $actual);
	}

	/**
	 * testGetFull
	 *
	 * @since 3.0.0
	 *
	 * @param string $route
	 * @param array $expectArray
	 *
	 * @dataProvider providerResolver
	 */

	public function testGetFull($route = null, $expectArray = [])
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$resolver = new Router\Resolver($this->_request);
		$resolver->init();

		/* actual */

		$actual = $resolver->getFull();

		/* compare */

		$this->assertEquals($expectArray['full'], $actual);
	}
}