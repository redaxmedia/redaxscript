<?php
namespace Redaxscript\Tests;

use Redaxscript\Config;
use org\bovigo\vfs\vfsStream as Stream;

/**
 * ConfigTest
 *
 * @since 2.2.0
 *
 * @package Redaxscript
 * @category Tests
 * @author Henry Ruhs
 */

class ConfigTest extends TestCase
{
	/**
	 * instance of the config class
	 *
	 * @var object
	 */

	protected $_config;

	/**
	 * setUp
	 *
	 * @since 2.4.0
	 */

	public function setUp()
	{
		$this->_config = Config::getInstance();
	}

	/**
	 * testSetAndGet
	 *
	 * @since 2.2.0
	 */

	public function testSetAndGet()
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

		$actual = $this->_config->get();

		/* compare */

		$this->assertArrayHasKey('dbHost', $actual);
	}

	/**
	 * testWrite
	 *
	 * @since 2.4.0
	 */

	public function testWrite()
	{
		/* setup */

		Stream::setup('root');

		/* actual */

		$actual = $this->_config->write(Stream::url('root/config.php'));

		/* compare */

		$this->assertNotFalse($actual);
	}

	/**
	 * testInit
	 *
	 * @since 2.4.0
	 */

	public function testInit()
	{
		/* setup */

		Stream::setup('root');
		$this->_config->init(Stream::url('root/config.php'));

		/* actual */

		$actual = $this->_config->get('dbType');

		/* compare */

		$this->assertNotEmpty($actual);
	}
}
