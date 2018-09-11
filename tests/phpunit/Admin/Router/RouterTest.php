<?php
namespace Redaxscript\Tests\Admin\Router;

use Redaxscript\Admin;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * RouterTest
 *
 * @since 3.3.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Admin\Router\Router
 */

class RouterTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.3.0
	 */

	public function setUp()
	{
		parent::setUp();
		$optionArray =
		[
			'adminName' => 'Test',
			'adminUser' => 'test',
			'adminPassword' => 'test',
			'adminEmail' => 'test@test.com'
		];
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
		$installer->insertUsers($optionArray);
	}

	/**
	 * tearDown
	 *
	 * @since 3.3.0
	 */

	public function tearDown()
	{
		$this->dropDatabase();
	}

	/**
	 * testHeader
	 *
	 * @since 3.3.0
	 *
	 * @param array $registryArray
	 * @param array $postArray
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testHeader(array $registryArray = [], array $postArray = [], bool $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);
		$this->_request->set('post', $postArray);
		$this->_request->set('server',
		[
			'HTTP_HOST' => 'localhost',
			'HTTP_USER_AGENT' => 'redaxscript',
			'REMOTE_ADDR' => 'localhost'
		]);
		$router = new Admin\Router\Router($this->_registry, $this->_request, $this->_language, $this->_config);
		$router->init();

		/* actual */

		$actual = $router->routeHeader();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testContent
	 *
	 * @since 3.3.0
	 *
	 * @param array $registryArray
	 * @param array $queryArray
	 * @param array $postArray
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testContent(array $registryArray = [], array $queryArray = [], array $postArray = [], bool $expect = null)
	{
		/* setup */

		$this->_registry->init($registryArray);
		$this->_request->set('get', $queryArray);
		$this->_request->set('post', $postArray);
		$router = new Admin\Router\Router($this->_registry, $this->_request, $this->_language, $this->_config);
		$router->init();

		/* actual */

		$actual = $router->routeContent();

		/* compare */

		$expect ? $this->assertString($actual) : $this->assertNull($actual);
	}
}
