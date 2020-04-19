<?php
namespace Redaxscript\Tests;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

/**
 * RecoverTest
 *
 * @since 4.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class RecoverTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.2.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$this->_driver->get('http://localhost:8000/?p=login/recover');
	}

	/**
	 * testTitle
	 *
	 * @since 4.2.0
	 */

	public function testTitle() : void
	{
		/* expect and actual */

		$expect = $this->_language->get('recovery') . ' - ' . $this->_language->get('_package')['name'];
		$actual = $this->_driver->getTitle();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testRecover
	 *
	 * @since 4.2.0
	 */

	public function testRecover() : void
	{
		/* setup */

		$formElement = $this->_driver->findElement(WebDriverBy::tagName('form'));
		$emailElement = $formElement->findElement(WebDriverBy::id('email'));
		$buttonElement = $formElement->findElement(WebDriverBy::tagName('button'));

		/* interact */

		$emailElement->sendKeys('test@redaxscript.com');
		$buttonElement->click();

		/* compare */

		$this->_driver->wait(5)->until(WebDriverExpectedCondition::urlIs('http://localhost:8000/?p=login'));
		$this->expectNotToPerformAssertions();
	}
}
