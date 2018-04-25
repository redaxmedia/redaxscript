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
	 * providerHeader
	 *
	 * @since 3.3.0
	 *
	 * @return array
	 */

	public function providerHeader() : array
	{
		return $this->getProvider('tests/provider/Admin/Router/router_header.json');
	}

	/**
	 * providerContent
	 *
	 * @since 3.3.0
	 *
	 * @return array
	 */

	public function providerContent() : array
	{
		return $this->getProvider('tests/provider/Admin/Router/router_content.json');
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
	 * @dataProvider providerHeader
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
	 * @dataProvider providerContent
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

		$expect ? $this->assertString($actual) : $this->assertFalse($actual);
	}
}