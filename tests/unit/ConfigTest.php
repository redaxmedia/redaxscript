<?php
namespace Redaxscript\Tests;

use org\bovigo\vfs\vfsStream as Stream;
use org\bovigo\vfs\vfsStreamFile as StreamFile;
use org\bovigo\vfs\vfsStreamWrapper as StreamWrapper;

/**
 * ConfigTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 *
 * @covers Redaxscript\Config
 */

class ConfigTest extends TestCaseAbstract
{
	/**
	 * array to restore config
	 *
	 * @var array
	 */

	protected $_configArray = [];

	/**
	 * setUp
	 *
	 * @since 3.0.0
	 */

	public function setUp() : void
	{
		parent::setUp();
		Stream::setup('root');
		$file = new StreamFile('config.php');
		StreamWrapper::getRoot()->addChild($file);
		$this->_configArray = $this->_config->getArray();
	}

	/**
	 * tearDown
	 *
	 * @since 3.1.0
	 */

	public function tearDown() : void
	{
		$this->_config->setArray($this->_configArray);
	}

	/**
	 * testInit
	 *
	 * @since 2.4.0
	 */

	public function testInit() : void
	{
		/* setup */

		$this->_config->init('config.php');

		/* actual */

		$actualArray = $this->_config->getArray();

		/* compare */

		$this->assertArrayHasKey('dbType', $actualArray);
	}

	/**
	 * testGetAndSet
	 *
	 * @since 2.2.0
	 */

	public function testGetAndSet() : void
	{
		/* setup */

		$this->_config->set('dbHost', 'localhost');

		/* actual */

		$actual = $this->_config->get('dbHost');

		/* compare */

		$this->assertEquals('localhost', $actual);
	}

	/**
	 * testGetArray
	 *
	 * @since 4.0.0
	 */

	public function testGetArray() : void
	{
		/* actual */

		$actualArray = $this->_config->getArray();

		/* compare */

		$this->assertArrayHasKey('dbHost', $actualArray);
	}

	/**
	 * testParse
	 *
	 * @since 3.0.0
	 *
	 * @param string $dbUrl
	 * @param array $configArray
	 *
	 * @dataProvider providerAutoloader
	 */

	public function testParse(string $dbUrl = null, array $configArray = []) : void
	{
		/* setup */

		$this->_config->init(Stream::url('root' . DIRECTORY_SEPARATOR . 'config.php'));
		$this->_config->parse($dbUrl);

		/* actual */

		$actual = $this->_config->getArray();

		/* compare */

		$this->assertEquals($configArray, $actual);
	}

	/**
	 * testWrite
	 *
	 * @since 2.4.0
	 */

	public function testWrite() : void
	{
		/* setup */

		$this->_config->init(Stream::url('root' . DIRECTORY_SEPARATOR . 'config.php'));

		/* actual */

		$actual = $this->_config->write();

		/* compare */

		$this->assertNotFalse($actual);
	}
}
