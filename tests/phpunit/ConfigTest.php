<?php
namespace Redaxscript\Tests;

use Redaxscript\Config;
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
 */

class ConfigTest extends TestCaseAbstract
{
	/**
	 * instance of the config class
	 *
	 * @var object
	 */

	protected $_config;

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

	public function setUp()
	{
		$this->_config = Config::getInstance();
		$this->_configArray = $this->_config->get();
	}

	/**
	 * setUpBeforeClass
	 *
	 * @since 3.0.0
	 */

	public static function setUpBeforeClass()
	{
		Stream::setup('root');
		$file = new StreamFile('config.php');
		StreamWrapper::getRoot()->addChild($file);
	}

	/**
	 * tearDown
	 *
	 * @since 3.0.0
	 */

	public function tearDown()
	{
		$this->_config->set('dbType', $this->_configArray['dbType']);
	}

	/**
	 * providerParse
	 *
	 * @since 3.0.0
	 *
	 * @return array
	 */

	public function providerParse()
	{
		return $this->getProvider('tests/provider/config_parse.json');
	}

	/**
	 * testInit
	 *
	 * @since 2.4.0
	 */

	public function testInit()
	{
		/* setup */

		$this->_config->init('config.php');

		/* actual */

		$actualArray = $this->_config->get();

		/* compare */

		$this->assertArrayHasKey('dbType', $actualArray);
	}

	/**
	 * testGetAndSet
	 *
	 * @since 2.2.0
	 */

	public function testGetAndSet()
	{
		/* setup */

		$this->_config->set('dbHost', 'localhost');

		/* actual */

		$actual = $this->_config->get('dbHost');

		/* compare */

		$this->assertEquals('localhost', $actual);
	}

	/**
	 * testGetAll
	 *
	 * @since 2.2.0
	 */

	public function testGetAll()
	{
		/* actual */

		$actualArray = $this->_config->get();

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
	 * @dataProvider providerParse
	 */

	public function testParse($dbUrl = null, $configArray = [])
	{
		/* setup */

		$this->_config->init(Stream::url('root/config.php'));
		$this->_config->parse($dbUrl);

		/* actual */

		$actual = $this->_config->get();

		/* compare */

		$this->assertEquals($configArray, $actual);
	}

	/**
	 * testWrite
	 *
	 * @since 2.4.0
	 */

	public function testWrite()
	{
		/* setup */

		$this->_config->init(Stream::url('root/config.php'));

		/* actual */

		$actual = $this->_config->write();

		/* compare */

		$this->assertNotFalse($actual);
	}
}
