<?php
namespace Redaxscript\Tests;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

/**
 * LoginTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class LoginTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.0.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$this->_driver->get('http://localhost:8000/?p=login');
	}

	/**
	 * testTitle
	 *
	 * @since 4.0.0
	 */

	public function testTitle() : void
	{
		/* expect and actual */

		$expect = $this->_language->get('login') . ' - ' . $this->_language->get('name', '_package');
		$actual = $this->_driver->getTitle();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testLogin
	 *
	 * @since 4.0.0
	 */

	public function testLogin() : void
	{
		/* setup */

		$formElement = $this->_driver->findElement(WebDriverBy::tagName('form'));
		$userElement = $formElement->findElement(WebDriverBy::id('user'));
		$passwordElement = $formElement->findElement(WebDriverBy::id('password'));
		$buttonElement = $formElement->findElement(WebDriverBy::tagName('button'));

		/* interact */

		$userElement->sendKeys('test');
		$passwordElement->sendKeys('test');
		$buttonElement->click();

		/* compare */

		$this->_driver->wait(5)->until(WebDriverExpectedCondition::urlIs('http://localhost:8000/?p=admin'));
		$this->expectNotToPerformAssertions();
	}
}
