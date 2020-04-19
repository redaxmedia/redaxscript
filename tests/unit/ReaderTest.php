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
 *
 * @covers Redaxscript\Reader
 */

class ReaderTest extends TestCaseAbstract
{
	/**
	* testLoadAndConvertJSON
	*
	* @since 3.1.0
	*/

	public function testLoadAndConvertJSON() : void
	{
		/* setup */

		$reader = new Reader();
		$reader->init();
		$reader->loadJSON('tests' . DIRECTORY_SEPARATOR . 'unit-provider' . DIRECTORY_SEPARATOR . 'ReaderTest.json');

		/* actual */

		$actualArray = $reader->getArray();
		$actualObject = $reader->getObject();
		$actualJSON = $reader->getJSON();
		$actualXML = $reader->getXML();

		/* compare */

		$this->assertEquals('1', $actualArray['@attributes']['id']);
		$this->assertEquals('2', $actualArray['one'][0]['@attributes']['id']);
		$this->assertEquals('3', $actualArray['one'][1]['@attributes']['id']);
		$this->assertEquals('4', $actualArray['two']['@attributes']['id']);
		$this->assertEquals('1', $actualObject->{'@attributes'}->id);
		$this->assertEquals('2', $actualObject->one{0}->{'@attributes'}->id);
		$this->assertEquals('3', $actualObject->one{1}->{'@attributes'}->id);
		$this->assertEquals('4', $actualObject->two->{'@attributes'}->id);
		$this->assertIsString($actualJSON);
		$this->assertIsString($actualXML);
	}

	/**
	 * testLoadAndConvertXML
	 *
	 * @since 3.1.0
	 */

	public function testLoadAndConvertXML() : void
	{
		/* setup */

		$reader = new Reader();
		$reader->init();
		$reader->loadXML('tests' . DIRECTORY_SEPARATOR . 'unit-provider' . DIRECTORY_SEPARATOR . 'ReaderTest.xml');

		/* actual */

		$actualArray = $reader->getArray();
		$actualObject = $reader->getObject();
		$actualJSON = $reader->getJSON();
		$actualXML = $reader->getXML();

		/* compare */

		$this->markTestSkipped();
		$this->assertEquals('1', $actualArray['@attributes']['id']);
		$this->assertEquals('2', $actualArray['one'][0]);
		$this->assertEquals('3', $actualArray['one'][1]);
		$this->assertEquals('4', $actualArray['two']);
		$this->assertEquals('1', $actualObject->attributes()->id);
		$this->assertEquals('2', $actualObject->one{0}->attributes()->id);
		$this->assertEquals('3', $actualObject->one{1}->attributes()->id);
		$this->assertEquals('4', $actualObject->two->attributes()->id);
		$this->assertIsString($actualJSON);
		$this->assertIsString($actualXML);
	}

	/**
	 * testLoadAndConvertExternalXML
	 *
	 * @since 3.1.0
	 *
	 * @requires OS Linux
	 */

	public function testLoadExternalXML() : void
	{
		/* setup */

		$reader = new Reader();
		$reader->init(
		[
			'curl' =>
			[
				CURLOPT_TIMEOUT_MS => 5000
			]
		]);
		$reader->loadXML('https://service.redaxscript.com/xml/pad.xml');

		/* actual */

		$actual = $reader->getObject();

		/* compare */

		$this->assertIsObject($actual);
	}
}
