<?php
namespace Redaxscript\Tests;

use Redaxscript\Parameter;
use Redaxscript\Request;

/**
 * ParameterTest
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ParameterTest extends TestCase
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
	 * providerParameter
	 *
	 * @since 2.4.0
	 *
	 * @return array
	 */

	public function providerParameter()
	{
		return $this->getProvider('tests/provider/parameter.json');
	}

	/**
	 * testGetFirst
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expect
	 *
	 * @dataProvider providerParameter
	 */

	public function testGetFirst($route = null, $expect = array())
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actual = $parameter->getFirst();

		/* compare */

		$this->assertEquals($expect['first'], $actual);
	}

	/**
	 * testGetSecond
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expect
	 *
	 * @dataProvider providerParameter
	 */

	public function testGetSecond($route = null, $expect = array())
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actual = $parameter->getSecond();

		/* compare */

		$this->assertEquals($expect['second'], $actual);
	}

	/**
	 * testGetThird
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expect
	 *
	 * @dataProvider providerParameter
	 */

	public function testGetThird($route = null, $expect = array())
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actual = $parameter->getThird();

		/* compare */

		$this->assertEquals($expect['third'], $actual);
	}

	/**
	 * testGetFourth
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expect
	 *
	 * @dataProvider providerParameter
	 */

	public function testGetFourth($route = null, $expect = array())
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actual = $parameter->getFourth();

		/* compare */

		$this->assertEquals($expect['fourth'], $actual);
	}

	/**
	 * testGetLast
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expect
	 *
	 * @dataProvider providerParameter
	 */

	public function testGetLast($route = null, $expect = array())
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actual = $parameter->getLast();

		/* compare */

		$this->assertEquals($expect['last'], $actual);
	}

	/**
	 * testGetSub
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expect
	 *
	 * @dataProvider providerParameter
	 */

	public function testGetSub($route = null, $expect = array())
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actual = $parameter->getSub();

		/* compare */

		$this->assertEquals($expect['sub'], $actual);
	}

	/**
	 * testGetAdmin
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expect
	 *
	 * @dataProvider providerParameter
	 */

	public function testGetAdmin($route = null, $expect = array())
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actual = $parameter->getAdmin();

		/* compare */

		$this->assertEquals($expect['admin'], $actual);
	}

	/**
	 * testGetTable
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expect
	 *
	 * @dataProvider providerParameter
	 */

	public function testGetTable($route = null, $expect = array())
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actual = $parameter->getTable();

		/* compare */

		$this->assertEquals($expect['table'], $actual);
	}

	/**
	 * testGetAlias
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expect
	 *
	 * @dataProvider providerParameter
	 */

	public function testGetAlias($route = null, $expect = array())
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actual = $parameter->getAlias();

		/* compare */

		$this->assertEquals($expect['alias'], $actual);
	}

	/**
	 * testGetId
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expect
	 *
	 * @dataProvider providerParameter
	 */

	public function testGetId($route = null, $expect = array())
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$parameter = new Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actual = $parameter->getId();

		/* compare */

		$this->assertEquals($expect['id'], $actual);
	}

	/**
	 * testGetToken
	 *
	 * @since 2.4.0
	 *
	 * @param string $route
	 * @param array $expect
	 *
	 * @dataProvider providerParameter
	 */

	public function testGetToken($route = null, $expect = array())
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$this->_request->setServer('REMOTE_ADDR', 'test');
		$this->_request->setServer('HTTP_USER_AGENT', 'test');
		$this->_request->setServer('HTTP_HOST', 'test');
		$parameter = new Parameter($this->_request);
		$parameter->init();

		/* actual */

		$actual = $parameter->getToken();

		/* compare */

		$this->assertEquals($expect['token'], $actual);
	}
}