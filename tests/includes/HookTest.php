<?php
namespace Redaxscript\Tests;
use Redaxscript\Db;
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
			'alias' => 'call_home',
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
			'alias' => 'call_home',
		));
		$module->uninstall();
	}

	/**
	 * testGet
	 *
	 * @since 2.2.0
	 */

	public function testGet()
	{
		/* setup */

		Hook::init($this->_registry);

		/* result */

		$result = Hook::get();

		/* compare */

		$this->assertArrayHasKey('call_home', $result);
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

		$this->assertEquals(1, $result);
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

		$this->assertEquals(2, $result);
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
