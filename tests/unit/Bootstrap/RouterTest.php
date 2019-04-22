<?php
namespace Redaxscript\Tests\Bootstrap;

use Redaxscript\Bootstrap;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * RouterTest
 *
 * @since 3.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Bootstrap\BootstrapAbstract
 * @covers Redaxscript\Bootstrap\Router
 *
 * @runTestsInSeparateProcesses
 */

class RouterTest extends TestCaseAbstract
{
	/**
	 * testRouter
	 *
	 * @since 3.1.0
	 *
	 * @param string $route
	 * @param array $registryArray
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testRouter(string $route = null, array $registryArray = [], array $expectArray = []) : void
	{
		/* setup */

		$this->_request->setQuery('p', $route);
		$this->_registry->init($registryArray);
		new Bootstrap\Router($this->_registry, $this->_request);

		/* actual */

		$actualArray =
		[
			'firstParameter' => $this->_registry->get('firstParameter'),
			'firstSubParameter' => $this->_registry->get('firstSubParameter'),
			'secondParameter' => $this->_registry->get('secondParameter'),
			'secondSubParameter' => $this->_registry->get('secondSubParameter'),
			'thirdParameter' => $this->_registry->get('thirdParameter'),
			'thirdSubParameter' => $this->_registry->get('thirdSubParameter'),
			'adminParameter' => $this->_registry->get('adminParameter'),
			'tableParameter' => $this->_registry->get('tableParameter'),
			'idParameter' => $this->_registry->get('idParameter'),
			'aliasParameter' => $this->_registry->get('aliasParameter'),
			'lastParameter' => $this->_registry->get('lastParameter'),
			'lastSubParameter' => $this->_registry->get('lastSubParameter'),
			'tokenParameter' => $this->_registry->get('tokenParameter'),
			'liteRoute' => $this->_registry->get('liteRoute'),
			'fullRoute' => $this->_registry->get('fullRoute'),
			'parameterRoute' => $this->_registry->get('parameterRoute'),
			'languageRoute' => $this->_registry->get('languageRoute'),
			'templateRoute' => $this->_registry->get('templateRoute'),
		];

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}
}
