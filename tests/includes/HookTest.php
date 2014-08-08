<?php

/**
 * Redaxscript Hook Test
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Test
 * @author Henry Ruhs
 */

class Redaxscript_Hook_Test extends PHPUnit_Framework_TestCase
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
		$this->_registry = Redaxscript\Registry::getInstance();
	}

	/**
	 * setUpBeforeClass
	 *
	 * @since 2.2.0
	 */

	public static function setUpBeforeClass()
	{
		$module = new Redaxscript_Module(array(
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
		$module = new Redaxscript_Module(array(
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

		Redaxscript_Hook::init($this->_registry);

		/* result */

		$result = Redaxscript_Hook::get();

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

		Redaxscript_Hook::init($this->_registry);

		/* result */

		$result = Redaxscript_Hook::trigger('hook_method', array(
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

		Redaxscript_Hook::init($this->_registry);

		/* result */

		$result = Redaxscript_Hook::trigger('hook_function', array(
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

		Redaxscript_Hook::init($this->_registry);

		/* result */

		$result = Redaxscript_Hook::trigger('hook_invalid');

		/* compare */

		$this->assertEquals(false, $result);
	}
}

/**
 * testHookMethod
 *
 * @since 2.2.0
 */

class Redaxscript_Module_Call_Home
{
	public static function hookMethod($first = null, $second = null)
	{
		return $first;
	}
}

/**
 * testHookFunction
 *
 * @since 2.2.0
 */

function call_home_hook_function($first = null, $second = null)
{
	return $second;
}