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
	 * testGetFirst
	 *
	 * @since 2.4.0
	 */

	public function testGetFirst()
	{
		/* setup */

		$this->_request->setQuery('p', 'hello/world');
		$parameter = new Parameter($this->_request);

		/* result */

		$result = $parameter->getFirst();

		/* compare */

		$this->assertEquals('hello', $result);
	}

	/**
	 * testGetSecond
	 *
	 * @since 2.4.0
	 */

	public function testGetSecond()
	{
		/* setup */

		$this->_request->setQuery('p', 'hello/world');
		$parameter = new Parameter($this->_request);

		/* result */

		$result = $parameter->getSecond();

		/* compare */

		$this->assertEquals('world', $result);
	}
}