<?php
namespace Redaxscript\Tests\Router;

use Redaxscript\Router;
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
 * @covers Redaxscript\Router\Router
 * @covers Redaxscript\Router\RouterAbstract
 */

class RouterTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.3.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$optionArray = $this->getOptionArray();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
		$installer->insertUsers($optionArray);
		$installer->rawMigrate();
	}

	/**
	 * tearDown
	 *
	 * @since 3.3.0
	 */

	public function tearDown() : void
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

	public function testHeader(array $registryArray = [], array $postArray = [], bool $expect = null) : void
	{
		/* setup */

		$this->_registry->init($registryArray);
		$this->_request->set('post', $postArray);
		$router = new Router\Router($this->_registry, $this->_request, $this->_language, $this->_config);
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
	 * @param array $settingArray
	 * @param bool $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testContent(array $registryArray = [], array $queryArray = [], array $postArray = [], array $settingArray = [], bool $expect = null) : void
	{
		/* setup */

		$this->_registry->init($registryArray);
		$this->_request->set('get', $queryArray);
		$this->_request->set('post', $postArray);
		$setting = $this->settingFactory();
		$setting->set('recovery', $settingArray['recovery']);
		$setting->set('registration', $settingArray['registration']);
		$router = new Router\Router($this->_registry, $this->_request, $this->_language, $this->_config);
		$router->init();

		/* actual */

		$actual = $router->routeContent();

		/* compare */

		$expect ? $this->assertIsString($actual) : $this->assertNull($actual);
	}
}
