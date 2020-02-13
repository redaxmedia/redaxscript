<?php
namespace Redaxscript\Tests;

use Facebook\WebDriver\WebDriverBy;

/**
 * CommentTest
 *
 * @since 4.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class CommentTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.2.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$this->_driver->get('http://localhost:8000/?p=home/welcome');
	}

	/**
	 * testComment
	 *
	 * @since 4.2.0
	 */

	public function testComment() : void
	{
		/* setup */

		$formElement = $this->_driver->findElement(WebDriverBy::tagName('form'));
		$authorElement = $formElement->findElement(WebDriverBy::id('author'));
		$emailElement = $formElement->findElement(WebDriverBy::id('email'));
		$urlElement = $formElement->findElement(WebDriverBy::id('url'));
		$textElement = $formElement->findElement(WebDriverBy::id('text'));
		$buttonElement = $formElement->findElement(WebDriverBy::tagName('button'));

		/* interact */

		$authorElement->sendKeys('test');
		$emailElement->sendKeys('test@test.com');
		$urlElement->sendKeys('https://test.com');
		$textElement->sendKeys('test');
		$buttonElement->click();

		/* compare */

		$commentOne = $this->_driver->findElement(WebDriverBy::id('comment-1'));
		$commentTwo = $this->_driver->findElement(WebDriverBy::id('comment-2'));
		$this->assertTrue($commentOne->isDisplayed());
		$this->assertTrue($commentTwo->isDisplayed());
	}
}
