<?php
namespace Redaxscript\Tests;

use Redaxscript\Hook;
use Redaxscript\Registry;

/**
 * HookTest
 *
 * @since 2.4.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class HookTest extends TestCase
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * setUp
	 *
	 * @since 2.4.0
	 */

	public function setUp()
	{
		$this->_registry = Registry::getInstance();
	}

	/**
	 * testGetModules
	 *
	 * @since 2.4.0
	 */

	public function testGetModules()
	{
		/* setup */

		Hook::init($this->_registry);

		/* actual */

		$actual = Hook::getModules();

		/* compare */

		$this->assertArrayHasKey('Test', $actual);
	}

	/**
	 * testGetEvents
	 *
	 * @since 2.4.0
	 */

	public function testGetEvents()
	{
		/* setup */

		Hook::init($this->_registry);
		Hook::trigger('render');

		/* actual */

		$actual = Hook::getEvents();

		/* compare */

		$this->assertArrayHasKey('render', $actual);
	}

	/**
	 * testTriggerMethod
	 *
	 * @since 2.4.0
	 */

	public function testTriggerMethod()
	{
		/* setup */

		Hook::init($this->_registry);

		/* actual */

		$actual = Hook::trigger('render');

		/* compare */

		$this->assertEquals(0, $actual);
	}

	/**
	 * testTriggerInvalid
	 *
	 * @since 2.4.0
	 */

	public function testTriggerInvalid()
	{
		/* setup */

		Hook::init($this->_registry);

		/* actual */

		$actual = Hook::trigger('invalid');

		/* compare */

		$this->assertEquals(false, $actual);
	}
}
