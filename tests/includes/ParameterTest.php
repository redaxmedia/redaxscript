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

		/* result */

		$result = $parameter->getFirst();

		/* compare */

		$this->assertEquals($expect['first'], $result);
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

		/* result */

		$result = $parameter->getSecond();

		/* compare */

		$this->assertEquals($expect['second'], $result);
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

		/* result */

		$result = $parameter->getThird();

		/* compare */

		$this->assertEquals($expect['third'], $result);
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

		/* result */

		$result = $parameter->getFourth();

		/* compare */

		$this->assertEquals($expect['fourth'], $result);
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

		/* result */

		$result = $parameter->getLast();

		/* compare */

		$this->assertEquals($expect['last'], $result);
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

		/* result */

		$result = $parameter->getSub();

		/* compare */

		$this->assertEquals($expect['sub'], $result);
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

		/* result */

		$result = $parameter->getAdmin();

		/* compare */

		$this->assertEquals($expect['admin'], $result);
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

		/* result */

		$result = $parameter->getTable();

		/* compare */

		$this->assertEquals($expect['table'], $result);
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

		/* result */

		$result = $parameter->getAlias();

		/* compare */

		$this->assertEquals($expect['alias'], $result);
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

		/* result */

		$result = $parameter->getId();

		/* compare */

		$this->assertEquals($expect['id'], $result);
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

		/* result */

		$result = $parameter->getToken();

		/* compare */

		$this->assertEquals($expect['token'], $result);
	}
}