<?php

/**
 * Redaxscript Hook Test
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
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
		$this->_registry = Redaxscript_Registry::getInstance();
	}

	/**
	 * setUpBeforeClass
	 *
	 * @since 2.2.0
	 */

	public static function setUpBeforeClass()
	{
		$callHome = Redaxscript_Db::forPrefixTable('modules')->create();
		$callHome->set(array(
			'name' => 'Call home',
			'alias' => 'call_home',
			'status' => 1,
			'access' => 0
		));
		$callHome->save();
	}

	/**
	 * tearDownAfterClass
	 *
	 * @since 2.2.0
	 */

	public static function tearDownAfterClass()
	{
		Redaxscript_Db::forPrefixTable('modules')->where('alias', 'call_home')->deleteMany();
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

class Redaxscript_Modules_Call_Home
{
	public function hookMethod($first = null, $second = null)
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