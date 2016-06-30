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
		$reader->loadJSON('tests/provider/reader.json');

		/* actual */

		$actual = $reader->getArray();

		/* compare */

		$this->assertEquals('4', $actual['two']['@attributes']['id']);
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
		$reader->loadXML('tests/provider/reader.xml');

		/* actual */

		$actual = $reader->getObject();

		/* compare */

		$this->assertEquals('4', $actual->two->attributes()->id);
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
		$reader->loadXML('tests/provider/reader.xml');

		/* actual */

		$actual = $reader->getArray();

		/* compare */

		$this->assertEquals('1', $actual['@attributes']['id']);
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
		$reader->loadJSON('tests/provider/reader.json');

		/* actual */

		$actual = $reader->getObject();

		/* compare */

		$this->assertEquals('1', $actual->id);
	}

	/**
	 * testConvertToJSON
	 *
	 * @since 3.0.0
	 */

	public function testConvertToJSON()
	{
		/* setup */

		$reader = new Reader();
		$reader->loadXML('tests/provider/reader.xml');

		/* actual */

		$actual = $reader->getJSON();

		/* compare */

		$this->assertString($actual);
	}

	/**
	 * testConvertToJSON
	 *
	 * @since 3.0.0
	 */

	public function testConvertToXML()
	{
		/* setup */

		$reader = new Reader();
		$reader->loadJSON('tests/provider/reader.json');

		/* actual */

		$actual = $reader->getXML();

		/* compare */

		$this->assertString($actual);
	}
}
