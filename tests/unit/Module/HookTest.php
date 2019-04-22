<?php
namespace Redaxscript\Tests\Module;

use Redaxscript\Module;
use Redaxscript\Tests\TestCaseAbstract;

/**
 * HookTest
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Module\Hook
 */

class HookTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 3.1.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$this->createDatabase();
		$this->installTestDummy();
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown() : void
	{
		$this->uninstallTestDummy();
		$this->dropDatabase();
	}

	/**
	 * testGetModuleArray
	 *
	 * @since 2.4.0
	 */

	public function testGetModuleArray() : void
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

	public function testGetEventArray() : void
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
	 *
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testCollect(array $expectArray = []) : void
	{
		/* setup */

		Module\Hook::construct($this->_registry, $this->_request, $this->_language, $this->_config);
		Module\Hook::init();

		/* actual */

		$actualArray = Module\Hook::collect('adminNotification');

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}

	/**
	 * testCollectInvalid
	 *
	 * @since 3.0.0
	 */

	public function testCollectInvalid() : void
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

	public function testTrigger() : void
	{
		/* setup */

		Module\Hook::construct($this->_registry, $this->_request, $this->_language, $this->_config);
		Module\Hook::init();

		/* actual */

		$actual = Module\Hook::trigger('render');

		/* compare */

		$this->assertEquals('Two', $actual);
	}

	/**
	 * testTriggerInvalid
	 *
	 * @since 2.4.0
	 */

	public function testTriggerInvalid() : void
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
