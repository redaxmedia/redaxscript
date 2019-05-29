<?php
namespace Redaxscript\Tests;

use Facebook\WebDriver\WebDriverBy;

/**
 * ErrorTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ErrorTest extends TestCaseAbstract
{
	/**
	 * testError
	 *
	 * @since 4.0.0
	 *
	 * @param string $url
	 * @param string $expect
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testError(string $url = null, string $expect = null) : void
	{
		/* setup */

		$this->_driver->get($url);
		$titleElement = $this->_driver->findElement(WebDriverBy::cssSelector('h2.rs-title-content'));

		/* actual */

		$actual = $titleElement->getText();

		/* compare */

		$this->assertEquals($expect, $actual);
	}
}
