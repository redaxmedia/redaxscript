<?php
namespace Redaxscript\Tests;

use Redaxscript\Hook;
use Redaxscript\Module;
use Redaxscript\Registry;

/**
 * HookTest
 *
 * @since 2.2.0
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
	 * @since 2.2.0
	 */

	public function setUp()
	{
		$this->_registry = Registry::getInstance();
	}

	/**
	 * setUpBeforeClass
	 *
	 * @since 2.2.0
	 */

	public static function setUpBeforeClass()
	{
		$module = new Module(array(
			'name' => 'Call home',
			'alias' => 'CallHome',
		));
		$module->install();
	}

	/**
	 * tearDownAfterClass
	 *
	 * @since 2.2.0
	 */

	public static function tearDownAfterClass()
	{
		$module = new Module(array(
			'alias' => 'CallHome',
		));
		$module->uninstall();
	}

	/**
	 * testGetModules
	 *
	 * @since 2.2.0
	 */

	public function testGetModules()
	{
		/* setup */

		Hook::init($this->_registry);

		/* result */

		$result = Hook::getModules();

		/* compare */

		$this->assertArrayHasKey('CallHome', $result);
	}

	/**
	 * testGetHooks
	 *
	 * @since 2.2.0
	 */

	public function testGetHooks()
	{
		/* setup */

		Hook::init($this->_registry);
		Hook::trigger('hook_method');

		/* result */

		$result = Hook::getHooks();

		/* compare */

		$this->assertArrayHasKey('hook_method', $result);
	}

	/**
	 * testTriggerMethod
	 *
	 * @since 2.2.0
	 */

	public function testTriggerMethod()
	{
		/* setup */

		Hook::init($this->_registry);

		/* result */

		$result = Hook::trigger('hook_method', array(
			1,
			2
		));

		/* compare */

		$this->assertEquals(-1, $result);
	}

	/**
	 * testTriggerFunction
	 *
	 * @since 2.2.0
	 */

	public function testTriggerFunction()
	{
		/* setup */

		Hook::init($this->_registry);

		/* result */

		$result = Hook::trigger('hook_function', array(
			1,
			2
		));

		/* compare */

		$this->assertEquals(3, $result);
	}

	/**
	 * testTriggerInvalid
	 *
	 * @since 2.2.0
	 */

	public function testTriggerInvalid()
	{
		/* setup */

		Hook::init($this->_registry);

		/* result */

		$result = Hook::trigger('hook_invalid');

		/* compare */

		$this->assertEquals(false, $result);
	}
}
