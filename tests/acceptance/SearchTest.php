<?php
namespace Redaxscript\Tests;

use Facebook\WebDriver\WebDriverBy;

/**
 * SearchTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class SearchTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.0.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$this->_driver->get('http://localhost:8000/?p=search/welcome');
	}

	/**
	 * testTitle
	 *
	 * @since 4.0.0
	 */

	public function testTitle() : void
	{
		/* expect and actual */

		$expect = $this->_language->get('search') . ' - ' . $this->_language->get('name', '_package');
		$actual = $this->_driver->getTitle();

		/* compare */

		$this->assertEquals($expect, $actual);
	}

	/**
	 * testSearch
	 *
	 * @since 4.0.0
	 *
	 * @param string $url
	 * @param array $expectArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testSearch(string $url = null, array $expectArray = []) : void
	{
		/* setup */

		$this->_driver->get($url);
		$titleElement = $this->_driver->findElement(WebDriverBy::cssSelector('h2.rs-title-result'));
		$linkElement = $this->_driver->findElement(WebDriverBy::cssSelector('a.rs-link-result'));

		/* actual */

		$actualArray =
		[
			'title' => $titleElement->getText(),
			'link' => $linkElement->getText()
		];

		/* compare */

		$this->assertEquals($expectArray, $actualArray);
	}
}
