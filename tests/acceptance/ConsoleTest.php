<?php
namespace Redaxscript\Tests;

use Facebook\WebDriver\WebDriverBy;
use Redaxscript\Console\Command;

/**
 * ConsoleTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ConsoleTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.0.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$this->_driver->get('http://localhost:8000/console.php');
	}

	/**
	 * testTitle
	 *
	 * @since 4.0.0
	 */

	public function testTitle() : void
	{
		/* expect and actual */

		$expect = $this->_language->get('console');
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

		$helpCommand = new Command\Help($this->_registry, $this->_request, $this->_language, $this->_config);
		$formElement = $this->_driver->findElement(WebDriverBy::tagName('form'));
		$promptElement = $formElement->findElement(WebDriverBy::id('prompt'));
		$boxElement = $this->_driver->findElement(WebDriverBy::id('box'));

		/* compare */

		$this->assertNotTrue($boxElement->getText());

		/* interact and compare */

		$promptElement->sendKeys('help');
		$formElement->submit();
		$boxElement->isDisplayed();
		$this->assertStringContainsString($helpCommand->getHelp(), $boxElement->getAttribute('textContent'));
	}
}
