<?php
namespace Redaxscript\Tests;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverSelect;

/**
 * InstallationTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class InstallationTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.0.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$this->_driver->get('http://localhost:8000/install.php');
	}

	/**
	 * testTitle
	 *
	 * @since 4.0.0
	 */

	public function testTitle() : void
	{
		/* expect and actual */

		$expect = $this->_language->get('installation');
		$actual = $this->_driver->getTitle();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testBehaviour
	 *
	 * @since 4.0.0
	 */

	public function testBehaviour() : void
	{
		/* setup */

		$formElement = $this->_driver->findElement(WebDriverBy::tagName('form'));
		$dbHostElement = $formElement->findElement(WebDriverBy::id('db-host'));
		$dbTypeSelect = new WebDriverSelect($formElement->findElement(WebDriverBy::id('db-type')));
		$dbNameElement = $formElement->findElement(WebDriverBy::id('db-name'));
		$dbUserElement = $formElement->findElement(WebDriverBy::id('db-user'));
		$dbPasswordElement = $formElement->findElement(WebDriverBy::id('db-password'));
		$dbPrefixElement = $formElement->findElement(WebDriverBy::id('db-prefix'));
		$adminNameElement = $formElement->findElement(WebDriverBy::id('admin-name'));
		$adminUserElement = $formElement->findElement(WebDriverBy::id('admin-user'));
		$adminPasswordElement = $formElement->findElement(WebDriverBy::id('admin-password'));
		$adminEmailElement = $formElement->findElement(WebDriverBy::id('admin-email'));
		$labelAccountElement =  $formElement->findElement(WebDriverBy::cssSelector('[for*="Account"]'));

		/* compare */

		$this->assertFalse($adminNameElement->isDisplayed());
		$this->assertFalse($adminUserElement->isDisplayed());
		$this->assertFalse($adminPasswordElement->isDisplayed());
		$this->assertFalse($adminEmailElement->isDisplayed());

		/* interact and compare */

		$dbTypeSelect->selectByValue('sqlite');
		$this->assertTrue($dbHostElement->isDisplayed());
		$this->assertFalse($dbNameElement->isDisplayed());
		$this->assertFalse($dbUserElement->isDisplayed());
		$this->assertFalse($dbPasswordElement->isDisplayed());
		$this->assertTrue($dbPrefixElement->isDisplayed());

		/* interact and compare */

		$dbTypeSelect->selectByValue('mysql');
		$this->assertTrue($dbHostElement->isDisplayed());
		$this->assertTrue($dbNameElement->isDisplayed());
		$this->assertTrue($dbUserElement->isDisplayed());
		$this->assertTrue($dbPasswordElement->isDisplayed());
		$this->assertTrue($dbPrefixElement->isDisplayed());

		/* interact and compare */

		$labelAccountElement->click();
		$this->assertTrue($adminNameElement->isDisplayed());
		$this->assertTrue($adminUserElement->isDisplayed());
		$this->assertTrue($adminPasswordElement->isDisplayed());
		$this->assertTrue($adminEmailElement->isDisplayed());
	}

	/**
	 * testInstall
	 *
	 * @since 4.0.0
	 */

	public function testInstall() : void
	{
		/* setup */

		$formElement = $this->_driver->findElement(WebDriverBy::tagName('form'));
		$dbHostElement = $formElement->findElement(WebDriverBy::id('db-host'));
		$dbTypeSelect = new WebDriverSelect($formElement->findElement(WebDriverBy::id('db-type')));
		$dbPrefixElement = $formElement->findElement(WebDriverBy::id('db-prefix'));
		$adminNameElement = $formElement->findElement(WebDriverBy::id('admin-name'));
		$adminUserElement = $formElement->findElement(WebDriverBy::id('admin-user'));
		$adminPasswordElement = $formElement->findElement(WebDriverBy::id('admin-password'));
		$adminEmailElement = $formElement->findElement(WebDriverBy::id('admin-email'));
		$labelAccountElement =  $formElement->findElement(WebDriverBy::cssSelector('[for*="Account"]'));
		$buttonElement = $formElement->findElement(WebDriverBy::tagName('button'));

		/* interact */

		$dbTypeSelect->selectByValue('sqlite');
		$dbHostElement->sendKeys('build/test.sqlite');
		$dbPrefixElement->sendKeys('test_');
		$labelAccountElement->click();
		$adminNameElement->sendKeys('Test');
		$adminUserElement->sendKeys('test');
		$adminPasswordElement->sendKeys('test');
		$adminEmailElement->sendKeys('test@test.de');
		$buttonElement->click();

		/* compare */

		$this->_driver->wait(5)->until(WebDriverExpectedCondition::urlIs('http://localhost:8000/index.php'));
		$this->expectNotToPerformAssertions();
	}
}
