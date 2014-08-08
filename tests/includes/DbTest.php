<?php

/**
 * Redaxscript Db Test
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Test
 * @author Henry Ruhs
 */

class Redaxscript\Db_Test extends PHPUnit_Framework_TestCase
{
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
		$this->_config = Redaxscript_Config::getInstance();
	}

	/**
	 * testInit
	 *
	 * @since 2.2.0
	 */

	public function testInit()
	{
		/* setup */

		Redaxscript\Db::init($this->_config);

		/* result */

		$result = Redaxscript\Db::getDb();

		/* compare */

		$this->assertInstanceOf('PDO', $result);
	}
}