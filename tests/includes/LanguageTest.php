<?php

/**
 * Redaxscript Language Test
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class Redaxscript_Language_Test extends PHPUnit_Framework_TestCase
{
	/**
	 * language
	 *
	 * instance of the language class
	 *
	 * @var object
	 */

	protected $_language;

	/**
	 * setUp
	 *
	 * @since 2.2.0
	 */

	protected function setUp()
	{
		$this->_language = Redaxscript_Language::instance();
		$this->_language->init();
	}

	/**
	 * testGet
	 *
	 * @since 2.2.0
	 */

	public function testGet()
	{
		/* result */

		$result = $this->_language->get('home');

		/* compare */

		$this->assertEquals('Home', $result);
	}

	/**
	 * testGetInvalidKey
	 *
	 * @since 2.2.0
	 */

	public function testGetInvalidKey()
	{
		/* result */

		$result = $this->_language->get('invalidKey');

		/* compare */

		$this->assertEquals(null, $result);
	}

	/**
	 * testGetNull
	 *
	 * @since 2.2.0
	 */

	public function testGetNull()
	{
		/* result */

		$result = $this->_language->get();

		/* compare */

		$this->assertEquals(null, $result);
	}

	/**
	 * testReset
	 *
	 * @since 2.2.0
	 */

	public function testReset()
	{
		/* setup */

		$this->_language->reset();

		/* result */

		$result = $this->_language->instance();

		/* compare */

		$this->assertInstanceOf('Redaxscript_Language', $result);
	}
}