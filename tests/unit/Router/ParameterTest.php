<?php
namespace Redaxscript\Tests\Router;

use Redaxscript\Router;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * ParameterTest
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Router\Parameter
 */

class ParameterTest extends TestCaseAbstract
{
	/**
	 * testGetFirst
	 *
	 * @since 3.1.0
	 *
	 * @param string $route
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetFirst(string $route = null, array $expectArray = []) : void
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Router\Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actualArray =
		[
			'first' => $parameter->getFirst(),
			'firstSub' => $parameter->getFirstSub()
		];

		/* compare */

		$this->assertEquals($expectArray['first'], $actualArray['first']);
		$this->assertEquals($expectArray['firstSub'], $actualArray['firstSub']);
	}

	/**
	 * testGetSecond
	 *
	 * @since 3.1.0
	 *
	 * @param string $route
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetSecond(string $route = null, array $expectArray = []) : void
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Router\Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actualArray =
		[
			'second' => $parameter->getSecond(),
			'secondSub' => $parameter->getSecondSub()
		];

		/* compare */

		$this->assertEquals($expectArray['second'], $actualArray['second']);
		$this->assertEquals($expectArray['secondSub'], $actualArray['secondSub']);
	}

	/**
	 * testGetThird
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetThird(string $route = null, array $expectArray = []) : void
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Router\Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actualArray =
		[
			'third' => $parameter->getThird(),
			'thirdSub' => $parameter->getThirdSub(),
		];

		/* compare */

		$this->assertEquals($expectArray['third'], $actualArray['third']);
		$this->assertEquals($expectArray['thirdSub'], $actualArray['thirdSub']);
	}

	/**
	 * testGetFourth
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetFourth(string $route = null, array $expectArray = []) : void
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Router\Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actualArray =
		[
			'fourth' => $parameter->getFourth(),
			'fourthSub' => $parameter->getFourthSub(),
		];

		/* compare */

		$this->assertEquals($expectArray['fourth'], $actualArray['fourth']);
		$this->assertEquals($expectArray['fourthSub'], $actualArray['fourthSub']);
	}

	/**
	 * testGetLast
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetLast(string $route = null, array $expectArray = []) : void
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Router\Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actualArray =
		[
			'last' => $parameter->getLast(),
			'lastSub' => $parameter->getLastSub()
		];

		/* compare */

		$this->assertEquals($expectArray['last'], $actualArray['last']);
		$this->assertEquals($expectArray['lastSub'], $actualArray['lastSub']);
	}

	/**
	 * testGetAdmin
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetAdmin(string $route = null, array $expectArray = []) : void
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Router\Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actual = $parameter->getAdmin();

		/* compare */

		$this->assertEquals($expectArray['admin'], $actual);
	}

	/**
	 * testGetTable
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetTable(string $route = null, array $expectArray = []) : void
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Router\Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actual = $parameter->getTable();

		/* compare */

		$this->assertEquals($expectArray['table'], $actual);
	}

	/**
	 * testGetAlias
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetAlias(string $route = null, array $expectArray = []) : void
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Router\Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actual = $parameter->getAlias();

		/* compare */

		$this->assertEquals($expectArray['alias'], $actual);
	}

	/**
	 * testGetId
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetId(string $route = null, array $expectArray = []) : void
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Router\Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actual = $parameter->getId();

		/* compare */

		$this->assertEquals($expectArray['id'], $actual);
	}

	/**
	 * testGetToken
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testGetToken(string $route = null, array $expectArray = []) : void
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$this->_request->set('server',
		[
			'HTTP_HOST' => 'localhost',
			'HTTP_USER_AGENT' => 'redaxscript',
			'REMOTE_ADDR' => 'localhost'
		]);
		$parameter = new Router\Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actual = $parameter->getToken();

		/* compare */

		$this->assertEquals($expectArray['token'], $actual);
	}
}
