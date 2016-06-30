<?php
namespace Redaxscript\Tests;

use Redaxscript\Reader;

/**
 * ReaderTest
 *
 * @since 3.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ReaderTest extends TestCaseAbstract
{
	/**
	* testLoadAndGetJSON
	*
	* @since 3.0.0
	*/

	public function testLoadAndGetJSON()
	{
		/* setup */

		$reader = new Reader();
		$reader->loadJSON('https://validator.nu/?doc=http://redaxscript.com&out=json');

		/* actual */

		$actual = $reader->getArray();

		/* compare */

		$this->assertEquals('http://redaxscript.com', $actual['url']);
	}

	/**
	* testLoadAndGetXML
	*
	* @since 3.0.0
	*/

	public function testLoadAndGetXML()
	{
		/* setup */

		$reader = new Reader();
		$reader->loadXML('https://validator.nu/?doc=http://redaxscript.com&out=xml');

		/* actual */

		$actual = $reader->getArray();

		/* compare */

		$this->assertEquals('http://redaxscript.com', $actual['@attributes']['url']);
	}

	/**
	 * testConvertToArray
	 *
	 * @since 3.0.0
	 */

	public function testConvertToArray()
	{
		/* setup */

		$reader = new Reader();
		$reader->loadXML('https://validator.nu/?doc=http://redaxscript.com&out=xml');

		/* actual */

		$actual = $reader->getArray();

		/* compare */

		$this->assertEquals('http://redaxscript.com', $actual['@attributes']['url']);
	}

	/**
	 * testConvertToObject
	 *
	 * @since 3.0.0
	 */

	public function testConvertToObject()
	{
		/* setup */

		$reader = new Reader();
		$reader->loadJSON('https://validator.nu/?doc=http://redaxscript.com&out=json');

		/* actual */

		$actual = $reader->getObject();

		/* compare */

		$this->assertEquals('http://redaxscript.com', $actual->url);
	}
}
