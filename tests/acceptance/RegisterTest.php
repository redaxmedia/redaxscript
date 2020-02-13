<?php
namespace Redaxscript\Tests;

/**
 * RegisterTest
 *
 * @since 4.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class RegisterTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.2.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$this->_driver->get('http://localhost:8000/?p=register');
	}

	/**
	 * testTitle
	 *
	 * @since 4.2.0
	 */

	public function testTitle() : void
	{
		/* expect and actual */

		$expect = $this->_language->get('registration') . ' - ' . $this->_language->get('_package')['name'];
		$actual = $this->_driver->getTitle();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
