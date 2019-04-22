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
 *
 * @covers Redaxscript\Router\Resolver
 */

class ResolverTest extends TestCaseAbstract
{
	/**
	 * testGetLite
	 *
	 * @since 3.0.0
	 *
	 * @param string $route
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetLite(string $route = null, array $expectArray = []) : void
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
	 * @dataProvider providerAutoloader
	 */

	public function testGetFull(string $route = null, array $expectArray = []) : void
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