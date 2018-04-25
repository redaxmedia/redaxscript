<?php
namespace Redaxscript\Tests\Module;

use Redaxscript\Module;
use Redaxscript\Modules\TestDummy;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * HookTest
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class HookTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp()
	{
		parent::setUp();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$testDummy = new TestDummy\TestDummy($this->_registry, $this->_request, $this->_language, $this->_config);
		$testDummy->install();
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown()
	{
		$testDummy = new TestDummy\TestDummy($this->_registry, $this->_request, $this->_language, $this->_config);
		$testDummy->uninstall();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawDrop();
	}

	/**
	 * testGetModuleArray
	 *
	 * @since 2.4.0
	 */

	public function testGetModuleArray()
	{
		/* setup */

		Module\Hook::construct($this->_registry, $this->_request, $this->_language, $this->_config);
		Module\Hook::init();

		/* actual */

		$actualArray = Module\Hook::getModuleArray();

		/* compare */

		$this->assertArrayHasKey('TestDummy', $actualArray);
	}

	/**
	 * testGetEventArray
	 *
	 * @since 2.4.0
	 */

	public function testGetEventArray()
	{
		/* setup */

		Module\Hook::construct($this->_registry, $this->_request, $this->_language, $this->_config);
		Module\Hook::init();
		Module\Hook::trigger('render');

		/* actual */

		$actualArray = Module\Hook::getEventArray();

		/* compare */

		$this->assertArrayHasKey('render', $actualArray);
	}

	/**
	 * testCollect
	 *
	 * @since 2.4.0
	 */

	public function testCollect()
	{
		/* setup */

		Module\Hook::construct($this->_registry, $this->_request, $this->_language, $this->_config);
		Module\Hook::init();

		/* actual */

		$actualArray = Module\Hook::collect('adminPanelNotification');

		/* compare */

		$this->assertEquals('Test Dummy', $actualArray['info']['Test Dummy'][0]);
	}

	/**
	 * testCollectInvalid
	 *
	 * @since 3.0.0
	 */

	public function testCollectInvalid()
	{
		/* setup */

		Module\Hook::construct($this->_registry, $this->_request, $this->_language, $this->_config);
		Module\Hook::init();

		/* actual */

		$actual = Module\Hook::collect('invalidMethod');

		/* compare */

		$this->assertEmpty($actual);
	}

	/**
	 * testTrigger
	 *
	 * @since 2.4.0
	 */

	public function testTrigger()
	{
		/* setup */

		Module\Hook::construct($this->_registry, $this->_request, $this->_language, $this->_config);
		Module\Hook::init();

		/* actual */

		$actual = Module\Hook::trigger('render');

		/* compare */

		$this->assertEquals(2, $actual);
	}

	/**
	 * testTriggerInvalid
	 *
	 * @since 2.4.0
	 */

	public function testTriggerInvalid()
	{
		/* setup */

		Module\Hook::construct($this->_registry, $this->_request, $this->_language, $this->_config);
		Module\Hook::init();

		/* actual */

		$actual = Module\Hook::trigger('invalidMethod');

		/* compare */

		$this->assertNull($actual);
	}
}
