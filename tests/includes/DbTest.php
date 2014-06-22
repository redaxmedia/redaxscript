<?php

/**
 * Redaxscript Db Test
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class Redaxscript_Db_Test extends PHPUnit_Framework_TestCase
{
	/**
	 * instance of the registry class
	 *
	 * @var object
	 */

	protected $_registry;

	/**
	 * instance of the config class
	 *
	 * @var object
	 */

	protected $_config;

	/**
	 * setUp
	 *
	 * @since 2.2.0
	 */

	public function setUp()
	{
		$this->_registry = Redaxscript_Registry::getInstance();
		$this->_config = Redaxscript_Config::getInstance();
	}

	/**
	 * testConnect
	 *
	 * @since 2.2.0
	 */

	public function testConntect()
	{
		/* setup */

		Redaxscript_Db::connect($this->_registry, $this->_config);

		/* result */

		$result = $this->_registry->get('dbConnected');

		/* compare */

		$this->assertTrue($result);
	}
}