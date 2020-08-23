<?php
namespace Redaxscript\Tests;

use Redaxscript\Dater;

/**
 * DaterTest
 *
 * @since 4.0.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Dater
 */

class DaterTest extends TestCaseAbstract
{
	/**
	 * setUp
	 *
	 * @since 4.0.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		$optionArray = $this->getOptionArray();
		$installer = $this->installerFactory();
		$installer->init();
		$installer->rawCreate();
		$installer->insertSettings($optionArray);
		$installer->rawMigrate();
	}

	/**
	 * tearDown
	 *
	 * @since 4.0.0
	 */

	public function tearDown() : void
	{
		$this->dropDatabase();
	}

	/**
	 * testInit
	 *
	 * @since 4.0.0
	 */

	public function testInit() : void
	{
		/* setup */

		$dater = new Dater();
		$dater->init();

		/* actual */

		$actualDateTime = $dater->getDateTime();
		$actualTimeZone = $dater->getTimeZone();
		$actualTimeStamp = $dater->getDateTime()->getTimestamp();

		/* compare */

		$this->assertInstanceOf('DateTime', $actualDateTime);
		$this->assertInstanceOf('DateTimeZone', $actualTimeZone);
		$this->assertNotEquals(0, $actualTimeStamp);
	}

	/**
	 * testGetFormat
	 *
	 * @since 4.0.0
	 */

	public function testGetFormat() : void
	{
		/* setup */

		$dater = new Dater();
		$dater->init(1483261800);

		/* actual */

		$actualTime = $dater->formatTime();
		$actualDate = $dater->formatDate();
		$actualField = $dater->formatField();

		/* compare */

		$this->assertEquals('10:10', $actualTime);
		$this->assertEquals('01.01.2017', $actualDate);
		$this->assertEquals('2017-01-01T10:10', $actualField);
	}
}
