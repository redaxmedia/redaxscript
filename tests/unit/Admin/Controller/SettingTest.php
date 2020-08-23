<?php
namespace Redaxscript\Tests\Admin\Controller;

use Redaxscript\Admin;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * SettingTest
 *
 * @since 4.1.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Admin\Controller\Setting
 * @covers Redaxscript\Admin\Controller\ControllerAbstract
 */

class SettingTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.1.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$optionArray = $this->getOptionArray();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
		$installer->rawMigrate();
	}

	/**
	 * tearDown
	 *
	 * @since 4.1.0
	 */

	public function tearDown() : void
	{
		$this->dropDatabase();
	}

	/**
	 * testUpdate
	 *
	 * @since 4.1.0
	 *
	 * @param array $registryArray
	 * @param array $postArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testUpdate(array $registryArray = [], array $postArray = [], string $expect = null) : void
	{
		/* setup */

		$this->_registry->init($registryArray);
		$this->_request->set('post', $postArray);
		$settingController = new Admin\Controller\Setting($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $settingController->process('update');

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testInvalid
	 *
	 * @since 4.1.0
	 *
	 * @param array $registryArray
	 * @param array $postArray
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testInvalid(array $registryArray = [], array $postArray = [], string $expect = null) : void
	{
		/* setup */

		$this->_registry->init($registryArray);
		$this->_request->set('post', $postArray);
		$settingController = new Admin\Controller\Setting($this->_registry, $this->_request, $this->_language, $this->_config);

		/* actual */

		$actual = $settingController->process('invalid');

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
