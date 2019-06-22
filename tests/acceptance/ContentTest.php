<?php
namespace Redaxscript\Tests;

use Facebook\WebDriver\WebDriverBy;

/**
 * ContentTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ContentTest extends TestCaseAbstract
{
	/**
	 * testTitle
	 *
	 * @since 4.0.0
	 */

	public function testTitle() : void
	{
		/* expect and actual */

		$expect = $this->_language->get('home') . ' - ' . $this->_language->get('_package')['name'];
		$actual = $this->_driver->getTitle();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testLayout
	 *
	 * @since 4.0.0
	 */

	public function testLayout() : void
	{
		/* setup */

		$headerElement = $this->_driver->findElement(WebDriverBy::id('header'));
		$mainElement = $this->_driver->findElement(WebDriverBy::id('main'));
		$contentElement = $this->_driver->findElement(WebDriverBy::id('content'));
		$sidebarElement = $this->_driver->findElement(WebDriverBy::id('sidebar'));
		$footerElement = $this->_driver->findElement(WebDriverBy::id('footer'));

		/* compare */

		$this->assertTrue($headerElement->isDisplayed());
		$this->assertTrue($mainElement->isDisplayed());
		$this->assertTrue($contentElement->isDisplayed());
		$this->assertTrue($sidebarElement->isDisplayed());
		$this->assertTrue($footerElement->isDisplayed());
	}
}
