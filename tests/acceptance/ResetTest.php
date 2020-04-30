<?php
namespace Redaxscript\Tests;

use Facebook\WebDriver\WebDriverBy;

/**
 * ResetTest
 *
 * @since 4.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ResetTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.2.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$this->_driver->get('http://localhost:8000/?p=login/reset/abc/1');
	}

	/**
	 * testTitle
	 *
	 * @since 4.2.0
	 */

	public function testTitle() : void
	{
		/* expect and actual */

		$expect = $this->_language->get('reset') . ' - ' . $this->_language->get('_package')['name'];
		$actual = $this->_driver->getTitle();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testReset
	 *
	 * @since 4.2.0
	 */

	public function testRecover() : void
	{
		/* setup */

		$formElement = $this->_driver->findElement(WebDriverBy::tagName('form'));
		$passwordElement = $formElement->findElement(WebDriverBy::id('password'));
		$taskElement = $formElement->findElement(WebDriverBy::id('task'));
		$buttonElement = $formElement->findElement(WebDriverBy::tagName('button'));

		/* compare */

		$this->assertTrue($passwordElement->isDisplayed());
		$this->assertTrue($taskElement->isDisplayed());
		$this->assertTrue($buttonElement->isDisplayed());
	}
}
