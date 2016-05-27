<?php
namespace Redaxscript\Tests\Router;

use Redaxscript\Request;
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
 */

class ParameterTest extends TestCaseAbstract
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

	public function setUp()
	{
		$this->_request = Request::getInstance();
	}

	/**
	 * providerParameter
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerParameter()
	{
		return $this->getProvider('tests/provider/Router/parameter.json');
	}

	/**
	 * testGetFirst
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expectArray
	 *
	 * @dataProvider providerParameter
	 */

	public function testGetFirst($route = null, $expectArray = array())
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Router\Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actual = $parameter->getFirst();

		/* compare */

		$this->assertEquals($expectArray['first'], $actual);
	}

	/**
	 * testGetSecond
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expectArray
	 *
	 * @dataProvider providerParameter
	 */

	public function testGetSecond($route = null, $expectArray = array())
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Router\Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actual = $parameter->getSecond();

		/* compare */

		$this->assertEquals($expectArray['second'], $actual);
	}

	/**
	 * testGetThird
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expectArray
	 *
	 * @dataProvider providerParameter
	 */

	public function testGetThird($route = null, $expectArray = array())
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Router\Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actual = $parameter->getThird();

		/* compare */

		$this->assertEquals($expectArray['third'], $actual);
	}

	/**
	 * testGetFourth
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expectArray
	 *
	 * @dataProvider providerParameter
	 */

	public function testGetFourth($route = null, $expectArray = array())
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Router\Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actual = $parameter->getFourth();

		/* compare */

		$this->assertEquals($expectArray['fourth'], $actual);
	}

	/**
	 * testGetLast
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expectArray
	 *
	 * @dataProvider providerParameter
	 */

	public function testGetLast($route = null, $expectArray = array())
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Router\Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actual = $parameter->getLast();

		/* compare */

		$this->assertEquals($expectArray['last'], $actual);
	}

	/**
	 * testGetSub
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expectArray
	 *
	 * @dataProvider providerParameter
	 */

	public function testGetSub($route = null, $expectArray = array())
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Router\Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actual = $parameter->getSub();

		/* compare */

		$this->assertEquals($expectArray['sub'], $actual);
	}

	/**
	 * testGetAdmin
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expectArray
	 *
	 * @dataProvider providerParameter
	 */

	public function testGetAdmin($route = null, $expectArray = array())
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
	 * @dataProvider providerParameter
	 */

	public function testGetTable($route = null, $expectArray = array())
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
	 * @dataProvider providerParameter
	 */

	public function testGetAlias($route = null, $expectArray = array())
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
	 * @dataProvider providerParameter
	 */

	public function testGetId($route = null, $expectArray = array())
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
	 * @dataProvider providerParameter
	 */

	public function testGetToken($route = null, $expectArray = array())
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$this->_request->setServer('REMOTE_ADDR', 'test');
		$this->_request->setServer('HTTP_USER_AGENT', 'test');
		$this->_request->setServer('HTTP_HOST', 'test');
		$parameter = new Router\Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actual = $parameter->getToken();

		/* compare */

		$this->assertEquals($expectArray['token'], $actual);
	}
}